<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPortfolioContactFieldsToPhotographersTable extends Migration
{
    public function up()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->string('portfolio_contact_email')->nullable()->after('portfolio_show_contact');
            $table->string('portfolio_contact_phone')->nullable()->after('portfolio_contact_email');
            $table->string('portfolio_instagram')->nullable()->after('portfolio_contact_phone');
            $table->string('portfolio_footer_text')->nullable()->after('portfolio_instagram');
        });
    }

    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropColumn(['portfolio_contact_email', 'portfolio_contact_phone', 'portfolio_instagram', 'portfolio_footer_text']);
        });
    }
}
