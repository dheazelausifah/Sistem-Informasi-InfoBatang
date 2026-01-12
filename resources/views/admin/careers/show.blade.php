@extends('layouts.admin')

@section('title', 'Detail Lowongan')
@section('page-title', 'Lihat Detail Karir')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Pekerjaan</h2>
    </div>

    <!-- Detail Container -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header Card -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-gray-900">{{ $career->judul }}</h3>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $career->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($career->status) }}
                </span>
            </div>

            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-briefcase w-5"></i>
                    <span>{{ $career->tipe_pekerjaan }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt w-5"></i>
                    <span>{{ $career->lokasi }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-layer-group w-5"></i>
                    <span>{{ $career->level }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-money-bill-wave w-5"></i>
                    <span>{{ $career->gaji ?? 'Negotiable' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar w-5"></i>
                    <span>{{ $career->created_at->format('Y-m-d') }}</span>
                </div>
            </div>
        </div>

        <!-- Body Card -->
        <div class="p-6 space-y-6">
            <!-- Deskripsi Pekerjaan -->
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Pekerjaan</h4>
                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $career->deskripsi }}</p>
            </div>

            <!-- Tugas & Tanggung Jawab -->
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-3">Tugas & Tanggung Jawab</h4>
                <div class="text-gray-700 text-sm leading-relaxed space-y-2">
                    @php
                        $tanggungJawab = explode("\n", $career->tanggung_jawab);
                    @endphp
                    @foreach($tanggungJawab as $index => $item)
                        @if(trim($item))
                            <p>{{ $index + 1 }}. {{ trim($item) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Kualifikasi -->
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-3">Kualifikasi</h4>
                <div class="text-gray-700 text-sm leading-relaxed space-y-2">
                    @php
                        $kualifikasi = explode("\n", $career->kualifikasi);
                    @endphp
                    @foreach($kualifikasi as $item)
                        @if(trim($item))
                            <p>• {{ trim($item) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-3">
            <a href="{{ route('admin.careers.edit', $career->id_karir) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition text-center">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.careers.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition text-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
