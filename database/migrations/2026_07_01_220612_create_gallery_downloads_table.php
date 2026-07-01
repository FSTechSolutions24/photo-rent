<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_downloads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gallery_id')->nullable();            
            $table->bigInteger('folder_id')->nullable();
            $table->string('user_type')->nullable(); //should be either client or guest
            $table->string('requested_by_email')->nullable(); //should be the email of the user requested the download
            $table->boolean('full_gallery')->nullable();
            $table->string('url')->nullable();  //the url of the gallery after compress it to be sent to the user
            $table->string('status')->nullable(); //this to know the current status of the requested download
            $table->bigInteger('size')->nullable(); //this to know the size of the gallery
            $table->dateTime('expires_at')->nullable(); //this to delete the gallery after when it expires
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gallery_downloads', function (Blueprint $table) {
            //
        });
    }
}
