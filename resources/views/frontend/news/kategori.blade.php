@extends('layouts.main')

@section('title', $category->nama_kategori . ' - Berita')

@php
use Illuminate\Support\Facades\DB;
@endphp

@section('content')

<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-6">

        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('terkini') }}" class="hover:text-blue-600 transition">Beranda</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">{{ $category->nama_kategori }}</span>
            </nav>
        </div>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="inline-block px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold mb-4">
                <i class="bi bi-tag-fill mr-1"></i> {{ $category->nama_kategori }}
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Berita {{ $category->nama_kategori }}
            </h1>
            <p class="text-gray-600">
                Menampilkan {{ $berita->total() }} berita terkini
            </p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- News Grid (Left) -->
            <div class="lg:col-span-2">

                @if($berita->count() > 0)
                    <!-- News Grid - Tampilan Kotak Kecil -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        @foreach($berita as $item)
                        <a href="{{ route('berita.detail', $item->id_berita) }}"
                           class="group bg-white rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 flex flex-col h-full">

                            <!-- Image -->
                            <div class="relative overflow-hidden">
                                <img src="{{ $item->image_url }}"
                                     class="w-full h-44 object-cover"
                                     alt="{{ $item->judul }}"
                                     onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=600&h=400&fit=crop'">
                            </div>

                            <!-- Content -->
                            <div class="p-4 flex flex-col flex-1">
                                <!-- Category Badge -->
                                <div class="inline-block text-xs text-blue-600 font-semibold mb-2 uppercase tracking-wide">
                                    {{ $item->category->nama_kategori }}
                                </div>

                                <!-- Title with Simple Highlight -->
                                <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug mb-3 flex-1 group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $item->judul }}
                                </h3>

                                <!-- Footer -->
                                <div class="pt-3 border-t border-gray-100">
                                    <!-- Meta Info -->
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                        <span class="font-medium text-gray-700">InfoBatang</span>
                                        <span>{{ $item->tanggal_publish ? $item->tanggal_publish->format('d M Y') : '-' }}</span>
                                    </div>

                                    <!-- Stats -->
                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <i class="bi bi-eye"></i>
                                            <span>{{ $item->formatted_views ?? $item->views }}</span>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="bi bi-chat-dots"></i>
                                            <span>{{ $item->comments_count ?? 0 }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- Custom Pagination (12 items per page) -->
                    @if($berita->hasPages())
                    <div class="flex items-center justify-center gap-2 mt-8">
                        <span class="text-sm text-gray-600 mr-2">Halaman:</span>

                        <!-- Page Numbers -->
                        @foreach(range(1, $berita->lastPage()) as $page)
                            @if($page == $berita->currentPage())
                                <button class="w-9 h-9 rounded bg-orange-500 text-white font-semibold text-sm hover:bg-orange-600 transition">
                                    {{ $page }}
                                </button>
                            @else
                                <a href="{{ $berita->url($page) }}"
                                   class="w-9 h-9 rounded bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-orange-500 hover:text-white hover:border-orange-500 transition flex items-center justify-center">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        <!-- Next Button -->
                        @if($berita->hasMorePages())
                        <a href="{{ $berita->nextPageUrl() }}"
                           class="ml-2 px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded hover:bg-orange-600 transition">
                            Selanjutnya
                        </a>
                        @endif
                    </div>
                    @endif

                @else
                    <!-- Empty State -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-16 text-center">
                        <i class="bi bi-info-circle text-6xl text-blue-500 mb-4 block"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Berita</h3>
                        <p class="text-gray-600">Belum ada berita di kategori ini</p>
                        <a href="{{ route('terkini') }}" class="inline-block mt-6 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            Lihat Berita Lainnya
                        </a>
                    </div>
                @endif

            </div>

            <!-- Sidebar (Right) -->
            <div class="lg:col-span-1">

                <!-- Trending Headlines -->
                <div class="bg-white rounded-xl p-6 mb-6 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-bold text-gray-900">Trending Headlines</h3>
                        <a href="{{ route('terkini') }}" class="text-xs text-blue-600 font-semibold hover:underline">
                            View All
                        </a>
                    </div>

                    @php
                        $trending = \App\Models\News::with('category')
                            ->where('status', 'publish')
                            ->orderBy('views', 'desc')
                            ->take(5)
                            ->get();

                        foreach($trending as $trend) {
                            $trend->comments_count = DB::table('komentar')
                                ->where('id_berita', $trend->id_berita)
                                ->count();
                        }
                    @endphp

                    <div class="space-y-4">
                        @forelse($trending as $trend)
                        <a href="{{ route('berita.detail', $trend->id_berita) }}"
                           class="group flex gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <!-- Image -->
                            <img src="{{ $trend->image_url }}"
                                 class="w-20 h-16 object-cover rounded-lg flex-shrink-0"
                                 alt="{{ $trend->judul }}"
                                 onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=200&h=150&fit=crop'">

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug mb-1 group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $trend->judul }}
                                </h4>
                                <div class="text-xs text-gray-500">
                                    <span class="text-blue-600 font-medium">{{ $trend->category->nama_kategori }}</span> •
                                    {{ $trend->tanggal_publish ? $trend->tanggal_publish->format('d M') : '-' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-eye"></i> {{ $trend->formatted_views ?? $trend->views }} •
                                    <i class="bi bi-chat-dots"></i> {{ $trend->comments_count }}
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-4">Belum ada berita trending</p>
                        @endforelse
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm sticky top-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-5">Kategori Lainnya</h3>

                    <div class="space-y-2">
                        @foreach($allCategories as $cat)
                        <a href="{{ route('berita.kategori', $cat->id_kategori) }}"
                           class="group flex items-center justify-between py-3 px-4 rounded-lg transition-colors duration-200 {{ $cat->id_kategori == $category->id_kategori ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="text-sm flex items-center gap-2">
                                <i class="bi bi-folder text-xs {{ $cat->id_kategori == $category->id_kategori ? 'text-blue-600' : 'text-gray-400' }}"></i>
                                <span>{{ $cat->nama_kategori }}</span>
                            </span>
                            <span class="text-xs px-2 py-1 rounded-full {{ $cat->id_kategori == $category->id_kategori ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                                {{ $cat->news_count }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection
