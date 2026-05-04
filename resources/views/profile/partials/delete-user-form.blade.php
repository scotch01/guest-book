<section class="space-y-4">
    <header>
        <h2 class="text-lg font-bold text-red-700">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-red-600/80 leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <x-danger-button
        class="rounded-xl px-5 py-2.5 shadow-md shadow-red-100 transition-all active:scale-95"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-gray-800">
                {{ __('Are you sure?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full border-none bg-gray-100 focus:ring-2 focus:ring-red-500/20 rounded-xl"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl border-none bg-gray-100 hover:bg-gray-200 text-gray-600">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="rounded-xl bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95">
                    {{ __('Confirm Delete') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>