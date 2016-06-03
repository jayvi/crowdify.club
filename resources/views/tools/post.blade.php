@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    @include('perk.includes.home_nav')
                </div>

                <div class="col-md-7">
                    <div class="panel z1 tools-post">
                        <div class="panel-heading">
                            <h4>{{$posts->title}}
                                @if($auth->check() && $auth->user()->isAdmin())
                                    <a class="pull-right" href="{{ route('Tools::edit', ['id' => $posts->name]) }}"><span class="ion-edit"></span> Edit</a>
                                @endif
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $posts->description !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="addthis_native_toolbox"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel z1">
                        <div class="panel-body">
                            <div id="disqus_thread"></div>
                        <script>
                            /**
                             *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                             *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                             */
                            /* */
                             var disqus_config = function () {
                             this.page.url = '{{route('Tools::webpost',array('id' => $posts->name))}}';  // Replace PAGE_URL with your page's canonical URL variable
                             this.page.identifier = 'crowdify-tool-'+'{{$posts->id.'-'.$posts->title}}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                             };

                            (function() {  // DON'T EDIT BELOW THIS LINE
                                var d = document, s = d.createElement('script');


                                s.src = '{{env('TOOLS_DISQUS_URL','//crowdify-tools.disqus.com/embed.js')}}';

                                s.setAttribute('data-timestamp', +new Date());
                                (d.head || d.body).appendChild(s);
                            })();
                        </script>
                        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    @include('includes.right_block')
                </div>
            </div>
        </div>
    </main>
@stop

@section('scripts')
    <script id="dsq-count-scr" src="{{env('TOOLS_DISQUS_COUNT_URL','//crowdify-tools.disqus.com/count.js')}}" async></script>
    <script>
        $(document).ready(function(){
            setPageTitle('{{'Crowdify Tools | '.$posts->title}}');
        });
    </script>
@endsection