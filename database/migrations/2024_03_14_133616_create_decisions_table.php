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
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contrevenant_id")->constrained();
            $table->text("user_id")->nullable();
            $table->text("numero_decision")->nullable();
            $table->text("date_document")->nullable();
            $table->text("date_reception")->nullable();
            $table->text("numero_pv")->nullable();
            $table->text("date_pv")->nullable();
            $table->text("description_infraction")->nullable();
            $table->text("decisions_link")->nullable();
            $table->integer('etat')->default(1);
            $table->text('recherche')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
