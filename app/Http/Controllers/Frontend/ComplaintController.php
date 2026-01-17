<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::orderBy('nama_kategori', 'asc')->get();
        return view('frontend.complaint.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'lokasi_kejadian' => 'required|string|max:150',
            'judul' => 'required|string|max:150',
            'isi_pengaduan' => 'required|string',
            'foto' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        // Upload foto
        $lampiranPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengaduan'), $namaFile);
            $lampiranPath = 'uploads/pengaduan/' . $namaFile;
        }

        // Generate ID
        $latest = DB::table('pengaduan')->orderBy('id_pengaduan', 'desc')->first();
        $lastId = $latest ? intval(substr($latest->id_pengaduan, 3)) : 0;
        $newId = 'PGD' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        // Insert pengaduan
        DB::table('pengaduan')->insert([
            'id_pengaduan' => $newId,
            'nama' => $validated['nama'],
            'no_hp' => $validated['telepon'],
            'judul_laporan' => $validated['judul'],
            'isi_laporan' => $validated['isi_pengaduan'],
            'lokasi' => $validated['lokasi_kejadian'],
            'lampiran' => $lampiranPath,
            'STATUS' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert notifikasi LANGSUNG
        DB::table('notifications')->insert([
            'type' => 'complaint',
            'message' => 'Pengaduan baru dari ' . $validated['nama'] . ': ' . $validated['judul'],
            'url' => '/admin/complaints',
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('sukses', 'Pengaduan berhasil dikirim!');
    }
}
