<x-app-layout>
    <div class="p-6 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Tamu</h1>
            <p class="text-sm text-gray-500">Informasi lengkap pengunjung</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6 grid grid-cols-2 gap-4 text-sm">

            <div>
                <p class="text-gray-500">Nama</p>
                <p class="font-semibold">{{ $guest->nama }}</p>
            </div>

            <div>
                <p class="text-gray-500">No Identitas</p>
                <p class="font-semibold">{{ $guest->no_identitas }}</p>
            </div>

            <div>
                <p class="text-gray-500">Instansi</p>
                <p class="font-semibold">{{ $guest->nama_instansi }}</p>
            </div>

            <div>
                <p class="text-gray-500">Tanggal Kunjungan</p>
                <p class="font-semibold">{{ $guest->tanggal_kunjungan }}</p>
            </div>

            <div>
                <p class="text-gray-500">Keperluan</p>
                <p class="font-semibold">{{ $guest->keperluan }}</p>
            </div>

            <div>
                <p class="text-gray-500">Jenis Layanan</p>
                <p class="font-semibold">{{ $guest->jenis_layanan }}</p>
            </div>

            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-semibold">{{ $guest->email }}</p>
            </div>

            <div>
                <p class="text-gray-500">Telepon</p>
                <p class="font-semibold">{{ $guest->telepon }}</p>
            </div>

        </div>

        <a href="{{ route('guests.index') }}" class="text-blue-600 hover:underline text-sm">
            ← Kembali
        </a>

    </div>
</x-app-layout>
