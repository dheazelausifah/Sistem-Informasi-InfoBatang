<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Halaman Daftar Karir
     * File: resources/views/frontend/career/index.blade.php
     */
    public function index()
    {
        $lowongan = Career::where('status', 'aktif')
            ->where('batas_lamaran', '>=', now())
            ->latest('tanggal_dibuat')
            ->paginate(12);

        return view('frontend.career.index', compact('lowongan'));
    }

    /**
     * Detail Lowongan Karir
     * File: resources/views/frontend/career/detail.blade.php
     */
    public function detail($slug)
    {
        $lowongan = Career::where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Lowongan terkait (random)
        $lowonganLain = Career::where('status', 'aktif')
            ->where('id_karir', '!=', $lowongan->id_karir)
            ->where('batas_lamaran', '>=', now())
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('frontend.career.detail', compact('lowongan', 'lowonganLain'));
    }
}
