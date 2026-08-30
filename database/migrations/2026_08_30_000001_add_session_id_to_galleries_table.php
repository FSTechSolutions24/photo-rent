<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionIdToGalleriesTable extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->foreignId('session_id')->nullable()->after('client_id')->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
        });
    }
}
