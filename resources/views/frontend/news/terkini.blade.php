@extends('layouts.main')

@section('title', 'Terkini')

@section('content')

<!-- HERO SLIDER -->
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-6">
        <div class="relative h-[450px] overflow-hidden bg-black rounded-xl max-w-[1400px] mx-auto">
            @forelse($beritaHero as $index => $berita)
            <div class="absolute w-full h-full opacity-0 transition-opacity duration-1000 hero-slide {{ $index === 0 ? '!opacity-100' : '' }}">
                <img src="{{ $berita->image_url }}"
                     alt="{{ $berita->judul }}"
                     class="w-full h-full object-cover opacity-70"
                     onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1200&h=450&fit=crop'">
                <div class="absolute bottom-0 left-0 right-0 p-10 bg-gradient-to-t from-black via-black/50 to-transparent">
                    <h1 class="text-3xl font-bold text-white mb-3 leading-tight max-w-3xl">
                        {{ $berita->judul }}
                    </h1>
                    <div class="flex gap-4 items-center text-xs text-white/90">
                        <span class="flex items-center gap-1">
                            <i class="bi bi-person-circle"></i>
                            InfoBatang
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-calendar3"></i>
                            {{ $berita->tanggal_publish ? $berita->tanggal_publish->format('d M Y') : '-' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-eye"></i>
                            {{ number_format($berita->views) }} views
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="absolute w-full h-full opacity-100">
                <img src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1200&h=450&fit=crop"
                     alt="No news"
                     class="w-full h-full object-cover opacity-70">
                <div class="absolute bottom-0 left-0 right-0 p-10 bg-gradient-to-t from-black via-black/50 to-transparent">
                    <h1 class="text-3xl font-bold text-white mb-2">Belum ada berita tersedia</h1>
                    <p class="text-sm text-white/90">Mohon tunggu update berita terbaru</p>
                </div>
            </div>
            @endforelse

            @if($beritaHero->count() > 1)
            <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                @foreach($beritaHero as $index => $berita)
                <button class="hero-indicator w-2 h-2 rounded-full bg-white/50 transition-all duration-300 {{ $index === 0 ? '!w-6 !bg-white' : '' }}"
                        onclick="showHeroSlide({{ $index }})">
                </button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-white py-10">
    <div class="container mx-auto px-6">

        <!-- SEARCH BAR -->
        <div class="mb-8">
            <form action="{{ route('terkini') }}" method="GET" class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari berita..."
                           class="w-full px-5 py-3 pl-12 pr-24 border-2 border-gray-200 rounded-full focus:border-blue-500 focus:outline-none transition-colors">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors font-semibold text-sm">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- BREAKING NEWS SECTION -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 relative pb-3">
                    Breaking News
                    <span class="absolute bottom-0 left-0 w-16 h-1 bg-blue-600 rounded-full"></span>
                </h2>

                @if(request('search'))
                <a href="{{ route('terkini') }}"
                   class="text-sm text-gray-600 hover:text-blue-600 transition-colors flex items-center gap-1">
                    <i class="bi bi-x-circle"></i>
                    Reset Pencarian
                </a>
                @endif
            </div>

            @if($beritaTerkini->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($beritaTerkini as $berita)
                <a href="{{ route('berita.detail', $berita->id_berita) }}"
                   class="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">

                    <!-- Image -->
                    <div class="relative h-48 overflow-hidden bg-gray-100">
                        <img src="{{ $berita->image_url }}"
                             class="w-full h-full object-cover"
                             alt="{{ $berita->judul }}"
                             onerror="this.src='https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&h=300&fit=crop'">
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <!-- Category -->
                        <span class="inline-block text-xs text-blue-600 font-semibold uppercase tracking-wide mb-2">
                            {{ $berita->category->nama_kategori }}
                        </span>

                        <!-- Title -->
                        <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-snug mb-3 min-h-[42px] group-hover:text-blue-600 transition-colors">
                            {{ $berita->judul }}
                        </h3>

                        <!-- Meta Info -->
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <span class="font-medium text-gray-700">InfoBatang</span>
                            <span>•</span>
                            <span>{{ $berita->tanggal_publish ? $berita->tanggal_publish->format('d M Y') : '-' }}</span>
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center gap-4 text-xs text-gray-500 pt-3 border-t border-gray-100">
                            <span class="flex items-center gap-1">
                                <i class="bi bi-eye"></i>
                                {{ number_format($berita->views) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="bi bi-chat-dots"></i>
                                {{ $berita->comments_count ?? 0 }}
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($beritaTerkini->hasPages())
            <div class="flex items-center justify-center gap-2 mt-8">
                <span class="text-sm text-gray-600 mr-2">Halaman:</span>

                @foreach(range(1, $beritaTerkini->lastPage()) as $page)
                    @if($page == $beritaTerkini->currentPage())
                        <button class="w-9 h-9 rounded bg-orange-500 text-white font-semibold text-sm">
                            {{ $page }}
                        </button>
                    @else
                        <a href="{{ $beritaTerkini->appends(['search' => request('search')])->url($page) }}"
                           class="w-9 h-9 rounded bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-orange-500 hover:text-white hover:border-orange-500 transition flex items-center justify-center">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if($beritaTerkini->hasMorePages())
                <a href="{{ $beritaTerkini->appends(['search' => request('search')])->nextPageUrl() }}"
                   class="ml-2 px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded hover:bg-orange-600 transition">
                    Selanjutnya
                </a>
                @endif
            </div>
            @endif

            @else
            <!-- Empty State -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-16 text-center">
                <i class="bi bi-newspaper text-6xl text-blue-300 mb-4 block"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    @if(request('search'))
                        Tidak ada hasil untuk "{{ request('search') }}"
                    @else
                        Belum Ada Berita
                    @endif
                </h3>
                <p class="text-gray-600 mb-4">
                    @if(request('search'))
                        Coba kata kunci lain atau lihat semua berita
                    @else
                        Berita terkini akan segera hadir di sini
                    @endif
                </p>
                @if(request('search'))
                <a href="{{ route('terkini') }}"
                   class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Lihat Semua Berita
                </a>
                @endif
            </div>
            @endif
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
// Hero Slider
let currentHeroSlide = 0;
const heroSlides = document.querySelectorAll('.hero-slide');
const heroIndicators = document.querySelectorAll('.hero-indicator');

function showHeroSlide(n) {
    if (heroSlides.length === 0) return;

    heroSlides[currentHeroSlide].classList.remove('!opacity-100');
    if (heroIndicators[currentHeroSlide]) {
        heroIndicators[currentHeroSlide].classList.remove('!w-6', '!bg-white');
    }

    currentHeroSlide = n;
    if (currentHeroSlide >= heroSlides.length) currentHeroSlide = 0;
    if (currentHeroSlide < 0) currentHeroSlide = heroSlides.length - 1;

    heroSlides[currentHeroSlide].classList.add('!opacity-100');
    if (heroIndicators[currentHeroSlide]) {
        heroIndicators[currentHeroSlide].classList.add('!w-6', '!bg-white');
    }
}

// Auto slide every 5 seconds
if (heroSlides.length > 1) {
    setInterval(() => {
        showHeroSlide(currentHeroSlide + 1);
    }, 5000);
}
</script>
@endsection
