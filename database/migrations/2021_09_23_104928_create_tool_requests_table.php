<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tool_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('tool_id');
            $table->integer('user_id');
            $table->dateTime('delivery_date');
            $table->dateTime('expected_return_date');
            $table->integer('site_id');
            $table->enum('pickup_type', ['UPS', 'EPT', 'FSE'])->default('UPS');
            $table->string('DHL_details')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
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
        Schema::dropIfExists('tool_requests');
    }
}
