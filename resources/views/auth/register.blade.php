<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" class="login login-signup">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# klout__: http://ogp.me/ns/fb/klout__#">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" type="image/png" href="{{url('assets/images/favicon_48x48.png')}}"/>


    <link href="{{elixir('css/all.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>


<body>
    <div class="wrapper-table">
        <div class="align-center">
            <div class="auth-box center-block">
                <header>
                    <a href="{{route('auth::login')}}">Login</a>
                    <a class="active" href="{{route('auth::register')}}">Sign Up</a>
                </header>
                <div class="auth-body">
                    {!! Form::open(array(
                     'url' => route('auth::register')
                    )) !!}

                    <div class="form-group">
                        <input type="text" name="first_name" id="first_name" placeholder="First Name" value="" class="auth-field">
                        @if($errors->has('first_name'))
                            <label class="text-danger">{{ $errors->get('first_name')[0] }}</label>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" value="" class="auth-field">
                        @if($errors->has('last_name'))
                            <label class="text-danger">{{ $errors->get('last_name')[0] }}</label>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="text" name="email" id="email" placeholder="Email" value="" class="auth-field">
                        @if($errors->has('email'))
                            <label class="text-danger">{{ $errors->get('email')[0] }}</label>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" id="password" placeholder="Password" value="" class="auth-field">
                        @if($errors->has('password'))
                            <label class="text-danger">{{ $errors->get('password')[0] }}</label>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_confirmation" id="password" placeholder="Password" value="" class="auth-field">
                        @if($errors->has('password_confirmation'))
                            <label class="text-danger">{{ $errors->get('password_confirmation')[0] }}</label>
                        @endif
                    </div>

                    <input type="submit" class="btn btn-success modal-login-btn" value="Sign Up"/>

                    {!! Form::close() !!}

                    <h3 class="text-center">OR</h3>

                    <a href='{{route('auth::social::login', array('provider'=> 'twitter'))}}' class="btn btn-default twitter"> <i class="ion-social-twitter modal-icons"></i> Sign In with Twitter </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

