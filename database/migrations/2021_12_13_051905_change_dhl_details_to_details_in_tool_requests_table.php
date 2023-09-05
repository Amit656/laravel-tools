<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDhlDetailsToDetailsInToolRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tool_requests', function (Blueprint $table) {
            $table->renameColumn('DHL_details', 'details');
            $table->dropColumn('UPS_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tool_requests', function (Blueprint $table) {
            $table->renameColumn('details', 'DHL_details');
            $table->string('UPS_details')->nullable();
        });
    }
}
