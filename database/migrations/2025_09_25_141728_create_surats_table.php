<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('surats', function (Blueprint $table) {
            $table->id('surat_id');
            $table->foreignId('mhs_id')->constrained('mahasiswas', 'mhs_id');
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats', 'jenis_id');
            $table->string('atas_nama');
            $table->foreignId('ormawa_id')->nullable()->constrained('ormawas', 'ormawa_id');
            $table->string('perihal');
            $table->text('deskripsi');
            $table->string('status');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('surats');
    }
};