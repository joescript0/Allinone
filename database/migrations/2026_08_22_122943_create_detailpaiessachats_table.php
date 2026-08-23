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
        Schema::create('detailpaiessachats', function (Blueprint $table) {
            $table->id();
            $table->integer("facture_id")->default(0);
            $table->integer("payer")->default(0);
            $table->double("montant_recu")->default(0);
            $table->integer("devise_recu")->default(0);
            $table->integer("mode_de_paiement")->default(0);
            $table->string("date_creation")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailpaiessachats');
    }
};
