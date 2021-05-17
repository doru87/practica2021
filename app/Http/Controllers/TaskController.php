<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function updateTaskAssignment(Request $request, $id){
        $task = Task::where('id',$id)->first();
        $error = '';

        if($task){
            if($request->userAssignedId == $task->assignment){
                $task->update(['assignment' => NULL]);
            }else{
                $task->update(['assignment' => $request->userAssignedId]);
            }
            
        }else{
            $error = 'Task not found!';
        }
        return response()->json(['error' => $error]);
        
    }

    public function updateTaskName(Request $request, $id){
        $task = Task::where('id',$id)->first();
        $error = '';

        if($task){
            $task->update(['name' => $request->taskEditName]);
        }else{
            $error='Task not found';
        }
        return response()->json(['error' => $error ]);
    }

    public function updateTaskDescription(Request $request, $id) {
        $task = Task::where('id',$id)->first();
        $error = '';

        if($task){
            $task->update(['description' => $request->taskEditDescription]);
        }else{
            $error='Task not found';
        }
        return response()->json(['error' => $error ]);
    }

    public function updateTaskStatus(Request $request, $id) {
        $task = Task::where('id',$id)->first();
        $error = '';

        if($task){
            $task->update(['status' => $request->statusId]);
        }else{
            $error='Task not found';
        }
        return response()->json(['error' => $error ]);
    }

    public function updateTaskDateCreation(Request $request, $id) {
        $task = Task::where('id',$id)->first();
        $error = '';

        if($task){
            $task->update(['created_at' => $request->selectedDateTime]);
        }else{
            $error='Task not found';
        }
        return response()->json(['error' => $error ]);
    }

    public function deleteTask($id){
        $task = Task::where('id',$id)->first();
        $error= '';

        if($task){
           $task->delete();
        }else{
            $error = 'Task not found!';
        }
        return response()->json(['error' => $error]);
    }
}
