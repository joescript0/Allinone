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
        Schema::table('articlestocks', function (Blueprint $table) {
            // Supprimer les colonnes seulement si elles existent
            if (Schema::hasColumn('articlestocks', 'devise_achat')) {
                $table->dropColumn('devise_achat');
            }
            if (Schema::hasColumn('articlestocks', 'prix_achat')) {
                $table->dropColumn('prix_achat');
            }
            if (Schema::hasColumn('articlestocks', 'voir_stock')) {
                $table->dropColumn('voir_stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articlestocks', function (Blueprint $table) {
            // Recréer les colonnes en cas de rollback (avec des types par défaut)
            // Ajustez les types et attributs selon votre besoin réel
            if (!Schema::hasColumn('articlestocks', 'devise_achat')) {
                $table->string('devise_achat')->nullable();
            }
            if (!Schema::hasColumn('articlestocks', 'prix_achat')) {
                $table->decimal('prix_achat', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('articlestocks', 'voir_stock')) {
                $table->boolean('voir_stock')->default(false);
            }
        });
    }
};
