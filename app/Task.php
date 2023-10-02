<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    protected $table = 'todo';

    public $timestamps = false;

    public static function getTask($type, $user, $date='', $format_date=''){
        $query = Task::where('user_id', $user);

        $query->when(($date != '' && $format_date != ''), function($q) use ($date, $format_date){
            return $q->where(function($r) use ($date, $format_date){                    
                                $r->where(DB::raw('DATE_FORMAT(start_date, "'.$format_date.'")'), $date)
                                        ->orWhere(DB::raw('DATE_FORMAT(end_date, "'.$format_date.'")'), $date);
                            });
        });

        if($type == 'complete'){

            $query->where('status', 1);
        }
        else if ($type == 'progress'){

            $query->where('status', 0);
        }
        
        $task = $query->get();

        return $task;
    }

    public static function getTaskByFilter($user, $start, $end, $category, $status){

        $query = Task::where('user_id', $user);
        
        // conditional clauses
        $query->when($start != '' && $end != '', function($q) use ($start, $end){
                    $q->where(function($r) use ($start, $end){                    
                        $r->whereBetween(DB::raw('DATE_FORMAT(start_date, "%Y-%m-%d")'), [$start, $end])
                                ->orWhereBetween(DB::raw('DATE_FORMAT(end_date, "%Y-%m-%d")'), [$start, $end]);
                    });
                });

        $query->when($category, function($q) use ($category){
            return $q->whereIn('category_id', $category);
        });
        
        $query->when($status != '', function($q) use ($status){
            return $q->where('status', $status);
        });        

        $task = $query->select('start_date', 'end_date', 'id', 'title', 'status')
                    ->orderBy('start_date','ASC')
                    ->get();

        return $task;
    }

    public static function renderTaskPerDate($task_list){
        $arr_all_task = [];
        $arr_date_time = [];
        $arr_selected_date = [];

        // create array of task time
        foreach($task_list as $tpd){
            $start = date('d-M-Y', strtotime($tpd->start_date)); 
            $end = date('d-M-Y', strtotime($tpd->end_date));

            if(!in_array($tpd->start_date, $arr_date_time)){
                array_push($arr_date_time, $tpd->start_date);
            }

            if(!in_array($tpd->end_date, $arr_date_time)){
                array_push($arr_date_time, $tpd->end_date);
            }

            // get task date
            if(!in_array($start, $arr_selected_date)){
                array_push($arr_selected_date, $start);
            }

            if(!in_array($end, $arr_selected_date)){
                array_push($arr_selected_date, $end);
            }
        }

        // create array task group by date
        foreach($arr_date_time as $dt){
            $arr_task = [];

            $date = date('d-M-Y', strtotime($dt));

            // if($date == $arr_selected_date[0]){

                foreach($task_list as $tpd){
                    $start = $tpd->start_date; 
                    $end = $tpd->end_date;
        
                    if($start == $dt || $end == $dt){
                    
                        $data = [
                            'id'=>$tpd->id,
                            'title'=>$tpd->title,
                            'status'=>$tpd->status,
                        ];
        
                        array_push($arr_task, $data);
                    }
                }
            
                $arr_task_per_date = [
                    'time' => $dt,
                    'task' => $arr_task
                ];

                array_push($arr_all_task, $arr_task_per_date);
            // }
        }

        return ['task' => $arr_all_task, 'date'=>$arr_selected_date];
    }  
}