<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pauds', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 8)->nullable()->unique();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->foreignId('kecamatan_id')
                ->constrained('kecamatans')
                ->restrictOnDelete();
            $table->string('kelurahan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('jenis_satuan')->nullable();
            $table->string('status')->nullable();
            $table->string('kepala_sekolah')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->timestamps();

            $table->index('nama');
            $table->index('kelurahan');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pauds');
    }
};