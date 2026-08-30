<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrialFieldsToPhotographersTable extends Migration
{
    public function up()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->boolean('is_trial')->default(false)->after('active');
            $table->dateTime('trial_started_at')->nullable()->after('is_trial');
            $table->dateTime('trial_ends_at')->nullable()->index()->after('trial_started_at');
        });
    }

    public function down()
    {
        Schema::table('photographers', function (Blueprint $table) {
            $table->dropIndex(['trial_ends_at']);
            $table->dropColumn(['is_trial', 'trial_started_at', 'trial_ends_at']);
        });
    }
}
