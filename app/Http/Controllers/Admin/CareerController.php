<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class CareerController extends Controller
{
    /**
     * Display a listing of careers with pagination, filter, and search
     */
    public function index(Request $request)
    {
        try {
            $query = Career::query();

            // Filter by status
            if ($request->filled('filter_status')) {
                $query->where('status', $request->filter_status);
            }

            // Filter by tanggal (dari)
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('created_at', '>=', $request->tanggal_dari);
            }

            // Filter by tanggal (sampai)
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('created_at', '<=', $request->tanggal_sampai);
            }

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('judul', 'like', '%' . $searchTerm . '%')
                      ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%')
                      ->orWhere('id_karir', 'like', '%' . $searchTerm . '%')
                      ->orWhere('lokasi', 'like', '%' . $searchTerm . '%')
                      ->orWhere('tipe_pekerjaan', 'like', '%' . $searchTerm . '%');
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $careers = $query->latest('created_at')
                            ->paginate($perPage)
                            ->appends($request->except('page'));

            return view('admin.careers.index', compact('careers'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    /**
     * Show the form for creating a new career
     */
    public function create()
    {
        return view('admin.careers.create');
    }

    /**
     * Store a newly created career in database
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'judul' => 'required|max:150',
            'tipe_pekerjaan' => 'required|max:50',
            'lokasi' => 'required|max:100',
            'level' => 'required|max:50',
            'gaji' => 'nullable|max:50',
            'deskripsi' => 'required',
            'tanggung_jawab' => 'required',
            'kualifikasi' => 'required',
            'status' => 'required|in:aktif,nonaktif'
        ], [
            'judul.required' => 'Judul lowongan wajib diisi',
            'judul.max' => 'Judul maksimal 150 karakter',
            'tipe_pekerjaan.required' => 'Tipe pekerjaan wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'level.required' => 'Level wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'tanggung_jawab.required' => 'Tanggung jawab wajib diisi',
            'kualifikasi.required' => 'Kualifikasi wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus aktif atau nonaktif'
        ]);

        try {
            DB::beginTransaction();

            // Generate ID (KRR001, KRR002, dst)
            $lastCareer = Career::orderBy('id_karir', 'desc')->first();
            $number = $lastCareer ? intval(substr($lastCareer->id_karir, 3)) + 1 : 1;
            $id = 'KRR' . str_pad($number, 3, '0', STR_PAD_LEFT);

            // Create career
            Career::create([
                'id_karir' => $id,
                'judul' => $validated['judul'],
                'tipe_pekerjaan' => $validated['tipe_pekerjaan'],
                'lokasi' => $validated['lokasi'],
                'level' => $validated['level'],
                'gaji' => $validated['gaji'] ?? 'Negotiable',
                'deskripsi' => $validated['deskripsi'],
                'tanggung_jawab' => $validated['tanggung_jawab'],
                'kualifikasi' => $validated['kualifikasi'],
                'status' => $validated['status'],
                'id_admin' => 'ADM001', // TODO: Ganti dengan auth()->user()->id_admin
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.careers.index')
                ->with('success', 'Lowongan berhasil ditambahkan!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan lowongan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified career
     */
    public function show($id)
    {
        try {
            $career = Career::findOrFail($id);
            return view('admin.careers.show', compact('career'));
        } catch (Exception $e) {
            return redirect()->route('admin.careers.index')
                ->with('error', 'Lowongan tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified career
     */
    public function edit($id)
    {
        try {
            $career = Career::findOrFail($id);
            return view('admin.careers.edit', compact('career'));
        } catch (Exception $e) {
            return redirect()->route('admin.careers.index')
                ->with('error', 'Lowongan tidak ditemukan');
        }
    }

    /**
     * Update the specified career in database
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'judul' => 'required|max:150',
            'tipe_pekerjaan' => 'required|max:50',
            'lokasi' => 'required|max:100',
            'level' => 'required|max:50',
            'gaji' => 'nullable|max:50',
            'deskripsi' => 'required',
            'tanggung_jawab' => 'required',
            'kualifikasi' => 'required',
            'status' => 'required|in:aktif,nonaktif'
        ], [
            'judul.required' => 'Judul lowongan wajib diisi',
            'tipe_pekerjaan.required' => 'Tipe pekerjaan wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'level.required' => 'Level wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'tanggung_jawab.required' => 'Tanggung jawab wajib diisi',
            'kualifikasi.required' => 'Kualifikasi wajib diisi',
            'status.required' => 'Status wajib dipilih'
        ]);

        try {
            DB::beginTransaction();

            $career = Career::findOrFail($id);

            $career->update([
                'judul' => $validated['judul'],
                'tipe_pekerjaan' => $validated['tipe_pekerjaan'],
                'lokasi' => $validated['lokasi'],
                'level' => $validated['level'],
                'gaji' => $validated['gaji'] ?? 'Negotiable',
                'deskripsi' => $validated['deskripsi'],
                'tanggung_jawab' => $validated['tanggung_jawab'],
                'kualifikasi' => $validated['kualifikasi'],
                'status' => $validated['status'],
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.careers.index')
                ->with('success', 'Lowongan berhasil diperbarui!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui lowongan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified career from database
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $career = Career::findOrFail($id);
            $judul = $career->judul; // Simpan untuk message

            $career->delete();

            DB::commit();

            return redirect()->route('admin.careers.index')
                ->with('success', "Lowongan '{$judul}' berhasil dihapus!");

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus lowongan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status career (aktif/nonaktif)
     */
    public function toggleStatus($id)
    {
        try {
            $career = Career::findOrFail($id);
            $newStatus = $career->status == 'aktif' ? 'nonaktif' : 'aktif';

            $career->update([
                'status' => $newStatus,
                'updated_at' => now()
            ]);

            return redirect()->back()
                ->with('success', "Status lowongan berhasil diubah menjadi {$newStatus}!");

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}
