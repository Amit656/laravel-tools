<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('tool_id')->nullable();
            $table->string('asset')->nullable();
            $table->string('sort_field')->nullable();
            $table->string('description')->nullable();
            $table->string('serial_no');
            $table->string('product_no');
            $table->string('type_of_use')->nullable();
            $table->integer('modality_id');
            $table->dateTime('calibration_date')->nullable();
            $table->integer('site_id')->nullable();
            $table->enum('status', ['available', 'busy', 'calibration'])->default('available');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tools');
    }
}
