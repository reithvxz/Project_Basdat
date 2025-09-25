<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('template_surats', function (Blueprint $table) {
            $table->id('template_id'); // Perbaikan ada di sini, dari ->id() menjadi ->id('template_id')
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats', 'jenis_id');
            $table->string('nama_template');
            $table->string('file_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_surats');
    }
};