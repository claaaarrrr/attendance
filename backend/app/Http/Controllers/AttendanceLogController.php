<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AttendanceLogController extends Controller
{
    public function getAttendance(Request $request)
    {
        $user = Auth::user();
        $user_role = $user->user_role;
        $hashid = $user->hashed_user_id;

        $itemsPerPage = $request->get('itemsPerPage', 10);
        $page = $request->get('page', 1);

        $query = User::select(
            DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name, users.suffix) AS name"),
            'users.gender',
            'users.email',
            DB::raw("TIME(attendance_logs.created_at) as time_in"),
            DB::raw("DATE(attendance_logs.created_at) as date_in")
        )->leftJoin('attendance_logs', 'users.hashed_user_id', '=', 'attendance_logs.hashed_user_id')
            ->whereNotNull('attendance_logs.created_at')
            ->orderBy('attendance_logs.created_at', 'desc');

        if ($user_role == 1) {
            $query->where('users.hashed_user_id', $hashid);
        }

        $attResults = $query->paginate($itemsPerPage, ['*'], 'page', $page);

        if ($attResults->isEmpty()) {
            return response()->json([
                'message' => 'No Results Found.'
            ], 204);
        }

        return response()->json([
            'data' => $attResults->items(),
            'total' => $attResults->total(),
            'per_page' => $itemsPerPage,
            'current_page' => $page,
        ]);
    }
}
