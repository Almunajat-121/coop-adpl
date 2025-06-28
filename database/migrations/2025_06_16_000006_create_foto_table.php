<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('foto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang');
            $table->string('url_foto');
            $table->timestamps();
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('foto');
    }
};
