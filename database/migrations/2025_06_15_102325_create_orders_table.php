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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();

        // Relasi ke user yang memesan. Jika user dihapus, order tetap ada (user_id jadi NULL).
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

        // Relasi ke pegawai/admin yang memproses (ACC_BY Anda).
        $table->foreignId('processed_by_user_id')->nullable()->constrained('users')->nullOnDelete();

        $table->string('status')->default('menunggu_pembayaran');
        $table->unsignedBigInteger('total_harga');
        $table->text('alamat_pengiriman');
        $table->string('nomor_resi')->nullable();
        $table->text('catatan_pembeli')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
