<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>LAPORAN BULANAN</h2>
    <div class="subtitle">
        Bulan: {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Pegawai</th>
                <th class="text-center">Jumlah Tamu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $row->nama }}</td>
                    <td class="text-center">{{ $row->total }}</td>
                </tr>
            @endforeach

            {{-- Separator --}}
            <tr>
                <td colspan="3" class="bold">Ringkasan Layanan</td>
            </tr>

            {{-- PPID --}}
            <tr>
                <td colspan="2">Total Keperluan PPID</td>
                <td class="text-center">{{ $ppid }}</td>
            </tr>

            {{-- PST --}}
            @foreach ($pstGrouped as $jenis => $items)
                <tr>
                    <td colspan="2">
                        Pelayanan PST - {{ $jenis }}
                    </td>
                    <td class="text-center">{{ $items }}</td>
                </tr>
            @endforeach

            {{-- TOTAL PST --}}
            <tr>
                <td colspan="2" class="bold">Total Pelayanan PST</td>
                <td class="text-center bold">{{ $totalPst }}</td>
            </tr>

            {{-- TOTAL --}}
            <tr>
                <td colspan="2" class="bold">Total Tamu</td>
                <td class="text-center bold">{{ $total }}</td>
            </tr>

        </tbody>
    </table>

    <br><br>

    <table width="100%" style="border: none;">
        <tr>
            <td style="border: none;"></td>

            <td style="border: none; text-align: center; width: 300px;">
                Diketahui,<br>
                Kepala BPS Kota Pariaman
                <br><br><br><br><br>

                <strong>Riqadli, S.Si, MM</strong><br>
                NIP. 197105131992021002
            </td>
        </tr>
    </table>

</body>

</html>
