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
        Schema::table('transfertstocks', function (Blueprint $table) {
            //
            $table->integer("qte_trouve")->default(0);
            $table->integer("qte_total")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfertstocks', function (Blueprint $table) {
            //
        });
    }
};
