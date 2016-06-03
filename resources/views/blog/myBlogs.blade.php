<?php
use Jenssegers\Agent\Agent as Agent;
$Agent = new Agent();
?>
@extends('layout.master')

@section('navbar')
    @include('perk.includes.navbar')
@stop

@section('header')
    @include('perk.includes.header')
@stop


@section('content')
    <main class="content">
        <div class="ccontainer">
            <div class="row">
                @if($auth->check())
                    <div class="col-md-2">

                        <?php
                        if ($Agent->isMobile()) {
                        ?>
                        <ul class="nav nav-tabs">
                            <li role="presentation"><a href="{{ route('blog::home') }}">Blogs</a></li>
                            <li role="presentation"><a href="{{ route('blog::create') }}">Create</a></li>
                            <li role="presentation" class="active"><a href="{{ route('blog::my-blogs') }}">My Blogs</a></li>
                        </ul>
                        <?php
                        }
                        ?>
                        @include('perk.includes.blog_nav')
                    </div>
                    <div class="col-md-7">
                        @if($myBlogs && count($myBlogs) > 0)
                            @include('blog.includes.blogs', array('blogs' => $myBlogs))
                        @else
                            <div class="panel z1">
                                <div class="panel-body">
                                    <p>There is nothing to show right now!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        @include('includes.right_block')
                    </div>
                @else
                    <div class="col-md-12">
                        @if($myBlogs && count($myBlogs) > 0)
                            @include('blog.includes.blogs', array('blogs' => $myBlogs))
                        @else
                            <div class="panel z1">
                                <div class="panel-body">
                                    <p>There is nothing to show right now!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>

    @include('blog.includes.modal.delete_blog')

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.delete-link').on('click', function(e){
                e.preventDefault();
                var delete_id = $(this).data('blog-id');
                $('#delete-modal-blog-id').val(delete_id);
            });
        });
    </script>
@endsection

