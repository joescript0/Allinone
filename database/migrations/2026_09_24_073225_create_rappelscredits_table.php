<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rappelscredits', function (Blueprint $table) {
            $table->id();

            // ✅ Référence vers factureasses (pas factures)
            $table->unsignedBigInteger('factureass_id')->nullable()->index();

            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->string('client_nom')->nullable();

            $table->string('facture_numero')->nullable();
            $table->decimal('credit_usd', 15, 2)->default(0);
            $table->decimal('credit_cdf', 15, 2)->default(0);
            $table->string('devise', 10)->nullable();

            $table->text('message');
            $table->enum('mode', ['all', 'selected'])->default('selected');

            $table->json('filters')->nullable();
            $table->json('payload')->nullable();

            $table->enum('statut', ['pending', 'sent', 'failed', 'read'])->default('pending');
            $table->text('erreur_message')->nullable();

            $table->boolean('envoye_email')->default(false);
            $table->boolean('envoye_sms')->default(false);
            $table->boolean('envoye_whatsapp')->default(false);

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamp('envoye_le')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['factureass_id', 'statut']);
            $table->index(['client_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rappelscredits');
    }
};