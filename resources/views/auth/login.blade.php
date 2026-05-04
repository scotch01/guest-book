<x-guest-layout>
    <div class="flex flex-col items-center rounded-xl justify-center min-h-[60vh]">
        
        <form method="POST" action="{{ route('login') }}" 
            class="w-full sm:max-w-md p-8 bg-gray-500/10 backdrop-blur-lg border border-gray-200/60 rounded-xl shadow-2xl">
            @csrf

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center tracking-tight">{{ __('Sign In') }}</h2>

            <!-- Email Address -->
            <div class="space-y-1">
                <x-input-label for="email" :value="__('Email')" class="text-xs uppercase tracking-widest text-gray-500 ml-1" />
                <x-text-input id="email" class="block w-full border bg-white/40 focus:bg-white/60 focus:ring-2 focus:ring-indigo-500/50 rounded-xl transition-all" 
                             type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="mt-5 space-y-1">
                <x-input-label for="password" :value="__('Password')" class="text-xs uppercase tracking-widest text-gray-500 ml-1" />
                <x-text-input id="password" class="block w-full border bg-white/40 focus:bg-white/60 focus:ring-2 focus:ring-indigo-500/50 rounded-xl transition-all"
                                type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Options -->
            {{-- <div class="flex items-center justify-between mt-6 text-sm">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded-md border-none bg-white/60 text-indigo-600 shadow-sm focus:ring-indigo-500/50" name="remember">
                    <span class="ms-2 text-gray-600 hover:text-gray-800 transition-colors">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div> --}}

            <div class="mt-8">
                <x-primary-button class="w-full justify-center py-3.5 bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 active:scale-[0.98] transition-all">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

    </div>
</x-guest-layout>