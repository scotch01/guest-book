<x-app-layout>
    <div class="p-6 space-y-6 max-w-7xl mx-auto">

        {{-- HEADER CONTAINER --}}
        <div class="bg-gray-500/5 backdrop-blur-md border border-gray-200/50 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                        Laporan Bulanan
                    </h1>
                    <div class="flex items-center mt-1 text-gray-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm">
                            Bulan: <span class="font-semibold text-indigo-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</span>
                        </p>
                    </div>
                </div>

                {{-- FILTER + EXPORT --}}
                <div class="flex flex-wrap gap-3 items-center">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="month"
                               name="month"
                               value="{{ $month }}"
                               class="bg-white border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm cursor-pointer" onclick="this.showPicker()">

                        <button type="submit"
                                class="inline-flex items-center bg-white hover:bg-gray-50 text-gray-700 font-semibold text-sm px-4 py-2 rounded-xl border border-gray-200 shadow-sm transition-all active:scale-95">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filter
                        </button>
                    </form>

                    <a href="{{ route('report.monthly.pdf', ['month' => $month]) }}" target="_blank"
                       class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-4 py-2 rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    {{-- HEAD --}}
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200">
                            <th class="border-r border-gray-200 px-4 py-4 w-16 text-center font-bold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="border-r border-gray-200 px-6 py-4 text-left font-bold text-gray-600 uppercase tracking-wider">Nama Pegawai</th>
                            <th class="px-6 py-4 text-center w-48 font-bold text-gray-600 uppercase tracking-wider">Jumlah Tamu</th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @foreach ($data as $i => $row)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                <td class="border-r border-gray-200 px-4 py-3.5 text-center font-medium">{{ $i + 1 }}</td>
                                <td class="border-r border-gray-200 px-6 py-3.5">{{ $row->nama }}</td>
                                <td class="px-6 py-3.5 text-center font-medium">{{ $row->total }}</td>
                            </tr>
                        @endforeach

                        {{-- SEPARATOR --}}
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-6 py-3 font-bold text-indigo-900 border-y border-gray-200">
                                Ringkasan Layanan
                            </td>
                        </tr>

                        {{-- PPID --}}
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td colspan="2" class="border-r border-gray-200 px-6 py-3 text-gray-600 italic">Total Keperluan PPID</td>
                            <td class="px-6 py-3 text-center font-semibold text-gray-900 bg-gray-50/30">{{ $ppid }}</td>
                        </tr>

                        {{-- PST DETAIL --}}
                        @foreach ($pstGrouped as $jenis => $items)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td colspan="2" class="border-r border-gray-200 px-6 py-3 text-gray-600">Pelayanan PST - {{ $jenis }}</td>
                                <td class="px-6 py-3 text-center font-semibold text-gray-900">{{ $items }}</td>
                            </tr>
                        @endforeach

                        {{-- TOTAL PST --}}
                        <tr class="bg-indigo-50/40 border-y border-gray-200">
                            <td colspan="2" class="border-r border-gray-200 px-6 py-3 font-bold text-gray-800">Total Pelayanan PST</td>
                            <td class="px-6 py-3 text-center font-bold text-indigo-700">{{ $totalPst }}</td>
                        </tr>

                        {{-- TOTAL TAMU --}}
                        <tr class="bg-indigo-600 text-white">
                            <td colspan="2" class="px-6 py-4 font-bold text-lg rounded-bl-xl">Total Keseluruhan Tamu</td>
                            <td class="px-6 py-4 text-center font-black text-xl">{{ $total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>