<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceLogController extends Controller
{
    public function getAttendance(Request $request)
    {
        $user_role = Auth::user()->user_role;
        $hashid = Auth::user()->hashed_user_id;

        $itemsPerPage = $request->get('itemsPerPage', 10);
        $page = $request->get('page', 1);

        if ($user_role == 0) {
            $attResults = User::select(
                DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name, users.suffix) AS name"),
                'users.gender',
                'users.email',
                'attendance_logs.created_at as time_in',
            )
                ->leftJoin('attendance_logs', 'users.hashed_user_id', '=', 'attendance_logs.hashed_user_id')
                ->whereNotNull('attendance_logs.created_at')
                ->get();
            $totalItems = $attResults->count();

            if ($totalItems < 0) {
                return response()->json([
                    'message' => 'No Results Found.'
                ], 204);
            }

            return response()->json([
                'data' => array_values($attResults->forPage($page, $itemsPerPage)->toArray()),
                'total' => $totalItems,
                'per_page' => $itemsPerPage,
                'current_page' => $page,
            ]);
        } else {
            return;
        }
    }
}
