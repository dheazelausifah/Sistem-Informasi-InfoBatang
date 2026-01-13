@extends('layouts.main')

@section('title', 'Terkini')

@section('styles')
<style>
/* Hero Slider Custom Styles */
.hero-slider-container {
    background: #f9fafb;
    padding: 20px 0;
}

.hero-slider {
    position: relative;
    height: 450px;
    overflow: hidden;
    background: #000;
    border-radius: 12px;
    max-width: 1400px;
    margin: 0 auto;
}

.slide {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slide.active {
    opacity: 1;
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.7;
}

.slide-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 50px 40px;
    background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.5) 70%, transparent 100%);
}

.slide-indicators {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
}

.indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: all 0.3s;
    border: none;
    padding: 0;
}

.indicator.active {
    background: white;
    width: 24px;
    border-radius: 4px;
}

/* News Card Styles - Simple */
.news-card {
    transition: all 0.2s ease;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    display: block;
}

.news-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.news-img-wrapper {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #f3f4f6;
}

.news-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.news-card:hover .news-img {
    transform: scale(1.05);
}

.news-content {
    padding: 14px;
}

.news-meta-top {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 10px;
    color: #6b7280;
    margin-bottom: 8px;
}

.news-category {
    color: #1E88E5;
    font-weight: 600;
}

.news-source {
    font-weight: 500;
}

.news-date {
    color: #9ca3af;
}

.news-title {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 42px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 24px;
    position: relative;
    padding-bottom: 12px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #1E88E5;
    border-radius: 2px;
}

@media (max-width: 768px) {
    .hero-slider {
        height: 300px;
        border-radius: 8px;
    }
    .slide-content {
        padding: 30px 20px;
    }
}
</style>
@endsection

@section('content')

<!-- HERO SLIDER WITH BORDER -->
<div class="hero-slider-container">
    <div class="container mx-auto px-6">
        <div class="hero-slider">
            @php
                $heroSlides = [
                    [
                        'title' => 'Democrats Overhaul Party\'s Primary Calendar, Upending a Political Tradition',
                        'author' => 'Dina Overa',
                        'time' => 'Just now',
                        'image' => 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=1200&h=450&fit=crop'
                    ],
                    [
                        'title' => 'Pembangunan Infrastruktur Jalan Raya Meningkat di Kabupaten Batang',
                        'author' => 'Admin InfoBatang',
                        'time' => '2 hours ago',
                        'image' => 'https://images.unsplash.com/photo-1581094271901-8022df4466f9?w=1200&h=450&fit=crop'
                    ],
                    [
                        'title' => 'Festival Budaya Batang 2026 Akan Digelar Bulan Depan',
                        'author' => 'Redaksi',
                        'time' => '5 hours ago',
                        'image' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&h=450&fit=crop'
                    ],
                ];
            @endphp

            @foreach($heroSlides as $index => $slide)
            <div class="slide {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}">
                <div class="slide-content text-white">
                    <h1 class="text-2xl md:text-3xl font-bold mb-3 leading-tight max-w-3xl">
                        {{ $slide['title'] }}
                    </h1>
                    <div class="text-xs opacity-90 flex gap-4 items-center">
                        <span class="flex items-center gap-1">
                            <i class="bi bi-person-circle"></i>
                            by {{ $slide['author'] }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-clock"></i>
                            {{ $slide['time'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="slide-indicators">
                @foreach($heroSlides as $index => $slide)
                <button class="indicator {{ $index === 0 ? 'active' : '' }}"
                        onclick="showSlide({{ $index }})"
                        aria-label="Slide {{ $index + 1 }}">
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="bg-white py-10">
    <div class="container mx-auto px-6">

        <!-- BREAKING NEWS SECTION -->
        <div class="mb-10">
            <h2 class="section-title">Breaking News</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $breakingNews = [
                        [
                            'title' => 'News Title Lorem Ipsum Dolor Sit Amet',
                            'category' => '#Kabar',
                            'date' => '1 hour Ago',
                            'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&h=300&fit=crop'
                        ],
                        [
                            'title' => 'News Title Lorem Ipsum Dolor Sit Amet',
                            'category' => '#Breaking',
                            'date' => '1 hour Ago',
                            'image' => 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?w=400&h=300&fit=crop'
                        ],
                        [
                            'title' => 'News Title Lorem Ipsum Dolor Sit Amet',
                            'category' => '#Breaking',
                            'date' => '1 hour Ago',
                            'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop'
                        ],
                        [
                            'title' => 'News Title Lorem Ipsum Dolor Sit Amet',
                            'category' => '#Breaking',
                            'date' => '1 hour Ago',
                            'image' => 'https://images.unsplash.com/photo-1556911261-6bd341186b2f?w=400&h=300&fit=crop'
                        ],
                        [
                            'title' => 'UMKM Batang Raih Penghargaan Nasional',
                            'category' => '#UMKM',
                            'date' => '2 hours Ago',
                            'image' => 'https://images.unsplash.com/photo-1556742111-a301076d9d18?w=400&h=300&fit=crop'
                        ],
                        [
                            'title' => 'Tingkat Pengangguran Menurun di Kabupaten Batang',
                            'category' => '#Politik',
                            'date' => '3 hours Ago',
                            'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=300&fit=crop'
                        ],
                        [
                            'title' => 'Festival Kuliner Nusantara Hadir di Batang',
                            'category' => '#Kuliner',
                            'date' => '5 hours Ago',
                            'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=400&h=300&fit=crop'
                        ],
                        [
                            'title' => 'Tim Sepak Bola Batang Juara Turnamen Regional',
                            'category' => '#Olahraga',
                            'date' => '1 day Ago',
                            'image' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=300&fit=crop'
                        ],
                    ];
                @endphp

                @foreach($breakingNews as $news)
                <a href="#" class="news-card">
                    <div class="news-img-wrapper">
                        <img src="{{ $news['image'] }}" class="news-img" alt="{{ $news['title'] }}">
                    </div>
                    <div class="news-content">
                        <div class="news-meta-top">
                            <span class="news-category">{{ $news['category'] }}</span>
                            <span class="news-source">InfoBatang</span>
                            <span class="news-date">• {{ $news['date'] }}</span>
                        </div>
                        <h3 class="news-title">{{ $news['title'] }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const indicators = document.querySelectorAll('.indicator');

if (slides.length > 0 && indicators.length > 0) {
    function showSlide(n) {
        slides[currentSlide].classList.remove('active');
        indicators[currentSlide].classList.remove('active');

        currentSlide = n;
        if (currentSlide >= slides.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = slides.length - 1;

        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');
    }

    // Auto slide setiap 5 detik
    if (slides.length > 1) {
        setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);
    }
}
</script>
@endsection
