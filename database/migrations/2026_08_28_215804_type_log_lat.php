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
        Schema::table('prospects', function (Blueprint $table) {
            // Version avec DOUBLE (ce que vous avez demandé)
            $table->double("latitude")->default(1)->change();
            $table->double("longitude")->default(1)->change();

            // Version avec DECIMAL (RECOMMANDÉE pour les GPS, plus précise)
            // $table->decimal("latitude", 10, 7)->default(1)->change();
            // $table->decimal("longitude", 10, 7)->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            // Pour revenir en arrière (repasser en integer)
            $table->integer("latitude")->default(1)->change();
            $table->integer("longitude")->default(1)->change();
        });
    }
};
