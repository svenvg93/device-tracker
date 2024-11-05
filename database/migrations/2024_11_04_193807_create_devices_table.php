<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the device
            $table->string('device_type'); // Predefined device type (e.g., Mobile, Tablet)
            $table->string('network'); // Predefined network (e.g., WiFi, Ethernet)
            $table->date('current_date')->nullable(); // Adjust as needed
            $table->integer('amount')->default(0); // Amount of devices for the week
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
        Schema::dropIfExists('devices');
    }
}
