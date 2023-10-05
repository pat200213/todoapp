<div class="date-bar">
    <ul id='date-list'>

        @foreach($arr_date as $key=>$dt)
            
            @php
                $full_date = date('d-M-Y', strtotime($dt));
               
                $active = '';

                if($key == 0){
                    $clickedDate = $full_date;
                    $active = 'active';
                }

                $today = explode('-', $full_date);

                $date = $today[0];
                $month = $today[1];
                $year = $today[2];

            @endphp

            <li class="nav-item {{$active}}" selected_date="{{$full_date}}" onclick="dateClicked(this)">  
                <span class="day">{{$month}}</span>
                <span class="date">{{$date}}</span>
                <span class="year">{{$year}}</span>
                @if(in_array($full_date, $selected_date))
                    <span class="notif"></span>
                @endif

            </li>

        @endforeach
    </ul>
</div>

<div class="task-list">
    <div class="grid">
        <small class="task-list-filter">Time</small>
        <small class="task-list-filter">Task</small>
    </div>
</div>

<div id='task-bar' class="task-bar">
    <ul class="time-bar">

        @foreach(collect($arr_all_task)->sortBy('time')->values()->all() as $key=>$task)

            @php
                $date = date('d-M-Y', strtotime($task['time']));
                $time = date('H:i', strtotime($task['time']));
            @endphp
           
            @if($date == $clickedDate)
                <li>
                    <div class="grid">
                        
                        <span class="time">{{$time}}</span>
                    
                        <div class="task">
                            @foreach($task['task'] as $t)
                                <div style='position: relative;'>
                                    <label id="dropdown-task-list-{{$t['id']}}" class="task-{{$t['category']}}" data-toggle="dropdown" aria-haspopup="true">
                                        <span>{{$t['title']}}</span>
                                        <i class="{{ $t['status'] == 0 ? 'fa fa-spinner':'fa fa-check'}}"></i>
                                    </label>

                                    <div class="dropdown-menu" aria-labelledby="dropdown-task-list-{{$t['id']}}">
                                        <a class="task-item" onclick="showTask({{$t['id']}}, 'view-task')"><i class="fa fa-eye"></i></a>
                                        <a class="task-item" onclick="editTask({{$t['id']}}, 'edit-task')"><i class="fa fa-pencil"></i></a>
                                        <a class="task-item" onclick="response('delete-modal','delete_task_detail', {{$t['id']}})"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                    </div>
                </li>
            @endif

        @endforeach
    </ul>
</div>