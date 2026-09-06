<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryFaceTables extends Migration
{
    public function up()
    {
        Schema::create('gallery_face_clusters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained()->onDelete('cascade');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('representative_media_face_id')->nullable();
            $table->unsignedInteger('face_count')->default(0);
            $table->string('status', 20)->default('pending');
            $table->string('model_version');
            $table->string('thumbnail_disk')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->timestamps();
            $table->index(['gallery_id', 'status']);
        });

        Schema::create('media_faces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->foreignId('gallery_face_cluster_id')->nullable()->constrained('gallery_face_clusters')->nullOnDelete();
            $table->unsignedSmallInteger('face_index');
            $table->decimal('box_x', 9, 8);
            $table->decimal('box_y', 9, 8);
            $table->decimal('box_width', 9, 8);
            $table->decimal('box_height', 9, 8);
            $table->decimal('detection_confidence', 8, 7);
            $table->decimal('quality_score', 8, 7)->nullable();
            $table->json('embedding');
            $table->string('crop_disk');
            $table->string('crop_path');
            $table->string('model_version');
            $table->timestamps();
            $table->unique(['media_id', 'face_index']);
        });

        Schema::table('gallery_face_clusters', function (Blueprint $table) {
            $table->foreign('representative_media_face_id')
                ->references('id')->on('media_faces')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('gallery_face_clusters', function (Blueprint $table) {
            $table->dropForeign(['representative_media_face_id']);
        });
        Schema::dropIfExists('media_faces');
        Schema::dropIfExists('gallery_face_clusters');
    }
}
