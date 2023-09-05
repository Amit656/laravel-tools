<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpsDetailsColumnInToolReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tool_returns', function (Blueprint $table) {
            $table->string('UPS_details')->nullable()->after('drop_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tool_returns', function (Blueprint $table) {
            $table->dropColumn('UPS_details');
        });
    }
}
