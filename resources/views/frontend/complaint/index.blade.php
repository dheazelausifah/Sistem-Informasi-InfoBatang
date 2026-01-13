@extends('layouts.main')

@section('title', 'Pengaduan')

@section('content')

<!-- HERO SECTION -->
<section class="bg-gradient-to-r from-blue-700 to-blue-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-center gap-4">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                <i class="bi bi-chat-square-text text-4xl"></i>
            </div>
            <h1 class="font-philosopher text-3xl md:text-4xl font-bold">
                Layanan Pengaduan Online InfoBatang
            </h1>
        </div>
    </div>
</section>

<!-- INFO BOX -->
<section class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-md p-6 flex items-start gap-4 border-l-4 border-blue-600">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-file-text text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-2">Laporkan Pengaduan Anda !</h3>
                    <p class="text-gray-600 text-sm">
                        Selamat datang di menu ini anda dapat melaporkan keluhan dengan mengisi form dibawah berikut.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FORM SECTION -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">

                @if(session('sukses'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="bi bi-check-lg text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-800">Terima kasih!</h4>
                            <p class="text-green-700 text-sm">{{ session('sukses') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <h4 class="font-bold text-red-800 mb-2">Terjadi Kesalahan:</h4>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-6">

                        <!-- Left Column -->
                        <div class="space-y-6">

                            <!-- Nama -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="nama"
                                       value="{{ old('nama') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('nama') border-red-500 @enderror"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel"
                                       name="telepon"
                                       value="{{ old('telepon') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('telepon') border-red-500 @enderror"
                                       placeholder="08xx-xxxx-xxxx"
                                       required>
                                @error('telepon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori Berita -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori Berita <span class="text-red-500">*</span>
                                </label>
                                <select name="kategori"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('kategori') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->nama_kategori }}" {{ old('kategori') == $category->nama_kategori ? 'selected' : '' }}>
                                            {{ $category->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Kejadian -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Kejadian <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       name="tanggal_kejadian"
                                       value="{{ old('tanggal_kejadian') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('tanggal_kejadian') border-red-500 @enderror"
                                       required>
                                @error('tanggal_kejadian')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lokasi Kejadian -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Lokasi Kejadian <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="lokasi_kejadian"
                                       value="{{ old('lokasi_kejadian') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('lokasi_kejadian') border-red-500 @enderror"
                                       placeholder="Contoh: Jl. Pemuda No. 123"
                                       required>
                                @error('lokasi_kejadian')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">

                            <!-- Judul Laporan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Judul Laporan <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="judul"
                                       value="{{ old('judul') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('judul') border-red-500 @enderror"
                                       placeholder="Judul singkat pengaduan"
                                       required>
                                @error('judul')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Isi Laporan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Isi Laporan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="isi_pengaduan"
                                          rows="8"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none @error('isi_pengaduan') border-red-500 @enderror"
                                          placeholder="Jelaskan detail pengaduan Anda..."
                                          required>{{ old('isi_pengaduan') }}</textarea>
                                @error('isi_pengaduan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Lampiran -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Upload Lampiran (PNG, JPG, DOC, PDF)
                                </label>
                                <div class="relative">
                                    <input type="file"
                                           name="foto"
                                           id="fileUpload"
                                           accept=".png,.jpg,.jpeg,.doc,.docx,.pdf"
                                           class="hidden"
                                           onchange="updateFileName(this)">
                                    <label for="fileUpload"
                                           class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                                        <div class="text-center">
                                            <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600 font-medium" id="fileLabel">
                                                Klik untuk upload file
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Maksimal 10MB
                                            </p>
                                        </div>
                                    </label>
                                </div>
                                @error('foto')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center">
                        <button type="submit"
                                class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-16 py-4 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                            Lapor Sekarang
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>

<!-- Success Modal (Hidden by default) -->
<div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative animate-fade-in">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="bi bi-x-lg text-2xl"></i>
        </button>

        <div class="text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-check-lg text-green-500 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Terima kasih!</h3>
            <p class="text-gray-600 mb-6">
                Laporan anda segera diproses !
            </p>
            <button onclick="closeModal()"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-8 py-3 rounded-lg transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function updateFileName(input) {
    const label = document.getElementById('fileLabel');
    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
    } else {
        label.textContent = 'Klik untuk upload file';
    }
}

function closeModal() {
    document.getElementById('successModal').classList.add('hidden');
}

// Show modal if success message exists
@if(session('sukses'))
    setTimeout(() => {
        document.getElementById('successModal').classList.remove('hidden');
    }, 300);
@endif
</script>
@endsection
