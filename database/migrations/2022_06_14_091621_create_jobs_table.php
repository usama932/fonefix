<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string("serial_number")->nullable();
            $table->string("job_sheet_number")->nullable();
            $table->string("password")->nullable();
            $table->string("pattern")->nullable();
            $table->string("tracking_id")->nullable();
            $table->string("product_configuration")->nullable();
            $table->string("problem_by_customer")->nullable();
            $table->string("condition_of_product")->nullable();
            $table->string("document")->nullable();
            $table->date("expected_delivery")->nullable();
            $table->text("comment")->nullable();
            $table->text("description")->nullable();
            $table->float("cost")->nullable();
            $table->integer("service_type")->default(1);
            $table->integer("status")->default(1);
            $table->integer("sms")->default(0);
            $table->integer("email")->default(0);
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('courier_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('id_card_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('device_id')
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
        Schema::dropIfExists('jobs');
    }
}
