<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function approve($id)
    {
        try {
            // Cek apakah pengaduan ada
            $complaint = DB::table('pengaduan')
                ->where('id_pengaduan', $id)
                ->first();

            if (!$complaint) {
                return redirect()->back()
                    ->with('error', 'Pengaduan tidak ditemukan');
            }

            // Update status
            $affected = DB::table('pengaduan')
                ->where('id_pengaduan', $id)
                ->update([
                    'status' => 'approved',
                    'id_admin' => 'ADM001',
                ]);

            if ($affected > 0) {
                return redirect()->route('admin.complaints.index')
                    ->with('success', 'Pengaduan berhasil dikonfirmasi!');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal mengupdate status pengaduan');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal konfirmasi pengaduan: ' . $e->getMessage());
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
