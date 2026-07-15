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
        Schema::create('listespaies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("annee_id")->constrained();
            $table->foreignId("moi_id")->constrained();
            $table->integer("supprimer")->default(0);
            $table->integer("etat")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listespaies');
    }
};
