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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string("nom")->default("");
            $table->foreignId("user_id")->constrained();
            $table->foreignId("pointdeventes_id")->constrained();
            $table->string("description")->nullable();
            $table->integer("etat")->default(1);
            $table->integer("supprimer")->default(0);
            $table->string("date_creation")->default("");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
