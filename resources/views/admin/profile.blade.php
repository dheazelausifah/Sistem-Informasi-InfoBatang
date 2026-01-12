@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Profile</h2>
        <p class="text-gray-600 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <i class="fas fa-check-circle mr-3"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Error -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <span class="font-semibold">Terjadi kesalahan:</span>
        </div>
        <ul class="list-disc list-inside ml-6">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Side - Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-center">
                    <div class="relative inline-block mb-4">
                        <img id="profilePreview"
                             src="{{ $admin->profile_image ? asset('storage/' . $admin->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($admin->name) . '&background=5B73E8&color=fff&size=200' }}"
                             class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100">

                        <button type="button"
                                onclick="document.getElementById('profileImageInput').click()"
                                class="absolute bottom-0 right-0 bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition">
                            <i class="fas fa-camera text-sm"></i>
                        </button>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800">
                        {{ $admin->name }}
                    </h3>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ $admin->email }}
                    </p>

                    <span class="inline-block mt-3 px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                        <i class="fas fa-circle text-green-500 text-xs mr-1"></i>
                        Active
                    </span>
                </div>

                <!-- Form Upload Image -->
                <form action="{{ route('admin.profile.upload-image') }}" method="POST" enctype="multipart/form-data" id="uploadImageForm">
                    @csrf
                    <input type="file" id="profileImageInput" name="profile_image" class="hidden" accept="image/*" onchange="previewAndUploadImage(event)">
                </form>

                <!-- Info tambahan -->
                <div class="mt-6 pt-6 border-t text-sm text-gray-600">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-user-tag w-5"></i>
                        <span>{{ '@' . $admin->username }}</span>
                    </div>
                    @if($admin->phone)
                    <div class="flex items-center">
                        <i class="fas fa-phone w-5"></i>
                        <span>{{ $admin->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm">

                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex">
                        <button type="button"
                                onclick="switchTab('info')"
                                id="tab-info"
                                class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">
                            <i class="fas fa-user mr-2"></i>
                            Informasi Akun
                        </button>
                        <button type="button"
                                onclick="switchTab('password')"
                                id="tab-password"
                                class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                            <i class="fas fa-lock mr-2"></i>
                            Ubah Password
                        </button>
                    </nav>
                </div>

                <!-- ================= INFORMASI AKUN ================= -->
                <div id="content-info" class="tab-content p-6">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">

                            <!-- Nama -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', $admin->name) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                            </div>

                            <!-- Username -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="username"
                                       value="{{ old('username', $admin->username) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Masukkan username"
                                       required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $admin->email) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="admin@infobatang.id"
                                       required>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    No. Telepon
                                </label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone', $admin->phone) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="08xxxxxxxxxx">
                            </div>

                            <!-- Button -->
                            <div class="flex justify-end pt-4 border-t">
                                <button type="submit"
                                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- ================= UBAH PASSWORD ================= -->
                <div id="content-password" class="tab-content p-6 hidden">
                    <form action="{{ route('admin.profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Password Lama <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       name="current_password"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Masukkan password lama"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       name="new_password"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Masukkan password baru (min. 8 karakter)"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       name="new_password_confirmation"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Ulangi password baru"
                                       required>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Perhatian:</strong> Setelah password diubah, Anda akan otomatis logout dan harus login kembali.
                            </div>

                            <div class="flex justify-end pt-4 border-t">
                                <button type="submit"
                                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center">
                                    <i class="fas fa-key mr-2"></i>
                                    Ubah Password
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
// Switch Tab
function switchTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-indigo-600', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');

    // Add active class to selected button
    const activeButton = document.getElementById('tab-' + tab);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-indigo-600', 'text-indigo-600');
}

// Preview and Upload Image
function previewAndUploadImage(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('profilePreview').src = event.target.result;
        };
        reader.readAsDataURL(file);

        // Submit form
        document.getElementById('uploadImageForm').submit();
    }
}
</script>
@endsection
