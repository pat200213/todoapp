<ul class="time-bar">
    @foreach(collect($arr_all_task)->sortBy('time')->values()->all() as $key=>$task)

        @php
            $date = date('Y-m-d', strtotime($task['time']));
            $time = date('H:i', strtotime($task['time']));
        @endphp

        @if($date == $selected_date)
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