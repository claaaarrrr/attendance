<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function getSchedule()
    {
        $sched = Schedule::first();
        return $sched;
    }

    public function updateSchedule(Request $request)
    {
        $sched = Schedule::findorFail($request->id);
        if ($request->has('time_in')) {
            $sched->time_in = $request->time_in;
        }

        if ($request->has('time_out')) {
            $sched->time_out = $request->time_out;
        }
        $sched->save();
        if (!$sched) {
            return response()->json([
                'message' => 'Schedule update failed',
                'icon' => 'error',
                'title' => 'Failed',
            ], 204);
        }
        return response()->json([
            'message' => 'Schedule has been updated Successfully',
            'icon' => 'success',
            'title' => 'Updated',
        ], 200);
    }
}
