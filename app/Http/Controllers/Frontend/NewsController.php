<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Halaman Terkini (Landing Page)
     * File: resources/views/frontend/news/terkini.blade.php
     */
    public function terkini()
    {
        // Berita untuk Hero Slider (3 berita terbaru)
        $beritaHero = News::where('status', 'publish')
            ->with('category')
            ->latest('tanggal_publish')
            ->take(3)
            ->get();

        // Breaking News / Berita Terkini (4 berita)
        $beritaTerkini = News::where('status', 'publish')
            ->with('category')
            ->latest('tanggal_publish')
            ->skip(3)
            ->take(4)
            ->get();

        // Berita Populer (berdasarkan views)
        $beritaPopuler = News::where('status', 'publish')
            ->with('category')
            ->orderBy('views', 'desc')
            ->take(4)
            ->get();

        // Semua kategori dengan jumlah berita
        $kategori = Category::withCount('news')->get();

        return view('frontend.news.terkini', compact(
            'beritaHero',
            'beritaTerkini',
            'beritaPopuler',
            'kategori'
        ));
    }

    /**
     * Detail Berita
     * File: resources/views/frontend/news/detail.blade.php
     */
    public function detail($slug)
    {
        // Cari berita berdasarkan slug
        $berita = News::where('slug', $slug)
            ->where('status', 'publish')
            ->with(['category', 'comments' => function($query) {
                $query->where('status', 'approved')->latest();
            }])
            ->firstOrFail();

        // Tambah jumlah views
        $berita->increment('views');

        // Berita terkait (kategori sama, exclude berita ini)
        $beritaTerkait = News::where('id_kategori', $berita->id_kategori)
            ->where('id_berita', '!=', $berita->id_berita)
            ->where('status', 'publish')
            ->latest('tanggal_publish')
            ->take(4)
            ->get();

        return view('frontend.news.detail', compact('berita', 'beritaTerkait'));
    }

    /**
     * Berita per Kategori
     * File: resources/views/frontend/news/kategori.blade.php
     */
    public function kategori($slug)
    {
        // Cari kategori berdasarkan slug
        $kategori = Category::where('slug', $slug)->firstOrFail();

        // Berita dalam kategori ini dengan pagination
        $berita = News::where('id_kategori', $kategori->id_kategori)
            ->where('status', 'publish')
            ->with('category')
            ->latest('tanggal_publish')
            ->paginate(12);

        // Semua kategori untuk sidebar/menu
        $semuaKategori = Category::withCount('news')->get();

        return view('frontend.news.kategori', compact('kategori', 'berita', 'semuaKategori'));
    }
}
