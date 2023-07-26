<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('user_all')->default(0);
            $table->boolean('user_add')->default(0);
            $table->boolean('user_edit')->default(0);
            $table->boolean('user_view')->default(0);
            $table->boolean('user_delete')->default(0);
            $table->boolean('user_view_full')->default(0);
            $table->boolean('user_history')->default(0);
            $table->boolean('user_enable')->default(0);
            $table->boolean('brand_all')->default(0);
            $table->boolean('brand_add')->default(0);
            $table->boolean('brand_edit')->default(0);
            $table->boolean('brand_view')->default(0);
            $table->boolean('brand_delete')->default(0);
            $table->boolean('device_all')->default(0);
            $table->boolean('device_add')->default(0);
            $table->boolean('device_edit')->default(0);
            $table->boolean('device_view')->default(0);
            $table->boolean('device_delete')->default(0);
            $table->boolean('product_all')->default(0);
            $table->boolean('product_add')->default(0);
            $table->boolean('product_edit')->default(0);
            $table->boolean('product_view')->default(0);
            $table->boolean('product_delete')->default(0);
            $table->boolean('product_manage_stock')->default(0);
            $table->boolean('product_purchase_price')->default(0);
            $table->boolean('product_sell_price')->default(0);
            $table->boolean('product_discount')->default(0);
            $table->boolean('job_all')->default(0);
            $table->boolean('job_add')->default(0);
            $table->boolean('job_edit')->default(0);
            $table->boolean('job_view')->default(0);
            $table->boolean('job_delete')->default(0);
            $table->boolean('job_change_status')->default(0);
            $table->boolean('job_add_parts')->default(0);
            $table->boolean('job_assigned')->default(0);
            $table->boolean('invoice_all')->default(0);
            $table->boolean('invoice_add')->default(0);
            $table->boolean('invoice_edit')->default(0);
            $table->boolean('invoice_view')->default(0);
            $table->boolean('invoice_delete')->default(0);
            $table->boolean('invoice_change_status')->default(0);
            $table->boolean('enquiries_all')->default(0);
            $table->boolean('enquiries_add')->default(0);
            $table->boolean('enquiries_edit')->default(0);
            $table->boolean('enquiries_view')->default(0);
            $table->boolean('enquiries_delete')->default(0);
            $table->boolean('enquiries_send')->default(0);
            $table->boolean('setting_all')->default(0);
            $table->boolean('setting_view_all')->default(0);
            $table->boolean('setting_basic_view')->default(0);
            $table->boolean('setting_basic_edit')->default(0);
            $table->boolean('setting_sms_view')->default(0);
            $table->boolean('setting_sms_edit')->default(0);
            $table->boolean('setting_job_view')->default(0);
            $table->boolean('setting_job_edit')->default(0);
            $table->boolean('setting_email_view')->default(0);
            $table->boolean('setting_email_edit')->default(0);
            $table->boolean('setting_other_view')->default(0);
            $table->boolean('setting_other_edit')->default(0);
            $table->boolean('setting_cms_view')->default(0);
            $table->boolean('setting_cms_edit')->default(0);
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
        Schema::dropIfExists('roles');
    }
}
