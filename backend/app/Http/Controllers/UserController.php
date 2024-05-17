<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function ReadUserQR(Request $request)
    {
        // Set the timezone to Asia/Manila
        $role = Auth::user()->user_role_desc;
        date_default_timezone_set('Asia/Manila');
        $AttendanceLog = new AttendanceLog();
        $AttendanceLog->hashed_user_id = $request->input('hashed_user_id');
        $AttendanceLog->scanned_by = $role;
        $AttendanceLog->save();
        return response()->json(['message' => 'success'], 201);
    }

    public function insertUser(Request $request)
    {
        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender');
        $user->address = $request->input('address');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password')); // Hash the password
        $user->save();

        $hashedUserId = Hash::make($user->id);
        $user->hashed_user_id = $hashedUserId;
        $user->save();

        return response()->json(['message' => 'User inserted successfully'], 201);
    }

    public function deleteUser(Request $request)
    {
        $id = $request->input('id');

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function GetUsers(Request $request)
    {
        $users = User::all();
        return $users;
    }

    public function Login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $login = DB::table('users')
            ->where('username', $request->username)
            ->select('users.password', 'users.username', 'users.user_role')
            ->first();

        if ($login) {
            $userrole = $login->user_role;
        } else {
            return response()->json(
                [
                    'message' => 'Please Check the Username or Password.',
                    'icon' => 'error'
                ]
            );
        }

        if ($login) {
            if (Hash::check($request->password, $login->password)) {
                $passwordGrantClient = Client::where('password_client', 1)->first();
                $response = [
                    'grant_type' => 'password',
                    'client_id' => $passwordGrantClient->id,
                    'client_secret' => $passwordGrantClient->secret,
                    'username' => $request->username,
                    'user_role' => $userrole,
                    'password' => $request->password,
                    'scope' => '*',
                ];
                if (Auth::attempt($credentials)) {
                    $tokenRequest = Request::create('/oauth/token', 'post', $response);
                    $response = app()->handle($tokenRequest);
                    $data = json_decode($response->getContent());
                    $token = $data->access_token;
                    $responseContent = [
                        'message' => 'Login Successfully!',
                        'icon' => 'success',
                        'token' => $token,
                        'user_role' => $userrole,
                    ];
                    return response()->json($responseContent, 200);
                }
            } else {
                return response()->json(
                    [
                        'message' => 'The password were incorrect',
                        'icon' => 'error'
                    ],
                );
            }
        } else {
            return response()->json(
                [
                    'message' => 'The username were incorrect',
                    'icon' => 'error'
                ],
            );
        }
    }

    public function GetUserDetails()
    {
        $userId = Auth::user()->id;
        $userDetail = DB::table('users')
            ->where('users.id', $userId)
            ->select(
                'users.id',
                'users.gender',
                'users.email',
                'users.address',
                'users.hashed_user_id',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.user_role',
                'users.user_role_desc',
                'users.suffix',
                'users.username',
                DB::raw("CONCAT_WS(' ', users.first_name, users.middle_name, users.last_name, users.suffix) AS name"),
                'users.profile_pic_path'
            )
            ->first();
        if ($userDetail && $userDetail->profile_pic_path) {
            $fullPath = public_path('storage/' . $userDetail->profile_pic_path);
            $relativePath = str_replace(public_path('storage/'), '', $fullPath);
            $userDetail->profile_pic_path = env('APP_URL') . '/storage/' . $relativePath;
            $check = env('APP_URL');
        }
        return $userDetail;
    }

    public function UpdateUserDetails(Request $request)
    {
        $userId = Auth::user()->id;

        try {
            $user = User::findOrFail($userId);

            $currentUserDetails = $this->GetUserDetails();

            $validatedData = $request->validate([
                'gender' => 'nullable|string',
                'email' => 'nullable|email',
                'address' => 'nullable|string',
                'first_name' => 'nullable|string',
                'middle_name' => 'nullable|string',
                'last_name' => 'nullable|string',
                'suffix' => 'nullable|string',
                // 'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                // 'username' => 'nullable|string',
                // 'password' => 'string',
            ]);

            foreach ($validatedData as $key => $value) {
                if ($value !== null && $currentUserDetails->{$key} !== $value) {
                    if ($key === 'password') {
                        // Hash the password before saving it
                        $user->{$key} = Hash::make($value);
                    } else {
                        $user->{$key} = $value;
                    }
                }
            }

            if ($request->hasFile('profile_pic')) {
                $profilePic = $request->file('profile_pic');
                $profilePicPath = $profilePic->store('images', 'public');
                $user->profile_pic_path = $profilePicPath;
            }

            $user->save();

            $updatedUserDetails = $this->GetUserDetails();

            return response()->json([
                'message' => 'User details updated successfully', 'user' => $updatedUserDetails,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user details', 'error' => $e->getMessage()], 500);
        }
    }



    public function Logout(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();
        return ['message' => 'success'];
    }

    public function clearSuffix()
    {
        $id = Auth::user()->id;

        $user = User::findOrFail($id);

        $user->suffix = null;

        $user->save();

        $updatedUserDetails = $this->GetUserDetails();

        return $updatedUserDetails;
    }



    function GetToday()
    {
        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');

        // Get the current date and time in the specified timezone
        $currentDateInTimeZone = date('Y-m-d H:i:s');

        // Get the timestamp from the current date and time
        $currentTimestamp = strtotime($currentDateInTimeZone);

        // Return an array with the date and timestamp
        return [
            'currentDateInTimeZone' => $currentDateInTimeZone,
            'currentTimestamp' => $currentTimestamp,
        ];
    }
}
