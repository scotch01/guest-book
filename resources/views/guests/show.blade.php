<x-app-layout>
    <div class="p-6 space-y-6 max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="bg-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Detail Tamu
                    </h1>
                    <div class="flex items-center mt-1 text-gray-500">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Informasi lengkap dan riwayat kunjungan pengunjung.</p>
                    </div>
                </div>

                <a href="{{ route('guests.index') }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-600 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition-all active:scale-95 ring-1 ring-black/5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- PROFILE SIDEBAR --}}
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm text-center">
                    <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <span class="text-2xl font-black uppercase">{{ substr($guest->nama, 0, 2) }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $guest->nama }}</h2>
                    <p class="text-sm text-gray-500">{{ $guest->nama_instansi }}</p>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col gap-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $guest->email }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $guest->telepon }}
                        </div>
                    </div>
                </div>

                {{-- STATUS CARD --}}
                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200">
                    <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Nomor Antrian</p>
                    <h3 class="text-4xl font-black mt-1">Q-{{ $guest->queue?->queue_number ?? '-' }}</h3>
                    <div class="mt-4 pt-4 border-t border-indigo-500/50 flex justify-between items-center text-sm">
                        <span>Petugas:</span>
                        <span class="font-bold">{{ $guest->assignment?->employee?->nama ?? 'Belum ada' }}</span>
                    </div>
                </div>
            </div>

            {{-- DETAILS TABLE AREA --}}
            <div class="md:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden h-fit">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Informasi Lengkap</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    
                    {{-- PERSONAL SECTION --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2">
                        <div class="p-6 border-r border-gray-100 space-y-1">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No. Identitas</p>
                            <p class="text-sm font-semibold text-gray-900 tracking-wider">{{ $guest->no_identitas }}</p>
                        </div>
                        <div class="p-6 space-y-1 bg-gray-50/30">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Instansi</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $guest->nama_instansi }}</p>
                        </div>
                    </div>

                    {{-- VISIT SECTION --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2">
                        <div class="p-6 border-r border-gray-100 space-y-1 bg-gray-50/30">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-indigo-500">Tanggal Kunjungan</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $guest->tanggal_kunjungan }}</p>
                        </div>
                        <div class="p-6 space-y-1 border-r border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest text-indigo-500">Jenis Layanan</p>
                            <span class="inline-flex px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-[11px] font-bold">
                                {{ $guest->jenis_layanan }}
                            </span>
                        </div>
                    </div>

                    {{-- NECESSITY SECTION --}}
                    <div class="p-6 space-y-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Keperluan / Pesan</p>
                        <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl italic text-gray-600 text-sm leading-relaxed">
                            "{{ $guest->keperluan }}"
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</x-app-layout>