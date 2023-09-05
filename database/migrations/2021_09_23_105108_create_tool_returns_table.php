<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tool_returns', function (Blueprint $table) {
            $table->id();
            $table->integer('tool_id');
            $table->integer('tool_request_id');
            $table->integer('user_id');
            $table->integer('site_id');
            $table->enum('return_status', ['good', 'bad'])->default('good');
            $table->enum('drop_type', ['UPS', 'EPT', 'FSE'])->default('UPS');
            $table->string('DHL_details')->nullable();
            $table->string('comment');
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
        Schema::dropIfExists('tool_returns');
    }
}
