@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-100px)]">
    <div class="w-full max-w-md">
        <!-- Form Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Form Header -->
            <div class="text-center py-6 px-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Edit Kategori</h2>
                <p class="text-sm text-gray-600">Silakan perbarui informasi kategori berita.</p>
            </div>

            <form action="{{ route('admin.categories.update', $category->id_kategori) }}" method="POST" class="px-6 pb-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- ID Kategori (Disabled) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Id Kategori</label>
                        <input type="text" value="{{ $category->id_kategori }}" disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">ID tidak dapat diubah</p>
                    </div>

                    <!-- Nama Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" placeholder="Contoh: Festival, Olahraga, Budaya"
                            value="{{ old('nama_kategori', $category->nama_kategori) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('nama_kategori') border-red-500 @enderror">
                        @error('nama_kategori')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" placeholder="Deskripsi kategori (opsional)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $category->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                        Update
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="block w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="success-alert">
    {{ session('success') }}
</div>
<script>
    setTimeout(() => {
        document.getElementById('success-alert').remove();
    }, 3000);
</script>
@endif

@if(session('error'))
<div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="error-alert">
    {{ session('error') }}
</div>
<script>
    setTimeout(() => {
        document.getElementById('error-alert').remove();
    }, 3000);
</script>
@endif
@endsection
