<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Halaman Form Pengaduan
     * File: resources/views/frontend/complaint/index.blade.php
     */
    public function index()
    {
        // Ambil beberapa pengaduan yang sudah disetujui untuk ditampilkan
        $pengaduanTerbaru = Complaint::where('status', 'disetujui')
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.complaint.index', compact('pengaduanTerbaru'));
    }

    /**
     * Kirim Pengaduan
     */
    public function kirim(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'judul' => 'required|string|max:255',
            'isi_pengaduan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'judul.required' => 'Judul pengaduan wajib diisi',
            'isi_pengaduan.required' => 'Isi pengaduan wajib diisi',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/pengaduan'), $namaFoto);
            $validated['foto'] = $namaFoto;
        }

        // Set status default
        $validated['status'] = 'pending';
        $validated['tanggal_pengaduan'] = now();

        // Simpan ke database
        Complaint::create($validated);

        return redirect()->back()->with('sukses', 'Pengaduan Anda berhasil dikirim. Terima kasih!');
    }
}
