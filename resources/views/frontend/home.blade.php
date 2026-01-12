@extends('layouts.welcome')

@section('title', 'Beranda')

@section('styles')
<style>
/* HERO SLIDER */
.hero-slider{
    position:relative;
    height:500px;
    border-radius:16px;
    overflow:hidden;
    background:#000;
    margin-bottom:50px;
}

.slide{
    position:absolute;
    width:100%;
    height:100%;
    opacity:0;
    transition:opacity .5s;
}

.slide.active{
    opacity:1;
}

.slide img{
    width:100%;
    height:100%;
    object-fit:cover;
    opacity:.7;
}

.slide-content{
    position:absolute;
    bottom:0;
    left:0;
    right:0;
    padding:40px;
    background:linear-gradient(to top, rgba(0,0,0,.9), transparent);
    color:white;
}

.slide-content h2{
    font-size:32px;
    margin-bottom:10px;
    line-height:1.3;
    font-weight:700;
}

.slide-meta{
    font-size:13px;
    opacity:.9;
}

.slide-meta i{
    margin-right:5px;
}

/* Slide Indicators */
.slide-indicators{
    position:absolute;
    bottom:20px;
    left:50%;
    transform:translateX(-50%);
    display:flex;
    gap:8px;
    z-index:10;
}

.indicator{
    width:10px;
    height:10px;
    border-radius:50%;
    background:rgba(255,255,255,.5);
    cursor:pointer;
    transition:.3s;
    border:none;
}

.indicator.active{
    background:white;
    width:30px;
    border-radius:5px;
}

/* SECTION TITLE */
.section-title{
    font-size:28px;
    font-weight:700;
    margin-bottom:30px;
    color:#2c3e50;
    position:relative;
    padding-bottom:12px;
}

.section-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:0;
    width:60px;
    height:4px;
    background:var(--primary-color);
    border-radius:2px;
}

/* NEWS CARD */
.news-card{
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
    transition:all .3s;
    cursor:pointer;
    height:100%;
    border:none;
}

.news-card:hover{
    transform:translateY(-4px);
    box-shadow:0 6px 20px rgba(0,0,0,.15);
}

.news-img-wrapper{
    position:relative;
    overflow:hidden;
    height:200px;
}

.news-img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:.3s;
}

.news-card:hover .news-img{
    transform:scale(1.05);
}

.news-category-badge{
    position:absolute;
    top:12px;
    left:12px;
    background:rgba(91, 115, 232, 0.95);
    color:white;
    font-size:11px;
    font-weight:600;
    padding:6px 14px;
    border-radius:20px;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.news-body{
    padding:20px;
}

.news-title{
    font-size:16px;
    font-weight:600;
    color:#2c3e50;
    margin-bottom:15px;
    line-height:1.5;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
    min-height:48px;
}

.news-footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:13px;
    color:#7f8c8d;
    padding-top:12px;
    border-top:1px solid #ecf0f1;
}

.news-time{
    display:flex;
    align-items:center;
    gap:5px;
}

.news-comments{
    display:flex;
    align-items:center;
    gap:5px;
}

/* RESPONSIVE */
@media (max-width:768px){
    .hero-slider{
        height:350px;
        margin-bottom:30px;
    }

    .slide-content{
        padding:25px;
    }

    .slide-content h2{
        font-size:20px;
    }

    .section-title{
        font-size:22px;
    }
}
</style>
@endsection

