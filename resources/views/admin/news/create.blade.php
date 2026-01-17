@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Berita</h2>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <p class="text-sm text-gray-600">Silakan lengkapi form untuk menambahkan berita.</p>
        </div>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6">
                <!-- Grid 2 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- ID Berita -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Id Berita</label>
                            <input type="text" value="Auto Generate" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        </div>

                        <!-- Judul Berita -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" placeholder="Masukkan judul berita" value="{{ old('judul') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Isi Berita -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Isi Berita <span class="text-red-500">*</span></label>
                            <textarea name="isi" rows="5" placeholder="Tulis isi berita..." required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none @error('isi') border-red-500 @enderror">{{ old('isi') }}</textarea>
                            @error('isi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Kejadian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- Lokasi Kejadian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kejadian</label>
                            <input type="text" name="lokasi_kejadian" placeholder="Masukkan lokasi kejadian" value="{{ old('lokasi_kejadian') }}"
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
                                    <option value="{{ $cat->id_kategori }}" {{ old('id_kategori') == $cat->id_kategori ? 'selected' : '' }}>
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
                            <input type="text" value="{{ auth()->user()->nama ?? 'ADM001' }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Publish -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publish</label>
                            <input type="datetime-local" name="tanggal_publish" value="{{ old('tanggal_publish') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan waktu sekarang (jika status publish)</p>
                        </div>

                        <!-- Upload Multiple Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Gambar (PNG, JPG, JPEG)
                            </label>
                            <div class="relative">
                                <input type="file"
                                    name="gambar[]"
                                    accept=".png,.jpg,.jpeg"
                                    multiple
                                    id="imageInput"
                                    onchange="previewImages(this)"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('gambar') border-red-500 @enderror">
                                @error('gambar')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @error('gambar.*')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle"></i> Max: 10MB per file | Bisa pilih beberapa gambar sekaligus (Ctrl+Click)
                                </p>
                            </div>

                            <!-- Image Preview -->
                            <div id="imagePreview" class="grid grid-cols-3 gap-2 mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('admin.news.index') }}" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition text-center">
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

// Preview Multiple Images
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';

    if (input.files) {
        const maxSize = 10 * 1024 * 1024; // 10MB per file
        let hasError = false;

        Array.from(input.files).forEach((file, index) => {
            // Validasi ukuran per file
            if (file.size > maxSize) {
                alert(`❌ File "${file.name}" terlalu besar!\n\nUkuran: ${(file.size / 1024 / 1024).toFixed(2)} MB\nMaksimal: 10 MB per file`);
                hasError = true;
                return;
            }

            // Validasi tipe file
            if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                alert(`❌ File "${file.name}" bukan gambar yang valid!\nHanya menerima: JPG, JPEG, PNG`);
                hasError = true;
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border-2 border-gray-300 hover:border-blue-500 transition">
                    <button type="button" onclick="removeImage(${index})"
                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-1 rounded-b-lg opacity-0 group-hover:opacity-100 transition">
                        <p class="text-xs truncate">${file.name}</p>
                        <p class="text-xs text-green-300">✓ ${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    </div>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

        // Reset input jika ada error
        if (hasError) {
            input.value = '';
            preview.innerHTML = '<p class="text-red-500 text-sm col-span-3 text-center py-4"><i class="fas fa-exclamation-triangle"></i> Upload dibatalkan karena ada file yang tidak valid</p>';
        }
    }
}

// Remove image from preview
function removeImage(index) {
    const input = document.getElementById('imageInput');
    const dt = new DataTransfer();
    const files = Array.from(input.files);

    files.forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });

    input.files = dt.files;
    previewImages(input);
}
</script>
@endsection
