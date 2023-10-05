@extends('layouts.index')

@section('title')
    Task
@endsection

@section('javascript')
    <script>
        function hide(id){
            $('#' + id).removeClass('show');
        }

        function showTask(idTask, targetModal){
            showDetail(idTask);
          
            showModal(targetModal);
        }

        function editTask(id, targetModal){

            var url = "{{route('task.edit', ['task'=>':idTask'])}}";
            url = url.replace(':idTask', id);

            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#list-task-edit').html(data.page);
                    showModal(targetModal);
                },
            });
        }

        function deleteTask(){
            var task = $('#delete_task_detail').val(); 

            var url = "{{route('task.destroy', ['task'=>':idTask'])}}";
            url = url.replace(':idTask', task);

            $('#form-delete-task').attr('action', url);
            $('#form-delete-task').submit();
            
        }

        function showModal(id){
            $('#' + id).css('display','block');
            $('#' + id).css('overflow-y', 'auto');
        }

        function submitModal(id){
            $('#' + id).submit();
        }

        function filterData(){
            var start_date = $('#start_date_filter').val();
            var end_date = $('#end_date_filter').val();

            var list_category = [];

            $('#category_type > li > label > input[type="checkbox"]:checked').each(function(){
                list_category.push($(this).val());
            });

            var status = $('#status_filter > label > input[type="radio"]:checked').val();
          
            $.ajax({
                type: "POST",
                url: "{{route('task.filter')}}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'start_date' : start_date,
                    'end_date': end_date,
                    'category': list_category,
                    'status': status,
                },
                success: function(data){
                    hide('dropdown-menu-filter');
                    
                    $('#card_task_list').html(data.page);
                }
            });
        }

        $('#status_filter > label > input[type="radio"]').on('click', function(){
            
            $('#status_filter > label').each(function(){
                $(this).removeClass('active');
            });
            
            $(this).parents().addClass('active');

        });

        $('input[type=date]').on('change', function(){
            var id = $(this).prop('id');

            if(id == "start_date_input"){
                var start_date = $(this).val();
                $('#end_date_input').attr('min', start_date);
            }

            else if(id == "start_date_filter"){
                var start_date = $(this).val();
                $('#end_date_filter').attr('min', start_date);
            }
        
        });

        function dateClicked(id){
                               
            $('#date-list > li').each(function(){
                $(this).removeClass('active');
            });
            
            $(id).addClass('active');

            // get task spesific date
            var date = $(id).attr('selected_date');

            var list_category = [];

            $('#category_type > li > label > input[type="checkbox"]:checked').each(function(){
                list_category.push($(this).val());
            });

            var status = $('#status_filter > label > input[type="radio"]:checked').val();

            $.ajax({
                type: "POST",
                url: "{{route('task.date.clicked')}}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'selected_date': date,
                    'category': list_category,
                    'status': status,
                },
                success: function(data){
                    $('#task-bar').html(data.page);
                }
            });
        }
    </script>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success_message'))
    <div class="alert alert-success">
        {{ session('success_message') }}
    </div>
    @endif

    @php
        $minDate = date("Y-m-d");
    @endphp

    <div class="task-list">
        <div class="row">
            <div class="col-lg-9">
                <h4 class="task-list-title">My Task Lists</h4>
            </div>
            <div class="col-lg-3 text-right">
                 <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary" onclick="showModal('add-task')">
                        <i class="fa fa-plus"></i>
                        Create Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="task-list">

       
        <div class="btn-group">
            <button id='button-menu-filter' class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                
                <i class="fa fa-filter"></i>
                <span class="task-list-filter">Filter</span>
            </button>
            
            <div id='dropdown-menu-filter' class="dropdown-menu px-4 py-3">

                <i class="fa fa-filter"></i>
                Choose Filter
                <button type="button" class="close" onclick="hide('dropdown-menu-filter')">
                    <span aria-hidden="true">×</span>
                </button>

                <div class="dropdown-divider"></div>

                <div class="form-group">
                    <label for="floating_title" class="control-label mb-1">
                        Category
                    </label>
                    <ul id='category_type' class="checkbox-dropdown-list">
                        @foreach($category_list as $list)
                            <li>
                                <label>
                                    <input type="checkbox" value="{{$list->id}}">{{$list->name}}
                                </label>
                            </li>
                        @endforeach
                        
                    </ul>
                </div>

                <div class="form-group">
                    <label for="floating_title" class="control-label mb-1">
                        Date
                    </label>  
                    
                    <div class="date_input">
                        <div class="start_date">
                            <p class="por-txt">Start</p>
                            <input type="date" id='start_date_filter' class="form-control">
                        </div>

                        <div class="arrow_icon">
                            <i class="fa fa-arrow-right"></i>
                        </div>
                
                        <div class="end_date">
                            <p class="por-txt">End</p>
                            <input type="date" id='end_date_filter' class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="floating_title" class="control-label mb-1">
                        Status
                    </label>

                    <div id="status_filter" class="btn-group btn-group-toggle btn-category" data-toggle="buttons">
                        
                        <label class="btn btn-sm btn-outline-info active">
                            <input type="radio" name="status_filter" value="" autocomplete="off"> All
                        </label>

                        <label class="btn btn-sm btn-outline-info">
                            <input type="radio" name="status_filter" value="1" autocomplete="off"> Complete
                        </label>

                        <label class="btn btn-sm btn-outline-info">
                            <input type="radio" name="status_filter" value="0" autocomplete="off"> In Progress
                        </label>
                        
                    </div>
                    
                </div>

                <div class="dropdown-divider"></div>

                <button type="button" class="btn btn-primary pull-right" onclick="filterData()">Apply</button>
                
            </div>
        </div>
    </div>
    
    <div class="card">
        <div id="card_task_list" class="card-body">
        
            <div class="date-bar">
                <ul id='date-list'>
                    <li class="active" onclick="dateClicked(this)">
                        @php
                            $arr_today = explode('-', $today);
                            $date = $arr_today[0];
                            $month = $arr_today[1];
                            $year = $arr_today[2];
                        @endphp
                        
                        <span class="day">{{$month}}</span>
                        <span class="date">{{$date}}</span>
                        <span class="year">{{$year}}</span>
                        
                        @if(!empty($arr_all_task))
                            <span class="notif"></span>
                        @endif
                    </li>
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
                    @if(!empty($arr_all_task))
                        @foreach(collect($arr_all_task)->sortBy('time')->values()->all() as $key=>$task)

                            @php
                                $date = date('d-M-Y', strtotime($task['time']));
                                $time = date('H:i', strtotime($task['time']));
                            @endphp
                        
                            @if($date == $today)
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
                    @else

                        <span class="por-txt">No Task Today</span>
                    @endif
                </ul>
        
            </div>
        </div>
    </div>

    <div id="add-task" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="card-title">Create Task</strong>
                    <button type="button" class="close" onclick="closeModal('add-task')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id='add-task-form' method="post" action="{{ route('task.store') }}">
                    @csrf
                        <div class="form-group">
                            <label for="floating_title" class="control-label mb-1">Title</label>  
                            <input type="text" id='floating_title' class="form-control" name="title"> 
                                                     
                        </div>
                        <div class="form-group">
                            <label for="floating_description" class="control-label mb-1">Description</label>   
                            <textarea id='floating_description' class="form-control" rows="3" name="description"></textarea>  
                                                 
                        </div>
                        <div class="form-group">
                            <div class="date_input">
                                <div class="start_date">
                                    <label for="cc-payment" class="control-label mb-1">Start Date</label>
                                        
                                    <input type="date" id='start_date_input' class="form-control" name="start_date" min="{{$minDate}}">
                                    <input type="time" id="start_time-input" class="form-control" step="any" name="start_time">
                                </div>

                                <div class="arrow_icon">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                        
                                <div class="end_date">
                                    <label for="cc-payment" class="control-label mb-1">End Date</label>
                                    <input type="date" id='end_date_input' class="form-control" name="end_date" min={{$minDate}}>
                                    <input type="time" id="end_time-input" class="form-control" step="any" name="end_time">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="floating_category" class="control-label mb-1">Category</label>
                            <div class="btn-group btn-group-toggle btn-category" data-toggle="buttons">
                                @foreach($category_list as $list)
                                    <label class="btn btn-sm btn-outline-info">
                                        <input type="radio" name="category" value="{{$list->id}}" autocomplete="off"> {{$list->name}}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="closeModal('add-task')">Close</button>
                    <button onclick="submitModal('add-task-form')" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
    </div>

    <div id="view-task" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="list-task-detail" class="modal-content">
                <!-- inject data from ajax  -->
            </div>
            <button type="button" class="btn-close-modal" onclick="closeModal('view-task')">
                <span>&times;</span>
            </button>
        </div>
    </div>

    <div id="edit-task" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="list-task-edit" class="modal-content">
                <!-- inject data from ajax -->
            </div>
        </div>
    </div>

    <div class="modal bd-example-modal-sm" id="delete-modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <form id='form-delete-task' method="post" action="">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <h4 class='card-title mb-15'>Delete This Task</h4>
                        <i class="fa fa-trash" style="margin-bottom: 10px; font-size: 38px; color: #ef5350;"></i>
                        <p class='mb-15 por-txt'>
                            This action will delete the task permanently. <br>
                            Are sure about that?
                        </p>
                        <input type="hidden" id="delete_task_detail" name="delete_task">
                    </div>
                    <div class="modal-footer">
                        <button type='button' class="btn btn-default" onclick="closeModal('delete-modal')">Cancel</button>
                        <button type='button' class="btn btn-danger" onclick="deleteTask()">Delete Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal bd-example-modal-sm" id="response-modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
               <form method="POST" action="{{route('task.update.status')}}">
                    @csrf
                    <div class="modal-body">
                        <h4 class='card-title mb-15'>Complete This Task</h4>
                        <i class="fa fa-check" style="margin-bottom: 10px; font-size: 38px; color: #4dbd74;"></i>
                        <p class='mb-15 por-txt'>
                            This action will change the status as complete and not show again. <br>
                            Are sure about that?
                        </p>
                        <input type="hidden" id="task_detail" name="complete_task">
                    </div>
                    <div class="modal-footer">
                        <button type='button' class="btn btn-default" onclick="closeModal('response-modal')">Cancel</button>
                        <button type='submit' class="btn btn-success">Complete Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection