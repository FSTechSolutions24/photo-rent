<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectedFolderIdsToGalleryDownloadsTable extends Migration
{
    public function up()
    {
        Schema::table('gallery_downloads', function (Blueprint $table) {
            $table->json('selected_folder_ids')->nullable()->after('folder_id');
        });
    }

    public function down()
    {
        Schema::table('gallery_downloads', function (Blueprint $table) {
            $table->dropColumn('selected_folder_ids');
        });
    }
}
