<form id='view-task-form' method="post" action="{{route('task.update', ['task'=>$task->id])}}">
    @csrf
    @method("PUT")
    <div class="modal-header">
        <strong class="card-title">Edit Task</strong>
        <button type="button" class="close" onclick="closeModal('edit-task')">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="floating_title" class="control-label mb-1">Title</label>  
            <input type="text" id='floating_title' class="form-control" name="title" value="{{$task->title}}"> 
                                    
        </div>
        <div class="form-group">
            <label for="floating_description" class="control-label mb-1">Description</label>   
            <textarea id='floating_description' class="form-control" rows="3" name="description">{{$task->description}}</textarea>  
                                
        </div>
        <div class="form-group">
            <div class="date_input">
                <div class="start_date">
                    <label for="cc-payment" class="control-label mb-1">Start Date</label>
                    
                    @php
                        $start_date = date('Y-m-d', strtotime($task->start_date));
                        $start_time = date('H:i', strtotime($task->start_date));
                    @endphp
                    <input type="date" id='start_date_input' class="form-control" name="start_date" value="{{$start_date}}">
                    <input type="time" id="start_time-input" class="form-control" step="any" name="start_time" value="{{$start_time}}">
                </div>

                <div class="arrow_icon">
                    <i class="fa fa-arrow-right"></i>
                </div>
        
                <div class="end_date">
                    <label for="cc-payment" class="control-label mb-1">End Date</label>

                    @php
                        $end_date = date('Y-m-d', strtotime($task->end_date));
                        $end_time = date('H:i', strtotime($task->end_date));
                    @endphp
                    
                    <input type="date" id='end_date_input' class="form-control" name="end_date" value="{{$end_date}}">
                    <input type="time" id="end_time-input" class="form-control" step="any" name="end_time" value={{$end_time}}>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="floating_category" class="control-label mb-1">Category</label>
            <div class="btn-group btn-group-toggle btn-category" data-toggle="buttons">
                @foreach($category_list as $list)
                    <label class="btn btn-sm btn-outline-info {{ ($list->id == $task->category_id) ? 'active focus':''}}">
                        <input type="radio" name="category" value="{{$list->id}}" autocomplete="off" {{ ($list->id == $task->category_id) ? 'checked':''}}> {{$list->name}}
                    </label>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="closeModal('edit-task')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>