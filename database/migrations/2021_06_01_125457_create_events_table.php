<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->string('title');
            $table->string('event_file')->nullable();
            $table->longText('narrative')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('event_category_id')
                ->nullable()
                ->constrained('event_categories')
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
        Schema::dropIfExists('events');
    }
}
