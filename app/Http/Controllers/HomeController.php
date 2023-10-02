<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
        $user_id=Auth::user()->id;
        $count_all_task = count(Task::getTask('all',$user_id));

        $count_complete_task = count(Task::getTask('complete',$user_id));

        $count_pending_task = count(Task::getTask('progress',$user_id));

        $today = Carbon::now()->format('d M Y');

        $today_task = Task::getTask('today',$user_id, $today, "%d %b %Y")->take(4);

        $count_today_complete = count(Task::getTask('complete',$user_id, $today, "%d %b %Y"));

        $setting = DB::table('user_settings as us')->join('settings as s', 's.id', '=', 'us.setting_id')
                            ->where('us.user_id', $user_id)
                            ->where('s.key', 'send notif')
                            ->select('s.key', 'us.value')
                            ->first();

        $show = ($setting->value == '1') ? 'true':'';
        
        return view('welcome', [
                                    'today'=>$today,
                                    'all_task' => $count_all_task,
                                    'complete_task' => $count_complete_task,
                                    'pending_task' => $count_pending_task,
                                    'today_task' => $today_task,
                                    'today_complete_task' => $count_today_complete,
                                    'show'=>$show
                                ]);
    }
}
