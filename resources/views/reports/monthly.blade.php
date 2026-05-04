<x-app-layout>

    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Laporan Bulanan
                </h1>
                <p class="text-sm text-gray-500">
                    Bulan:
                    <span class="font-semibold">
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                    </span>
                </p>
            </div>

            {{-- FILTER + EXPORT --}}
            <div class="flex flex-wrap gap-2 items-center">

                <form method="GET" class="flex items-center gap-2">
                    <input type="month"
                           name="month"
                           value="{{ $month }}"
                           class="border rounded px-2 py-1 text-sm">

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                        Filter
                    </button>
                </form>

                <a href="{{ route('report.monthly.pdf', ['month' => $month]) }}"
                   class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded">
                    Export PDF
                </a>

            </div>
        </div>

        {{-- CARD --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">

            {{-- TABLE --}}
            <table class="w-full text-sm border border-gray-300">

                {{-- HEAD --}}
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-3 py-2 w-12 text-center">No</th>
                        <th class="border px-3 py-2 text-left">Nama Pegawai</th>
                        <th class="border px-3 py-2 text-center w-40">Jumlah Tamu</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="text-gray-700">

                    {{-- DATA PEGAWAI --}}
                    @foreach ($data as $i => $row)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2 text-center">{{ $i + 1 }}</td>
                            <td class="border px-3 py-2">{{ $row->nama }}</td>
                            <td class="border px-3 py-2 text-center">{{ $row->total }}</td>
                        </tr>
                    @endforeach

                    {{-- SEPARATOR --}}
                    <tr>
                        <td colspan="3" class="border px-3 py-2 bg-gray-50 font-semibold">
                            Ringkasan Layanan
                        </td>
                    </tr>

                    {{-- PPID --}}
                    <tr>
                        <td colspan="2" class="border px-3 py-2">
                            Total Keperluan PPID
                        </td>
                        <td class="border px-3 py-2 text-center">
                            {{ $ppid }}
                        </td>
                    </tr>

                    {{-- PST DETAIL --}}
                    @foreach ($pstGrouped as $jenis => $items)
                        <tr>
                            <td colspan="2" class="border px-3 py-2">
                                Pelayanan PST - {{ $jenis }}
                            </td>
                            <td class="border px-3 py-2 text-center">
                                {{ $items }}
                            </td>
                        </tr>
                    @endforeach

                    {{-- TOTAL PST --}}
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="2" class="border px-3 py-2">
                            Total Pelayanan PST
                        </td>
                        <td class="border px-3 py-2 text-center">
                            {{ $totalPst }}
                        </td>
                    </tr>

                    {{-- TOTAL TAMU --}}
                    <tr class="bg-gray-100 font-bold text-gray-800">
                        <td colspan="2" class="border px-3 py-2">
                            Total Tamu
                        </td>
                        <td class="border px-3 py-2 text-center">
                            {{ $total }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>