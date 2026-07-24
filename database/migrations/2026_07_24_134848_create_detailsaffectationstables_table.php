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
        Schema::create('detailsaffectationstables', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("table_id")->constrained();
            $table->integer("supprimer")->default(0);
            $table->integer("etat")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailsaffectationstables');
    }
};
