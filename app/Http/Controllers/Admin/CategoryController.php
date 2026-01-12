<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori dengan search & filter
     */
    public function index(Request $request)
    {
        $query = DB::table('kategori');

    // Search
    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            $q->where('id_kategori', 'like', "%{$search}%")
              ->orWhere('nama_kategori', 'like', "%{$search}%");
        });
    }

    // Filter kategori
    if ($filterCategory = $request->input('filter_category')) {
        $query->where('id_kategori', $filterCategory);
    }

    $categories = $query->orderBy('created_at', 'desc')->paginate(10);
    $categories->appends($request->all()); // Supaya pagination tetap bawa filter

    // Ambil semua kategori untuk dropdown
    $allCategories = DB::table('kategori')->orderBy('nama_kategori')->get();

    return view('admin.categories.index', compact('categories', 'allCategories'));
    }

    /**
     * Tampilkan form tambah kategori
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Simpan kategori baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_kategori' => 'required|unique:kategori,id_kategori|max:10',
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable|max:500',
        ], [
            'id_kategori.required' => 'ID Kategori wajib diisi',
            'id_kategori.unique' => 'ID Kategori sudah digunakan',
            'nama_kategori.required' => 'Nama Kategori wajib diisi',
        ]);

        // Insert ke database
        DB::table('kategori')->insert([
            'id_kategori' => $request->id_kategori,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit kategori
     */
    public function edit($id_kategori)
    {
        $category = DB::table('kategori')->where('id_kategori', $id_kategori)->first();

        if (!$category) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak ditemukan');
        }

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, $id_kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable|max:500',
        ], [
            'nama_kategori.required' => 'Nama Kategori wajib diisi',
        ]);

        DB::table('kategori')
            ->where('id_kategori', $id_kategori)
            ->update([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Hapus kategori
     */
    public function destroy($id_kategori)
    {
        // Cek apakah kategori digunakan di tabel berita
        $newsCount = DB::table('berita')->where('id_kategori', $id_kategori)->count();

        if ($newsCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan di berita!');
        }

        DB::table('kategori')->where('id_kategori', $id_kategori)->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
