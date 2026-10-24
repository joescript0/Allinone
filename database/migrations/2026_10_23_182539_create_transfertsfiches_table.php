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
        Schema::create('transfertsfiches', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->integer("destinataire_id")->constrained();
            $table->foreignId("patient_id")->constrained();
            $table->integer("motif_id")->default(0);
            $table->integer("service_id")->default(0);
            $table->double('tarifs')->default(20000);
            $table->integer('devise')->default(1);
            $table->integer('etat')->default(1);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfertsfiches');
    }
};
