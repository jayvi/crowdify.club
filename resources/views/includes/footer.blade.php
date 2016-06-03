<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <ul class="nav nav-pills">
                    <li><a href="{{ route('blog::home') }}">Blog</a></li>
                    <li><a href="{{ route('terms-of-services') }}">Terms of Service</a></li>
                    {{--<li><a href="#">Privacy</a></li>--}}
                    {{--<li><a href="#">Support</a></li>--}}
                    {{--<li><a href="#">Help</a></li>--}}
                </ul>
            </div>

            <div class="col-md-4">
                <p class="site-info">Copyright © 2009-{{ date('Y') }} – <a href="{{url('/')}}">Crowdify</a></p>
            </div>
        </div>
    </div>
</footer>