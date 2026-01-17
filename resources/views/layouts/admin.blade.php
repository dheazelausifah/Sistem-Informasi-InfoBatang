<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - InfoBatang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Philosopher:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .font-philosopher { font-family: 'Philosopher', serif; }

        /* Pulse animation untuk notifikasi baru */
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        .pulse-ring {
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
    </style>
</head>

<body class="bg-gray-100">
<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-64 bg-gradient-to-b from-indigo-600 to-indigo-700 text-white flex flex-col transition-all duration-300">
        <a href="{{ route('admin.profile') }}" class="block p-6 text-center border-b border-indigo-500 hover:bg-indigo-600 transition cursor-pointer">
            <div class="w-16 h-16 bg-white rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                <img src="{{ Auth::user()->profile_image ?? 'https://www.pngmart.com/files/21/Admin-Profile-Vector-PNG-Clipart.png' }}" class="w-full h-full object-cover">
            </div>
            <h3 class="font-semibold text-sm">{{ Auth::user()->name ?? 'Admin' }}</h3>
            <p class="text-xs text-indigo-200 mt-1">Lihat Profile</p>
        </a>

        <nav class="flex-1 px-4 py-6 overflow-y-auto">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->is('admin/dashboard') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-th-large w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                @php
                    $beritaActive = request()->is('admin/news*') || request()->is('admin/categories*');
                @endphp

                <li>
                    <button onclick="toggleSubmenu('berita')" class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition {{ $beritaActive ? 'bg-indigo-500' : 'hover:bg-indigo-500' }}">
                        <div class="flex items-center">
                            <i class="fas fa-newspaper w-5"></i>
                            <span class="ml-3">Kelola Berita</span>
                        </div>
                        <i class="fas fa-chevron-{{ $beritaActive ? 'up' : 'down' }} text-xs" id="berita-icon"></i>
                    </button>
                    <ul id="berita-menu" class="ml-8 mt-2 space-y-2 {{ $beritaActive ? '' : 'hidden' }}">
                        <li>
                            <a href="{{ route('admin.news.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition {{ request()->is('admin/news*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                                <i class="fas fa-file-alt w-4"></i>
                                <span class="ml-3">Berita</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition {{ request()->is('admin/categories*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                                <i class="fas fa-folder-open w-4"></i>
                                <span class="ml-3">Kategori Berita</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.comments.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->is('admin/comments*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-comments w-5"></i>
                        <span class="ml-3">Komentar</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.complaints.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->is('admin/complaints*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-exclamation-triangle w-5"></i>
                        <span class="ml-3">Pengaduan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.careers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->is('admin/careers*') ? 'bg-white text-indigo-600 font-medium' : 'hover:bg-indigo-500' }}">
                        <i class="fas fa-briefcase w-5"></i>
                        <span class="ml-3">Karir</span>
                    </a>
                </li>
            </ul>
        </nav>

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
                    <img src="{{ asset('images/logoAdmin.png') }}" class="h-14 w-14 mr-3 object-contain">
                    <h1 class="text-2xl font-bold text-gray-800 font-philosopher">InfoBatang</h1>
                </div>

                <!-- Notification Bell -->
                <div class="relative">
                    <button onclick="toggleNotification()" id="notifBell" class="relative text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bell text-xl notification-bell"></i>
                        <span id="notifBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center notification-badge">
                            0
                        </span>
                        <!-- Pulse ring untuk notifikasi baru -->
                        <span id="pulseRing" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-red-400 rounded-full pulse-ring"></span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                            <div class="flex items-center gap-2">
                                <span id="notifCountText" class="text-xs bg-red-500 text-white px-2 py-1 rounded-full">0 Baru</span>
                                <button onclick="markAllAsRead()" class="text-xs text-indigo-600 hover:text-indigo-800">
                                    Tandai Semua
                                </button>
                            </div>
                        </div>

                        <div id="notificationList" class="max-h-96 overflow-y-auto">
                            <!-- Notifikasi akan dimuat di sini via AJAX -->
                            <div class="p-8 text-center text-gray-400">
                                <i class="fas fa-bell-slash text-4xl mb-2"></i>
                                <p class="text-sm">Memuat notifikasi...</p>
                            </div>
                        </div>

                        <div class="p-3 border-t border-gray-200">
                            <a href="{{ route('admin.notifications.index') }}" class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
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

    if (!dropdown.classList.contains('hidden')) {
        loadNotifications();
    }
}

// Close notification when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationDropdown');
    const bellButton = document.getElementById('notifBell');

    if (dropdown && !dropdown.contains(event.target) && !bellButton.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

// Load notifications via AJAX
function loadNotifications() {
    fetch('{{ route("admin.notifications.unread") }}')
        .then(response => response.json())
        .then(data => {
            updateNotificationUI(data.notifications, data.count);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

// Update notification UI
function updateNotificationUI(notifications, count) {
    const badge = document.getElementById('notifBadge');
    const countText = document.getElementById('notifCountText');
    const list = document.getElementById('notificationList');

    // Update badge
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.remove('hidden');
        countText.textContent = `${count} Baru`;
    } else {
        badge.classList.add('hidden');
        countText.textContent = '0 Baru';
    }

    // Update list
    if (notifications.length === 0) {
        list.innerHTML = `
            <div class="p-8 text-center text-gray-400">
                <i class="fas fa-bell-slash text-4xl mb-2"></i>
                <p class="text-sm">Tidak ada notifikasi baru</p>
            </div>
        `;
        return;
    }

    list.innerHTML = notifications.map(notif => {
        const icons = {
            'comment': { icon: 'fa-comment', bg: 'bg-green-100', color: 'text-green-600' },
            'complaint': { icon: 'fa-exclamation-triangle', bg: 'bg-orange-100', color: 'text-orange-600' },
            'career': { icon: 'fa-briefcase', bg: 'bg-purple-100', color: 'text-purple-600' },
            'news': { icon: 'fa-newspaper', bg: 'bg-blue-100', color: 'text-blue-600' }
        };

        const config = icons[notif.type] || icons['news'];
        const timeAgo = getTimeAgo(notif.created_at);

        return `
            <a href="${notif.url}" onclick="markAsRead(${notif.id})" class="block p-4 hover:bg-gray-50 border-b border-gray-100 transition">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 ${config.bg} rounded-full flex items-center justify-center">
                        <i class="fas ${config.icon} ${config.color}"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-800">${notif.message}</p>
                        <p class="text-xs text-gray-400 mt-1">${timeAgo}</p>
                    </div>
                    <span class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </div>
            </a>
        `;
    }).join('');
}

// Mark notification as read
function markAsRead(id) {
    fetch(`/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    });
}

// Mark all as read
function markAllAsRead() {
    fetch('{{ route("admin.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(() => {
        loadNotifications();
    });
}

// Helper: Get time ago
function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    if (seconds < 60) return 'Baru saja';
    if (seconds < 3600) return `${Math.floor(seconds / 60)} menit yang lalu`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)} jam yang lalu`;
    return `${Math.floor(seconds / 86400)} hari yang lalu`;
}

// ============================================
// NOTIFICATION SOUND WITH TEXT-TO-SPEECH
// ============================================

// Global AudioContext (reuse untuk efisiensi)
let audioContext = null;
let isAudioInitialized = false;

// Initialize AudioContext setelah user interaction
function initializeAudio() {
    if (!isAudioInitialized) {
        try {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            // Resume context jika suspended
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }
            isAudioInitialized = true;
            console.log('✅ Audio notification ready!');

            // Tampilkan toast singkat bahwa audio ready
            showAudioReadyToast();
        } catch (error) {
            console.log('Audio initialization error:', error);
        }
    }
}


// Play notification sound dengan TTS
function playNotificationSound(type = 'default') {
    try {
        // Initialize audio jika belum
        if (!isAudioInitialized) {
            initializeAudio();
        }

        // Play beep dulu
        playBeepSound();

        // Kemudian TTS jika available
        if ('speechSynthesis' in window) {
            setTimeout(() => {
                const utterance = new SpeechSynthesisUtterance();

                // Tentukan pesan berdasarkan tipe
                let textToSpeak = '';
                switch(type) {
                    case 'complaint':
                        textToSpeak = 'Ada pengaduan masuk';
                        break;
                    case 'comment':
                        textToSpeak = 'Ada komentar baru';
                        break;
                    case 'career':
                        textToSpeak = 'Ada lamaran kerja baru';
                        break;
                    case 'news':
                        textToSpeak = 'Ada berita baru';
                        break;
                    default:
                        textToSpeak = 'Ada notifikasi baru';
                }

                utterance.text = textToSpeak;
                utterance.lang = 'id-ID';
                utterance.rate = 1.0;
                utterance.pitch = 1.0;
                utterance.volume = 0.8;

                window.speechSynthesis.speak(utterance);
            }, 300);
        }
    } catch (error) {
        console.log('TTS error:', error);
    }
}

// Beep sound function
function playBeepSound() {
    try {
        if (!audioContext) {
            console.log('AudioContext not initialized yet');
            return;
        }

        // Resume context jika suspended
        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }

        // First beep
        const osc1 = audioContext.createOscillator();
        const gain1 = audioContext.createGain();
        osc1.connect(gain1);
        gain1.connect(audioContext.destination);
        osc1.frequency.value = 800;
        osc1.type = 'sine';
        gain1.gain.setValueAtTime(0.3, audioContext.currentTime);
        gain1.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        osc1.start(audioContext.currentTime);
        osc1.stop(audioContext.currentTime + 0.1);

        // Second beep
        const osc2 = audioContext.createOscillator();
        const gain2 = audioContext.createGain();
        osc2.connect(gain2);
        gain2.connect(audioContext.destination);
        osc2.frequency.value = 1000;
        osc2.type = 'sine';
        gain2.gain.setValueAtTime(0.3, audioContext.currentTime + 0.15);
        gain2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
        osc2.start(audioContext.currentTime + 0.15);
        osc2.stop(audioContext.currentTime + 0.3);
    } catch (error) {
        console.log('Beep error:', error);
    }
}

// Show pulse effect
function showPulseEffect() {
    const pulse = document.getElementById('pulseRing');
    pulse.classList.remove('hidden');
    setTimeout(() => pulse.classList.add('hidden'), 2000);
}

// Check for new notifications every 10 seconds
let lastNotifCount = parseInt(localStorage.getItem('lastNotificationCount')) || 0;

function checkNewNotifications() {
    fetch('{{ route("admin.notifications.unread") }}')
        .then(response => response.json())
        .then(data => {
            updateNotificationUI(data.notifications, data.count);

            // Play sound jika ada notifikasi baru
            if (data.count > lastNotifCount && lastNotifCount > 0) {
                // Ambil tipe notifikasi terbaru
                const latestType = data.notifications[0]?.type || 'default';
                playNotificationSound(latestType);
                showPulseEffect();
            }

            lastNotifCount = data.count;
            localStorage.setItem('lastNotificationCount', data.count);
        })
        .catch(error => console.error('Error:', error));
}

// Check setiap 10 detik
setInterval(checkNewNotifications, 10000);

// Load pertama kali setelah 2 detik
setTimeout(() => {
    loadNotifications();
    checkNewNotifications();
}, 2000);

// Initialize audio saat user interaction (klik apapun di halaman)
document.addEventListener('click', initializeAudio, { once: true });
document.addEventListener('touchstart', initializeAudio, { once: true });
document.addEventListener('keydown', initializeAudio, { once: true });

// Initialize audio saat toggle notification (backup)
const originalToggleNotification = toggleNotification;
toggleNotification = function() {
    initializeAudio();
    originalToggleNotification();
};
</script>
</body>
</html>
