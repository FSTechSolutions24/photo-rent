<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAvailableStorageToPhotographersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        define('FIVE_GB', 5 * 1024 * 1024 * 1024);
        Schema::table('photographers', function (Blueprint $table) {
            // Drop old INT column
            $table->dropColumn('storage_limit');

            // Recreate as BIGINT
            $table->unsignedBigInteger('plan_storage')->after('subdomain')->default(FIVE_GB);

            // Add available_storage
            $table->unsignedBigInteger('available_storage')->after('plan_storage')->default(FIVE_GB);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            //
        });
    }
}
