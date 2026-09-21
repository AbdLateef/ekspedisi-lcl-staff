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
            $table->string('kurir')->nullable()->after('no_container');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_awbs', function (Blueprint $table) {
            $table->dropColumn('kurir');
        });
    }
};
