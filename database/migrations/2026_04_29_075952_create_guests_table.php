<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();

            // metadata dari google form
            $table->timestamp('form_timestamp')->nullable();

            // data utama tamu
            $table->string('nama');
            $table->string('no_identitas')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->unsignedInteger('umur')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();

            // instansi
            $table->string('kategori_instansi')->nullable();
            $table->string('nama_instansi')->nullable();

            // kontak
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();

            // 2 jenis email (PENTING)
            $table->string('email')->nullable();       // input user
            $table->string('form_email')->nullable();  // google form

            // kunjungan
            $table->date('tanggal_kunjungan')->nullable();

            // kebutuhan layanan
            $table->text('keperluan')->nullable();
            $table->string('jenis_layanan')->nullable();
            $table->string('jenis_data')->nullable();
            $table->string('level_data')->nullable();

            // deduplikasi (WAJIB)
            $table->string('source_key')->unique();

            $table->timestamps();

            // index penting
            $table->index('tanggal_kunjungan');
            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};