@extends('layouts.admin')

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

            <!-- Status Filter -->
            <form method="GET" action="{{ route('admin.complaints.index') }}" id="filterForm" class="flex items-center gap-3">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                <select name="filter_status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Complaints</option>
                    <option value="pending" {{ request('filter_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('filter_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                </select>

                <!-- Date From -->
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Dari Tanggal">

                <!-- Date To -->
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Sampai Tanggal">
            </form>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search -->
            <form method="GET" action="{{ route('admin.complaints.index') }}" class="relative">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="hidden" name="filter_status" value="{{ request('filter_status') }}">
                <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </form>

            <!-- Reset Filter Button -->
            @if(request('search') || request('filter_status') || request('date_from') || request('date_to'))
            <a href="{{ route('admin.complaints.index') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-50 transition" title="Reset Filter">
                <i class="fas fa-redo"></i>
            </a>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor HP</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lampiran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($complaints as $index => $complaint)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ($complaints->currentPage() - 1) * $complaints->perPage() + $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $complaint->id_pengaduan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $complaint->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $complaint->no_hp }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($complaint->judul_laporan, 30) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($complaint->created_at)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($complaint->lokasi ?? '-', 20) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $complaint->id_admin ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($complaint->lampiran && file_exists(public_path($complaint->lampiran)))
                                <img src="{{ asset($complaint->lampiran) }}" class="w-32 rounded">
                            @else
                                <img src="{{ asset('images/news/berita.png') }}" class="w-32 opacity-50">
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($complaint->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif($complaint->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($complaint->status ?? '-') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Lihat Detail -->
                                <a href="{{ route('admin.complaints.show', $complaint->id_pengaduan) }}" class="text-blue-500 hover:text-blue-700 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Approve (hanya untuk status pending) -->
                                @if($complaint->status == 'pending')
                                <button type="button" onclick="confirmApprove('{{ $complaint->id_pengaduan }}', '{{ addslashes($complaint->judul_laporan) }}')" class="text-green-500 hover:text-green-700 transition" title="Approve">
                                    <i class="fas fa-thumbs-up"></i>
                                </button>
                                @endif

                                <!-- Delete -->
                                <button type="button" onclick="confirmDeleteComplaint('{{ $complaint->id_pengaduan }}', '{{ addslashes($complaint->nama) }}')" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada pengaduan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
            <div class="text-sm text-gray-600">
                Showing {{ $complaints->firstItem() ?? 0 }} to {{ $complaints->lastItem() ?? 0 }} of {{ $complaints->total() }} entries
            </div>
            <div class="flex items-center gap-2">
                @if ($complaints->onFirstPage())
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed" disabled>
                        Previous
                    </button>
                @else
                    <a href="{{ $complaints->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">
                        Previous
                    </a>
                @endif

                @foreach ($complaints->getUrlRange(1, $complaints->lastPage()) as $page => $url)
                    @if ($page == $complaints->currentPage())
                        <button class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($complaints->hasMorePages())
                    <a href="{{ $complaints->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">
                        Next
                    </a>
                @else
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed" disabled>
                        Next
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-1">Apakah Anda yakin?</p>
            <p class="text-gray-600 mb-6">Ingin menghapus pengaduan dari: <span id="complaintTitle" class="font-semibold text-gray-900"></span>?</p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                        Tidak
                    </button>
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Approve</h3>
            <p class="text-gray-600 mb-1">Apakah Anda yakin?</p>
            <p class="text-gray-600 mb-6">Ingin menyetujui pengaduan: <span id="approveTitle" class="font-semibold text-gray-900"></span>?</p>

            <form id="approveForm" method="POST" action="">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                        Tidak
                    </button>
                    <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg transition">
                        Ya, Setujui
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

    function confirmDeleteComplaint(id, title) {
        document.getElementById('complaintTitle').textContent = title;
        document.getElementById('deleteForm').action = `/admin/complaints/${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function confirmApprove(id, title) {
        console.log('Approve button clicked for ID:', id);
        document.getElementById('approveTitle').textContent = title;
        document.getElementById('approveForm').action = `/admin/complaints/${id}/approve`;
        document.getElementById('approveModal').classList.remove('hidden');
        console.log('Form action set to:', document.getElementById('approveForm').action);
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    document.getElementById('approveModal').addEventListener('click', function(e) {
        if (e.target === this) closeApproveModal();
    });

    // Auto hide success alert
    @if(session('success'))
        setTimeout(() => {
            const alert = document.querySelector('.bg-green-100');
            if (alert) alert.remove();
        }, 5000);
    @endif

    // Auto hide error alert
    @if(session('error'))
        setTimeout(() => {
            const alert = document.querySelector('.bg-red-100');
            if (alert) alert.remove();
        }, 5000);
    @endif
</script>
@endsection
