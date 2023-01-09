<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_category', function (Blueprint $table) {
            $table->string('dest_customer_name')->after('customer_name');
            $table->string('customer_phone')->after('dest_customer_name')->nullable();
            $table->string('dest_customer_phone')->after('dest_customer_name')->nullable();
            $table->integer('address')->after('dest_customer_name');
            $table->integer('dest_address')->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_category', function (Blueprint $table) {
            $table->dropColumn('dest_customer_name');
            $table->dropColumn('customer_phone');
            $table->dropColumn('dest_customer_phone');
            $table->dropColumn('address');
            $table->dropColumn('dest_address');
        });
    }
};
