<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_akun');
            $table->string('no_pegawai')->unique();
            $table->timestamps();
            $table->foreign('id_akun')->references('id')->on('akun')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
