<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_parts', function (Blueprint $table) {
            $table->id();
            $table->float("amount")->nullable();
            $table->integer("quantity")->nullable();
            $table->text("description")->nullable();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('job_id')
                ->nullable()
                ->constrained()
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
        Schema::dropIfExists('use_parts');
    }
}
