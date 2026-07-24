<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfertstocks', function (Blueprint $table) {
            // Supprimer les anciennes colonnes si elles existent
            if (Schema::hasColumn('transfertstocks', 'stok_1')) {
                $table->dropColumn('stok_1');
            }
            if (Schema::hasColumn('transfertstocks', 'stok_2')) {
                $table->dropColumn('stok_2');
            }

            // Ajouter les nouvelles colonnes uniquement si elles n'existent pas déjà
            if (!Schema::hasColumn('transfertstocks', 'stock_1')) {
                $table->integer("stock_1")->default(0);
            }
            if (!Schema::hasColumn('transfertstocks', 'stock_2')) {
                $table->integer("stock_2")->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('transfertstocks', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées si elles existent
            if (Schema::hasColumn('transfertstocks', 'stock_1')) {
                $table->dropColumn('stock_1');
            }
            if (Schema::hasColumn('transfertstocks', 'stock_2')) {
                $table->dropColumn('stock_2');
            }
        });
    }
};
