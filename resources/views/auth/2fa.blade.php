@extends('layouts.master')

@section('title')
    Two-Factor Authentication
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Two-Factor Authentication Required</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        Please enter the verification code from your authenticator app to continue.
                    </div>

                    <form method="POST" action="{{ route('2fa.verify') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="code" class="col-md-4 col-form-label text-md-right">Authentication Code</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                    name="code" required autocomplete="off" autofocus>

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Verify Code
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
