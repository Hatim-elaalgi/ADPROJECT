<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Double Slider Login / Registration Form</title>
    <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container" id="container">

        <div class="form-container register-container">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Register here.</h1>
                <input type="text" placeholder="Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span style="color: red" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email">
                
                @error('email')
                <span style="color: red" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <input type="password" placeholder="Password" name="password" required autocomplete="new-password">
                @error('password')
                    <span style="color: red" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <input type="password" placeholder="Password confirmation" name="password_confirmation" required autocomplete="new-password">

                <button type="submit">Register</button>
                <span>or use your account</span>
                <div class="social-container">
                    <a href="#" class="social"><i class="lni lni-facebook-fill"></i></a>
                    <a href="#" class="social"><i class="lni lni-google"></i></a>
                    <a href="#" class="social"><i class="lni lni-linkedin-original"></i></a>
                </div>
            </form>
        </div>

      <div class="form-container login-container">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1>Login here.</h1>
            <input type="email" placeholder="Email" @error('email') @enderror name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span style="color: red" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror


            <input type="password" placeholder="Password" name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="content">
                <div class="checkbox">
                    <input type="checkbox" name="checkbox" id="checkbox">
                    <label >Remember me</label>
                </div>
              <div class="pass-link">
                <a href="#">Forgot password?</a>
              </div>   
            </div>
            <button type="submit">Login</button>
            <span>or use your account</span>
            <div class="social-container">
                <a href="#" class="social"><i class="lni lni-facebook-fill"></i></a>
                <a href="#" class="social"><i class="lni lni-google"></i></a>
                <a href="#" class="social"><i class="lni lni-linkedin-original"></i></a>
            </div>  
        </form>
      </div>

      <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1 class="title">Hello <br> friend</h1>
                <p>if you have an account, login here and have fun</p>
                <button class="ghost" id="login">Login
                    <i class="lni lni-arrow-left login"></i>
                </button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1 class="title">Start your <br> journey now </h1>
                <p>if you don't have an account yet, join us and start your journey.</p>
                <button class="ghost" id="register">Register
                    <i class="lni lni-arrow-right register"></i>
                </button>
            </div>
        </div>
      </div>


    </div>
    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
