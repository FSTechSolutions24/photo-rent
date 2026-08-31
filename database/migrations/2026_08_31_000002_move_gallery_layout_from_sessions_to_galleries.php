<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveGalleryLayoutFromSessionsToGalleries extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('gallery_layout', 30)->default('masonry')->after('session_id');
        });

        DB::table('galleries')
            ->whereNotNull('session_id')
            ->select('id', 'session_id')
            ->orderBy('id')
            ->get()
            ->each(function ($gallery) {
                $layout = DB::table('sessions')->where('id', $gallery->session_id)->value('gallery_layout');

                if (in_array($layout, ['masonry', 'editorial'], true)) {
                    DB::table('galleries')->where('id', $gallery->id)->update(['gallery_layout' => $layout]);
                }
            });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('gallery_layout');
        });
    }

    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('gallery_layout', 30)->default('masonry')->after('total_amount');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('gallery_layout');
        });
    }
}
