<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $needsPhone = ! Schema::hasColumn('users', 'phone');
        $needsVerifiedAt = ! Schema::hasColumn('users', 'phone_verified_at');

        if ($needsPhone || $needsVerifiedAt) {
            Schema::table('users', function (Blueprint $table) use ($needsPhone, $needsVerifiedAt) {
                if ($needsPhone) {
                    $table->string('phone', 16)->nullable()->unique()->after('email');
                }

                if ($needsVerifiedAt) {
                    $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
                }
            });
        }

        if (Schema::hasTable('pending_registrations')) {
            return;
        }

        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 16)->unique();
            $table->string('password');
            $table->string('otp_hash');
            $table->dateTime('otp_expires_at');
            $table->unsignedTinyInteger('otp_attempts')->default(0);
            $table->unsignedTinyInteger('otp_send_count')->default(1);
            $table->dateTime('otp_last_sent_at');
            $table->string('registration_ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->dropColumn(['phone', 'phone_verified_at']);
        });
    }
};
