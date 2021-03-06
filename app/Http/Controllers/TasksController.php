<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tasks = Task::all();
        return view('tasks.index', [
           'tasks' => $tasks,
        ]);
        
       $tasks = [];
       if (\Auth::check()) {
           $user = \Auth::user();
           $tasks =$user->tasks()->orderBy('created_at','desc')->paginate(10);
           $tasks = [
               'user' => $user,
               'tasks' => $tasks,
               ];
           }
        return view('welcome',$tasks);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        //
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
             'status' => 'required|max:10',
             'content' => 'required|max:10',
            
            ]);
        
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user_id;
        
       $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,

        ]);
        
        return back();
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
        $task = Task::findOrFail($id);
        
         return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $task = Task::findOrFail($id);
        //
        return view('tasks.edit', [
            'task' => $task,
        ]);
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
      
         $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:10',
          ]);
         $task = Task::findOrFail($id);
        //
        $task->status = $request->status;  
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = \App\Task::findOrFail($id);
        //
        if (\Auth::id() == $task->user_id){
            $task->delete();    
        }
        
        return redirect('/');
    }
}
