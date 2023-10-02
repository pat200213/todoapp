@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="info-side">
        <div class="info-container">
            <h4 class="info-title">Only one last step!</h4>
            <p class="info-desc">1. Confirm Email</p>
            <p class="info-desc" style='color: #fff; border-top: 1px solid #878787; border-bottom: 1px solid #878787;'>2. Reset Password</p>
        </div>
    </div>
    <div class="form-side">
        <div class="form-container">
            <h4 class="form-title">Reset Password</h4>
            <p class="form-desc">Please fill all informations below</p>
            
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

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

            <div class="form-group text-center mb-0">
                
                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
