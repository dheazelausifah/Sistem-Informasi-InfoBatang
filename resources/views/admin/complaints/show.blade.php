@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $complaint->judul_laporan }}</h2>
            <p class="text-sm text-gray-500 mt-1">Lihat Pengaduan</p>
        </div>
        <a href="{{ route('admin.complaints.index') }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Main Content Card -->
        <div class="w-full h-[420px] bg-gray-200 overflow-hidden relative">
            @if(
                $complaint->lampiran &&
                file_exists(public_path($complaint->lampiran))
            )
                <img
                    src="{{ asset($complaint->lampiran) }}"
                    alt="{{ $complaint->judul_laporan }}"
                    class="absolute inset-0 w-full h-full object-cover"
                >
            @else
                <img
                    src="{{ asset('images/news/berita.png') }}"
                    alt="Tidak ada lampiran"
                    class="absolute inset-0 w-full h-full object-cover opacity-70"
                >
            @endif
        </div>
        <!-- Content Section -->
        <div class="p-6">
            <!-- Meta Info -->
            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4 pb-4 border-b">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user"></i>
                    <span>{{ $complaint->nama }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::parse($complaint->created_at)->format('d-m-Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $complaint->lokasi ?? 'Tidak disebutkan' }}</span>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-6">
                @if($complaint->status == 'pending')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i> Pending
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i> Approved
                    </span>
                @endif
            </div>

            <!-- Isi Laporan -->
            <div class="prose max-w-none mb-6">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $complaint->isi_laporan }}</p>
            </div>

            <!-- Detail Info Grid -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Informasi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">ID Pengaduan</label>
                        <p class="text-gray-900">{{ $complaint->id_pengaduan }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Nomor HP</label>
                        <p class="text-gray-900">{{ $complaint->no_hp }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Tanggal Pengaduan</label>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($complaint->created_at)->format('d F Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Status</label>
                        <p class="text-gray-900">{{ ucfirst($complaint->status) }}</p>
                    </div>
                    @if($complaint->id_admin)
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Admin Penerima</label>
                        <p class="text-gray-900">{{ $complaint->id_admin }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                @if($complaint->status == 'pending')
                <form method="POST" action="{{ route('admin.complaints.approve', $complaint->id_pengaduan) }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg">
                        Konfirmasi
                    </button>
                </form>
                @endif


                <button type="button" onclick="confirmDelete()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-trash"></i>
                    Hapus
                </button>
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
            <p class="text-gray-600 mb-6">Ingin menghapus pengaduan ini?</p>

            <form action="{{ route('admin.complaints.destroy', $complaint->id_pengaduan) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition">
                        Tidak
                    </button>
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition">
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Auto hide success alert
    @if(session('success'))
        setTimeout(() => {
            const alert = document.querySelector('.bg-green-100');
            if (alert) alert.remove();
        }, 3000);
    @endif
</script>
@endsection
