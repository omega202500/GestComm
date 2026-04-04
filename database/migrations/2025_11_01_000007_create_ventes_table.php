<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->integer('total_quantite')->default(0);
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->string('facture_ref', 100)->nullable();
            $table->decimal('prix_unitaire', 12, 2);
            $table->timestamp('date_vente')->useCurrent();
            $table->timestamps();
            
            // Index pour les performances
            $table->index('commercial_id');
            $table->index('produit_id');
            $table->index('client_id');
            $table->index('date_vente');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventes');
    }
};