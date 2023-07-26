<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_tickets', function (Blueprint $table) {
            $table->id();
            $table->longText('remarks');
            $table->string('vehicle_mileage');
            $table->integer('status')->nullable();
            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('complaint_id')
                ->nullable()
                ->constrained('fleet_complaints')
                ->onDelete('cascade');
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
        Schema::dropIfExists('fleet_tickets');
    }
}
