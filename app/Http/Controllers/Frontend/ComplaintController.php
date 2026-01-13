<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Halaman Form Pengaduan
     */
    public function index()
    {
        // Ambil semua kategori dari database (jika ada kategori)
        $categories = \App\Models\Category::orderBy('nama_kategori', 'asc')->get();

        return view('frontend.complaint.index', compact('categories'));
    }

    /**
     * Kirim Pengaduan
     */
    public function store(Request $request)
    {
        // Validasi input user
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'kategori' => 'nullable|string',
            'tanggal_kejadian' => 'nullable|date',
            'lokasi_kejadian' => 'required|string|max:150',
            'judul' => 'required|string|max:150',
            'isi_pengaduan' => 'required|string',
            'foto' => 'nullable|file|mimes:jpeg,png,jpg,doc,docx,pdf|max:10240', // max 10MB
        ], [
            'nama.required' => 'Nama wajib diisi',
            'telepon.required' => 'Nomor telepon wajib diisi',
            'lokasi_kejadian.required' => 'Lokasi kejadian wajib diisi',
            'judul.required' => 'Judul laporan wajib diisi',
            'isi_pengaduan.required' => 'Isi laporan wajib diisi',
            'foto.mimes' => 'File harus berupa PNG, JPG, DOC, atau PDF',
            'foto.max' => 'Ukuran file maksimal 10MB',
        ]);

        // Upload lampiran jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengaduan'), $namaFile);
            $validated['lampiran'] = $namaFile;
        }

        // Generate ID pengaduan otomatis (PGD001, PGD002, dst)
        $latest = Complaint::orderBy('id_pengaduan', 'desc')->first();
        if ($latest) {
            $lastId = intval(substr($latest->id_pengaduan, 3));
            $newId = 'PGD' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'PGD001';
        }

        // Mapping data ke kolom tabel
        $data = [
            'id_pengaduan' => $newId,
            'nama' => $validated['nama'],
            'no_hp' => $validated['telepon'],
            'judul_laporan' => $validated['judul'],
            'isi_laporan' => $validated['isi_pengaduan'],
            'lokasi' => $validated['lokasi_kejadian'],
            'lampiran' => $validated['lampiran'] ?? null,
            'STATUS' => 'pending',
            'created_at' => now(),
        ];

        // Simpan ke database
        Complaint::create($data);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('sukses', 'Pengaduan Anda berhasil dikirim dan akan segera diproses!');
    }
}
