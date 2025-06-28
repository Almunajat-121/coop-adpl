<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_pembeli');
            $table->enum('status', ['diajukan', 'ditolak', 'diterima', 'selesai']);
            $table->timestamps();
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
