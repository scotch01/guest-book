<x-app-layout>
    <div class="p-6 space-y-6 max-w-3xl mx-auto">

        {{-- HEADER CONTAINER --}}
        <div class="bg-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Pegawai</h1>
                    <p class="text-sm text-gray-500 mt-1">Data petugas baru untuk membantu pelayanan tamu.</p>
                </div>
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('employees.store') }}" class="p-8 space-y-6">
                @csrf

                {{-- Nama --}}
                <div class="space-y-1.5">
                    <x-input-label for="nama" :value="__('Nama Lengkap')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
                    <x-text-input id="nama" name="nama" type="text" 
                        value="{{ old('nama') }}" 
                        class="block w-full border-none bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all py-3 px-4 shadow-sm" 
                        placeholder="Masukkan nama lengkap pegawai"
                        required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                </div>

                {{-- NIP --}}
                <div class="space-y-1.5">
                    <x-input-label for="nip" :value="__('NIP (Opsional)')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
                    <x-text-input id="nip" name="nip" type="text" 
                        value="{{ old('nip') }}" 
                        class="block w-full border-none bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all py-3 px-4 shadow-sm font-mono text-gray-600" 
                        placeholder="Contoh: 19XXXXXXXXXXXXXX" />
                    <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                </div>

                {{-- ACTIONS --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Simpan Pegawai
                    </button>

                    <a href="{{ route('employees.index') }}"
                       class="inline-flex items-center justify-center px-8 py-3 bg-white border border-gray-200 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all active:scale-95">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>