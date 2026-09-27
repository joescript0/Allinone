<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communiquerclients', function (Blueprint $table) {
            $table->id();

            // ✅ Référence vers la table clients
            $table->unsignedBigInteger('client_id')->nullable()->index();

            // Informations client (dénormalisées pour historique)
            $table->string('client_nom')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();

            // Message envoyé
            $table->text('message');

            // Mode d'envoi : 'all' (tous les clients filtrés) ou 'selected' (sélection manuelle)
            $table->enum('mode', ['all', 'selected'])->default('selected');

            // Filtres appliqués au moment de l'envoi (JSON)
            $table->json('filters')->nullable();

            // Snapshot complet des données envoyées (JSON)
            $table->json('payload')->nullable();

            // Statut de l'envoi
            $table->enum('statut', ['pending', 'sent', 'failed', 'read'])->default('pending');
            $table->text('erreur_message')->nullable();

            // Canaux d'envoi (utile si vous ajoutez SMS/WhatsApp plus tard)
            $table->boolean('envoye_email')->default(false);
            $table->boolean('envoye_sms')->default(false);
            $table->boolean('envoye_whatsapp')->default(false);

            // Utilisateur qui a déclenché l'envoi
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // Dates
            $table->timestamp('envoye_le')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index composés utiles
            $table->index(['client_id', 'statut']);
            $table->index(['user_id', 'created_at']);
            $table->index(['client_email']);
            $table->index(['client_phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communiquerclients');
    }
};