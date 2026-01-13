@extends('layouts.main')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-700 to-blue-900 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold mb-4 font-philosopher">Bergabung bersama tim Info Batang<br>dan berkembang bersama kami</h1>
            <p class="text-blue-100 text-lg">Temukan peluang karir terbaik di Infobatang</p>
        </div>
    </div>
</section>

<!-- Job Listings -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">

        @if($lowongan->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($lowongan as $job)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">

                <!-- Header dengan Badge -->
                <div class="flex items-center justify-between mb-4">
                    <!-- Tipe Pekerjaan -->
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                        {{ $job->tipe_pekerjaan == 'Full-time' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $job->tipe_pekerjaan == 'Magang' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $job->tipe_pekerjaan == 'Part-time' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ $job->tipe_pekerjaan }}
                    </span>

                    <!-- Status Badge -->
                    @if($job->status == 'aktif')
                        <span class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                            Aktif
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">
                            Tutup
                        </span>
                    @endif
                </div>

                <!-- Job Title -->
                <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">
                    {{ $job->judul }}
                </h3>

                <!-- Job Details -->
                <div class="space-y-2.5 text-sm text-gray-600 mb-5">
                    <div class="flex items-center">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="bi bi-briefcase text-gray-500"></i>
                        </div>
                        <span class="ml-2">{{ $job->level }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="bi bi-geo-alt text-gray-500"></i>
                        </div>
                        <span class="ml-2">{{ $job->lokasi }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="bi bi-cash-stack text-gray-500"></i>
                        </div>
                        <span class="ml-2">{{ $job->gaji }}</span>
                    </div>
                </div>

                <!-- Footer dengan Timestamp dan Button -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="bi bi-clock mr-1"></i>
                        <span>{{ $job->created_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('career.detail', $job->id_karir) }}"
                       class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition">
                        Lihat Detail <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach

        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $lowongan->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-briefcase text-5xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Lowongan Tersedia</h3>
            <p class="text-gray-500">Saat ini belum ada lowongan pekerjaan yang dibuka. Silakan cek kembali nanti.</p>
        </div>
        @endif

    </div>
</section>
@endsection
