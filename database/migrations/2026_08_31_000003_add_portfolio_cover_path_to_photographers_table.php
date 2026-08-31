<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPortfolioCoverPathToPhotographersTable extends Migration
{
    public function up()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->string('portfolio_cover_path')->nullable()->after('portfolio_bio');
        });
    }

    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropColumn('portfolio_cover_path');
        });
    }
}
