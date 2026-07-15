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
        Schema::table('Factureasses', function (Blueprint $table) {
            //
            $table->dropColumn('mode_de_paiment');
            $table->integer("mode_de_paiement")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Factureasses', function (Blueprint $table) {
            //
        });
    }
};
