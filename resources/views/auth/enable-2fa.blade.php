@extends('layouts.master')

@section('title')
    Enable Two-Factor Authentication
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Enable Two-Factor Authentication</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p>To enable two-factor authentication, follow these steps:</p>
                    <ol>
                        <li>Install Google Authenticator on your mobile device</li>
                        <li>Scan the QR code below with the app</li>
                        <li>Enter the verification code from the app to confirm setup</li>
                    </ol>

                    <div class="text-center my-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $qrCodeUrl }}" alt="QR Code">
                    </div>

                    <div class="alert alert-info">
                        If you can't scan the QR code, enter this code manually in your app: <strong>{{ $secret }}</strong>
                    </div>

                    <form method="POST" action="{{ route('2fa.confirm') }}">
                        @csrf
                        <input type="hidden" name="secret" value="{{ $secret }}">

                        <div class="form-group row mb-3">
                            <label for="code" class="col-md-4 col-form-label text-md-right">Verification Code</label>

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
                                    Enable 2FA
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
