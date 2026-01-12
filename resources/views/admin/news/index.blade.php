@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative flex items-center justify-between" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative flex items-center justify-between" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
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

            <!-- Category Filter -->
            <form method="GET" action="{{ route('admin.news.index') }}" id="filterForm">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                <select name="kategori" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All News</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id_kategori }}" {{ request('kategori') == $cat->id_kategori ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search -->
            <form method="GET" action="{{ route('admin.news.index') }}" class="relative">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </form>

            <!-- Reset Filter Button -->
            @if(request('search') || request('kategori') || request('status') || request('tanggal_dari') || request('tanggal_sampai'))
            <a href="{{ route('admin.news.index') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-50 transition" title="Reset Filter">
                <i class="fas fa-redo"></i>
            </a>
            @endif

            <!-- Filter Button -->
            <button onclick="toggleFilterModal()" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-50 transition" title="Advanced Filter">
                <i class="fas fa-filter"></i>
            </button>

            <!-- Add Button -->
            <a href="{{ route('admin.news.create') }}"
               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg shadow transition">
                <i class="fas fa-plus-circle"></i>
                Tambah Berita
            </a>
        </div>
    </div>

    <!-- Active Filters Display -->
    @if(request('kategori') || request('status') || request('tanggal_dari') || request('tanggal_sampai'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm font-semibold text-blue-800">Active Filters:</span>

            @if(request('kategori'))
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                Kategori: {{ $categories->firstWhere('id_kategori', request('kategori'))->nama_kategori ?? request('kategori') }}
                <a href="{{ route('admin.news.index', array_filter(request()->except('kategori'))) }}" class="ml-1 hover:text-blue-600">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif

            @if(request('status'))
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                Status: {{ ucfirst(request('status')) }}
                <a href="{{ route('admin.news.index', array_filter(request()->except('status'))) }}" class="ml-1 hover:text-blue-600">
                    <i class="fas fa-times"></i>
                </a>
            </span>
            @endif

            @if(request('tanggal_dari') || request('tanggal_sampai'))
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                Tanggal: {{ request('tanggal_dari') ?? 'Awal' }} - {{ request('tanggal_sampai') ?? 'Akhir' }}
                <a href="{{ route('admin.news.index', array_filter(request()->except(['tanggal_dari', 'tanggal_sampai']))) }}" class="ml-1 hover:text-blue-600">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Berita</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul Berita</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Isi Berita</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Publish</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($news as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $news->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->id_berita }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="max-w-xs truncate" title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="max-w-xs truncate" title="{{ strip_tags($item->isi) }}">
                                {{ Str::limit(strip_tags($item->isi), 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            {{ $item->tanggal_publish ? $item->tanggal_publish->format('Y-m-d H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($item->gambar)
                                <img src="{{ $item->image_url }}" alt="Gambar" class="w-12 h-12 object-cover rounded cursor-pointer hover:scale-110 transition" onclick="showImageModal('{{ $item->image_url }}', '{{ $item->judul }}')">
                            @else
                                <span class="text-gray-400 text-xs italic">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {!! $item->status_badge !!}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $item->category->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $item->id_admin }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.news.edit', $item->id_berita) }}"
                                   class="text-orange-500 hover:text-orange-700 transition"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="deleteForm-{{ $item->id_berita }}" action="{{ route('admin.news.destroy', $item->id_berita) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmDelete('{{ $item->id_berita }}', '{{ addslashes($item->judul) }}')"
                                            class="text-red-500 hover:text-red-700 transition"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium">Tidak ada data berita</p>
                                <p class="text-sm mt-1">Silakan tambahkan berita baru dengan klik tombol "Tambah Berita"</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex flex-wrap items-center justify-between border-t border-gray-200 gap-4">
            <div class="text-sm text-gray-600">
                @if($news->total() > 0)
                    Showing {{ $news->firstItem() }} to {{ $news->lastItem() }} of {{ $news->total() }} entries
                @else
                    No entries found
                @endif
            </div>
            <div class="flex items-center gap-2">
                {{ $news->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filter Modal -->
<div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Filter Berita</h3>
                <button onclick="toggleFilterModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form method="GET" action="{{ route('admin.news.index') }}" class="space-y-4">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                <!-- Kategori Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id_kategori }}" {{ request('kategori') == $cat->id_kategori ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
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

<!-- Modal untuk Preview Gambar -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2 z-10">
            <i class="fas fa-times text-xl"></i>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-screen rounded-lg">
        <p id="modalCaption" class="text-white text-center mt-4 text-lg"></p>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-1">Apakah Anda yakin?</p>
            <p class="text-gray-600 mb-6">Ingin menghapus berita <span id="newsTitle" class="font-semibold text-gray-900"></span>?</p>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                    Tidak
                </button>
                <button onclick="submitDelete()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteFormId = null;

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
    window.location.href = '{{ route('admin.news.index') }}';
}

function confirmDelete(id, title) {
    deleteFormId = id;
    document.getElementById('newsTitle').textContent = title;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    deleteFormId = null;
}

function submitDelete() {
    if (deleteFormId) {
        document.getElementById('deleteForm-' + deleteFormId).submit();
    }
}

function showImageModal(imageUrl, caption) {
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('modalCaption').textContent = caption;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
        closeDeleteModal();
        toggleFilterModal();
    }
});

// Auto-hide success/error message after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endsection
