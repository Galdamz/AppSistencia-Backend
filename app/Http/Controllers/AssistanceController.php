<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $fields = $request->validate([
            "secret_code" => ["required", "string", "min:8"],
            "meeting_id" => ["required", "integer"],
            "course_id" => ["required", "integer"],
        ]);

        $role_id = Auth::user()->role_id;

        Meeting::where("secret_code", $fields["secret_code"])->where("course_id", $fields["course_id"])->firstOrFail();

        if ($role_id !== 3) {
            return response([
                "message" => "Oh No...
                 Only students can do this"
            ], 403);
        }

        $currentDate = Carbon::now();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
