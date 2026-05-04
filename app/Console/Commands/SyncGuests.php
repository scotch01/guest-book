<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google_Client;
use Google_Service_Sheets;
use App\Models\Guest;
use Illuminate\Support\Str;

class SyncGuests extends Command
{
    protected $signature = 'sync:guests';
    protected $description = 'Sync data dari Google Sheets ke database';

    public function handle()
    {
        $client = new Google_Client();
        $client->setAuthConfig(config('services.google.credentials'));
        $client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);

        $service = new Google_Service_Sheets($client);

        $spreadsheetId = env('GOOGLE_SHEETS_ID');
        $range = env('GOOGLE_SHEETS_RANGE');

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $rows = $response->getValues();

        if (!$rows) {
            $this->info('Tidak ada data.');
            return;
        }

        foreach ($rows as $row) {
            $data = $this->mapRow($row);

            if (!$data) continue;

            $guest = Guest::firstOrCreate(
                ['source_key' => $data['source_key']],
                $data
            );

            // kalau baru dibuat → generate queue
            if ($guest->wasRecentlyCreated && !empty($guest->tanggal_kunjungan)) {
                app(\App\Services\QueueService::class)->generate(
                    $guest->id,
                    $guest->tanggal_kunjungan
                );
            }
        }

        $this->info('Sync selesai.');
    }

    private function mapRow($row)
    {
        // safety helper
        $get = fn($i) => $row[$i] ?? null;

        // mapping sesuai urutan spreadsheet
        $mapped = [
            'form_timestamp'     => $this->parseTimestamp($get(0)),
            'nama'               => $get(1),
            'no_identitas'       => $get(2),
            'jenis_kelamin'      => $get(3),
            'umur'               => $this->toInt($get(4)),
            'pendidikan'         => $get(5),
            'pekerjaan'          => $get(6),
            'kategori_instansi'  => $get(7),
            'nama_instansi'      => $get(8),
            'alamat'             => $get(9),
            'telepon'            => $get(10),
            'email'              => $get(11),
            'tanggal_kunjungan'  => $this->parseDate($get(12)),
            'keperluan'          => $get(13),
            'jenis_layanan'      => $get(14),
            'jenis_data'         => $get(15),
            'level_data'         => $get(16),
            'form_email'         => $get(17),
        ];

        // validasi minimal
        if (!$mapped['nama']) {
            return null;
        }

        // deduplication key
        $mapped['source_key'] = md5(
            ($mapped['nama'] ?? '') .
            ($mapped['tanggal_kunjungan'] ?? '') .
            ($mapped['email'] ?? '') .
            ($mapped['form_email'] ?? '')
        );

        return $mapped;
    }

    private function parseDate($value)
    {
        if (!$value) return null;

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseTimestamp($value)
    {
        if (!$value) return null;

        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function toInt($value)
    {
        return is_numeric($value) ? (int)$value : null;
    }
}
