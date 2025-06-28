<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('tipe')->default('jual');
            $table->decimal('harga', 12, 2)->nullable();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_kategori');
            $table->string('status')->default('tersedia');
            $table->timestamps();
            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
