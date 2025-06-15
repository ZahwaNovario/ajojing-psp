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
        Schema::create('order_details', function (Blueprint $table) {
        $table->id();

        // Relasi ke induk order. Jika order dihapus, detailnya ikut terhapus.
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

        // Relasi ke barang. Jika barang dihapus, relasinya jadi NULL tapi record order detail tetap ada.
        $table->foreignId('barang_id')->nullable()->constrained('barangs')->nullOnDelete();

        $table->string('nama_barang'); // Salinan nama barang saat dibeli
        $table->unsignedInteger('kuantitas');
        $table->unsignedBigInteger('harga_saat_pembelian'); // Salinan harga saat dibeli

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
