@extends('layouts.index')

@section('javascript')
    <script>
       
        $(document).ready(function(){
            var percent = $('#progress-bar-task').attr('value-now') + '%';
            document.documentElement.style.setProperty('--value', percent);
           
            $('#progress-bar-task').css('width', percent);
            
            $('#progress-bar-task').css('animation', 'load 2s forwards');
            $('#progress-bar-task').css('color', '#fff');

            $('#todo > li').each(function(idx){
                if(idx == 0){
                    $(this).children().css('background', 'rgba(153, 171, 180, 0.1)');

                    var idTask = $('label > a',this).attr('taskdetail');
                    
                    showDetail(idTask);
                }

                $(this).click(function(){
                    $('#todo > li > label').attr('style','');

                    var idTask = $('label > a', this).attr('taskdetail');
                    
                    showDetail(idTask);

                    $(this).children().css('background', 'rgba(153, 171, 180, 0.1)');
                });
            });
        });
        
    </script>
@endsection

@section('title')
    Dashboard
@endsection

@section('content')

    <div class="profile">
    
        <h4 class="box-title">Welcome, {{Auth::user()->first_name}}</h4>
        <div class="por-txt">{{$today}}</div>
  
    </div>

    @if($today_task->isNotEmpty() && $show)
        <div class="sufee-alert alert with-close alert-secondary alert-dismissible fade show">
            <span class="badge badge-pill badge-secondary">Info</span>
            You have task today!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif

    <div class="info">
        <div class="info-body">
           
            <div class="stat-text"><span class="count">{{$all_task}}</span></div>
            <div class="stat-heading"><i class="fa fa-list"></i>Task</div>
                    
        </div>
        <div class="info-body">
            
            <div class="stat-text"><span class="count">{{$complete_task}}</span></div>
            <div class="stat-heading"> <i class="fa fa-check"></i>Complete</div>
                    
        </div>
        <div class="info-body">
           
            <div class="stat-text"><span class="count">{{$pending_task}}</span></div>
            <div class="stat-heading"><i class="fa fa-spinner"></i>In Progress</div>
    
        </div>
    </div>
    

    <div class="animated fadeIn">
        
        <div class="clearfix"></div>
                 
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-9">
                                <h4 class="card-title box-title col-lg">Today Tasks</h4>
                            </div>
                            <div class="col-lg-2 text-right">
                                <a href="{{route('task.index')}}" class="btn btn-default por-txt">
                                    View All
                                </a>
                              
                            </div>
                        </div>

                        @if($today_task->isNotEmpty())
                            <div class="progress">
                            
                                @php
                                    $percentage = floor(($today_complete_task/count($today_task)) * 100);
                                @endphp
                                
                                <div id='progress-bar-task' class="progress-bar" role="progressbar" value-now="{{$percentage}}" aria-valuemin="0" 
                                            aria-valuemax="100">
                                    {{$percentage}}% completed
                                </div>
                            </div>
                        @endif
                           
                        <div class="card-content">
                            <div class="todo-list">
                                <div class="tdl-holder">
                                    <div class="tdl-content">
                                        <ul id='todo'>
                                            @if($today_task->isNotEmpty())
                                                @foreach($today_task as $task)
                                               
                                                    <li>
                                                        <label>
                                                            <span>{{$task->title}}</span>
                                                            <a id='btn_task_detail' href="#" taskdetail="{{$task->id}}" class="fa fa-chevron-circle-right"></a>
                                                        </label>
                                                    </li>
                                                @endforeach  
                                            @else
                                                <label class="text-center">
                                                    <span>No Task Today</span>
                                                </label>
                                            @endif                    
                                            
                                        </ul>           
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
            
            <div class="col-lg-4" id="todo-detail">
                <div id='list-task-detail' class="card">
                    <!-- inject info from ajax -->
                    
                </div>
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