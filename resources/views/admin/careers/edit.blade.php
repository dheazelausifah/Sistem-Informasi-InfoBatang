@extends('layouts.admin')

@section('title', 'Edit Lowongan')
@section('page-title', 'Ubah Lowongan Pekerjaan')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Ubah Lowongan Pekerjaan</h2>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <p class="text-sm text-gray-600">Silakan lengkapi form untuk menambahkan Lowongan.</p>
        </div>

        <form action="{{ route('admin.careers.update', $career->id_karir) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6">
                <!-- Grid 2 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- ID Karir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Id Karir</label>
                            <input type="text" value="{{ $career->id_karir }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        </div>

                        <!-- Judul Lowongan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Lowongan</label>
                            <input type="text" name="judul" placeholder="Contoh: Content Creator" value="{{ old('judul', $career->judul) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Pekerjaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pekerjaan</label>
                            <select name="tipe_pekerjaan" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('tipe_pekerjaan') border-red-500 @enderror">
                                <option value="">Pilih Tipe</option>
                                <option value="Full-time" {{ old('tipe_pekerjaan', $career->tipe_pekerjaan) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="Part-time" {{ old('tipe_pekerjaan', $career->tipe_pekerjaan) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                <option value="Contract" {{ old('tipe_pekerjaan', $career->tipe_pekerjaan) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Freelance" {{ old('tipe_pekerjaan', $career->tipe_pekerjaan) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                <option value="Internship" {{ old('tipe_pekerjaan', $career->tipe_pekerjaan) == 'Internship' ? 'selected' : '' }}>Internship</option>
                            </select>
                            @error('tipe_pekerjaan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                            <input type="text" name="lokasi" placeholder="On-site | Kabupaten Batang" value="{{ old('lokasi', $career->lokasi) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('lokasi') border-red-500 @enderror">
                            @error('lokasi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                            <select name="level" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('level') border-red-500 @enderror">
                                <option value="">Pilih Level</option>
                                <option value="Freshgraduate" {{ old('level', $career->level) == 'Freshgraduate' ? 'selected' : '' }}>Freshgraduate</option>
                                <option value="Junior" {{ old('level', $career->level) == 'Junior' ? 'selected' : '' }}>Junior</option>
                                <option value="Senior" {{ old('level', $career->level) == 'Senior' ? 'selected' : '' }}>Senior</option>
                                <option value="Manager" {{ old('level', $career->level) == 'Manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            @error('level')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaji -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gaji</label>
                            <input type="text" name="gaji" placeholder="Negotiable / 3-4 jt" value="{{ old('gaji', $career->gaji) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('gaji') border-red-500 @enderror">
                            @error('gaji')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <!-- ID Admin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Id Admin</label>
                            <input type="text" value="{{ $career->id_admin }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status', $career->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $career->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Pekerjaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pekerjaan</label>
                            <textarea name="deskripsi" rows="4" placeholder="Gambaran umum pekerjaan..." required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $career->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggung Jawab -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggung Jawab</label>
                            <textarea name="tanggung_jawab" rows="4" placeholder="Apa yang dikerjakan..." required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none @error('tanggung_jawab') border-red-500 @enderror">{{ old('tanggung_jawab', $career->tanggung_jawab) }}</textarea>
                            @error('tanggung_jawab')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kualifikasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kualifikasi</label>
                            <textarea name="kualifikasi" rows="4" placeholder="Syarat pelamar..." required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none @error('kualifikasi') border-red-500 @enderror">{{ old('kualifikasi', $career->kualifikasi) }}</textarea>
                            @error('kualifikasi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition">
                    Edit
                </button>
                <a href="{{ route('admin.careers.index') }}" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded-lg transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
