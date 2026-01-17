<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('category');

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('isi', 'like', '%' . $request->search . '%')
                  ->orWhere('id_berita', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $news = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Get all categories for filter dropdown
        $categories = Category::all();

        return view('admin.news.index', compact('news', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // ✅ VALIDASI dengan max 10MB per image
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // 10MB = 10240KB
            'status' => 'required|in:draft,publish',
            'tanggal_kejadian' => 'nullable|date',
            'lokasi_kejadian' => 'nullable|string|max:255',
            'tanggal_publish' => 'nullable|date'
        ]);

        // Generate ID (BRT001, BRT002, dst)
        $lastNews = News::orderBy('id_berita', 'desc')->first();
        $number = $lastNews ? intval(substr($lastNews->id_berita, 3)) + 1 : 1;
        $id = 'BRT' . str_pad($number, 3, '0', STR_PAD_LEFT);

        $data = [
            'id_berita' => $id,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'id_kategori' => $request->id_kategori,
            'status' => $request->status,
            'id_admin' => auth()->user()->id_admin ?? 'ADM001',
            'tanggal_publish' => $request->status == 'publish'
                ? ($request->tanggal_publish ?? now())
                : $request->tanggal_publish,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // ✅ UPLOAD MULTIPLE IMAGES
        if ($request->hasFile('gambar')) {
            $uploadedImages = [];

            // Buat folder jika belum ada
            if (!file_exists(public_path('images/news'))) {
                mkdir(public_path('images/news'), 0777, true);
            }

            foreach ($request->file('gambar') as $image) {
                $imageName = time() . '_' . uniqid() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/news'), $imageName);
                $uploadedImages[] = 'images/news/' . $imageName;
            }

            // Simpan sebagai JSON array
            $data['gambar'] = json_encode($uploadedImages);
        }

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    public function show($id)
    {
        $news = News::with('category')->findOrFail($id);
        return view('admin.news.show', compact('news'));
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = Category::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // ✅ VALIDASI dengan max 10MB per image
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // 10MB = 10240KB
            'status' => 'required|in:draft,publish',
            'tanggal_kejadian' => 'nullable|date',
            'lokasi_kejadian' => 'nullable|string|max:255',
            'tanggal_publish' => 'nullable|date'
        ]);

        $news = News::findOrFail($id);

        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'id_kategori' => $request->id_kategori,
            'status' => $request->status,
            'tanggal_publish' => $request->status == 'publish' && !$news->tanggal_publish
                ? ($request->tanggal_publish ?? now())
                : ($request->tanggal_publish ?? $news->tanggal_publish),
            'updated_at' => now()
        ];

        // ✅ UPLOAD MULTIPLE IMAGES BARU
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($news->gambar) {
                $oldImages = json_decode($news->gambar, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImage) {
                        if (file_exists(public_path($oldImage))) {
                            unlink(public_path($oldImage));
                        }
                    }
                }
            }

            // Upload gambar baru
            $uploadedImages = [];
            foreach ($request->file('gambar') as $image) {
                $imageName = time() . '_' . uniqid() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/news'), $imageName);
                $uploadedImages[] = 'images/news/' . $imageName;
            }
            $data['gambar'] = json_encode($uploadedImages);
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diupdate');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        try {
            // Hapus semua komentar terkait berita ini terlebih dahulu
            \DB::table('komentar')->where('id_berita', $id)->delete();

            // ✅ Hapus multiple gambar
            if ($news->gambar) {
                $images = json_decode($news->gambar, true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        if (file_exists(public_path($image))) {
                            unlink(public_path($image));
                        }
                    }
                }
            }

            // Hapus berita
            $news->delete();

            return redirect()->route('admin.news.index')
                ->with('success', 'Berita berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.news.index')
                ->with('error', 'Gagal menghapus berita: ' . $e->getMessage());
        }
    }
}
