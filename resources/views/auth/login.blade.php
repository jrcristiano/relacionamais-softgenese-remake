@extends('layouts.admin') @section('title', 'Plataforma administrativa | Login') @push('styles')
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,800&display=swap" rel="stylesheet"> @endpush @section('content')
<div class="container">
    <div class="row py-3 mb-4 justify-content-around d-flex">
        <div class="col-md-5 text-center">
            <a class="sgi-login-logo navbar-brand font-weight-bold mt-5 mb-3" href="{{ route('login') }}">
                <img src="{{ asset('/imgs/logo.png') }}" width="155" height="75" alt="Logo tipo da SoftGenese">
            </a>
            <div class="pt-4 pb-3 text-center shadow rounded sgi-card-position">
                <h3 class="mt-1 font-weight-bold">Entrar</h3>
                <div class="card-body mt-2">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('auth.email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus> @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span> @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans('auth.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"> @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span> @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old( 'remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ trans('auth.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt"></i> {{ trans('auth.enter') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ trans('auth.forgot_password') }}
                                    </a> @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
