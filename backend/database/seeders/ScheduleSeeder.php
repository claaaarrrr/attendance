<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sched = new Schedule();
        $sched->time_in = '08:00:00';
        $sched->time_out = '17:30:00';
        $sched->save();
    }
}
