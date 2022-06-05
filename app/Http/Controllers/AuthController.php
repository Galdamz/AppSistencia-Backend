<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // $role_id = Auth::user()->role_id;

        $fields = $request->validate([
            "first_name" => ["required", "string", "min:2"],
            "last_name" => ["required", "string", "min:2"],
            "uid" => ["nullable", "string", "min:2"],
            "password" => ["required", "string", "min:8", "confirmed"],
            "email" => ["required", "string", "unique:users,email"],
            "role_id" => ["required", "integer"]
        ]);

        // if ($fields["role"] === 1 && $role_id === 1) {
        //     return response([
        //         "message" => "Oh No... I't Looks like you can't do that."
        //     ], 403);

        // } else {

        // }

        $user = User::create([
            "first_name" => $fields["first_name"],
            "last_name" => $fields["last_name"],
            "uid" => isset($fields["uid"]) ? $fields["uid"] : null,
            "email" => $fields["email"],
            "password" => bcrypt($fields["password"]),
            "role_id" => $fields["role_id"],
        ]);

        $token = $user->createToken("x_access_token")->plainTextToken;

        $response = [
            "role_id" => $user["role_id"],
            "x_access_token" => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required"
        ]);

        $user = User::where("email", $fields["email"])->first();

        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response([
                "message" => "Bad Credentials"
            ], 401);
        }
        $token = $user->createToken("x_access_token")->plainTextToken;

        $response = [
            "role_id" => $user["role_id"],
            "x_access_token" => $token,
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return (["message" => "Logged-out"]);
    }
}
