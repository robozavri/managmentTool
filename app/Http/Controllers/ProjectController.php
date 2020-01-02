<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;


class ProjectController extends Controller
{
    public function create(Request $request)
    {

        $Project = new Project();
        $Project->userId =  $request->user()->id;
        $Project->title =  $request->get('title');
        if( $Project->save() )
        {
            return response()->json([
                "result" => "yes",
                "message" => "Project was successfully created"
            ]);
        }
        return response()->json([
            "result" => "no",
            "message" => "Project was not created"
        ]);
    }
}
