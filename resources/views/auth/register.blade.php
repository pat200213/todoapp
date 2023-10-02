@extends('layouts.app')

@section('title')
    Register
@endsection

@section('content')
<div class="register-container">
    <div class="info-side">
        <div class="info-container">
            <h4 class="info-title">Welcome Back!</h4>
            <p class="info-desc">Already have account. login with only one step.</p>
            <a class="btn btn-outline-secondary" href="{{ route('login') }}">Login</a>
        </div>
    </div>
    <div class="form-side">
        <div class="form-container">
          
            <h4 class="form-title">Register</h4>
            <p class="form-desc">Please fill all informations below</p>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="first_name">First Name</label>

                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name">Last Name</label>

                    
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>

                    
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <div class="input-group-append">
                            <span class="input-group-text" id="iconPassword" onclick='tooglePass(this.id, "password")'><i class="fa fa-eye"></i></span>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                
                    <small id="emailHelp" class="form-text text-muted">min 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>

                    <div class="input-group">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <div class="input-group-append">
                            <span class="input-group-text" id="iconRePassword" onclick='tooglePass(this.id, "password-confirm")'><i class="fa fa-eye"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0 text-center">
                    
                    <button type="submit" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </form>
                
        </div>
    </div>
</div>
@endsection
