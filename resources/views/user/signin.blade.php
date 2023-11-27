@extends('layouts.master')
@section('title')
    Signin
@endsection
@section('content')
<section class="vh-100">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 text-black">
  
          <div class="d-flex align-items-center justify-content-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
  
            <form style="width: 23rem;" action="{{ route('user.signin') }}" method="POST">
  
              <h3 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Log in</h3>
              <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign in</p>
              @if(session('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
          @endif
  
              <div class="form-outline mb-4">
                <input type="email" id="form2Example18" name="email" class="form-control form-control-lg" />
                <label class="form-label" for="form2Example18">Email address</label>
              </div>
  
              <div class="form-outline mb-4">
                <input type="password" id="form2Example28" name="password" class="form-control form-control-lg" />
                <label class="form-label" for="form2Example28">Password</label>
              </div>
  
              <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                <button type="submit" class="btn btn-primary btn-lg">Login</button>
              </div>

            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                <a href="{{ route('login.google') }}" class="btn btn-danger btn-md btn-block"
                role="button">
                Continue with <i class="fab fa-google me-2"></span></i></a>
            </div>

              <p>Don't have an account? <a href="{{ route('user.signup') }}" class="link-info">Register here</a></p>

              {{ csrf_field() }}
  
            </form>
  
          </div>
  
        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img3.webp"
            alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
        </div>
      </div>
    </div>
  </section>
@endsection
