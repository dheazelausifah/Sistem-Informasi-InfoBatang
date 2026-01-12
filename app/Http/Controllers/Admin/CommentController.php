<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Tampilkan daftar komentar
     */
    public function index(Request $request)
    {
        $query = DB::table('komentar')
            ->join('user', 'komentar.id_user', '=', 'user.id_user')
            ->join('berita', 'komentar.id_berita', '=', 'berita.id_berita')
            ->select(
                'komentar.id_komentar',
                'komentar.isi_komentar',
                'komentar.tanggal_komentar',
                'komentar.id_berita',
                'komentar.id_user',
                'user.nama_user',
                'user.email'
            );

        // Filter by berita
        if ($request->filled('filter_berita')) {
            $query->where('komentar.id_berita', $request->filter_berita);
        }

        // Filter by tanggal (dari)
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('komentar.tanggal_komentar', '>=', $request->tanggal_dari);
        }

        // Filter by tanggal (sampai)
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('komentar.tanggal_komentar', '<=', $request->tanggal_sampai);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('komentar.id_komentar', 'like', "%{$search}%")
                  ->orWhere('user.nama_user', 'like', "%{$search}%")
                  ->orWhere('user.email', 'like', "%{$search}%")
                  ->orWhere('komentar.isi_komentar', 'like', "%{$search}%");
            });
        }

        // Per page setting
        $perPage = $request->input('per_page', 10);

        // Pagination with appended query strings
        $komentars = $query->orderBy('komentar.tanggal_komentar', 'desc')
                           ->paginate($perPage)
                           ->appends($request->except('page'));

        // Get list berita for filter dropdown
        $beritas = DB::table('berita')
            ->select('id_berita', 'judul')
            ->orderBy('judul', 'asc')
            ->get();

        return view('admin.comments.index', compact('komentars', 'beritas'));
    }

    /**
     * Hapus komentar
     */
    public function destroy($id)
    {
        try {
            DB::table('komentar')->where('id_komentar', $id)->delete();

            return redirect()->route('admin.comments.index')
                             ->with('success', 'Komentar berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.comments.index')
                             ->with('error', 'Gagal menghapus komentar: ' . $e->getMessage());
        }
    }
}
