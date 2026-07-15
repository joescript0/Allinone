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
        Schema::table('Articles', function (Blueprint $table) {
            //
            $table->double("prix_detail")->default(0);
            $table->double("prix_gros")->default(0);
            $table->integer("taille_lot")->default(0);
            $table->integer("taille_piece")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Articles', function (Blueprint $table) {
            //
        });
    }
};
