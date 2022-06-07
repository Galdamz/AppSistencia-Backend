<?php

namespace App\Http\Controllers;

use App\Models\Assistance;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Hoa\Console;

class AssistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $id = Auth::user()->id;
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
            "secret_code" => ["required", "string", "min:8", "max:15"],
        ]);

        $role_id = Auth::user()->role_id;
        $id = Auth::user()->id;

        $validMeeting = Meeting::where("secret_code", $fields["secret_code"])->firstOrFail();

        $isAlreadyRegistered = Assistance::where("user_id", $id)->where("meeting_id", $validMeeting["id"])->get();

        if(count($isAlreadyRegistered) >= 1){
            return response([
                "error" => "You are already registered in this meeting!"
            ], 403);
        }

        if ($role_id !== 3) {
            return response([
                "message" => "Oh No...
                 Only students can do this"
            ], 403);
        }

        $currentTime = Carbon::now()->format('H:i:s');
        $parsedTime = Carbon::parse($validMeeting["finish_time"])->format('H:i:s');

        $isOnTime = $parsedTime > $currentTime;

        if(!$isOnTime){
            return response([
                "message"=> "Too late...",
                "currentTime" => $currentTime,
                "expectedTime" => $parsedTime,
                "isOnTime" => $isOnTime
            ], 400);
        }else{
            $assistance = Assistance::create([
                "meeting_id"=>$validMeeting["id"],
                "user_id"=> $id,
                "registered_date"=> Carbon::now()
            ]);

            return response(["message"=>"You has been successfully adeded"], 201);
        }

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
