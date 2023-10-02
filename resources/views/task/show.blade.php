<div class="card-header">
    <h5 class="card-title mb-1">{{$task->title}}</h5>
    <small class="badge badge-{{ $cat->color }}">{{$cat->name}}</small>
</div>
<div class="card-body text-center">
    @php
        $start_date = date('d M Y H:i', strtotime($task->start_date));
        $end_date = date('d M Y H:i', strtotime($task->end_date));
    @endphp
    <h5 class="card-title">{{$start_date}} - {{$end_date}}</h5>
    <p class="card-text">{{$task->description}}</p>

    @if($task->status == 0)
        <button type='button' onclick="response('response-modal','task_detail', {{$task->id}})" class="btn btn-sm btn-outline-success">Complete</button>
    @else
        <span class="flat-color-5"><i class="fa fa-check">Completed</i></span>
    @endif

</div>