@extends('layouts.admin')

@section('title', 'Kelola Karir')
@section('page-title', 'Karir')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Filter & Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- Show entries -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Show</label>
                <select onchange="changePerPage(this.value)" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <!-- Filter Status -->
            <form method="GET" action="{{ route('admin.careers.index') }}" id="quickFilterForm">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                <select name="filter_status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Careers</option>
                    <option value="aktif" {{ request('filter_status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('filter_status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </form>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search -->
            <form method="GET" action="{{ route('admin.careers.index') }}" class="relative">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="hidden" name="filter_status" value="{{ request('filter_status') }}">
                <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </form>

            <!-- Reset Filter Button -->
            @if(request('search') || request('filter_status') || request('tanggal_dari') || request('tanggal_sampai'))
            <a href="{{ route('admin.careers.index') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-50 transition" title="Reset Filter">
                <i class="fas fa-redo"></i>
            </a>
            @endif

            <!-- Filter Button -->
            <button onclick="toggleFilterModal()" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-50 transition" title="Advanced Filter">
                <i class="fas fa-filter"></i>
            </button>

             <!-- Add Button -->
            <a href="{{ route('admin.careers.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Tambah Lowongan
            </a>
        </div>
    </div>

    <!-- Active Filters Display -->
    @if(request('filter_status') || request('tanggal_dari') || request('tanggal_sampai'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm font-semibold text-blue-800">Active Filters:</span>

            @if(request('filter_status'))
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                Status: {{ ucfirst(request('filter_status')) }}
                <a href="{{ route('admin.careers.index', array_filter(request()->except('filter_status'))) }}" class="ml-1 hover:text-blue-600">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif

            @if(request('tanggal_dari') || request('tanggal_sampai'))
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                Tanggal: {{ request('tanggal_dari') ?? 'Awal' }} - {{ request('tanggal_sampai') ?? 'Akhir' }}
                <a href="{{ route('admin.careers.index', array_filter(request()->except(['tanggal_dari', 'tanggal_sampai']))) }}" class="ml-1 hover:text-blue-600">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Id Karir</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul Lowongan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Post</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Id Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($careers as $index => $career)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ($careers->currentPage() - 1) * $careers->perPage() + $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $career->id_karir }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $career->judul }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="max-w-xs" title="{{ $career->deskripsi }}">
                                {{ Str::limit($career->deskripsi, 80) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">{{ $career->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $career->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $career->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $career->id_admin }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.careers.show', $career->id_karir) }}" class="text-blue-500 hover:text-blue-700 transition" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.careers.edit', $career->id_karir) }}" class="text-yellow-500 hover:text-yellow-700 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="confirmDeleteCareer('{{ $career->id_karir }}', '{{ $career->judul }}')" class="text-red-500 hover:text-red-700 transition" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium">Belum ada data lowongan</p>
                                <a href="{{ route('admin.careers.create') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-2">Tambah Lowongan Pertama</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-600">
                @if($careers->total() > 0)
                    Showing {{ $careers->firstItem() }} to {{ $careers->lastItem() }} of {{ $careers->total() }} entries
                @else
                    No entries found
                @endif
            </div>
            <div class="flex items-center gap-2">
                {{ $careers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filter Modal -->
<div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Filter Lowongan</h3>
                <button onclick="toggleFilterModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form method="GET" action="{{ route('admin.careers.index') }}" class="space-y-4">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="filter_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('filter_status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('filter_status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <!-- Date Range Filter -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="resetFilter()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                        Reset
                    </button>
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteCareerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-1">Apakah Anda yakin?</p>
            <p class="text-gray-600 mb-6">Ingin menghapus lowongan <span id="careerTitle" class="font-semibold text-gray-900"></span>?</p>

            <form id="deleteCareerForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteCareerModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}

function toggleFilterModal() {
    const modal = document.getElementById('filterModal');
    modal.classList.toggle('hidden');
    document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
}

function resetFilter() {
    window.location.href = '{{ route('admin.careers.index') }}';
}

function confirmDeleteCareer(id, title) {
    document.getElementById('careerTitle').textContent = title;
    document.getElementById('deleteCareerForm').action = "{{ url('admin/careers') }}/" + id;
    document.getElementById('deleteCareerModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteCareerModal() {
    document.getElementById('deleteCareerModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
document.getElementById('deleteCareerModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteCareerModal();
});

document.getElementById('filterModal').addEventListener('click', function(e) {
    if (e.target === this) toggleFilterModal();
});

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteCareerModal();
        const filterModal = document.getElementById('filterModal');
        if (!filterModal.classList.contains('hidden')) {
            toggleFilterModal();
        }
    }
});

// Auto hide success alert
@if(session('success') || session('error'))
    setTimeout(() => {
        const alert = document.querySelector('[role="alert"]');
        if (alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
@endif
</script>
@endsection
