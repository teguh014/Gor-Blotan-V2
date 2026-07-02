@php
    $layout = auth()->user()->isAdmin() ? 'admin-layout' : 'customer-layout';
@endphp

<x-dynamic-component :component="$layout" title="Profil Saya">
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900">Profil Saya</h2>
        <p class="text-sm text-gray-500">Kelola informasi akun dan pengaturan keamanan Anda</p>
    </div>

    <div class="max-w-3xl space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-dynamic-component>
