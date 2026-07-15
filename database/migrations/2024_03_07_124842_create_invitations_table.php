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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->text('date_invitation')->nullable();
            $table->text('heure_invitation')->nullable();
            $table->text('date_document')->nullable();
            $table->foreignId("verbalisateur_id")->constrained();
            $table->text('libelle')->nullable();
            $table->text('signer_par')->nullable();
            $table->text('description')->nullable();
            $table->text('invitation_link')->nullable();
            $table->text('statut')->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
