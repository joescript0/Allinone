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
        Schema::table('signesvitauxes', function (Blueprint $table) {
            //
            $table->double('tarifss')->default(20000);
            $table->double('tarifs')->default(20000);
            $table->integer('devise')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signesvitauxes', function (Blueprint $table) {
            //
        });
    }
};
