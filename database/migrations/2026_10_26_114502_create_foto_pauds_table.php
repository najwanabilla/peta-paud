<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_pauds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paud_id')
                ->constrained('pauds')
                ->cascadeOnDelete();
            $table->string('path_foto');
            $table->string('keterangan')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_pauds');
    }
};