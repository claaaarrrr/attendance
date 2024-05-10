<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getSchedule()
    {
        $sched = Schedule::all();
        return $sched;
    }
}
