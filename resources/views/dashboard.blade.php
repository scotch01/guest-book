<x-app-layout>
    <div class="p-6 space-y-6 max-w-7xl mx-auto">

        {{-- HEADER SECTION --}}
        <div class="bg-gradient-to-r from-indigo-50/50 to-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Dashboard Hari Ini
                    </h1>
                    <p class="text-gray-500 mt-1">Pantau antrian dan statistik layanan secara real-time.</p>
                </div>

                {{-- SYNC DATA (Smoke Glass Style) --}}
                <div
                    class="bg-white/40 backdrop-blur-md border border-indigo-100 rounded-2xl p-4 flex items-center justify-between gap-6 shadow-sm min-w-[320px] ring-1 ring-indigo-500/5">
                    <div>
                        <div class="text-sm font-bold text-indigo-900/80">Sync Data Tamu</div>
                        <div class="text-xs text-indigo-500/60 flex items-center gap-1 mt-0.5 font-medium">
                            <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @if ($lastSync)
                                {{ \Carbon\Carbon::parse($lastSync)->translatedFormat('j F Y, H:i') }} WIB
                            @else
                                Belum pernah sync
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('sync.guests') }}" id="syncForm">
                        @csrf
                        <button id="syncButton" {{ !$canSync ? 'disabled' : '' }}
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-all active:scale-95
                        {{ $canSync ? 'bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200' : 'bg-gray-400 cursor-not-allowed' }}">

                            <span id="spinner" class="hidden">
                                <svg class="w-4 h-4 text-white animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4a10 10 0 00-10 10h2z"></path>
                                </svg>
                            </span>

                            <span id="btnText" data-remaining="{{ $remainingSeconds }}"
                                class="flex items-center gap-2">
                                <svg id="hourglass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-pulse text-indigo-200"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M6 2h12v2h-1v2c0 2.21-1.79 4-4 4s-4-1.79-4-4V4H6V2zm1 20h10v-2h-1v-2c0-2.21-1.79-4-4-4s-4 1.79-4 4v2H7v2z" />
                                </svg>
                                <span id="countdownText">
                                    @if ($canSync)
                                        Sync Sekarang
                                    @else
                                        Tunggu...
                                    @endif
                                </span>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ALERTS --}}
        @if (session('success') || session('error'))
            <div
                class="rounded-xl p-4 {{ session('success') ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }} text-sm font-bold animate-fade-in flex items-center gap-3 shadow-sm">
                <span class="w-2 h-2 rounded-full {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        {{-- BENTO GRID STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

            {{-- Main Stats --}}
            <div class="md:col-span-3 bg-white border border-gray-100 p-5 rounded-2xl shadow-sm ring-1 ring-black/[0.02]">
                <div class="text-xs uppercase tracking-widest text-gray-400 font-bold">Total Tamu</div>
                <div class="text-4xl font-black text-gray-900 mt-2 flex items-baseline gap-2">
                    {{ $totalToday }}
                    <span class="text-xs font-medium text-gray-400 uppercase">Orang</span>
                </div>
            </div>

            <div class="md:col-span-3 bg-white border-l-4 border-l-green-500 border border-gray-100 p-5 rounded-2xl shadow-sm">
                <div class="text-xs uppercase tracking-widest text-green-600/70 font-bold">Sudah Dilayani</div>
                <div class="text-4xl font-black text-green-600 mt-2">{{ $assignedToday }}</div>
            </div>

            <div class="md:col-span-3 bg-white border-l-4 border-l-red-500 border border-gray-100 p-5 rounded-2xl shadow-sm">
                <div class="text-xs uppercase tracking-widest text-red-600/70 font-bold">Menunggu</div>
                <div class="text-4xl font-black text-red-600 mt-2">{{ $unassignedToday }}</div>
            </div>

            <div class="md:col-span-3 bg-indigo-600 border border-indigo-700 p-5 rounded-2xl shadow-indigo-100 shadow-xl">
                <div class="text-xs uppercase tracking-widest text-indigo-200 font-bold">Antrian Terakhir</div>
                <div class="text-4xl font-black text-white mt-2">{{ $lastQueue }}</div>
            </div>

            {{-- Secondary Stats --}}
            <div class="md:col-span-6 flex gap-4">
                <div
                    class="flex-1 bg-white border border-gray-100 p-5 rounded-2xl shadow-sm flex items-center justify-between border-b-4 border-b-blue-500">
                    <div>
                        <div class="text-xs uppercase tracking-widest text-gray-400 font-bold">PPID</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $ppidToday }}</div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="flex-1 bg-white border border-gray-100 p-5 rounded-2xl shadow-sm flex items-center justify-between border-b-4 border-b-orange-500">
                    <div>
                        <div class="text-xs uppercase tracking-widest text-gray-400 font-bold">Pelayanan PST</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $pstToday }}</div>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-xl text-orange-600 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- ANTRIAN TABLE --}}
            <div
                class="md:col-span-12 lg:col-span-8 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50/50 to-white">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-2 h-4 bg-indigo-600 rounded-full"></span>
                        Antrian Hari Ini
                    </h2>
                    <span class="text-[10px] font-black px-2 py-0.5 bg-indigo-600 text-white rounded uppercase tracking-tighter animate-pulse">Live</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/80 text-gray-500 text-[10px] uppercase tracking-widest font-black">
                            <tr>
                                <th class="px-6 py-3 text-center border-r border-gray-100 w-16">No</th>
                                <th class="px-6 py-3 text-left border-r border-gray-100">Nama</th>
                                <th class="px-6 py-3 text-left border-r border-gray-100">Keperluan</th>
                                <th class="px-6 py-3 text-left border-r border-gray-100">Pegawai</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($queues as $q)
                                <tr class="hover:bg-indigo-50/20 transition-colors group">
                                    <td
                                        class="px-6 py-4 text-center font-bold text-indigo-600 border-r border-gray-50">
                                        {{ $q->queue_number }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 border-r border-gray-50 uppercase text-xs tracking-tight">
                                        {{ $q->guest->nama }}</td>
                                    <td class="px-6 py-4 text-gray-500 border-r border-gray-50 text-xs">
                                        {{ $q->guest->keperluan }}</td>
                                    <td class="px-6 py-4 text-indigo-900/60 border-r border-gray-50 font-medium text-xs">
                                        {{ $q->guest->assignment->employee->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($q->guest->assignment)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-green-100 text-green-700 border border-green-200">SELESAI</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-orange-100 text-orange-700 border border-orange-200 animate-pulse">MENUNGGU</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Tidak ada
                                        antrian aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- DISTRIBUSI PEGAWAI --}}
            <div
                class="md:col-span-12 lg:col-span-4 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden h-fit">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-bold text-gray-800">Distribusi Pegawai</h2>
                </div>
                <div class="p-0">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase tracking-widest font-black">
                            <tr>
                                <th class="px-6 py-3 text-left border-r border-gray-100 uppercase">Pegawai</th>
                                <th class="px-6 py-3 text-center uppercase">Tamu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($employeeStats as $row)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-700 border-r border-gray-50 text-xs uppercase tracking-tighter">
                                        {{ $row->nama }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded text-xs">{{ $row->total }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-gray-400 italic text-xs">Belum ada
                                        data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
        {{-- Script Tetap Sama (Logic Tidak Disentuh) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('btnText');
            const countdownText = document.getElementById('countdownText');
            const btn = document.getElementById('syncButton');
            const hourglass = document.getElementById('hourglass');
            const spinner = document.getElementById('spinner');
            const form = document.getElementById('syncForm');
            if (!wrapper || !countdownText || !btn) return;
            let remaining = parseInt(wrapper.dataset.remaining || 0);

            function formatTime(sec) {
                const m = Math.floor(sec / 60);
                const s = sec % 60;
                return `${m}:${s.toString().padStart(2, '0')}`;
            }

            function tick() {
                if (remaining <= 0) {
                    countdownText.innerText = 'Sync Sekarang';
                    btn.disabled = false;
                    btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    btn.classList.add('bg-indigo-600');
                    if (hourglass) hourglass.classList.remove('hidden');
                    return;
                }
                countdownText.innerText = `Tunggu ${formatTime(remaining)}`;
                remaining--;
                setTimeout(tick, 1000);
            }
            if (remaining > 0) {
                btn.disabled = true;
                btn.classList.add('cursor-not-allowed');
                if (hourglass) hourglass.classList.remove('hidden');
                tick();
            } else {
                if (hourglass) hourglass.classList.add('hidden');
            }
            if (form) {
                form.addEventListener('submit', function() {
                    btn.disabled = true;
                    if (spinner) spinner.classList.remove('hidden');
                    if (hourglass) hourglass.classList.add('hidden');
                    countdownText.innerText = 'Sync berjalan...';
                });
            }
        });
    </script>
</x-app-layout>