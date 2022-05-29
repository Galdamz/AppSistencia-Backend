<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $role_id = Auth::user()->role_id;
        $id = Auth::user()->id;

        if ($role_id !== 2) {
            return response([
                "message" => "Oh No...
                Only professors can do this"
            ], 403);
        }

        $response = Course::all()->where('professor_id', $id);
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
        $id = Auth::user()->id;
        $role_id = Auth::user()->role_id;

        if ($role_id !== 2) {
            return response([
                "message" => "Oh No...
                Only professors can do this"
            ], 403);
        }

        $fields = $request->validate([
            "name" => ["required", "string", "min:3"],
            "description" => ["required", "string", "min:4"],
        ]);

        $response = Course::create([
            "name" => $fields["name"],
            "description" => $fields["description"],
            "professor_id" => $id
        ]);

        return response($response, 201);
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

        $response = Course::find($id);

        return response($response);
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

        $course = Course::find($id);
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

        return Course::destroy($id);
    }
}
