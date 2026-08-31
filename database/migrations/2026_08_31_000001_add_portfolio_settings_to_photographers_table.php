<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPortfolioSettingsToPhotographersTable extends Migration
{
    public function up()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->string('portfolio_title')->nullable()->after('subdomain');
            $table->text('portfolio_bio')->nullable()->after('portfolio_title');
            $table->string('portfolio_theme')->default('classic')->after('portfolio_bio');
            $table->string('portfolio_primary_color', 7)->default('#173f67')->after('portfolio_theme');
            $table->string('portfolio_accent_color', 7)->default('#c5965d')->after('portfolio_primary_color');
            $table->boolean('portfolio_show_contact')->default(true)->after('portfolio_accent_color');
        });
    }

    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropColumn(['portfolio_title', 'portfolio_bio', 'portfolio_theme', 'portfolio_primary_color', 'portfolio_accent_color', 'portfolio_show_contact']);
        });
    }
}
