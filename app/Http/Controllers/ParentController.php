<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Task;
use App\User;
use App\Notifications\EmailReminder;
use App\Notifications\WebReminder;


class ParentController extends Controller
{
    public function view($route, $params){
        // send notification
        
        $login_user = Auth::user();
        $user_id = $login_user->id;

        $setting = DB::table('user_settings as us')->join('settings as s', 's.id', '=', 'us.setting_id')
                            ->where('us.user_id', $user_id)
                            ->select('s.key', 'us.value')
                            ->get();

        foreach($setting as $set){
            if($set->key == 'send notif'){
                $show = ($set->value == '1') ? 'true':'';
            }
            else{
                $minute = explode(':', $set->value);

                $start_minute = (int)$minute[0];

                $start_reminder = Carbon::now()->format("Y-m-d H:i");
                $end_reminder = Carbon::now()->addMinute($start_minute)->format("Y-m-d H:i");
            }
        }

        $reminder_upcoming_task = [];

        if($show){
            // check upcoming task at range time which already set in settings
            $reminder_upcoming_task = Task::getTaskByFilter($user_id, $start_reminder, $end_reminder,"%Y-%m-%d %H:%i", '','0');
            
            // send notification logged user at dashboard
            if($reminder_upcoming_task->isNotEmpty()){

                foreach($reminder_upcoming_task as $todo){
                    $time = Carbon::createFromFormat('Y-m-d H:i:s', $todo->start_date)->format('d-m-Y H:i');

                    // check if notification not duplicated
                    $notif = $login_user->notifications()->where('data->id', $todo->id)->get();

                    if($notif->isEmpty())
                        $login_user->notify(new WebReminder($todo->id, $todo->title, $time));
                  
                }
                
            }

            // broadcast notification via email to all registered users
            // $listUser = User::all();

            // foreach($listUser as $user){
            //     $message = [
            //         'greeting' => 'Hi,'.$user->first_name,
            //         'body' => 'We see you have task today! Just don\'t forget to check it and do it',
            //         'actionText' => 'View My Task',
            //         'actionURL' => route('task.index')
            //     ];

            //     $upcoming_task = Task::getTask('progress', $user->id, $today, "%d %b %Y");

            //     // send email
            //     if($upcoming_task->isNotEmpty())
            //         $user->notify(new EmailReminder($message, $upcoming_task));
            // }
        }

        return view($route, $params);
    }
}
