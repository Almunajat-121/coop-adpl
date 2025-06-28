<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_pelapor');
            $table->string('alasan');
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->timestamps();
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('id_pelapor')->references('id')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_admin')->references('id')->on('admin')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
