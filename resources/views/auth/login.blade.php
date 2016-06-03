<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" class="login login-signup">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# klout__: http://ogp.me/ns/fb/klout__#">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" type="image/png" href="{{url('assets/images/favicon_48x48.png')}}"/>
    <meta name="google-site-verification" content="h3bRaeeQyvRtdEW0wABndIVZ4WWx__SyiETDmbFORfU" />s
    <link href="{{elixir('css/all.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>


<body class="login">
    <div class="wrapper-table">
        <div class="row">
            <div class="col-md-12">
                <img class="logo" src="{{url('/assets/images/logo.png')}}" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8 col-md-offset-3">

                    </div>
                </div>
                <div class="log-box">
                    <div class="auth-body">
                        <h1 class="lovetech"  style="padding: 20px; font-size: 2.5em;">Power up your use of the internet by accessing the power of the crowd!</h1>
                        <a href='{{route('auth::social::login', array('provider'=> 'twitter'))}}' class="btn btn-default twitter-b"> <i class="ion-social-twitter modal-icons"></i> Sign In with Twitter </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

