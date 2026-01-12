@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Filter & Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- Show entries -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Show</label>
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>

            <!-- Category Filter -->
           <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2">
    <label class="text-sm text-gray-600">Filter:</label>
    <select name="filter_category" onchange="this.form.submit()"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">All Categories</option>
        @foreach($allCategories as $cat)
            <option value="{{ $cat->id_kategori }}" {{ request('filter_category') == $cat->id_kategori ? 'selected' : '' }}>
                {{ $cat->nama_kategori }}
            </option>
        @endforeach
    </select>
</form>

        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-2">
    <!-- Search -->
    <form method="GET" action="{{ route('admin.categories.index') }}" class="relative w-full md:w-64">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID atau Nama Kategori"
            class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full">
        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    </form>

    <!-- Reset Filter Button -->
            @if(request('search') || request('filter_berita'))
            <a href="{{ route('admin.categories.index') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-50 transition">
                <i class="fas fa-redo"></i>
            </a>
            @endif

    <!-- Add Button -->
    <a href="{{ route('admin.categories.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
        <i class="fas fa-plus-circle"></i>
        Tambah Kategori
    </a>
</div>
    </div>

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

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Kategori</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $index => $category)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-center text-sm text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">{{ $category->id_kategori }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-700">{{ $category->nama_kategori }}</td>
                        <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $category->deskripsi ?? '-' }}</td>
                        <td class="px-6 text-center py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id_kategori) }}" class="text-orange-500 hover:text-orange-700 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id_kategori) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $category->id_kategori }}', '{{ $category->nama_kategori }}')" class="text-red-500 hover:text-red-700 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada kategori. Silakan tambah kategori baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
            <p class="text-gray-600 mb-6">Ingin menghapus kategori <span id="categoryName" class="font-semibold text-gray-900"></span>?</p>

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

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Berhasil dihapus</h3>
            <p class="text-gray-600 mb-6">Kategori Berita</p>
        </div>
    </div>
</div>

<script>
    let deleteFormId = null;

    function confirmDelete(id, name) {
        deleteFormId = id;
        document.getElementById('categoryName').textContent = name;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteFormId = null;
    }

    function submitDelete() {
        // Show success modal
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('successModal').classList.remove('hidden');

        // Auto close success modal and submit form
        setTimeout(() => {
            document.getElementById('successModal').classList.add('hidden');
            // Submit the actual delete form
            const form = document.querySelector(`form[action*="${deleteFormId}"]`);
            if (form) {
                form.submit();
            }
        }, 1500);
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endsection
