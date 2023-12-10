@extends('layouts.master2')

@section('content')
    <div class="page-content d-flex align-items-center justify-content-center">

        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-8 col-xl-6 mx-auto">
                <div class="card">
                    <div class="row">
                        <div class="col-md-4 pe-md-0">
                            <div class="auth-side-wrapper"
                                style="background-image: url({{ url('https://multigraphicsagency.com/wp-content/uploads/2020/04/logo.png') }})">

                            </div>
                        </div>
                        <div class="col-md-8 ps-md-0">
                            <div class="auth-form-wrapper px-4 py-5">
                                <a href="#" class="noble-ui-logo d-block mb-2">{{ trans('panel.site_title') }}</a>
                                <h5 class="text-muted fw-normal mb-4">Bienvenido de vuelta!</h5>

                                @if (session()->has('message'))
                                    <p class="alert alert-info">
                                        {{ session()->get('message') }}
                                    </p>
                                @endif
                                <form class="forms-sample" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="userEmail" class="form-label">{{ trans('global.login_email') }}</label>
                                        <input type="email" name="email"
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required
                                            id="userEmail" placeholder="{{ trans('global.login_email') }}">
                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="userPassword"
                                            class="form-label">{{ trans('global.login_password') }}</label>
                                        <input type="password" name="password"
                                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                            id="userPassword" autocomplete="current-password"
                                            placeholder="{{ trans('global.login_password') }}">
                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-check mb-3">
                                        <input name="remember" type="checkbox" class="form-check-input" id="authCheck">
                                        <label class="form-check-label" for="authCheck">
                                            {{ trans('global.remember_me') }}
                                        </label>
                                    </div>
                                    <div>
                                        <button type="submit"
                                            class="btn btn-primary me-2 mb-2 mb-md-0">{{ trans('global.login') }}</button>

                                    </div>

                                    @if (Route::has('password.request'))
                                        <p class="mb-1">
                                            <a href="{{ route('password.request') }}" class="d-block mt-3 text-muted">
                                                {{ trans('global.forgot_password') }}
                                            </a>
                                        </p>
                                    @endif

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
