<x-app-layout>
    <div x-data="{ open: false, action: null }" @keydown.escape.window="open = false">
        <div class="p-6 space-y-6 max-w-7xl mx-auto">

            {{-- HEADER CONTAINER --}}
            <div class="bg-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                            Data Pegawai
                        </h1>
                        <div class="flex items-center mt-1 text-gray-500">
                            <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-sm">Kelola daftar petugas pelayanan dan hak akses sistem.</p>
                        </div>
                    </div>

                    <a href="{{ route('employees.create') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Pegawai
                    </a>
                </div>
            </div>

            {{-- FLASH MESSAGE --}}
            @if (session('success'))
                <div
                    class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-2xl text-sm font-bold flex items-center gap-3 animate-fade-in shadow-sm">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

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

            {{-- TABLE CARD --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    @if ($employees->total() > 0)
                        <div
                            class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                            <form method="GET" class="flex items-center gap-3">
                                <label
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tampilkan:</label>
                                <div class="relative">
                                    <select name="per_page" onchange="this.form.submit()"
                                        class="pl-3 pr-8 py-1.5 text-sm bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 appearance-none cursor-pointer font-medium text-gray-700">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    @endif
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-4 text-center font-bold border-r border-gray-100 w-16">No</th>
                                <th class="px-6 py-4 text-left font-bold border-r border-gray-100">Nama Lengkap</th>
                                <th class="px-6 py-4 text-left font-bold border-r border-gray-100">Identitas (NIP)</th>
                                <th class="px-6 py-4 text-center font-bold border-r border-gray-100 w-32">Status</th>
                                <th class="px-6 py-4 text-center font-bold">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse ($employees as $i => $emp)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td
                                        class="px-6 py-4 text-center border-r border-gray-100 font-medium text-gray-400 italic">
                                        {{ $employees->firstItem() + $i }}
                                    </td>
                                    <td class="px-6 py-4 border-r border-gray-100">
                                        <div class="font-bold text-gray-900">{{ $emp->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 border-r border-gray-100">
                                        <span
                                            class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-600 border border-gray-200">
                                            {{ $emp->nip ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4 border-r border-gray-100 text-center">
                                        @if ($emp->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-green-100 text-green-700 border border-green-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-gray-100 text-gray-400 border border-gray-200">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTION --}}
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-4">
                                            <a href="{{ route('employees.edit', $emp) }}"
                                                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-bold text-xs uppercase tracking-tighter transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>

                                            @if ($emp->is_active)
                                                <form method="POST" action="{{ route('employees.destroy', $emp) }}"
                                                    x-ref="form{{ $emp->id }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                        @click="
                                                    open = true;
                                                    action = $refs.form{{ $emp->id }};
                                                    "
                                                        class="inline-flex items-center text-red-500 hover:text-red-700 font-bold text-xs uppercase tracking-tighter transition-colors">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                        Nonaktifkan
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('employees.activate', $emp) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        class="inline-flex items-center text-green-600 hover:text-green-800 font-bold text-xs uppercase tracking-tighter transition-colors">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-gray-400 italic">
                                        Belum ada data pegawai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator && $employees->hasPages())
                        <div class="p-4 border-t">
                            {{ $employees->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
        <!-- MODAL -->
        <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center">

            <!-- overlay -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>

            <!-- modal -->
            <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-red-100 text-red-600 p-2 rounded-full">
                        <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.451 11.48c.75 1.335-.213 2.996-1.742 2.996H3.548c-1.53 0-2.492-1.66-1.743-2.996L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 7v4a1 1 0 001.993.117L11 11V7a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold">Konfirmasi</h2>
                </div>

                <p class="text-gray-500 mb-8 font-medium leading-relaxed">
                    Anda akan <span class="text-red-600 font-bold">menonaktifkan</span> pegawai ini dari sistem pelayanan tamu. Tindakan ini dapat diubah kapan saja.
                </p>

                <div class="flex justify-end gap-3">
                    <button @click="open = false" class="px-4 py-2 rounded-lg border">
                        Batal
                    </button>

                    <button @click="action.submit()"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        Ya, Nonaktifkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
