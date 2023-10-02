@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="info-side">
        <div class="info-container">
            <h4 class="info-title">Oops!</h4>
            <p class="info-desc">You aren't forgot your password. Just login with only one step!</p>
            <a class="btn btn-outline-secondary" href="{{ route('login') }}">Login</a>
        </div>
    </div>
    <div class="form-side">
        <div class="form-container">
            <h4 class="form-title">Forgot Password</h4>
            <p class="form-desc">provide your email in the form below to begin</p>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
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

                <div class="form-group mb-0 text-center">
                  
                    <button type="submit" class="btn btn-primary">Confrim</button>
                  
                </div>

            </form>
        
        </div>
    </div>
</div>
@endsection
