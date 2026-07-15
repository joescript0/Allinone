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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->text("motif")->nullable();
            $table->integer("user_id_transfert")->default(0);
            $table->integer("user_id_descente")->default(0);
            $table->double("latitude")->default(0);
            $table->double("longitude")->default(0);
            $table->integer("etat")->default(0);
            $table->integer("supprimer")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
