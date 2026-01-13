<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title','InfoBatang') - Berbagi Informasi dan Gagasan</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Philosopher:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<script>
tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                poppins: ['Poppins','sans-serif'],
                philosopher: ['Philosopher','serif'],
            },
            colors:{
                primary:'#1E88E5',
                secondary:'#34495e',
            }
        }
    }
}
</script>

<style>
body{font-family:'Poppins',sans-serif}
.line-clamp-2{
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}

/* Dropdown Styles - Improved */
.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    min-width: 220px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-radius: 0.75rem;
    margin-top: 0.75rem;
    z-index: 50;
    padding: 0.5rem;
    border: 1px solid #e5e7eb;
}

.dropdown-content.show {
    display: block;
}

.dropdown-content a {
    display: block;
    padding: 0.875rem 1rem;
    color: #374151;
    transition: all 0.2s;
    font-size: 0.875rem;
    border-radius: 0.5rem;
    font-weight: 500;
}

.dropdown-content a:hover {
    background: #eff6ff;
    color: #1E88E5;
    transform: translateX(4px);
}

/* Active Menu Styling */
.nav-link {
    position: relative;
    padding: 1.75rem 0;
    transition: color 0.2s;
    cursor: pointer;
}

.nav-link.active {
    color: #1E88E5;
}

.nav-link:hover {
    color: #1E88E5;
}

/* Mobile Menu Smooth Animation */
#mobileMenu {
    transition: all 0.3s ease;
    max-height: 0;
    overflow: hidden;
}

#mobileMenu.show {
    max-height: 500px;
}

/* Chevron Animation */
.dropdown-toggle i,
#mobileBeritaToggle i {
    transition: transform 0.3s ease;
}

.dropdown-toggle.active i,
#mobileBeritaToggle.active i {
    transform: rotate(180deg);
}
</style>

@yield('styles')
</head>
<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
<div class="container mx-auto px-4 md:px-6">
    <div class="flex justify-between items-center">

        <!-- Logo & Brand -->
        <a href="{{ route('terkini') }}" class="flex items-center gap-3 py-3 md:py-4">
            <img src="{{ asset('images/logoAdmin.png') }}" class="h-10 w-10 md:h-12 md:w-12 object-contain">
            <div>
                <div class="font-semibold text-base md:text-lg text-gray-800">InfoBatang</div>
                <div class="text-[10px] md:text-xs text-gray-500">Berbagi Informasi dan Gagasan</div>
            </div>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden lg:flex gap-8 xl:gap-10 items-center">
            <a href="{{ route('terkini') }}"
               class="nav-link text-sm font-medium {{ request()->routeIs('terkini') || request()->routeIs('home') ? 'active' : 'text-gray-700' }}">
                Terkini
            </a>

            <!-- Dropdown Berita -->
            <div class="dropdown">
                <button id="desktopBeritaToggle"
                    class="dropdown-toggle nav-link text-sm font-medium {{ request()->routeIs('berita.*') ? 'active' : 'text-gray-700' }} flex items-center gap-1.5">
                    Berita <i class="bi bi-chevron-down text-xs"></i>
                </button>

                <div id="desktopBeritaMenu" class="dropdown-content">
                    @php
                        $categories = \App\Models\Category::orderBy('nama_kategori')->get();
                    @endphp
                    @forelse($categories as $cat)
                        <a href="{{ route('berita.kategori', $cat->slug ?? $cat->id_kategori) }}">
                            <i class="mr-2 text-xs"></i>
                            {{ $cat->nama_kategori }}
                        </a>
                    @empty
                        <span class="block px-4 py-3 text-gray-400 text-xs text-center">Belum ada kategori</span>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('karir.index') }}"
               class="nav-link text-sm font-medium {{ request()->routeIs('karir.*') ? 'active' : 'text-gray-700' }}">
                Karir
            </a>

            <a href="{{ route('pengaduan.index') }}"
               class="nav-link text-sm font-medium {{ request()->routeIs('pengaduan.*') ? 'active' : 'text-gray-700' }}">
                Pengaduan
            </a>

            <a href="#"
               class="nav-link text-sm font-medium text-gray-700">
                Tentang
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="lg:hidden p-2 text-gray-700 hover:text-primary transition">
            <i class="bi bi-list text-3xl"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="lg:hidden pb-4">
        <div class="space-y-1 pt-2">
            <a href="{{ route('terkini') }}"
               class="block py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('terkini') ? 'bg-blue-50 text-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                Terkini
            </a>

            <div>
                <button id="mobileBeritaToggle"
                        class="w-full text-left py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('berita.*') ? 'bg-blue-50 text-primary' : 'text-gray-700 hover:bg-gray-50' }} flex justify-between items-center">
                    Berita
                    <i class="bi bi-chevron-down text-xs"></i>
                </button>
                <div id="mobileBeritaMenu" class="hidden pl-4 mt-1 space-y-1">
                    @php
                        $mobileCategories = \App\Models\Category::orderBy('nama_kategori')->get();
                    @endphp
                    @forelse($mobileCategories as $cat)
                        <a href="{{ route('berita.kategori', $cat->slug ?? $cat->id_kategori) }}"
                           class="block py-2.5 px-4 text-sm text-gray-600 hover:text-primary hover:bg-blue-50 rounded-lg transition">
                            <i class="bi bi-tag mr-2 text-xs"></i>
                            {{ $cat->nama_kategori }}
                        </a>
                    @empty
                        <span class="block py-2 px-4 text-xs text-gray-400">Belum ada kategori</span>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('karir.index') }}"
               class="block py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('karir.*') ? 'bg-blue-50 text-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                Karir
            </a>

            <a href="{{ route('pengaduan.index') }}"
               class="block py-3 px-4 text-sm font-medium rounded-lg {{ request()->routeIs('pengaduan.*') ? 'bg-blue-50 text-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                Pengaduan
            </a>

            <a href="#"
               class="block py-3 px-4 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                Tentang
            </a>
        </div>
    </div>
