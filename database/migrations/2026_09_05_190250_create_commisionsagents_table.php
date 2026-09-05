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
        Schema::create('commisionsagents', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("client_id")->constrained();
            $table->foreignId("article_id")->constrained();
            $table->double("montant")->default(0);
            $table->double("commision")->default(0);
            $table->integer("devise")->default(0);
            $table->string("date_creation")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commisionsagents');
    }
};
