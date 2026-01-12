@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Berita</h2>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <p class="text-sm text-gray-600">Edit informasi berita</p>
        </div>

        <form action="{{ route('admin.news.update', $news->id_berita) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6">
                <!-- Grid 2 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- ID Berita -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Id Berita</label>
                            <input type="text" value="{{ $news->id_berita }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        </div>

                        <!-- Judul Berita -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" placeholder="Masukkan judul berita" value="{{ old('judul', $news->judul) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Isi Berita -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Isi Berita <span class="text-red-500">*</span></label>
                            <textarea name="isi" rows="5" placeholder="Tulis isi berita..." required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none @error('isi') border-red-500 @enderror">{{ old('isi', $news->isi) }}</textarea>
                            @error('isi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Kejadian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', $news->tanggal_kejadian) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- Lokasi Kejadian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kejadian</label>
                            <input type="text" name="lokasi_kejadian" placeholder="Masukkan lokasi kejadian" value="{{ old('lokasi_kejadian', $news->lokasi_kejadian) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('lokasi_kejadian') border-red-500 @enderror">
                            @error('lokasi_kejadian')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <!-- ID Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="id_kategori" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('id_kategori') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_kategori }}"
                                        {{ old('id_kategori', $news->id_kategori) == $cat->id_kategori ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Admin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin</label>
                            <input type="text" value="{{ $news->id_admin }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="publish" {{ old('status', $news->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Publish -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publish</label>
                            <input type="datetime-local" name="tanggal_publish"
                                value="{{ old('tanggal_publish', $news->tanggal_publish ? $news->tanggal_publish->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan waktu sekarang (jika status publish)</p>
                        </div>

                        <!-- Upload Gambar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Baru (PNG, JPG, JPEG)</label>

                            @if($news->gambar)
                            <div class="mb-3">
                                <img src="{{ $news->image_url }}" alt="Current Image" class="w-32 h-32 object-cover rounded border">
                                <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                            </div>
                            @endif

                            <div class="relative">
                                <input type="file" name="gambar" accept=".png,.jpg,.jpeg"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('gambar') border-red-500 @enderror">
                                @error('gambar')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Max: 2MB. Biarkan kosong jika tidak ingin mengubah gambar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                    Update
                </button>
                <a href="{{ route('admin.news.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto set tanggal publish jika status = publish
    document.querySelector('select[name="status"]').addEventListener('change', function() {
        const tanggalPublish = document.querySelector('input[name="tanggal_publish"]');
        if (this.value === 'publish' && !tanggalPublish.value) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            tanggalPublish.value = now.toISOString().slice(0, 16);
        }
    });
</script>
@endsection
