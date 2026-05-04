<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-800">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div class="space-y-1">
            <x-input-label for="current_password" :value="__('Current Password')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
            <x-text-input id="current_password" name="current_password" type="password" class="block w-full border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <x-input-label for="password" :value="__('New Password')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
                <x-text-input id="password" name="password" type="password" class="block w-full border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all" />
            </div>
            <div class="space-y-1">
                <x-input-label for="password_confirmation" :value="__('Confirm')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block w-full border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all" />
            </div>
        </div>
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                {{ __('Update Password') }}
            </x-primary-button>
        </div>
    </form>
</section>