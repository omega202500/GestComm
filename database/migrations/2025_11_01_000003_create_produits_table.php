<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix_unitaire', 10, 2);
            $table->integer('stock')->default(0);
            $table->enum('type', ['menage', 'liquide', 'cosmetic', 'prestige']);
            $table->decimal('remise_menage', 5, 2)->default(0);
            $table->decimal('remise_liquide', 5, 2)->default(0);
            $table->decimal('remise_cosmetic', 5, 2)->default(0);
            $table->decimal('remise_prestige', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }
};