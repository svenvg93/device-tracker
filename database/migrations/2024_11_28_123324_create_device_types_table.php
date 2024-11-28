<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTypesTable extends Migration
{
    public function up()
    {
        Schema::create('type', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // The name of the network (e.g., 'B2C', 'B2B', 'Mobile')
            $table->timestamps();  // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('type');
    }
}
