<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->boolean('active')->default(0);
            $table->integer('role')->default(1);
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('line1')->nullable();
            $table->string('line2')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('account_sid')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('twilio_number')->nullable();
            $table->bigInteger('number_of_jobs')->nullable();
            $table->bigInteger('number_of_emails')->nullable();
            $table->bigInteger('whatsapp_number')->nullable();
            $table->bigInteger('fcm_token')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('disable_reason')->nullable();
            $table->foreignId('country_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('province_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('role_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
