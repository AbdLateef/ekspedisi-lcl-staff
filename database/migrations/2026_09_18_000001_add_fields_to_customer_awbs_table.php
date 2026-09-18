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
        Schema::table('customer_awbs', function (Blueprint $table) {
            $table->string('no_container')->nullable()->after('customer_name');
            $table->text('deskripsi')->nullable()->after('awb');
            $table->integer('jumlah_coli')->nullable()->after('deskripsi');
            $table->decimal('panjang', 8, 2)->nullable()->after('jumlah_coli');
            $table->decimal('lebar', 8, 2)->nullable()->after('panjang');
            $table->decimal('tinggi', 8, 2)->nullable()->after('lebar');
            $table->decimal('berat', 8, 2)->nullable()->after('tinggi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_awbs', function (Blueprint $table) {
            $table->dropColumn([
                'no_container',
                'deskripsi',
                'jumlah_coli',
                'panjang',
                'lebar',
                'tinggi',
                'berat',
            ]);
        });
    }
};
