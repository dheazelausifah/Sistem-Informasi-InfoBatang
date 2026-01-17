@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-bell mr-2"></i>
                Semua Notifikasi
            </h1>
            @if($notifications->total() > 0)
            <button onclick="markAllAsRead()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-check-double mr-2"></i>
                Tandai Semua Dibaca
            </button>
            @endif
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        @if($notifications->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada notifikasi</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($notifications as $notif)
            <div class="border rounded-lg p-4 {{ $notif->is_read ? 'bg-gray-50' : 'bg-blue-50 border-blue-200' }} hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            @php
                                $icons = [
                                    'comment' => ['icon' => 'fa-comment', 'color' => 'text-green-600', 'bg' => 'bg-green-100'],
                                    'complaint' => ['icon' => 'fa-exclamation-triangle', 'color' => 'text-orange-600', 'bg' => 'bg-orange-100'],
                                    'career' => ['icon' => 'fa-briefcase', 'color' => 'text-purple-600', 'bg' => 'bg-purple-100'],
                                    'news' => ['icon' => 'fa-newspaper', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100']
                                ];
                                $config = $icons[$notif->type] ?? $icons['news'];
                            @endphp

                            <div class="w-10 h-10 {{ $config['bg'] }} rounded-full flex items-center justify-center">
                                <i class="fas {{ $config['icon'] }} {{ $config['color'] }} text-lg"></i>
                            </div>

                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $notif->message }}</p>
                                <div class="flex items-center gap-3 text-sm text-gray-500 mt-1">
                                    <span>
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                    @if(!$notif->is_read)
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                        Baru
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 ml-4">
                        <a href="{{ $notif->url }}"
                           class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm whitespace-nowrap"
                           onclick="markAsRead({{ $notif->id }})">
                            <i class="fas fa-external-link-alt mr-1"></i>
                            Lihat
                        </a>

                        @if(!$notif->is_read)
                        <button type="button"
                                onclick="markAsRead({{ $notif->id }})"
                                class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"
                                title="Tandai dibaca">
                            <i class="fas fa-check"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<!-- Audio element untuk notifikasi suara -->
<audio id="notificationSound" preload="auto">
    <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
    <source src="{{ asset('sounds/notification.ogg') }}" type="audio/ogg">
</audio>

<script>
let lastNotificationCount = {{ $notifications->total() }};

// Fungsi untuk memainkan suara notifikasi
function playNotificationSound() {
    const audio = document.getElementById('notificationSound');
    if (audio) {
        audio.play().catch(error => {
            console.log('Audio play prevented:', error);
        });
    }
}

// Check untuk notifikasi baru setiap 10 detik
function checkNewNotifications() {
    fetch('{{ route("admin.notifications.unread") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            console.error(`HTTP error! status: ${response.status}`);
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            const currentCount = data.count;

            // Jika ada notifikasi baru (count bertambah), mainkan suara
            if (currentCount > lastNotificationCount) {
                playNotificationSound();

                // Update badge jika ada
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    badge.textContent = currentCount;
                    badge.classList.remove('hidden');
                }

                // Reload halaman untuk menampilkan notifikasi baru
                setTimeout(() => window.location.reload(), 1000);
            }

            lastNotificationCount = currentCount;
        }
    })
    .catch(error => {
        console.error('Error checking notifications:', error);
        // Stop polling jika error terus menerus
        if (error.message.includes('403')) {
            clearInterval(notificationInterval);
            console.log('Polling stopped due to authentication error');
        }
    });
}

// Mulai polling setiap 10 detik
const notificationInterval = setInterval(checkNewNotifications, 10000);

// Jalankan sekali saat halaman load (setelah 2 detik)
setTimeout(checkNewNotifications, 2000);

// Mark single notification as read
function markAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to update UI
            setTimeout(() => window.location.reload(), 300);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Mark all as read
function markAllAsRead() {
    if (!confirm('Tandai semua notifikasi sebagai dibaca?')) return;

    fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Auto-hide alerts
setTimeout(() => {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endsection
