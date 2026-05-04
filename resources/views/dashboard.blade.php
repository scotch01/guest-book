<x-app-layout>

    <div class="p-6 space-y-6">

        {{-- TITLE --}}
        <h1 class="text-2xl font-bold text-gray-800">
            Dashboard Hari Ini
        </h1>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="bg-white shadow rounded p-4">
                <div class="text-sm text-gray-500">Total Tamu</div>
                <div class="text-xl font-bold">{{ $totalToday }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-sm text-gray-500">Sudah Dilayani</div>
                <div class="text-xl font-bold text-green-600">{{ $assignedToday }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-sm text-gray-500">Belum Dilayani</div>
                <div class="text-xl font-bold text-red-600">{{ $unassignedToday }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-sm text-gray-500">Antrian Terakhir</div>
                <div class="text-xl font-bold text-blue-600">{{ $lastQueue }}</div>
            </div>

        </div>

        {{-- LAYANAN --}}
        <div class="grid grid-cols-2 gap-4">

            <div class="bg-white shadow rounded p-4">
                <div class="text-sm text-gray-500">PPID</div>
                <div class="text-xl font-bold">{{ $ppidToday }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-sm text-gray-500">Pelayanan PST</div>
                <div class="text-xl font-bold">{{ $pstToday }}</div>
            </div>

        </div>

        {{-- ANTRIAN --}}
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold mb-3">Antrian Hari Ini</h2>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">No</th>
                        <th class="border px-2 py-1">Nama</th>
                        <th class="border px-2 py-1">Keperluan</th>
                        <th class="border px-2 py-1">Pegawai</th>
                        <th class="border px-2 py-1">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queues as $q)
                        <tr>
                            <td class="border px-2 py-1 text-center">{{ $q->queue_number }}</td>
                            <td class="border px-2 py-1">{{ $q->guest->nama }}</td>
                            <td class="border px-2 py-1">{{ $q->guest->keperluan }}</td>
                            <td class="border px-2 py-1">
                                {{ $q->guest->assignment->employee->nama ?? '-' }}
                            </td>
                            <td class="border px-2 py-1 text-center">
                                @if($q->guest->assignment)
                                    <span class="text-green-600">Dilayani</span>
                                @else
                                    <span class="text-red-600">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3 text-gray-500">
                                Tidak ada antrian hari ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- DISTRIBUSI PEGAWAI --}}
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold mb-3">Distribusi Pegawai</h2>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Nama Pegawai</th>
                        <th class="border px-2 py-1">Jumlah Tamu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employeeStats as $row)
                        <tr>
                            <td class="border px-2 py-1">{{ $row->nama }}</td>
                            <td class="border px-2 py-1 text-center">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-3 text-gray-500">
                                Belum ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-app-layout>