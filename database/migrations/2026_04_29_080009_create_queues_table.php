<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guest_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('queue_number');
            $table->date('queue_date');

            $table->timestamps();

            // constraint penting (ANTI DUPLICATE)
            $table->unique(['queue_number', 'queue_date']);

            // index untuk performa
            $table->index('queue_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
