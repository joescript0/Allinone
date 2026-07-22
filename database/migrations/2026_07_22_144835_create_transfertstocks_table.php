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
        Schema::create('transfertstocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("article_id")->constrained();
            $table->text("commentaire")->nullable();
            $table->integer("qte")->default(0);
            $table->string("date_creation")->default("");
            $table->integer("stok_1")->default(0);
            $table->integer("stok_2")->default(0);
            $table->integer("supprimer")->default(0);
            $table->integer("annuler")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfertstocks');
    }
};
