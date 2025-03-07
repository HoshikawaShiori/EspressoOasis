@extends('layouts.master')
@section('title')
    Signin
@endsection
@section('content')
<section class="vh-100">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 text-black mt-5">

          <div class="d-flex align-items-center justify-content-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

            <form style="width: 23rem;" action="{{ route('user.signin') }}" method="POST" id="loginForm">
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
                <a href="{{ route('login.google') }}" class="btn btn-danger btn-md btn-block bg-danger"
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

  <!-- 2FA Modal -->
  <div class="modal fade" id="twoFactorModal" tabindex="-1" aria-labelledby="twoFactorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="twoFactorModalLabel">Two-Factor Authentication</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="twoFactorForm" action="{{ route('2fa.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="code" class="form-label">Enter Authentication Code</label>
              <input type="text" class="form-control" id="code" name="code" required autocomplete="off">
              <div class="form-text">Please enter the verification code from your authenticator app.</div>
            </div>
            <div id="twoFactorError" class="alert alert-danger d-none"></div>
            <button type="submit" class="btn btn-primary">Verify Code</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Show 2FA modal if needed
      @if(session('show_2fa_modal'))
          var modal = new bootstrap.Modal(document.getElementById('twoFactorModal'));
          modal.show();
      @endif

      // Handle 2FA form submission
      const twoFactorForm = document.getElementById('twoFactorForm');
      const twoFactorError = document.getElementById('twoFactorError');

      twoFactorForm.addEventListener('submit', function(e) {
          e.preventDefault();

          const formData = new FormData(twoFactorForm);

          fetch(twoFactorForm.action, {
              method: 'POST',
              body: formData,
              headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
          })
          .then(response => response.json())
          .then(data => {
              if (data.error) {
                  twoFactorError.textContent = data.error;
                  twoFactorError.classList.remove('d-none');
              } else if (data.success && data.redirect) {
                  window.location.href = data.redirect;
              }
          })
          .catch(error => {
              console.error('Error:', error);
              twoFactorError.textContent = 'An error occurred. Please try again.';
              twoFactorError.classList.remove('d-none');
          });
      });
  });
</script>
@endsection
