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
            //
            $table->integer("voir_stock")->default(1);
            $table->double("prix_achat")->default(0);
            $table->integer("devise_achat")->default(0);
            $table->integer("stock_id")->default(0);
            $table->integer("supprimer")->default(0);
            $table->integer("avoir_stock")->default(1);
            $table->string("date_creation")->default("");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articlestocks', function (Blueprint $table) {
            //
        });
    }
};
