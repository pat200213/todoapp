<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category_list = DB::table('categories')->get();
        $today = Carbon::now();
        $user = Auth::user()->id;

        // $task_list = Task::getTask('all', $user ,$today->format('d/M/Y'), "%d/%b/%Y");
       
        $task_list = Task::getTaskByFilter($user, $today->format('Y-m-d'), $today->format('Y-m-d'), '', '');
     
        $task = Task::renderTaskPerDate($task_list);
        $arr_all_task = $task['task'];

        return view('task.index', [
                                    'category_list' => $category_list,
                                    'arr_all_task' => $arr_all_task,
                                    'today' => $today->format('d-M-Y'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $validationData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'start_date' => ['required'],
            'start_time' => ['required'],
            'end_date' => ['required'],
            'end_time' => ['required'],
            'category' => ['required'],
        ]);

        Task::insert([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date.' '.$request->start_time,
            'end_date' => $request->end_date.' '.$request->end_time,
            'category_id' => $request->category,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->back()->with(['success_message'=>'Successfully created!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        $user = Auth::user()->id;
        $task = Task::where('id', $task->id)->first();

        $cat = DB::table('categories')->join('user_categories as uc', 'categories.id', '=', 'uc.category_id')
                                ->where('uc.user_id', $user)
                                ->where('categories.id', $task->category_id)
                                ->select('categories.id', 'categories.name', 'uc.color')
                                ->first();

        return response()->json([
            'page'=> view('task.show', compact('task','cat'))->render(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $task = Task::where('id', $task->id)->first();
        $category_list = DB::table('categories')->get();

        return response()->json([
            'page' => view('task.edit', compact('task', 'category_list'))->render(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validationData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'start_date' => ['required'],
            'start_time' => ['required'],
            'end_date' => ['required'],
            'end_time' => ['required'],
            'category' => ['required'],
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date.' '.$request->start_time,
            'end_date' => $request->end_date.' '.$request->end_time,
            'category_id' => $request->category,
        ];

        Task::where('id', $task->id)->update($data);

        return redirect()->back()->with('success_message', 'Task successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try{
            Task::where('id', $task->id)->delete();

            return redirect()->back()->with('success_message', 'Task successfully deleted!');
        }
        catch(Exception $e){
            return redirect()->back();
        }
    }

    public function completeTask(Request $request){
        Task::where('id', $request->complete_task)->update(['status'=>1]);

        return redirect()->back();
    }

    public function filterData(Request $request){
        $user_id = Auth::user()->id;

        $start = $request->start_date;
        $end = $request->end_date;

        $category = $request->category;
        $status = $request->status;
       
        // get spesific task
        $task_list = Task::getTaskByFilter($user_id, $start, $end, $category, $status);

        // render the task group by date
        $task = Task::renderTaskPerDate($task_list);
        $arr_all_task = $task['task'];
        $selected_date = $task['date'];

        // create array range date
        if($start && $end)
            $arr_date = CarbonPeriod::create($start, $end)->toArray();
        else
            $arr_date = $selected_date;

        return response()->json([
            // 'page'=>$selected_date,
           'page' => view('task.filter_date', compact('selected_date','arr_date', 'arr_all_task'))->render(),
        ]);

    }

    function taskByDateClicked(Request $request){
      
        $user_id = Auth::user()->id;

        $selected_date = Carbon::createFromFormat('d-M-Y', $request->selected_date)->format('Y-m-d');

        $category = $request->category;
        $status = $request->status;
       
        $task_list = Task::getTaskByFilter($user_id, $selected_date, $selected_date, $category, $status);

        $task = Task::renderTaskPerDate($task_list);
        $arr_all_task = $task['task'];

        return response()->json([
           'page' => view('task.filter_task', compact('arr_all_task','selected_date'))->render(),
        ]);
    }
}
