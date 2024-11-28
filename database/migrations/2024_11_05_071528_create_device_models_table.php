<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceModelsTable extends Migration
{
    public function up()
    {
        Schema::create('device_models', function (Blueprint $table) {
            $table->id();
            $table->string('device_name')->unique(); // Unique device name
            $table->string('color'); // Color hex code
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('device_colors');
    }
}
