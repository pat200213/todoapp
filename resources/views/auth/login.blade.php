@extends('layouts.app')

@section('title')
    Login
@endsection

@section('content')
<div class="login-container">
    <div class="info-side">
        <div class="info-container">
            <h4 class="info-title">Hello, Friend!</h4>
            <p class="info-desc">Want to gain new experience. Join with only one step!</p>
            <a class="btn btn-outline-secondary" href="{{ route('register') }}">Register</a>
        </div>
    </div>
    <div class="form-side">
        <div class="form-container">
            <h4 class="form-title">Login</h4>
            <p class="form-desc">please use your account</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <div class="input-group-append">
                            <span class="input-group-text" id="iconPassword" onclick='tooglePass(this.id, "password")'><i class="fa fa-eye"></i></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group text-md-right">
                    
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <div class="form-group mb-0 text-center">
                  
                    <button type="submit" class="btn btn-primary">Login</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
