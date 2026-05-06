<x-app-layout>
    <div class="p-6 space-y-6 max-w-5xl mx-auto">

        {{-- HEADER CONTAINER (Updated with Gradient) --}}
        <div class="bg-gradient-to-r from-indigo-50/50 to-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Detail Tamu
                    </h1>
                    <div class="flex items-center mt-1 text-gray-500">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium">Informasi lengkap dan riwayat kunjungan pengunjung.</p>
                    </div>
                </div>

                <a href="{{ route('guests.index') }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-indigo-100 rounded-xl font-bold text-xs text-indigo-600 uppercase tracking-widest shadow-sm hover:bg-indigo-50 transition-all active:scale-95 ring-1 ring-indigo-500/5">
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
                <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm text-center relative overflow-hidden">
                    {{-- Soft background accent --}}
                    <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>
                    
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-700 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner border-4 border-white">
                        <span class="text-3xl font-black uppercase">{{ substr($guest->nama, 0, 2) }}</span>
                    </div>
                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $guest->nama }}</h2>
                    <p class="text-sm font-bold text-indigo-600/70 uppercase tracking-tighter">{{ $guest->nama_instansi }}</p>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col gap-4">
                        <div class="flex items-center text-xs font-bold text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <svg class="w-4 h-4 mr-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="truncate">{{ $guest->email }}</span>
                        </div>
                        <div class="flex items-center text-xs font-bold text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <svg class="w-4 h-4 mr-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $guest->telepon }}
                        </div>
                    </div>
                </div>

                {{-- STATUS CARD (Refined) --}}
                <div class="bg-indigo-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-100 border border-indigo-500 relative overflow-hidden">
                    {{-- Decorative Circle --}}
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest">Nomor Antrian</p>
                    <h3 class="text-5xl font-black mt-1 tracking-tighter">Q-{{ $guest->queue?->queue_number ?? '-' }}</h3>
                    <div class="mt-6 pt-4 border-t border-indigo-400/30 flex justify-between items-center text-xs font-bold uppercase tracking-tighter">
                        <span class="opacity-70">Petugas:</span>
                        <span class="bg-white/20 px-2 py-1 rounded-lg">{{ $guest->assignment?->employee?->nama ?? 'Belum ada' }}</span>
                    </div>
                </div>
            </div>

            {{-- DETAILS TABLE AREA (Updated with subtle color zones) --}}
            <div class="md:col-span-2 bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden h-fit">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-indigo-600 rounded-full"></span>
                    <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest">Informasi Lengkap</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    
                    {{-- PERSONAL SECTION --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2">
                        <div class="p-6 border-r border-gray-50 space-y-1">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No. Identitas</p>
                            <p class="text-sm font-bold text-gray-900 tracking-widest font-mono italic px-2 py-1 bg-gray-50 rounded w-fit">{{ $guest->no_identitas }}</p>
                        </div>
                        <div class="p-6 space-y-1 bg-indigo-50/10">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Instansi</p>
                            <p class="text-sm font-bold text-gray-900 uppercase tracking-tight">{{ $guest->nama_instansi }}</p>
                        </div>
                    </div>

                    {{-- VISIT SECTION --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2">
                        <div class="p-6 border-r border-gray-50 space-y-1 bg-indigo-50/10">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Tanggal Kunjungan</p>
                            <p class="text-sm font-bold text-gray-900 uppercase">{{ $guest->tanggal_kunjungan }}</p>
                        </div>
                        <div class="p-6 space-y-1 border-r border-gray-50">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Jenis Layanan</p>
                            <span class="inline-flex px-3 py-1 bg-indigo-600 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                                {{ $guest->jenis_layanan }}
                            </span>
                        </div>
                    </div>

                    {{-- NECESSITY SECTION --}}
                    <div class="p-6 space-y-3">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Keperluan / Pesan</p>
                        <div class="p-5 bg-gradient-to-br from-gray-50 to-white border border-gray-100 rounded-2xl italic text-gray-600 text-sm leading-relaxed shadow-inner">
                            <svg class="w-4 h-4 text-indigo-300 mb-2" fill="currentColor" viewBox="0 0 20 20"><path d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7h10a1 1 0 110 2H5a1 1 0 010-2zm0 4h6a1 1 0 110 2H5a1 1 0 110-2z"/></svg>
                            "{{ $guest->keperluan }}"
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>