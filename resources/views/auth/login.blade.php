<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>ZIP | Login</title>

  <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>

<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="/"><b>Admin</b>ZIP</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
          <div class="input-group-append">
              <span class="fa fa-envelope input-group-text"></span>
          </div>
          @if ($errors->has('email'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" placeholder="Password" name="password" required>
          <div class="input-group-append">
              <span class="fa fa-lock input-group-text"></span>
          </div>
          @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
        </div>
        <div class="row">
          <div class="col-8">
            <div class="checkbox icheck">
              <label>
               <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
             <button type="submit" class="btn btn-primary btn-block btn-flat">
            {{ __('Login') }}
        </button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook-f mr-2"></i> Sign in using Facebook
        </a>
      </div>
      <!-- /.social-auth-links -->

      @if (Route::has('password.request'))
      <p class="mb-1">
        <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
      </p>
      @endif
      <p class="mb-0">
        <a href="#" class="text-center">Chức năng tự tạo tài khoản đăng nhập hệ thống đang được bảo trì. Liên hệ với ASM để được tạo tài khoản.</a>
      </p>
      @if (Route::has('register'))
      <p class="mb-0">
        <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
      </p>
      @endif
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<script src="{{asset('/js/app.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass   : 'iradio_square-blue',
      increaseArea : '20%' // optional
    })
  })
</script>
</body>

</html>
