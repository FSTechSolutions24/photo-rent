<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeAppointmentTimesNullable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE appointments MODIFY start_time TIME NULL');
        DB::statement('ALTER TABLE appointments MODIFY end_time TIME NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE appointments MODIFY start_time TIME NOT NULL');
        DB::statement('ALTER TABLE appointments MODIFY end_time TIME NOT NULL');
    }
}
