<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter period
        $period = $request->input('period', 'month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Set date range
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            switch ($period) {
                case 'week':
                    $start = Carbon::now()->subWeeks(2)->startOfDay();
                    $end = Carbon::now()->endOfDay();
                    break;
                case 'year':
                    $start = Carbon::now()->subMonths(12)->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                default: // month
                    $start = Carbon::now()->subMonths(6)->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
            }
        }

        // Total counts
        $totalNews = DB::table('berita')->count();
        $totalCareers = DB::table('karir')->where('status', 'aktif')->count();
        $totalComplaints = DB::table('pengaduan')->count();
        $newComplaints = DB::table('pengaduan')->where('status', 'pending')->count();
        $totalComments = DB::table('komentar')->count();

        // New items in period
        $newNewsCount = DB::table('berita')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $newComplaintsInPeriod = DB::table('pengaduan')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // ==========================================
        // GRAFIK LINE: Berita per bulan DENGAN SEMUA BULAN
        // ==========================================
        $newsPerMonthRaw = DB::table('berita')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Buat array SEMUA bulan dalam range (biar grafik lengkap)
        $newsPerMonth = collect();
        $currentMonth = $start->copy()->startOfMonth();

        while ($currentMonth <= $end) {
            $monthKey = $currentMonth->format('Y-m');
            $monthLabel = $currentMonth->format('M Y'); // Jan 2026, Feb 2026

            $total = isset($newsPerMonthRaw[$monthKey]) ? $newsPerMonthRaw[$monthKey]->total : 0;

            $newsPerMonth->push([
                'month' => $monthLabel,
                'total' => $total
            ]);

            $currentMonth->addMonth();
        }

        // Berita per kategori (TANPA filter periode, ambil semua)
        $newsPerCategory = DB::table('berita')
            ->join('kategori', 'berita.id_kategori', '=', 'kategori.id_kategori')
            ->select('kategori.nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori.id_kategori', 'kategori.nama_kategori')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Status pengaduan (ambil semua, bukan per periode)
        $complaintsByStatus = DB::table('pengaduan')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Top commented news
        $topCommentedNews = DB::table('berita')
            ->leftJoin('komentar', 'berita.id_berita', '=', 'komentar.id_berita')
            ->select('berita.judul', DB::raw('count(komentar.id_komentar) as total_comments'))
            ->groupBy('berita.id_berita', 'berita.judul')
            ->having('total_comments', '>', 0)
            ->orderBy('total_comments', 'desc')
            ->limit(5)
            ->get();

        // Latest complaints
        $latestComplaints = DB::table('pengaduan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalNews',
            'totalCareers',
            'totalComplaints',
            'newComplaints',
            'totalComments',
            'newNewsCount',
            'newComplaintsInPeriod',
            'newsPerCategory',
            'newsPerMonth',
            'complaintsByStatus',
            'topCommentedNews',
            'latestComplaints',
            'period',
            'startDate',
            'endDate'
        ));
    }
}
