<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Halaman Daftar Karir
     * Tampilkan SEMUA lowongan (aktif & nonaktif)
     */
    public function index()
    {
        // Tampilkan semua, tapi aktif di atas
        $lowongan = Career::orderByRaw("FIELD(status, 'aktif', 'nonaktif')")
            ->latest('created_at')
            ->paginate(12);

        return view('frontend.career.index', compact('lowongan'));
    }

    /**
     * Detail Lowongan Karir
     * Bisa diakses meskipun nonaktif
     */
    public function detail($id_karir)
    {
        $lowongan = Career::where('id_karir', $id_karir)
            ->firstOrFail();

        // Lowongan terkait (prioritas aktif)
        $lowonganLain = Career::where('id_karir', '!=', $lowongan->id_karir)
            ->orderByRaw("FIELD(status, 'aktif', 'nonaktif')")
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('frontend.career.detail', compact('lowongan', 'lowonganLain'));
    }
}
