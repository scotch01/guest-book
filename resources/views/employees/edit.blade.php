<x-app-layout>
    <div class="p-6 space-y-6 max-w-3xl mx-auto">

        {{-- HEADER CONTAINER --}}
        <div class="bg-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Pegawai</h1>
                    <p class="text-sm text-gray-500 mt-1">Perbarui informasi identitas petugas pelayanan.</p>
                </div>
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('employees.update', $employee) }}" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="space-y-1.5">
                    <x-input-label for="nama" :value="__('Nama Lengkap')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
                    <x-text-input id="nama" name="nama" type="text" 
                        value="{{ old('nama', $employee->nama) }}" 
                        class="block w-full border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all py-3 px-4 shadow-sm" 
                        required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                </div>

                {{-- NIP --}}
                <div class="space-y-1.5">
                    <x-input-label for="nip" :value="__('NIP (Opsional)')" class="text-xs uppercase tracking-widest text-gray-400 font-bold ml-1" />
                    <x-text-input id="nip" name="nip" type="text" 
                        value="{{ old('nip', $employee->nip) }}" 
                        class="block w-full border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl transition-all py-3 px-4 shadow-sm font-mono text-gray-600" 
                        placeholder="Contoh: 19XXXXXXXXXXXXXX" />
                    <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                </div>

                {{-- ACTIONS --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Data
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