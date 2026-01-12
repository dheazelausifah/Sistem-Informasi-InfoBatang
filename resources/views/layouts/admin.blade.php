<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - InfoBatang</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="data:,">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Philosopher:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .font-philosopher { font-family: 'Philosopher', serif; }
    </style>
</head>

<body class="bg-gray-100">
<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-64 bg-gradient-to-b from-indigo-600 to-indigo-700 text-white flex flex-col
               transition-all duration-300">

        <!-- Profile -->
        <a href="{{ route('admin.profile') }}" class="block p-6 text-center border-b border-indigo-500 hover:bg-indigo-600 transition cursor-pointer">
            <div class="w-16 h-16 bg-white rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                <img src="{{ Auth::user()->profile_image ?? 'https://www.pngmart.com/files/21/Admin-Profile-Vector-PNG-Clipart.png' }}"
                     class="w-full h-full object-cover">
            </div>
            <h3 class="font-semibold text-sm">{{ Auth::user()->name ?? 'Admin' }}</h3>
            <p class="text-xs text-indigo-200 mt-1">Lihat Profile</p>
        </a>

        <!-- Menu -->
        <nav class="flex-1 px-4 py-6 overflow-y-auto">
            <ul class="space-y-2">

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center px-4 py-3 rounded-lg transition
                       {{ request()->is('admin/dashboard') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-th-large w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                <!-- Kelola Berita -->
                @php
                    $beritaActive =
                        request()->is('admin/news*') ||
                        request()->is('admin/categories*');
                @endphp

                <li>
                    <button onclick="toggleSubmenu('berita')"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition
                        {{ $beritaActive ? 'bg-indigo-500' : 'hover:bg-indigo-500' }}">
                        <div class="flex items-center">
                            <i class="fas fa-newspaper w-5"></i>
                            <span class="ml-3">Kelola Berita</span>
                        </div>
                        <i class="fas fa-chevron-{{ $beritaActive ? 'up' : 'down' }} text-xs"
                           id="berita-icon"></i>
                    </button>

                    <ul id="berita-menu"
                        class="ml-8 mt-2 space-y-2 {{ $beritaActive ? '' : 'hidden' }}">

                        <li>
                            <a href="{{ route('admin.news.index') }}"
                               class="flex items-center px-4 py-2 rounded-lg text-sm transition
                               {{ request()->is('admin/news*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                                <i class="fas fa-file-alt w-4"></i>
                                <span class="ml-3">Berita</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.categories.index') }}"
                               class="flex items-center px-4 py-2 rounded-lg text-sm transition
                               {{ request()->is('admin/categories*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                                <i class="fas fa-folder-open w-4"></i>
                                <span class="ml-3">Kategori Berita</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Komentar -->
                <li>
                    <a href="{{ route('admin.comments.index') }}"
                       class="flex items-center px-4 py-3 rounded-lg transition
                       {{ request()->is('admin/comments*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-comments w-5"></i>
                        <span class="ml-3">Komentar</span>
                    </a>
                </li>

                <!-- Pengaduan -->
                <li>
                    <a href="{{ route('admin.complaints.index') }}"
                       class="flex items-center px-4 py-3 rounded-lg transition
                       {{ request()->is('admin/complaints*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-exclamation-triangle w-5"></i>
                        <span class="ml-3">Pengaduan</span>
                    </a>
                </li>

                <!-- Karir -->
                <li>
                    <a href="{{ route('admin.careers.index') }}"
                       class="flex items-center px-4 py-3 rounded-lg transition
                       {{ request()->is('admin/careers*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-briefcase w-5"></i>
                        <span class="ml-3">Karir</span>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-indigo-500">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center justify-center w-full px-4 py-3 bg-red-500 hover:bg-red-600 rounded-lg">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- TOP NAVBAR -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">

                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="mr-4 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <img src="{{ asset('images/logoAdmin.png') }}"
                         class="h-14 w-14 mr-3 object-contain">

                    <h1 class="text-2xl font-bold text-gray-800 font-philosopher">
                        InfoBatang
                    </h1>
                </div>

                <!-- Notification Button with Dropdown -->
                <div class="relative">
                    <button onclick="toggleNotification()" class="relative text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs
                                     w-5 h-5 rounded-full flex items-center justify-center">
                            3
                        </span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                        <!-- Header -->
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                            <span class="text-xs bg-red-500 text-white px-2 py-1 rounded-full">3 Baru</span>
                        </div>

                        <!-- Notification List -->
                        <div class="max-h-96 overflow-y-auto">
                            <!-- Notifikasi 1 -->
                            <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-newspaper text-blue-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-800">Berita Baru Dipublikasi</p>
                                        <p class="text-xs text-gray-500 mt-1">Berita "Pembangunan Jalan Tol" telah dipublikasi</p>
                                        <p class="text-xs text-gray-400 mt-1">2 menit yang lalu</p>
                                    </div>
                                    <span class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full"></span>
                                </div>
                            </a>

                            <!-- Notifikasi 2 -->
                            <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-comment text-green-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-800">Komentar Baru</p>
                                        <p class="text-xs text-gray-500 mt-1">Ada komentar baru di berita "Festival Budaya"</p>
                                        <p class="text-xs text-gray-400 mt-1">15 menit yang lalu</p>
                                    </div>
                                    <span class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full"></span>
                                </div>
                            </a>

                            <!-- Notifikasi 3 -->
                            <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-orange-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-800">Pengaduan Baru</p>
                                        <p class="text-xs text-gray-500 mt-1">Pengaduan baru tentang "Jalan Rusak"</p>
                                        <p class="text-xs text-gray-400 mt-1">1 jam yang lalu</p>
                                    </div>
                                    <span class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full"></span>
                                </div>
                            </a>

                            <!-- Notifikasi Lama -->
                            <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition opacity-60">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-briefcase text-purple-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-800">Lamaran Kerja Baru</p>
                                        <p class="text-xs text-gray-500 mt-1">Ada lamaran baru untuk posisi "Web Developer"</p>
                                        <p class="text-xs text-gray-400 mt-1">3 jam yang lalu</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Footer -->
                        <div class="p-3 border-t border-gray-200">
                            <a href="#" class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-ml-64');
    }

    function toggleSubmenu(menuId) {
        const menu = document.getElementById(menuId + '-menu');
        const icon = document.getElementById(menuId + '-icon');
        menu.classList.toggle('hidden');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    }

    function toggleNotification() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close notification when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const bellButton = event.target.closest('button[onclick="toggleNotification()"]');

        if (dropdown && !dropdown.contains(event.target) && !bellButton) {
            dropdown.classList.add('hidden');
        }
    });
</script>
</body>
</html>
