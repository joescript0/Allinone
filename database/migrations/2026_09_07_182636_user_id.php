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
        Schema::table('articlestocks', function (Blueprint $table) {
            //
            $table->integer('seuil_minimum')->nullable();
            $table->integer('seuil_maximum')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articlestocks', function (Blueprint $table) {
            //
        });
    }
};
