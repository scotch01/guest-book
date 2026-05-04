<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-800">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="space-y-1">
            <x-input-label for="name" :value="__('Name')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
            <x-text-input id="name" name="name" type="text" class="block w-full border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all" :value="old('name', $user->name)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
            <x-text-input id="email" name="email" type="email" class="block w-full border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all" :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>