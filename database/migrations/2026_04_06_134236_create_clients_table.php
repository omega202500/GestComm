<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->string('nom', 150);
        $table->string('telephone', 50)->nullable();
        $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
