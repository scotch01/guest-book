<x-app-layout>
    <div class="p-6 space-y-6 max-w-7xl mx-auto">
        
        {{-- HEADER CONTAINER --}}
        <div class="bg-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                {{ __('Profile Settings') }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi akun, keamanan, dan preferensi privasi Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left Side: Information & Password --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Right Side: Danger Zone & Extra Info --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="p-6 bg-red-50/50 border border-red-100 rounded-2xl shadow-sm">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>