</div>
</nav>

<main>
@yield('content')
</main>

<!-- FOOTER -->
@include('layouts.footer')

<script>
// Mobile Menu Toggle - Improved
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('show');

    // Change icon
    const icon = mobileMenuBtn.querySelector('i');
    if (mobileMenu.classList.contains('show')) {
        icon.classList.remove('bi-list');
        icon.classList.add('bi-x');
    } else {
        icon.classList.remove('bi-x');
        icon.classList.add('bi-list');
    }
});

// Mobile Berita Dropdown Toggle - Improved
const mobileBeritaToggle = document.getElementById('mobileBeritaToggle');
const mobileBeritaMenu = document.getElementById('mobileBeritaMenu');

if (mobileBeritaToggle) {
    mobileBeritaToggle.addEventListener('click', () => {
        mobileBeritaMenu.classList.toggle('hidden');
        mobileBeritaToggle.classList.toggle('active');
    });
}

// Desktop Berita Dropdown Toggle
const desktopToggle = document.getElementById('desktopBeritaToggle');
const desktopMenu = document.getElementById('desktopBeritaMenu');

if (desktopToggle && desktopMenu) {
    desktopToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        desktopMenu.classList.toggle('show');
        desktopToggle.classList.toggle('active');
    });

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', (e) => {
        if (!desktopToggle.contains(e.target) && !desktopMenu.contains(e.target)) {
            desktopMenu.classList.remove('show');
            desktopToggle.classList.remove('active');
        }
    });

    // Tutup dropdown kalau klik link di dalamnya
    const dropdownLinks = desktopMenu.querySelectorAll('a');
    dropdownLinks.forEach(link => {
        link.addEventListener('click', () => {
            desktopMenu.classList.remove('show');
            desktopToggle.classList.remove('active');
        });
    });
}

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
    if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.classList.remove('show');
        const icon = mobileMenuBtn.querySelector('i');
        icon.classList.remove('bi-x');
        icon.classList.add('bi-list');
    }
});
</script>

@yield('scripts')
</body>
</html>