@section('content')
<div class="container my-4">

    <!-- HERO SLIDER -->
    <div class="hero-slider">
        <div class="slide active">
            <img src="https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=1200&h=500&fit=crop" alt="Berita Utama">
            <div class="slide-content">
                <h2>Pemkab Batang Luncurkan Program Digitalisasi UMKM</h2>
                <div class="slide-meta">
                    <i class="bi bi-person-circle"></i> Admin InfoBatang •
                    <i class="bi bi-clock"></i> 2 jam yang lalu
                </div>
            </div>
        </div>

        <div class="slide">
            <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?w=1200&h=500&fit=crop" alt="Berita Ekonomi">
            <div class="slide-content">
                <h2>Investasi Sektor Pariwisata Batang Meningkat Signifikan</h2>
                <div class="slide-meta">
                    <i class="bi bi-person-circle"></i> Redaksi •
                    <i class="bi bi-clock"></i> 5 jam yang lalu
                </div>
            </div>
        </div>

        <div class="slide">
            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1200&h=500&fit=crop" alt="Berita Teknologi">
            <div class="slide-content">
                <h2>Batang Smart City: Inovasi Pelayanan Publik Berbasis Digital</h2>
                <div class="slide-meta">
                    <i class="bi bi-person-circle"></i> Tim Redaksi •
                    <i class="bi bi-clock"></i> 1 hari yang lalu
                </div>
            </div>
        </div>

        <div class="slide-indicators">
            <button class="indicator active" onclick="showSlide(0)" aria-label="Slide 1"></button>
            <button class="indicator" onclick="showSlide(1)" aria-label="Slide 2"></button>
            <button class="indicator" onclick="showSlide(2)" aria-label="Slide 3"></button>
        </div>
    </div>

    <!-- BREAKING NEWS SECTION -->
    <h2 class="section-title">Berita Terkini</h2>

    <div class="row g-4 mb-5">
        <!-- News Card 1 -->
        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=600&h=400&fit=crop" class="news-img" alt="Olahraga">
                    <span class="news-category-badge">Olahraga</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Tim Sepak Bola Batang Raih Juara Piala Pelajar Jawa Tengah</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 1 Jam Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 12
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Card 2 -->
        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&h=400&fit=crop" class="news-img" alt="Kuliner">
                    <span class="news-category-badge">Kuliner</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Festival Kuliner Nusantara di Alun-Alun Batang Sukses Digelar</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 3 Jam Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 8
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Card 3 -->
        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&h=400&fit=crop" class="news-img" alt="Budaya">
                    <span class="news-category-badge">Budaya</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Pertunjukan Wayang Kulit Meriahkan HUT Kemerdekaan RI</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 5 Jam Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 5
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Card 4 -->
        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=400&fit=crop" class="news-img" alt="UMKM">
                    <span class="news-category-badge">UMKM</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Pelaku UMKM Batang Raih Penghargaan Nasional Produk Unggulan</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 1 Hari Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 15
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KATEGORI POPULER -->
    <h2 class="section-title">Berita Populer</h2>

    <div class="row g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1532619675605-1ede6c2ed2b0?w=600&h=400&fit=crop" class="news-img" alt="Politik">
                    <span class="news-category-badge">Politik</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Bupati Batang Hadiri Rakor Pembangunan Infrastruktur Daerah</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 2 Hari Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 24
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1590012314607-cda9d9b699ae?w=600&h=400&fit=crop" class="news-img" alt="Kabar">
                    <span class="news-category-badge">Kabar</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Siswa Batang Wakili Indonesia di Kompetisi Sains Internasional</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 3 Hari Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 18
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?w=600&h=400&fit=crop" class="news-img" alt="Kriminal">
                    <span class="news-category-badge">Kriminal</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Polres Batang Ungkap Jaringan Pencurian Kendaraan Bermotor</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 4 Hari Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 32
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card news-card">
                <div class="news-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1455849318743-b2233052fcff?w=600&h=400&fit=crop" class="news-img" alt="Esai">
                    <span class="news-category-badge">Esai</span>
                </div>
                <div class="news-body">
                    <h3 class="news-title">Membangun Batang yang Inklusif: Perspektif Pembangunan Berkelanjutan</h3>
                    <div class="news-footer">
                        <span class="news-time">
                            <i class="bi bi-clock"></i> 5 Hari Lalu
                        </span>
                        <span class="news-comments">
                            <i class="bi bi-chat-dots"></i> 9
                        </span>
                    </div>
                </div>
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

function showSlide(n) {
    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');

    currentSlide = n;
    if(currentSlide >= slides.length) currentSlide = 0;
    if(currentSlide < 0) currentSlide = slides.length - 1;

    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
}

// Auto slide setiap 5 detik
setInterval(() => {
    showSlide(currentSlide + 1);
}, 5000);
</script>
@endsection
