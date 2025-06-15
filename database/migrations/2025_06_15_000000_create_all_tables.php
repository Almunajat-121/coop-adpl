<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
        });
        Schema::create('admin', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('no_pegawai');
            $table->foreign('id')->references('id')->on('akun')->onDelete('cascade');
        });
        Schema::create('pengguna', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->foreign('id')->references('id')->on('akun')->onDelete('cascade');
        });
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
        });
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('tipe')->nullable();
            $table->decimal('harga', 12, 2)->nullable();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('status')->nullable();
            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('set null');
        });
        Schema::create('foto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->string('url_foto');
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
        });
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_pembeli');
            $table->string('status');
            $table->timestamps();
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id')->on('pengguna')->onDelete('cascade');
        });
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_transaksi');
            $table->text('isi');
            $table->integer('rating');
            $table->foreign('id_transaksi')->references('id')->on('transaksi')->onDelete('cascade');
        });
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
    public function down()
    {
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('ulasan');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('foto');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('pengguna');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('akun');
    }
};
