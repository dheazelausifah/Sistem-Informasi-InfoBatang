@extends('layouts.main')

@section('title', 'Tentang')

@section('content')

<!-- HERO SECTION -->
<section class="bg-gradient-to-r text-black py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="font-philosopher text-4xl md:text-5xl font-bold mb-2">
            Tentang InfoBatang
        </h1>
        <p class="text-lg text-gray-700 mt-1">
            Berbagi informasi dan gagasan untuk masyarakat Batang
        </p>
    </div>
</section>


<!-- MAIN CONTENT -->
<section class="py-1">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">

            <!-- Deskripsi Utama -->
            <div class="mb-12 text-justify">
                <p class="text-gray-700 text-lg leading-relaxed mb-6">
                    Info Batang adalah portal informasi daerah yang berdiri sejak tahun 2012 dan menyajikan berita terkini, akurat, serta terpercaya seputar Kabupaten Batang dan sekitarnya. Info Batang hadir sebagai media informasi publik yang bertujuan untuk membangun desa digital yang mudah, cepat, dan transparan bagi masyarakat.
                </p>

                <p class="text-gray-700 text-lg leading-relaxed mb-6">
                    Info Batang memuat berbagai kategori informasi, mulai dari berita umum, politik, UMKM, budaya, kuliner, olahraga hingga informasi karier dan lowongan kerja. Selain itu, Info Batang juga menyediakan fitur pengaduan masyarakat sebagai sarana penyampaian aspirasi, laporan, dan masukan secara terbuka.
                </p>

                <p class="text-gray-700 text-lg leading-relaxed mb-6">
                    Seluruh konten yang ditampilkan dikelola oleh tim admin Info Batang dan diharapkan dapat menjadi sumber informasi yang bermanfaat, edukatif, serta memudahkan partisipasi aktif masyarakat dalam penyampaian informasi dan pengawasan publik.
                </p>
            </div>

            <!-- Disclaimer -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6 mb-12">
                <h3 class="font-bold text-xl text-gray-800 mb-3 flex items-center">
                    <i class="bi bi-exclamation-triangle-fill text-yellow-500 mr-2"></i>
                    Disclaimer:
                </h3>
                <p class="text-gray-700 leading-relaxed">
                    Info Batang tidak bertanggung jawab atas isi komentar yang disampaikan oleh pengguna. Setiap komentar merupakan tanggung jawab masing-masing pengguna dan akan dimoderasi sesuai kebijakan yang berlaku.
                </p>
            </div>

            <!-- Info Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-calendar-check text-blue-600 text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Sejak 2012</h4>
                    <p class="text-gray-600 text-sm">Berpengalaman lebih dari 10 tahun</p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-people-fill text-green-600 text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Terpercaya</h4>
                    <p class="text-gray-600 text-sm">Informasi akurat dan terpercaya</p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-lightning-fill text-purple-600 text-3xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 mb-2">Update Cepat</h4>
                    <p class="text-gray-600 text-sm">Berita terkini dan real-time</p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
