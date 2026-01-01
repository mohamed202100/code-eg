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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->default(1)
                ->comment('0 = inactive, 1 = active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->enum('status', ['0', '1'])->default('1');
        });
    }
};
