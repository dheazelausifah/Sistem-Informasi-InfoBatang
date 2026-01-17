<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Halaman Terkini - Menampilkan semua berita terbaru dengan search
     */
    public function index(Request $request)
    {
        // Hero Slider - 3 berita terpopuler
        $beritaHero = News::with('category')
            ->where('status', 'publish')
            ->orderBy('views', 'desc')
            ->take(3)
            ->get();

        // Hitung comments untuk hero
        foreach($beritaHero as $berita) {
            $berita->comments_count = DB::table('komentar')
                ->where('id_berita', $berita->id_berita)
                ->count();
        }

        // Query berita terkini dengan search
        $query = News::with('category')
            ->where('status', 'publish');

        // Handle search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('isi', 'like', '%' . $searchTerm . '%');
            });
        }

        // Pagination - 12 items per page
        $beritaTerkini = $query->orderBy('tanggal_publish', 'desc')
            ->paginate(12);

        // Hitung comments untuk setiap berita
        foreach($beritaTerkini as $berita) {
            $berita->comments_count = DB::table('komentar')
                ->where('id_berita', $berita->id_berita)
                ->count();
        }

        return view('frontend.news.terkini', compact('beritaHero', 'beritaTerkini'));
    }

    /**
     * Detail Berita
     */
    public function show($id)
    {
        $berita = News::with(['category'])
            ->where('id_berita', $id)
            ->where('status', 'publish')
            ->firstOrFail();

        // Increment views REALTIME setiap kali berita dibuka
        DB::table('berita')
            ->where('id_berita', $id)
            ->increment('views');

        // Reload data berita untuk mendapatkan views terbaru
        $berita->refresh();

        $berita->comments_count = DB::table('komentar')
            ->where('id_berita', $berita->id_berita)
            ->count();

        // Ambil komentar dengan JOIN ke user
        $comments = DB::table('komentar')
            ->join('user', 'komentar.id_user', '=', 'user.id_user')
            ->select(
                'komentar.id_komentar',
                'komentar.isi_komentar',
                'komentar.tanggal_komentar',
                'user.nama_user as nama',
                'user.email'
            )
            ->where('komentar.id_berita', $berita->id_berita)
            ->orderBy('komentar.tanggal_komentar', 'desc')
            ->get();

        $beritaTerkait = News::with('category')
            ->where('id_kategori', $berita->id_kategori)
            ->where('id_berita', '!=', $berita->id_berita)
            ->where('status', 'publish')
            ->orderBy('tanggal_publish', 'desc')
            ->take(10)
            ->get();

        foreach($beritaTerkait as $item) {
            $item->comments_count = DB::table('komentar')
                ->where('id_berita', $item->id_berita)
                ->count();
        }

        return view('frontend.news.detail', compact('berita', 'beritaTerkait', 'comments'));
    }

    /**
     * Berita berdasarkan Kategori
     */
    public function byCategory($id)
    {
        $category = Category::where('id_kategori', $id)->firstOrFail();

        $berita = News::with(['category'])
            ->where('id_kategori', $category->id_kategori)
            ->where('status', 'publish')
            ->orderBy('tanggal_publish', 'desc')
            ->paginate(12);

        foreach($berita as $item) {
            $item->comments_count = DB::table('komentar')
                ->where('id_berita', $item->id_berita)
                ->count();
        }

        $allCategories = Category::withCount([
            'news' => function($query) {
                $query->where('status', 'publish');
            }
        ])->orderBy('nama_kategori')->get();

        return view('frontend.news.kategori', compact('category', 'berita', 'allCategories'));
    }

    /**
     * Store Comment - Menyimpan komentar baru
     */
    public function storeComment(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'tanggal' => 'required|date',
            'isi_komentar' => 'required|string|max:1000'
        ]);

        $berita = DB::table('berita')->where('id_berita', $id)->first();

        // Cari/buat user
        $user = DB::table('user')->where('email', $validated['email'])->first();

        if (!$user) {
            $lastUser = DB::table('user')->where('id_user', 'LIKE', 'USR%')->orderBy('id_user', 'desc')->first();
            $lastNumber = $lastUser ? intval(substr($lastUser->id_user, 3)) : 0;
            $id_user = 'USR' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            DB::table('user')->insert([
                'id_user' => $id_user,
                'nama_user' => $validated['nama'],
                'email' => $validated['email'],
                'created_at' => now()
            ]);
        } else {
            $id_user = $user->id_user;
        }

        // Generate ID Komentar
        $lastKomentar = DB::table('komentar')->where('id_komentar', 'LIKE', 'KMT%')->orderBy('id_komentar', 'desc')->first();
        $lastNumber = $lastKomentar ? intval(substr($lastKomentar->id_komentar, 3)) : 0;
        $id_komentar = 'KMT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $tanggalKomentar = \Carbon\Carbon::parse($validated['tanggal'] . ' ' . now()->format('H:i:s'))
            ->setTimezone('Asia/Jakarta')
            ->format('Y-m-d H:i:s');

        // Insert komentar
        DB::table('komentar')->insert([
            'id_komentar' => $id_komentar,
            'isi_komentar' => $validated['isi_komentar'],
            'id_berita' => $id,
            'id_user' => $id_user,
            'tanggal_komentar' => $tanggalKomentar
        ]);

        // Insert notifikasi LANGSUNG
        DB::table('notifications')->insert([
            'type' => 'comment',
            'message' => 'Komentar baru dari ' . $validated['nama'] . ' pada berita: ' . $berita->judul,
            'url' => '/admin/comments',
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('berita.detail', $id)->with('success', 'Komentar berhasil ditambahkan!');
    }
}
