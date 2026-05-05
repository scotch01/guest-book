<x-app-layout>
    <div class="p-6 space-y-6 max-w-[1600px] mx-auto">

        {{-- HEADER & TABS --}}
        <div class="bg-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Data Tamu
                    </h1>
                    <div class="flex items-center mt-1 text-gray-500">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-sm text-gray-500">Manajemen daftar pengunjung yang bersumber dari Google Form.
                        </p>
                    </div>
                </div>

                <div
                    class="flex p-1 bg-white/40 backdrop-blur-md rounded-2xl border border-gray-200/50 shadow-sm w-fit ring-1 ring-black/5">
                    <a href="{{ route('guests.index', ['tab' => 'today']) }}"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $tab === 'today' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-white/20' }}">
                        Tamu Hari Ini
                    </a>
                    <a href="{{ route('guests.index', ['tab' => 'history']) }}"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $tab === 'history' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-white/20' }}">
                        Riwayat Tamu
                    </a>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div
                class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-2xl text-sm font-bold flex items-center gap-3 animate-fade-in shadow-sm">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4a1 1 0 112 0 1 1 0 01-2 0zm.293-9.707a1 1 0 011.414 0l.007.007v5.4a1 1 0 11-2 0v-5.4l.579-.007z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- FILTER CARD (Only for History) --}}
        @if ($tab === 'history')
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <input type="hidden" name="tab" value="history">

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-400 ml-1">Dari
                            Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="block border-gray-200 bg-gray-50 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm cursor-pointer"
                            onclick="this.showPicker()">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-400 ml-1">Sampai
                            Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="block border-gray-200 bg-gray-50 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm cursor-pointer"
                            onclick="this.showPicker()">
                    </div>

                    <button
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </form>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                @if ($guests->total() > 0)
                    <div
                        class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                        <form method="GET" class="flex items-center gap-3">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tampilkan:</label>
                            <div class="relative">
                                <select name="per_page" onchange="this.form.submit()"
                                    class="pl-3 pr-8 py-1.5 text-sm bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 appearance-none cursor-pointer font-medium text-gray-700">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                    </option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25
                                    </option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                    </option>
                                    <option value="50" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                    </option>
                                </select>
                            </div>
                        </form>
                    </div>
                @endif
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-4 text-center font-bold border-r border-gray-100">No</th>
                            <th class="px-6 py-4 text-left font-bold border-r border-gray-100">Informasi Tamu</th>
                            <th class="px-6 py-4 text-center font-bold border-r border-gray-100">Kunjungan</th>
                            <th class="px-6 py-4 text-left font-bold border-r border-gray-100">Layanan & Keperluan</th>
                            <th class="px-6 py-4 text-center font-bold border-r border-gray-100">Petugas</th>
                            <th class="px-6 py-4 text-center font-bold">Assignment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($guests as $i => $guest)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td
                                    class="px-4 py-4 text-center border-r border-gray-100 font-medium text-gray-400 italic">
                                    {{ $guests->firstItem() + $i }}
                                </td>
                                <td class="px-6 py-4 border-r border-gray-100">
                                    <div class="font-bold text-gray-900">{{ $guest->nama }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $guest->nama_instansi }}</div>
                                </td>
                                <td class="px-6 py-4 border-r border-gray-100 text-center">
                                    <div class="text-xs font-bold text-gray-800">{{ $guest->tanggal_kunjungan }}</div>
                                    <div class="mt-1 flex flex-col items-center gap-1">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-blue-600 text-white shadow-sm">
                                            Q-{{ $guest->queue?->queue_number ?? '-' }}
                                        </span>

                                        {{-- STATUS --}}
                                        @if ($guest->status === 'menunggu')
                                            <span class="text-[10px] font-bold text-yellow-600 uppercase">
                                                Menunggu
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-green-600 uppercase">
                                                Selesai
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 border-r border-gray-100">
                                    <div class="flex flex-col gap-1.5">
                                        <span
                                            class="w-fit px-2 py-1 text-[10px] font-bold uppercase tracking-tighter bg-indigo-100 text-indigo-700 rounded-md">
                                            {{ $guest->jenis_layanan }}
                                        </span>
                                        <div class="text-xs text-gray-600 line-clamp-1">{{ $guest->keperluan }}</div>
                                        <a href="{{ route('guests.show', $guest->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 text-[11px] font-bold underline decoration-indigo-200 underline-offset-2">Lihat
                                            Detail</a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 border-r border-gray-100 text-center">
                                    @if ($guest->assignment?->employee?->nama)
                                        <div class="text-xs font-semibold text-gray-800">
                                            {{ $guest->assignment->employee->nama }}
                                        </div>
                                    @elseif ($guest->status === 'dilayani')
                                        <span class="text-gray-300 italic text-xs">—</span>
                                    @else
                                        <span class="text-gray-300 italic text-xs">Belum ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center bg-gray-50/30">
                                    @if ($guest->assignment)
                                        {{-- SUDAH DI-ASSIGN = DILAYANI --}}
                                        <div class="flex flex-col items-center gap-1">
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-[10px] font-bold text-green-600 uppercase">
                                                Dilayani
                                            </span>
                                        </div>
                                    @elseif ($guest->status === 'menunggu')
                                        {{-- BELUM ASSIGN --}}
                                        <form method="POST" action="{{ route('assign.store') }}"
                                            class="flex flex-col gap-2 max-w-[150px] mx-auto">
                                            @csrf
                                            <input type="hidden" name="guest_id" value="{{ $guest->id }}">
                                            <div class="md:col-span-6" x-data="pegawaiDropdown(@js(
                                                $employees->map(
                                                    fn($e) => [
                                                        'id' => $e->id,
                                                        'text' => $e->nama,
                                                    ],
                                                ),
                                            ))">

                                                <input type="hidden" name="employee_id" :value="selectedId"
                                                    required>

                                                <div class="relative">

                                                    {{-- BUTTON --}}
                                                    <button type="button" @click="toggle($event)"
                                                        class="w-full text-left bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs shadow-sm flex justify-between items-center group">

                                                        <span class="font-medium text-gray-700 truncate"
                                                            x-text="selectedText || 'Pilih Pegawai'"></span>

                                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>

                                                    {{-- DROPDOWN --}}
                                                    <div x-show="open" x-cloak x-transition
                                                        @click.outside="open = false"
                                                        :class="openUp
                                                            ?
                                                            'absolute bottom-full mb-2 z-50 w-full bg-white border border-gray-200 rounded-xl shadow-xl' :
                                                            'absolute top-full mt-2 z-50 w-full bg-white border border-gray-200 rounded-xl shadow-xl'">

                                                        {{-- SEARCH --}}
                                                        <div class="p-2 border-b bg-gray-50">
                                                            <input type="text" x-model="search"
                                                                placeholder="Cari nama pegawai..."
                                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                                                        </div>

                                                        {{-- LIST --}}
                                                        <div class="max-h-48 overflow-y-auto">

                                                            <template x-for="item in filteredItems()"
                                                                :key="item.id">
                                                                <div @click="select(item)"
                                                                    class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-xs text-gray-700 font-medium transition-colors border-b border-gray-50 last:border-0"
                                                                    x-text="item.text">
                                                                </div>
                                                            </template>

                                                            <div x-show="filteredItems().length === 0"
                                                                class="px-3 py-6 text-center text-gray-400 text-xs">
                                                                Pegawai tidak ditemukan
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button
                                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 rounded-lg text-[10px] shadow-md shadow-green-100 transition-all active:scale-95">
                                                Assign Petugas
                                            </button>
                                        </form>
                                    @else
                                        {{-- HISTORIS --}}
                                        <div class="flex flex-col items-center gap-1">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase">
                                                Selesai
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400 italic">Tidak ada data tamu
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($guests instanceof \Illuminate\Pagination\LengthAwarePaginator && $guests->hasPages())
                    <div class="p-4 border-t">
                        {{ $guests->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function pegawaiDropdown(items) {
            return {
                open: false,
                openUp: false,
                search: '',
                selectedId: '',
                selectedText: '',
                items: items,

                toggle(event) {
                    this.open = !this.open

                    this.$nextTick(() => {
                        const rect = event.target.getBoundingClientRect()
                        const dropdownHeight = 250 // estimasi tinggi dropdown

                        const spaceBelow = window.innerHeight - rect.bottom

                        this.openUp = spaceBelow < dropdownHeight
                    })
                },

                filteredItems() {
                    return this.items.filter(i =>
                        i.text.toLowerCase().includes(this.search.toLowerCase())
                    )
                },

                select(item) {
                    this.selectedId = item.id
                    this.selectedText = item.text
                    this.open = false
                    this.search = ''
                }
            }
        }
    </script>
</x-app-layout>
