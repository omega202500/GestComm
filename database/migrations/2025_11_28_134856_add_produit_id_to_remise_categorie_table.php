<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table( function (Blueprint $table) {
            // Ajouter la colonne produit_id
            $table->unsignedBigInteger('produit_id')->nullable()->after('id');
            
            // Ajouter la clé étrangère
            $table->foreign('produit_id')
                  ->references('id')
                  ->on('produits')
                  ->onDelete('cascade');
            
            // Supprimer l'unicité sur categorie_id si elle existe
            // $table->dropUnique('remise_categorie_categorie_id_unique');
            
            // Ajouter une nouvelle contrainte d'unicité (produit_id + categorie_id)
            $table->unique(['produit_id']);
        });
    }

    public function down()
    {
        Schema::table( function (Blueprint $table) {
            // Supprimer la contrainte d'unicité
            $table->dropUnique(['produit_id']);
            
            // Supprimer la clé étrangère
            $table->dropForeign(['produit_id']);
            
            // Supprimer la colonne
            $table->dropColumn('produit_id');
            
            // Rétablir l'ancienne unicité
            $table->unique('categorie_id');
        });
    }
};