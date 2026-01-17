<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Complaint;
use App\Models\News;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('pengaduan');

        // Filter by status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('judul_laporan', 'like', "%$search%")
                  ->orWhere('isi_laporan', 'like', "%$search%")
                  ->orWhere('id_pengaduan', 'like', "%$search%");
            });
        }

        // Per page
        $perPage = $request->input('per_page', 10);

        $complaints = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show($id)
    {
        $complaint = DB::table('pengaduan')
            ->where('id_pengaduan', $id)
            ->first();

        if (!$complaint) {
            return redirect()->route('admin.complaints.index')
                ->with('error', 'Pengaduan tidak ditemukan');
        }

        return view('admin.complaints.show', compact('complaint'));
    }

    public function approve($id_pengaduan)
{
    try {
        // Gunakan Eloquent
        $complaint = Complaint::findOrFail($id_pengaduan);

        // ✅ Validasi: Cegah duplikasi
        if ($complaint->news) {
            return back()->with('error', 'Pengaduan ini sudah dijadikan berita!');
        }

        // ✅ Update status pengaduan
        $complaint->status = 'approved';
        $complaint->id_admin = auth()->user()->id_admin ?? 'ADM001';
        $complaint->save();

        // ✅ Generate ID berita otomatis
        $lastNews = News::orderBy('id_berita', 'desc')->first();
        $number = $lastNews ? intval(substr($lastNews->id_berita, 3)) + 1 : 1;
        $idBerita = 'BRT' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // ✅ Buat berita dari pengaduan
        News::create([
            'id_berita'       => $idBerita,
            'id_pengaduan'    => $complaint->id_pengaduan,
            'judul'           => $complaint->judul_laporan,
            'isi'             => $complaint->isi_laporan,
            'lokasi'          => $complaint->lokasi,
            'gambar'          => $complaint->lampiran,
            'tanggal_publish' => now(),
            'status'          => 'publish',
            'id_kategori'     => 'KT002', // Default kategori (sesuaikan)
            'id_admin'        => $complaint->id_admin,
            'views'           => 0,
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        // ✅ Create notification
        \App\Models\Notification::create([
            'type' => 'news',
            'message' => 'Pengaduan "' . \Str::limit($complaint->judul_laporan, 50) . '" berhasil dijadikan berita!',
            'url' => route('admin.news.index'),
            'is_read' => false
        ]);

        return redirect()
            ->route('admin.complaints.index')
            ->with('success', 'Pengaduan berhasil disetujui dan dijadikan berita!');

    } catch (\Exception $e) {
        \Log::error('Approve complaint error: ' . $e->getMessage());
        return back()->with('error', 'Gagal approve pengaduan: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        try {
            $complaint = DB::table('pengaduan')
                ->where('id_pengaduan', $id)
                ->first();

            if (!$complaint) {
                return redirect()->back()
                    ->with('error', 'Pengaduan tidak ditemukan');
            }

            // ✅ PENTING: Cek apakah sudah jadi berita
            $relatedNews = News::where('id_pengaduan', $id)->first();
            if ($relatedNews) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pengaduan yang sudah menjadi berita! Hapus berita terlebih dahulu.');
            }

            // Delete lampiran file if exists
            if ($complaint->lampiran && file_exists(public_path($complaint->lampiran))) {
                unlink(public_path($complaint->lampiran));
            }

            // Delete from database
            DB::table('pengaduan')
                ->where('id_pengaduan', $id)
                ->delete();

            return redirect()->route('admin.complaints.index')
                ->with('success', 'Pengaduan berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengaduan: ' . $e->getMessage());
        }
    }
}
