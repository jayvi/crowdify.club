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
                    <?php
                    use Jenssegers\Agent\Agent as Agent;
                    $Agent = new Agent();
                    ?>
                    <?php
                    if ($Agent->isMobile()) {
                    ?>
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="{{ route('blog::home') }}">Blogs</a></li>
                        <li role="presentation"><a href="{{ route('blog::create') }}">Create</a></li>
                        <li role="presentation"><a href="{{ route('blog::my-blogs') }}">My Blogs</a></li>
                    </ul>
                    <?php
                    }
                        ?>
                    @include('perk.includes.blog_nav')
                </div>
                <div class="col-md-7">
                    <div class="panel z1">
                        <div class="panel-body">
                            <h4 class="blog-title">
                                {{ $blog->title }}
                                @if($blog->user_id == $auth->id())
                                    <div class="dropdown dropdown-modify">
                                        <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="{{ route('blog::edit', ['id' => $blog->id]) }}">Edit</a></li>
                                            <li><a class="delete-link" data-toggle="modal" data-target="#delete-blog" data-blog-id="{{ $blog->id }}">Delete</a></li>
                                            @if($blog->status == 'Draft')
                                                <li><a href="{{ route('blog::publish', ['id' => $blog->id]) }}">Publish</a></li>
                                            @else
                                                <li><a href="{{ route('blog::un-publish', ['id' => $blog->id]) }}">Un-Publish</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </h4>
                            <span class="post-meta">
                                <a href="{{ route('perk::public_profile', array('username' => $blog->user->username )) }}">
                                    <img  class="img-circle profile-avatar blog-creator" src="{{ $blog->user->avatar }}">
                                    <span>{{ $blog->user->username }}</span>
                                </a>
                                <span> • {{ $blog->published_at ? $blog->published_at->toFormattedDateString() : $blog->created_at->toFormattedDateString() }}</span> •
                                @if(count($blog->categories) > 0)
                                    <span style="color: #64BEFF">
                                        Category:
                                        <?php
                                        $i = 0;
                                        $total = count($blog->categories);
                                        ?>
                                        @foreach($blog->categories as $category)
                                            <a href="{{ route('blog::home').'?category='.$category->name }}">{{ $category->name }}</a>
                                            @if($i < ($total - 1))
                                                |
                                            @endif
                                            <?php $i++; ?>
                                        @endforeach
                                    </span>
                                @endif
                            </span>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    @if(! is_null($blog->cover_photo))
                                        <div>
                                            <img src="{{ $blog->cover_photo }}" alt="" width="100%">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-text">
                                        {!! $blog->description !!}
                                    </div>
                                    @if(count($blog->tags) > 0)
                                        <span class="post-meta" style="color: #64BEFF;">
                                        Tag:
                                            @foreach($blog->tags as $tag)
                                                <a href="{{ route('blog::home').'?tag='.$tag->name }}">{{ $tag->name }} </a>
                                                @if($i < ($total - 1))
                                                    |
                                                @endif
                                                <?php $i++; ?>
                                            @endforeach
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
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
                                /*
                                 */

                                 var disqus_config = function () {
                                 this.page.url = '{{route('blog::show',array('id' => $blog->id))}}';
                                 this.page.identifier = 'crowdify-blog-'+'{{$blog->id.'-'.$blog->title}}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                                 };

                                (function() {  // DON'T EDIT BELOW THIS LINE
                                    var d = document, s = d.createElement('script');

                                    s.src = '{{env('BLOG_DISQUS_URL','//crowdify-blog.disqus.com/embed.js')}}';

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

    @if($auth->id() == $blog->user_id))
        @include('blog.includes.modal.delete_blog')
    @endif

@endsection

@section('scripts')
    <script id="dsq-count-scr" src="{{env('BLOG_DISQUS_COUNT_URL','//crowdify-blog.disqus.com/count.js')}}" async></script>
    <script type="text/javascript">
        $(document).ready(function () {

            setPageTitle('{{'Crowdify Blog | '.$blog->title}}');
            $('.delete-link').on('click', function(e){
                e.preventDefault();
                var delete_id = $(this).data('blog-id');
                $('#delete-modal-blog-id').val(delete_id);
            });
        });
    </script>

@endsection

