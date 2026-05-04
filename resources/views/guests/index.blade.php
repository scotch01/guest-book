<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Tamu</h1>
            <p class="text-sm text-gray-500">Daftar pengunjung berdasarkan Google Form</p>
        </div>

        <div class="flex gap-2 mb-4">
            <a href="{{ route('guests.index', ['tab' => 'today']) }}"
                class="px-4 py-2 rounded {{ $tab === 'today' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                Tamu Hari Ini
            </a>

            <a href="{{ route('guests.index', ['tab' => 'history']) }}"
                class="px-4 py-2 rounded {{ $tab === 'history' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                Riwayat Tamu
            </a>
        </div>

        {{-- FILTER CARD --}}
        @if ($tab === 'history')
            <div class="bg-white shadow rounded-lg p-4 mb-4">
                <form method="GET" class="flex gap-3 items-end">
                    <input type="hidden" name="tab" value="history">

                    <div>
                        <label class="text-xs">Dari</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="border px-2 py-1">
                    </div>

                    <div>
                        <label class="text-xs">Sampai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="border px-2 py-1">
                    </div>

                    <button class="bg-blue-600 text-white px-3 py-1 rounded">Filter</button>
                </form>
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Instansi</th>
                        <th class="p-3 text-center">Tanggal</th>
                        <th class="p-3 text-left">Keperluan</th>
                        <th class="p-3 text-left">Jenis Layanan</th>
                        <th class="p-3 text-center">Aksi</th>
                        <th class="p-3 text-center">Pegawai</th>
                        <th class="p-3 text-center">Antrian</th>
                        <th class="p-3 text-center">Assign</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($guests as $i => $guest)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3">
                                {{ $guests->firstItem() + $i }}
                            </td>
                            <td class="p-3 font-medium text-gray-800">
                                {{ $guest->nama }}
                            </td>
                            <td class="p-3 text-gray-600">
                                {{ $guest->nama_instansi }}
                            </td>
                            <td class="p-3 text-center">
                                {{ $guest->tanggal_kunjungan }}
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                    {{ $guest->keperluan }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                    {{ $guest->jenis_layanan }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('guests.show', $guest->id) }}"
                                    class="text-blue-600 hover:underline text-sm">
                                    Detail
                                </a>
                            </td>

                            <td class="p-3 text-center">
                                {{ $guest->assignment?->employee?->nama ?? '-' }}
                            </td>

                            <td class="p-3 text-center font-bold text-blue-600">
                                {{ $guest->queue?->queue_number ?? '-' }}
                            </td>

                            <td class="p-3 text-center">
                                @if (!$guest->assignment)
                                    <form method="POST" action="{{ route('assign.store') }}">
                                        @csrf
                                        <input type="hidden" name="guest_id" value="{{ $guest->id }}">

                                        <select name="employee_id" class="border rounded text-sm px-2 py-1" required>
                                            <option value="">Pilih</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->id }}">
                                                    {{ $emp->nama }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button class="bg-green-500 text-white px-2 py-1 rounded text-xs mt-1">
                                            Assign
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">Sudah</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div>
            {{ $guests->links() }}
        </div>

    </div>
</x-app-layout>
