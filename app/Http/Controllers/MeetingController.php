<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Assign;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $role_id = Auth::user()->role_id;

        $fields = $request->validate([
            "course_id" => ["required", "integer"],
        ]);

        // $fields = $request->validate([
        //     "course_id" => ["required", "integer"],
        //     "secret_code" => ["required", "string", "min:8"],
        //     "finish_time" => ["required", "time"],
        // ]);

        if ($role_id !== 2) {
            return response([
                "message" => "Oh No...
                Only professors can do this"
            ], 403);
        }

        $response = Meeting::all()->where('course_id', $fields["course_id"]);
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role_id = Auth::user()->role_id;

        $fields = $request->validate([
            "course_id" => ["required", "integer"],
            "secret_code" => ["required", "string", "min:8", "max:16", "unique:meetings,secret_code"],
            "finish_time" => ["required", "string"],
        ]);

        if ($role_id !== 2) {
            return response([
                "message" => "Oh No...
                Only professors can do this"
            ], 403);
        }

        $response = Meeting::create([
            "course_id" => $fields["course_id"],
            "secret_code" => $fields["secret_code"],
            "finish_time" => $fields["finish_time"],
            "start_date" => Carbon::now()
        ]);
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role_id = Auth::user()->role_id;

        if ($role_id !== 2) {
            return response([
                "message" => "Oh No...
                Only professors can do this"
            ], 403);
        }

        $response =  Assistance::with('student:id,first_name,last_name,email')->where("meeting_id", $id)->get();
        return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {
        $role_id = Auth::user()->role_id;

        if ($role_id !== 2) {
            return response([
                "message" => "Oh No...
                Only professors can do this"
            ], 403);
        }

        $course = Meeting::find($id);
        $course->update($request->all());
        return response($course, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role_id = Auth::user()->role_id;


        if ($role_id !== 2) {
            return response([
                "message" => "Oh No...
                Only professors can do this"
            ], 403);
        }

        return Meeting::destroy($id);
    }
}
