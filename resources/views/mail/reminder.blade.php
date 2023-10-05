@component('mail::message')
# {{$message['greeting']}}

<p>{{$message['body']}}</p>

<p>Total task: {{count($task)}}</p>

<p>List task:</p>


@foreach($task as $key=>$t)
    @php
        $start = date('d-M-Y H:i', strtotime($t->start_date));
        $end = date('d-M-Y H:i', strtotime($t->end_date));
    @endphp

    {{ ($key+1) }}. {{$t->title}}
    {{$start}} to {{$end}}

@endforeach

<p>Click this button to view your task.</p>

@component('mail::button', ['url' => $message['actionURL']])
{{$message['actionText']}}
@endcomponent

<p>We hope you enjoy our services</p>

Warm Regards,<br>
{{ config('app.name') }}
@endcomponent