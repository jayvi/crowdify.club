<div class="modal fade modal-auth" id="modal-auth">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                        <div class="auth-box">
                            <header>
                                <a class="active" href="{{route('auth::login')}}">Login</a>
                                {{--<a href="{{route('auth::register')}}">Sign Up</a>--}}
                            </header>
                            <div class="auth-body">
                                {{--{!! Form::open(array(--}}
                                    {{--'url' => route('auth::login'),--}}
                                {{--)) !!}--}}
                                {{--<div class="form-group">--}}
                                    {{--<input type="email" name="email" id="modal-login-email" placeholder="Email" value="" class="auth-field">--}}
                                    {{--@if($errors->has('email'))--}}
                                        {{--<label class="text-danger">{{ $errors->get('email')[0] }}</label>--}}
                                    {{--@endif--}}
                                {{--</div>--}}

                                {{--<div class="form-group">--}}
                                    {{--<input type="password" name="password" id="modal-login-password" placeholder="Password" value="" class="auth-field">--}}
                                    {{--<i class="fa fa-lock login-field-icon"></i>--}}
                                    {{--@if($errors->has('password'))--}}
                                        {{--<label class="text-danger">{{ $errors->get('password')[0] }}</label>--}}
                                    {{--@endif--}}
                                {{--</div>--}}

                                {{--<button type="button" class="btn btn-success modal-login-btn" id="modal-login-btn">Login</button>--}}
                                {{--@if(Session::has('loginError'))--}}
                                    {{--<label class="text-danger">{{ Session::get('loginError') }}</label>--}}
                                {{--@endif--}}

                                {{--<footer>--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--<div class="checkbox">--}}
                                                {{--<label>--}}
                                                    {{--<input type="checkbox" name="remember" >  Remember me--}}
                                                {{--</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--<a href="#">Forgot your password?</a>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</footer>--}}

                                {{--{!! Form::close() !!}--}}

                                {{--<h3 class="text-center">OR</h3>--}}


                                <a href='{{route('auth::social::login', array('provider'=> 'twitter'))}}' class="btn btn-default twitter"> <i class="ion-social-twitter modal-icons"></i> Sign In with Twitter </a>

                            </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

