<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillAppointmentPhotographerIds extends Migration
{
    public function up()
    {
        DB::table('appointments')
            ->whereNull('photographer_id')
            ->whereNotNull('session_id')
            ->orderBy('id')
            ->chunkById(100, function ($appointments) {
                foreach ($appointments as $appointment) {
                    $photographerId = DB::table('sessions')
                        ->where('id', $appointment->session_id)
                        ->value('photographer_id');

                    if ($photographerId) {
                        DB::table('appointments')->where('id', $appointment->id)->update([
                            'photographer_id' => $photographerId,
                        ]);
                    }
                }
            });
    }

    public function down()
    {
        // Existing appointment ownership cannot be safely inferred in reverse.
    }
}
