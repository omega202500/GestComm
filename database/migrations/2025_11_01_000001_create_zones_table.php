<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->string('nom', 100)->primary(); // nom comme clé primaire
            $table->text('description')->nullable();
            $table->string('region', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('zones');
    }
};