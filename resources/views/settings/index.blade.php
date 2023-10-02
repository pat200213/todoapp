@extends('layouts.index')

@section('title')
    Settings
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active show" id="custom-nav-contact-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="custom-nav-contact" aria-selected="true">My Profile</a>
                <a class="nav-item nav-link" id="custom-nav-profile-tab" data-toggle="tab" href="#nav-notification" role="tab" aria-controls="custom-nav-profile" aria-selected="false">Notification</a>
                <a class="nav-item nav-link" id="custom-nav-profile-tab" data-toggle="tab" href="#nav-category" role="tab" aria-controls="custom-nav-profile" aria-selected="false">Category</a>
                
            </div>
        </div>
    </div>
    
    @if(session('success_message'))
        <div class="alert alert-success" role="alert">
            {{session('success_message')}}
        </div>
    @endif

    <div class="tab-content">
        <div id="nav-profile" class="tab-pane fade show active">
            <div class="task-list">
                <h4 class="task-list-title">My Profile</h4>
                <small id="emailHelp" class="form-text text-muted">Update your latest profile.</small>
            </div>
    
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('setting.update.data') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="johndoe@gmail.com" name='email' value="{{Auth::user()->email}}">
                        </div>
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="exampleInputPassword1">First Name</label>
                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="John" name='first_name' value="{{Auth::user()->first_name}}">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="exampleInputPassword1">Last Name</label>
                                <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Doe" name ='last_name' value="{{Auth::user()->last_name}}">
                            </div>
                        </div>
                        <input type='hidden' name="tab_type" value="profile">
                                    
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="nav-notification" class="tab-pane fade">
            <div class="task-list">
                <h4 class="task-list-title">Notification</h4>
                <small id="emailHelp" class="form-text text-muted">Send a reminder at dashboard</small>
            </div>
    
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('setting.update.data') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-sm-3">Notify me</div>
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input position-static" type="checkbox" name="email_status" id="blankCheckbox" value="1" {{ $email_status == '1' ? 'checked':''}}>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-sm-3">Set reminder</div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <select class="form-control form-white" name="time">
                                            <option value="05:00" {{$minute == '05' ? 'selected':''}}>5</option>
                                            <option value="10:00" {{$minute == '10' ? 'selected':''}}>10</option>
                                            <option value="15:00" {{$minute == '15' ? 'selected':''}}>15</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4">minutes before</div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="nav-category" class="tab-pane fade">
            <div class="task-list">
                <h4 class="task-list-title">Category</h4>
                <small id="emailHelp" class="form-text text-muted">Change the color as you wish.</small>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('setting.update.data') }}">
                        @csrf
                        @method('PUT')
                        <label for="exampleInputEmail1">Pick color for the category</label>    
                        
                        @foreach($category_list as $list)
                            <div class="row form-group">    
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{ $list->name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control form-white" data-placeholder="Choose a color..." name="category_color_{{$list->id}}">
                                        @foreach($list_color as $key=>$c)
                                            <option value="{{ $c }}" {{ ($c == $list->color)? 'selected':'' }}>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endforeach

                        <input type='hidden' name="tab_type" value="color">

                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection