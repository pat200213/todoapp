<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends ParentController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $login_user = Auth::user();
        $user_id = $login_user->id;

        $count_all_task = count(Task::getTask('all',$user_id));

        $count_complete_task = count(Task::getTask('complete',$user_id));

        $count_pending_task = count(Task::getTask('progress',$user_id));

        $today = Carbon::now()->format('d M Y');

        $today_task = Task::getTask('today',$user_id, $today, "%d %b %Y")->take(4);

        $count_today_complete = count(Task::getTask('complete',$user_id, $today, "%d %b %Y"));

        return parent::view('welcome', [
                                    'today'=>$today,
                                    'all_task' => $count_all_task,
                                    'complete_task' => $count_complete_task,
                                    'pending_task' => $count_pending_task,
                                    'today_task' => $today_task,
                                    'today_complete_task' => $count_today_complete,
                                ]);
    }

    public function markNotif(){
        
        Auth::user()->unreadNotifications->markAsRead();
       
        return response()->json([
            'message'=>'success',
        ]);
    }
}
