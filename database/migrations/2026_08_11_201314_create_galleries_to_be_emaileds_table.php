<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesToBeEmailedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries_to_be_emaileds', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('gallery_downloads_id')->nullable();
            $table->string('send_to')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->string('status')->default('Pending')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galleries_to_be_emaileds');
    }
}
