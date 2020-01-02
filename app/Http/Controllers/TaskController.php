<?php

namespace App\Http\Controllers;

use App\Repository\TaskMove;
use App\Sorts;
use App\Task;
use DB;
use Exception;
use Illuminate\Http\Request;
use Validator;

class TaskController extends Controller
{
    /*
     * შექმნა
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'projectId' => 'required|exists:projects,id',
            'parentId' => 'nullable|exists:task,id',
            'status' => 'required|in:backlog,progress,test,done',
            //'sort' => 'json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result" => "no",
                "errors" => $validator->errors()->messages()
            ]);
        }

        $Task = new Task();

        $Task->userId =  $request->user()->id;
        $Task->projectId =  $request->get('projectId');
        $Task->parentId =  $request->get('parentId') ?? 0;
        $Task->title =  $request->get('title');
        $Task->description =  $request->get('description');
        $Task->childSort =  json_encode($request->get('childSort') );
        $Task->status =  $request->get('status');

        DB::beginTransaction();

        try{

            $Task->save();

            // თუ ყავს მშობელი მაშინ მშობელის childCount გავზარდოთ
            if( $request->get('parentId') != null || $request->get('parentId') != 0)
            {
                $TaskParent = Task::where('id',$request->get('parentId'))->first();
                $TaskParent->increment('childCount');
            }

            DB::commit();

            return response()->json([
                "result" => "yes",
                "message" => "Task was successfully created"
            ]);

        }catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "result" => "no",
                "error" => "Task was not created",
                "message" => $e->getMessage()
            ]);
        }

    }

    /*
     * გადატანა
     */
    public function move(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'taskId' => 'required|exists:task,id',
            'projectId' => 'required|exists:projects,id',
            'status' => 'required|in:backlog,progress,test,done',
           // 'sort' => 'json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result" => "no",
                "errors" => $validator->errors()->messages()
            ]);
        }

        try{

        $TaskParent = null;

        // მივიღოთ ის თასქი რომელიც უნდა გადავიტანოთ
        $Task = Task::where('id', $request->get('taskId') )
            ->where('projectId', $request->get('projectId') )
            ->first();

        $Task->status = $request->get('status');

            //  თუ თასქი დანში გადავარდა
            if( $request->get('status') == 'done')
            {
                // თუ მშობელი თასქის შვილები ყველა done ში გადავარდა მაშინ მშობელიც done ში წავიდეს
                $TaskParent = Task::where('id', $Task->parentId)->first();
                $TaskParent->increment('childDoneCount');
                if($TaskParent->childCount == $TaskParent->childDoneCount)
                {
                    $TaskParent->status = 'done';
                    $TaskParent->save();
                }
            }

            $TaskMove = new TaskMove();
            $TaskMove->moveAllSubChildes($Task,$request->get('status'));

            // განვაახლოთ სორტირება იმის მიხედვით თუ სად გადავიდა თასქი
            $Sorts =  Sorts::where('projectId', $request->get('projectId'))->first();
            if($Sorts == null)
            {
                $Sorts = new Sorts();
                $Sorts->projectId = $request->get('projectId');
            }

            $Sorts->{$request->get('status')} = json_encode( $request->get('sort') );

            $Task->save();
            $Sorts->save();

            return response()->json([
                "result" => "yes",
                "message" => "Task was successfully moved"
            ]);

        }catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "result" => "no",
                "error" => "Task was not moved",
                "message" => $e->getMessage()
            ]);
        }
    }

    /*
     * რედაქტირება
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'taskId' => 'nullable|exists:task,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result" => "no",
                "errors" => $validator->errors()->messages()
            ]);
        }

        $Task = Task::where('id',$request->get('taskId'))->first();
        $Task->title =  $request->get('title');
        $Task->description =  $request->get('description');

        if($Task->save())
        {
            return response()->json([
                "result" => "yes",
                "message" => "Task was successfully edited"
            ]);
        }
        return response()->json([
            "result" => "no",
            "errors" => "Editing failed"
        ]);

    }

    /*
     * წაშლა
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'taskId' => 'nullable|exists:task,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result" => "no",
                "errors" => $validator->errors()->messages()
            ]);
        }

        $Task = Task::where('id',$request->get('taskId'))->first();

        try{

            // წავშალოთ შვილობილი თასქები
            Task::where('parentId',$Task->id)->delete();
            $Task->delete();

            // მშობელს განვუახლოთ შვილების რაოდენობის მაჩვენებელი
            $parent = Task::where('id',$Task->parentId)->first();
            if($parent != null)
            {
                $parent->decrement('childCount');
                $parent->save();
            }


            DB::commit();

            return response()->json([
                "result" => "yes",
                "message" => "Task was successfully deleted"
            ]);

         }catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "result" => "no",
                "errors" => "Deleting failed"
            ]);
        }

    }

    /*
     * სორტირების განახლება
     */
    public function updateSort(Request $request)
    {

    }
}
