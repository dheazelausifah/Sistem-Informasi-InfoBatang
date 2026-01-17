@extends('layouts.main')

@section('title', $berita->judul . ' - InfoBatang')

@section('content')

<div class="bg-white min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
            <a href="{{ route('terkini') }}" class="hover:text-blue-600 transition">Beranda</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('berita.kategori', $berita->id_kategori) }}" class="hover:text-blue-600 transition">
                {{ $berita->category->nama_kategori }}
            </a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Detail</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- MAIN CONTENT (2/3) - TANPA BOX -->
            <div class="lg:col-span-2">

                <!-- Article (NO BOX) -->
                <article class="mb-8">

                    <!-- Category Badge -->
                    <span class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded uppercase mb-3">
                        {{ $berita->category->nama_kategori }}
                    </span>

                    <!-- Title -->
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-4">
                        {{ $berita->judul }}
                    </h1>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-person-circle text-gray-400"></i>
                            <span class="font-medium">InfoBatang</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar3 text-gray-400"></i>
                            <span>{{ $berita->tanggal_publish ? $berita->tanggal_publish->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-clock text-gray-400"></i>
                            <span>{{ $berita->tanggal_publish ? $berita->tanggal_publish->format('H:i') : '-' }} WIB</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-eye text-gray-400"></i>
                            <span>{{ number_format($berita->views) }}</span>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <img src="{{ $berita->image_url }}"
                         alt="{{ $berita->judul }}"
                         class="w-full h-auto rounded-lg mb-6"
                         onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1200&h=600&fit=crop'">

                    <!-- Article Content -->
                    <div class="prose max-w-none text-gray-700 text-base leading-relaxed mb-6">
                        {!! nl2br(e($berita->isi)) !!}
                    </div>

                    <!-- Share Buttons -->
                    <div class="flex items-center gap-3 py-4 border-t border-gray-200">
                        <span class="text-sm text-gray-600 font-medium">Bagikan:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank"
                           class="w-9 h-9 flex items-center justify-center bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($berita->judul) }}"
                           target="_blank"
                           class="w-9 h-9 flex items-center justify-center bg-sky-500 text-white rounded hover:bg-sky-600 transition">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . request()->url()) }}"
                           target="_blank"
                           class="w-9 h-9 flex items-center justify-center bg-green-500 text-white rounded hover:bg-green-600 transition">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <button onclick="copyLink()"
                                class="w-9 h-9 flex items-center justify-center bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>

                </article>

                <!-- Comment Section with Pagination -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-1 flex items-center gap-2">
                        <i class="bi bi-chat-left-text text-blue-600"></i>
                        Komentar ({{ $berita->comments_count }})
                    </h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Alamat email Anda tidak akan dipublikasikan. <span class="text-red-500">*</span> wajib diisi
                    </p>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif

                    <!-- Comment Form -->
                    <form action="{{ route('berita.comment.store', $berita->id_berita) }}" method="POST" class="mb-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nama Anda" value="{{ old('nama') }}" required>
                                @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="email@example.com" value="{{ old('email') }}" required>
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Komentar <span class="text-red-500">*</span>
                            </label>
                            <textarea name="isi_komentar" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Tulis komentar Anda..." required>{{ old('isi_komentar') }}</textarea>
                            @error('isi_komentar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            <i class="bi bi-send-fill mr-2"></i>Kirim Komentar
                        </button>
                    </form>

                    <!-- Comment List with Pagination -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">
                            Semua Komentar ({{ $berita->comments_count }})
                        </h4>

                        @if(count($comments) > 0)
                        <!-- Comment List -->
                        <div id="commentList" class="space-y-4 mb-6">
                            @foreach($comments as $index => $comment)
                            <div class="comment-item p-4 bg-gray-50 rounded-lg border border-gray-200 {{ $index >= 5 ? 'hidden' : '' }}" data-index="{{ $index }}">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-person-circle text-blue-600 text-xl"></i>
                                        <span class="font-semibold text-gray-900">{{ $comment->nama }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($comment->tanggal_komentar)->format('d M Y, H:i') }} WIB
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $comment->isi_komentar }}
                                </p>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if(count($comments) > 5)
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-sm text-gray-600 mr-2">Halaman:</span>
                            <div id="paginationButtons" class="flex items-center gap-2">
                                <!-- Pagination buttons will be generated by JavaScript -->
                            </div>
                            <button onclick="nextPage()" class="ml-2 px-3 py-1 bg-orange-500 text-white text-sm font-semibold rounded hover:bg-orange-600 transition">
                                Selanjutnya
                            </button>
                        </div>
                        @endif
                        @else
                        <div class="text-center py-12">
                            <i class="bi bi-chat-dots text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama!</p>
                        </div>
                        @endif
                    </div>

                </div>

            </div>

            <!-- SIDEBAR (1/3) -->
            <aside class="lg:col-span-1">

                <!-- Berita Terkait dengan Read More -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 sticky top-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 pb-3 border-b-2 border-blue-600">
                        Berita Terkait Lainnya
                    </h3>

                    <div id="relatedNews" class="space-y-4">
                        @forelse($beritaTerkait->take(3) as $related)
                        <a href="{{ route('berita.detail', $related->id_berita) }}"
                           class="block group hover:bg-gray-50 p-3 rounded-lg transition related-news-item">
                            <div class="flex gap-3">
                                <img src="{{ $related->image_url }}"
                                     alt="{{ $related->judul }}"
                                     class="w-24 h-20 object-cover rounded flex-shrink-0"
                                     onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=200&h=150&fit=crop'">
                                <div class="flex-1 min-w-0">
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded mb-1">
                                        {{ $related->category->nama_kategori }}
                                    </span>
                                    <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition leading-snug">
                                        {{ $related->judul }}
                                    </h4>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                        <span><i class="bi bi-calendar3"></i> {{ $related->tanggal_publish ? $related->tanggal_publish->format('d M Y') : '-' }}</span>
                                        <span><i class="bi bi-eye"></i> {{ number_format($related->views) }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada berita terkait</p>
                        @endforelse

                        @if(count($beritaTerkait) > 3)
                        <!-- Hidden News (Initially) -->
                        @foreach($beritaTerkait->skip(3) as $related)
                        <a href="{{ route('berita.detail', $related->id_berita) }}"
                           class="hidden block group hover:bg-gray-50 p-3 rounded-lg transition related-news-item">
                            <div class="flex gap-3">
                                <img src="{{ $related->image_url }}"
                                     alt="{{ $related->judul }}"
                                     class="w-24 h-20 object-cover rounded flex-shrink-0"
                                     onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=200&h=150&fit=crop'">
                                <div class="flex-1 min-w-0">
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded mb-1">
                                        {{ $related->category->nama_kategori }}
                                    </span>
                                    <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition leading-snug">
                                        {{ $related->judul }}
                                    </h4>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                        <span><i class="bi bi-calendar3"></i> {{ $related->tanggal_publish ? $related->tanggal_publish->format('d M Y') : '-' }}</span>
                                        <span><i class="bi bi-eye"></i> {{ number_format($related->views) }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach

                        <!-- Read More Button -->
                        <button onclick="toggleRelatedNews()" id="readMoreBtn" class="w-full mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                            <i class="bi bi-chevron-down mr-2"></i>
                            <span id="readMoreText">Lihat Lebih Banyak</span>
                        </button>
                        @endif
                    </div>

                </div>

            </aside>

        </div>
    </div>
</div>

<script>
// Comment Pagination
const totalComments = {{ count($comments) }};
const commentsPerPage = 5;
const totalPages = Math.ceil(totalComments / commentsPerPage);
let currentPage = 1;

// Initialize pagination
function initPagination() {
    if (totalComments <= commentsPerPage) return;

    const paginationContainer = document.getElementById('paginationButtons');
    paginationContainer.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.onclick = () => goToPage(i);
        button.className = `w-8 h-8 rounded ${i === 1 ? 'bg-orange-500 text-white' : 'bg-white border border-gray-300 text-gray-700'} hover:bg-orange-500 hover:text-white transition font-semibold`;
        button.setAttribute('data-page', i);
        paginationContainer.appendChild(button);
    }
}

function goToPage(page) {
    if (page < 1 || page > totalPages) return;

    currentPage = page;
    const comments = document.querySelectorAll('.comment-item');

    // Hide all comments
    comments.forEach(comment => {
        comment.classList.add('hidden');
    });

    // Show comments for current page
    const start = (page - 1) * commentsPerPage;
    const end = start + commentsPerPage;

    for (let i = start; i < end && i < totalComments; i++) {
        comments[i].classList.remove('hidden');
    }

    // Update pagination buttons
    const buttons = document.querySelectorAll('[data-page]');
    buttons.forEach(btn => {
        const btnPage = parseInt(btn.getAttribute('data-page'));
        if (btnPage === page) {
            btn.className = 'w-8 h-8 rounded bg-orange-500 text-white hover:bg-orange-500 hover:text-white transition font-semibold';
        } else {
            btn.className = 'w-8 h-8 rounded bg-white border border-gray-300 text-gray-700 hover:bg-orange-500 hover:text-white transition font-semibold';
        }
    });

    // Scroll to comments section
    document.getElementById('commentList').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function nextPage() {
    if (currentPage < totalPages) {
        goToPage(currentPage + 1);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initPagination();
});

// Related News Toggle
let isExpanded = false;
function toggleRelatedNews() {
    const items = document.querySelectorAll('.related-news-item.hidden');
    const btn = document.getElementById('readMoreBtn');
    const btnText = document.getElementById('readMoreText');
    const btnIcon = btn.querySelector('i');

    isExpanded = !isExpanded;

    items.forEach(item => {
        item.classList.toggle('hidden', !isExpanded);
        item.classList.toggle('block', isExpanded);
    });

    if (isExpanded) {
        btnText.textContent = 'Lihat Lebih Sedikit';
        btnIcon.classList.replace('bi-chevron-down', 'bi-chevron-up');
    } else {
        btnText.textContent = 'Lihat Lebih Banyak';
        btnIcon.classList.replace('bi-chevron-up', 'bi-chevron-down');
    }
}

// Copy Link
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
        toast.innerHTML = '<i class="bi bi-check-circle-fill"></i> Link berhasil disalin!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    });
}
</script>

@endsection
