@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Title & Global Filter -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>

        <!-- Global Filter Period -->
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-3">
            <!-- Period Buttons -->
            <div class="flex gap-2">
                <button type="submit" name="period" value="week"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('period') == 'week' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Minggu
                </button>
                <button type="submit" name="period" value="month"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('period', 'month') == 'month' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Bulan
                </button>
                <button type="submit" name="period" value="year"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('period') == 'year' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Tahun
                </button>
            </div>

            <div class="h-8 w-px bg-gray-300"></div>

            <!-- Custom Date Range -->
            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       placeholder="Dari"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="text-gray-500">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       placeholder="Sampai"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center gap-1">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
            </div>

            @if(request('start_date') || request('end_date') || request('period'))
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition flex items-center gap-1">
                <i class="fas fa-redo"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Card 1: Total Berita -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-newspaper text-2xl"></i>
                </div>
                @if($newNewsCount > 0)
                <span class="text-xs bg-white bg-opacity-30 px-2 py-1 rounded-full font-semibold">+{{ $newNewsCount }} baru</span>
                @endif
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $totalNews }}</h3>
            <p class="text-sm opacity-90">Total Berita</p>
            <a href="{{ route('admin.news.index') }}" class="mt-3 inline-flex items-center text-xs opacity-80 hover:opacity-100">
                Lihat detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Card 2: Total Comment -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-comments text-2xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $totalComments }}</h3>
            <p class="text-sm opacity-90">Total Komentar</p>
            <a href="{{ route('admin.comments.index') }}" class="mt-3 inline-flex items-center text-xs opacity-80 hover:opacity-100">
                Lihat detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Card 3: Total Complaint -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                @if($newComplaintsInPeriod > 0)
                <span class="text-xs bg-white bg-opacity-30 px-2 py-1 rounded-full font-semibold">+{{ $newComplaintsInPeriod }} baru</span>
                @endif
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $totalComplaints }}</h3>
            <p class="text-sm opacity-90">Total Pengaduan</p>
            <a href="{{ route('admin.complaints.index') }}" class="mt-3 inline-flex items-center text-xs opacity-80 hover:opacity-100">
                Lihat detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Card 4: Pending Complaints -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-bell text-2xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $newComplaints }}</h3>
            <p class="text-sm opacity-90">Pengaduan Pending</p>
            <a href="{{ route('admin.complaints.index', ['filter_status' => 'pending']) }}" class="mt-3 inline-flex items-center text-xs opacity-80 hover:opacity-100">
                Lihat detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Card 5: Active Careers -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-briefcase text-2xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $totalCareers }}</h3>
            <p class="text-sm opacity-90">Lowongan Aktif</p>
            <a href="{{ route('admin.careers.index', ['filter_status' => 'aktif']) }}" class="mt-3 inline-flex items-center text-xs opacity-80 hover:opacity-100">
                Lihat detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Charts Section Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Line Chart: Tren Berita -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h4 class="text-lg font-bold text-gray-800">Tren Publikasi Berita</h4>
                    <p class="text-sm text-gray-500 mt-1">Jumlah berita yang dipublikasikan per bulan</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                    <span class="text-sm text-gray-600">Berita</span>
                </div>
            </div>
            <div style="height: 300px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart: Status Pengaduan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="mb-6">
                <h4 class="text-lg font-bold text-gray-800">Status Pengaduan</h4>
                <p class="text-sm text-gray-500 mt-1">Distribusi status pengaduan</p>
            </div>
            <div class="flex items-center justify-center" style="height: 200px;">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="flex flex-col gap-3 mt-6">
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                        <span class="text-sm font-medium text-gray-700">Pending</span>
                    </div>
                    <span class="text-lg font-bold text-yellow-600">{{ $complaintsByStatus->where('status', 'pending')->first()->total ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        <span class="text-sm font-medium text-gray-700">Approved</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ $complaintsByStatus->where('status', 'approved')->first()->total ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bar Chart: Berita per Kategori -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="mb-6">
                <h4 class="text-lg font-bold text-gray-800">Berita per Kategori</h4>
                <p class="text-sm text-gray-500 mt-1">10 kategori dengan berita terbanyak</p>
            </div>
            <div style="height: 320px;">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Horizontal Bar: Top Commented News -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="mb-6">
                <h4 class="text-lg font-bold text-gray-800">Berita Paling Banyak Komentar</h4>
                <p class="text-sm text-gray-500 mt-1">Top 5 berita dengan komentar terbanyak</p>
            </div>
            <div style="height: 320px;">
                <canvas id="horizontalBarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Latest Activity Table -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="mb-6">
            <h4 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h4>
            <p class="text-sm text-gray-500 mt-1">10 pengaduan terakhir</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($latestComplaints as $complaint)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($complaint->created_at)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $complaint->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($complaint->judul_laporan, 40) }}</td>
                        <td class="px-6 py-4">
                            @if($complaint->status == 'pending')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.complaints.show', $complaint->id_pengaduan) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                Lihat <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Tidak ada aktivitas terbaru
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Line Chart - Tren Berita
const lineCtx = document.getElementById('lineChart').getContext('2d');
const newsData = {!! json_encode($newsPerMonth) !!};
const labels = newsData.map(item => item.month);
const data = newsData.map(item => item.total);

new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Berita',
            data: data,
            borderColor: '#6366F1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointBackgroundColor: '#6366F1',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
                borderColor: '#6366F1',
                borderWidth: 1,
                callbacks: {
                    label: function(context) {
                        return 'Berita: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                ticks: {
                    color: '#6b7280',
                    stepSize: 1,
                    precision: 0
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    color: '#6b7280',
                    maxRotation: 45,
                    minRotation: 0
                }
            }
        }
    }
});

// Pie Chart - Status Pengaduan
const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($complaintsByStatus->pluck('status')->map(function($s) { return ucfirst($s); })) !!},
        datasets: [{
            data: {!! json_encode($complaintsByStatus->pluck('total')) !!},
            backgroundColor: ['#FBBF24', '#10B981'],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12
            }
        },
        cutout: '65%'
    }
});

// Bar Chart - Berita per Kategori
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($newsPerCategory->pluck('nama_kategori')) !!},
        datasets: [{
            label: 'Jumlah Berita',
            data: {!! json_encode($newsPerCategory->pluck('total')) !!},
            backgroundColor: '#6366F1',
            borderRadius: 8,
            barThickness: 35
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                ticks: {
                    color: '#6b7280',
                    stepSize: 1
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    color: '#6b7280',
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        }
    }
});

// Horizontal Bar Chart - Top Commented News
const hBarCtx = document.getElementById('horizontalBarChart').getContext('2d');
new Chart(hBarCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($topCommentedNews->pluck('judul')->map(function($j) { return Str::limit($j, 25); })) !!},
        datasets: [{
            label: 'Komentar',
            data: {!! json_encode($topCommentedNews->pluck('total_comments')) !!},
            backgroundColor: '#10B981',
            borderRadius: 8,
            barThickness: 30
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                ticks: {
                    color: '#6b7280',
                    stepSize: 1
                }
            },
            y: {
                grid: { display: false },
                ticks: { color: '#6b7280' }
            }
        }
    }
});
</script>
@endsection
