<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaceProcessingColumns extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->boolean('face_processing_enabled')->default(false)->after('is_public');
            $table->boolean('face_filter_published')->default(false)->after('face_processing_enabled');
            $table->string('face_processing_status', 32)->default('disabled')->after('face_filter_published');
            $table->text('face_processing_error')->nullable()->after('face_processing_status');
            $table->timestamp('faces_clustered_at')->nullable()->after('face_processing_error');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->string('face_analysis_status', 32)->default('pending')->after('meta');
            $table->char('face_analysis_checksum', 64)->nullable()->after('face_analysis_status');
            $table->string('face_analysis_model_version')->nullable()->after('face_analysis_checksum');
            $table->text('face_analysis_error')->nullable()->after('face_analysis_model_version');
            $table->timestamp('face_analyzed_at')->nullable()->after('face_analysis_error');
            $table->index(['gallery_id', 'face_analysis_status']);
        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['gallery_id', 'face_analysis_status']);
            $table->dropColumn([
                'face_analysis_status', 'face_analysis_checksum', 'face_analysis_model_version',
                'face_analysis_error', 'face_analyzed_at',
            ]);
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn([
                'face_processing_enabled', 'face_filter_published', 'face_processing_status',
                'face_processing_error', 'faces_clustered_at',
            ]);
        });
    }
}
