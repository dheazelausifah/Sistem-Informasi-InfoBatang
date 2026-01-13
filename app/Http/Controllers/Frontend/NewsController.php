<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Halaman Terkini - Menampilkan semua berita terbaru
     */
    public function index()
    {
        // Berita untuk Hero Slider (3 berita terbaru)
        $beritaHero = News::with('category')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Breaking News (8 berita terbaru)
        $beritaTerkini = News::with('category')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Berita Populer (berdasarkan views - 8 berita)
        $beritaPopuler = News::with('category')
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(8)
            ->get();

        return view('frontend.news.terkini', compact('beritaHero', 'beritaTerkini', 'beritaPopuler'));
    }

    /**
     * Detail Berita
     */
    public function show($id)
    {
        $berita = News::with(['category', 'comments.user'])
            ->where('id_berita', $id)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $berita->increment('views');

        // Berita terkait (dari kategori yang sama)
        $beritaTerkait = News::with('category')
            ->where('id_kategori', $berita->id_kategori)
            ->where('id_berita', '!=', $berita->id_berita)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('frontend.news.detail', compact('berita', 'beritaTerkait'));
    }

    /**
     * Berita berdasarkan Kategori
     */
    public function byCategory($id)
    {
        $category = Category::where('id_kategori', $id)->firstOrFail();

        $berita = News::with('category')
            ->where('id_kategori', $category->id_kategori)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('frontend.news.kategori', compact('category', 'berita'));
    }
}
