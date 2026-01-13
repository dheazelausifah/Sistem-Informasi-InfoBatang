@extends('layouts.main')

@section('content')
<!-- Breadcrumb -->
<section class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-blue-600">
                <i class="bi bi-house-door"></i> Home
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('karir.index') }}" class="hover:text-blue-600">Karir</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">{{ $lowongan->judul }}</span>
        </nav>
    </div>
</section>

<!-- Job Detail -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">

                    <!-- Job Header -->
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                {{ $lowongan->tipe_pekerjaan == 'Full-time' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $lowongan->tipe_pekerjaan == 'Magang' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $lowongan->tipe_pekerjaan == 'Part-time' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ $lowongan->tipe_pekerjaan }}
                            </span>
                            @if($lowongan->status == 'aktif')
                                <span class="inline-block px-3 py-1 bg-green-500 text-white text-sm font-semibold rounded-full">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-red-500 text-white text-sm font-semibold rounded-full">
                                    Tutup
                                </span>
                            @endif
                        </div>

                        <h1 class="text-3xl font-bold text-gray-800 mb-4 font-poppins">
                            {{ $lowongan->judul }}
                        </h1>

                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="bi bi-briefcase mr-2"></i>
                                <span>{{ $lowongan->level }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bi bi-geo-alt mr-2"></i>
                                <span>{{ $lowongan->lokasi }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bi bi-cash-stack mr-2"></i>
                                <span>{{ $lowongan->gaji }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- Deskripsi Pekerjaan -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Deskripsi Pekerjaan</h2>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($lowongan->deskripsi)) !!}
                        </div>
                    </div>

                    <!-- Tanggung Jawab -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Tanggung Jawab & Tugas Utama</h2>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($lowongan->tanggung_jawab)) !!}
                        </div>
                    </div>

                    <!-- Kualifikasi -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Kualifikasi</h2>
                        <div class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($lowongan->kualifikasi)) !!}
                        </div>
                    </div>

                    <!-- Apply Button -->
                    @if($lowongan->status == 'aktif')
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Tertarik untuk bergabung?</h3>
                        <p class="text-gray-600 text-sm mb-4">Kirimkan lamaran Anda sekarang melalui WhatsApp!</p>
                        <a href="https://wa.me/6285727008125?text=Halo,%20saya%20tertarik%20melamar%20untuk%20posisi%20{{ urlencode($lowongan->judul) }}"
                           target="_blank"
                           class="inline-block px-8 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                            <i class="bi bi-whatsapp mr-2"></i>
                            Kirim Lamaran via WhatsApp
                        </a>
                    </div>
                    @else
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Lowongan Sudah Ditutup</h3>
                        <p class="text-gray-600 text-sm">Mohon maaf, lowongan ini sudah tidak menerima lamaran.</p>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">

                <!-- Job Info Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Lowongan</h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="text-gray-500 block mb-1">Tipe Pekerjaan</span>
                            <span class="font-semibold text-gray-800">{{ $lowongan->tipe_pekerjaan }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-1">Level</span>
                            <span class="font-semibold text-gray-800">{{ $lowongan->level }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-1">Lokasi</span>
                            <span class="font-semibold text-gray-800">{{ $lowongan->lokasi }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-1">Gaji</span>
                            <span class="font-semibold text-gray-800">{{ $lowongan->gaji }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-1">Status</span>
                            <span class="font-semibold {{ $lowongan->status == 'aktif' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($lowongan->status) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-1">Dipublikasi</span>
                            <span class="font-semibold text-gray-800">{{ $lowongan->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <hr class="my-4">

                           <!-- Share Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Bagikan Lowongan</h3>

                    <!-- Social Share Buttons -->
                    <div class="flex gap-2 mb-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank"
                           class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($lowongan->judul) }}"
                           target="_blank"
                           class="flex-1 px-4 py-2.5 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition text-center">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($lowongan->judul . ' - ' . url()->current()) }}"
                           target="_blank"
                           class="flex-1 px-4 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>

                    <!-- Copy Link Button -->
                    <button onclick="copyLink()" id="copyButton"
                            class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-semibold">
                        <i class="bi bi-link-45deg mr-2"></i>
                        Salin Link
                    </button>

                    <!-- Success Message (Hidden) -->
                    <div id="copySuccess" class="hidden mt-3 p-2 bg-green-100 text-green-700 text-xs text-center rounded">
                        <i class="bi bi-check-circle mr-1"></i>
                        Link berhasil disalin!
                    </div>
                </div>

                </div>

            </div>

        </div>

        <!-- Lowongan Lain -->
        @if($lowonganLain->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 font-poppins">Lowongan Lainnya</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @foreach($lowonganLain as $job)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                            {{ $job->tipe_pekerjaan == 'Full-time' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $job->tipe_pekerjaan == 'Magang' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $job->tipe_pekerjaan == 'Part-time' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ $job->tipe_pekerjaan }}
                        </span>
                        @if($job->status == 'aktif')
                            <span class="inline-block px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                Aktif
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">
                        {{ $job->judul }}
                    </h3>

                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <i class="bi bi-briefcase w-5"></i>
                            <span class="ml-2">{{ $job->level }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="bi bi-geo-alt w-5"></i>
                            <span class="ml-2">{{ $job->lokasi }}</span>
                        </div>
                    </div>

                    <a href="{{ route('career.detail', $job->id_karir) }}"
                       class="block text-center px-4 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition text-sm font-semibold">
                        Lihat Detail
                    </a>
                </div>
                @endforeach

            </div>
        </div>
        @endif

    </div>
</section>

<script>
// Copy Link Function
function copyLink() {
    const url = window.location.href;

    // Copy to clipboard
    navigator.clipboard.writeText(url).then(function() {
        // Show success message
        const successMsg = document.getElementById('copySuccess');
        const copyBtn = document.getElementById('copyButton');

        successMsg.classList.remove('hidden');
        copyBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Berhasil Disalin!';

        // Reset after 3 seconds
        setTimeout(function() {
            successMsg.classList.add('hidden');
            copyBtn.innerHTML = '<i class="bi bi-link-45deg mr-2"></i>Salin Link';
        }, 3000);
    }, function(err) {
        alert('Gagal menyalin link');
    });
}
</script>
@endsection
