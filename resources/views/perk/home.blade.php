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
            <div class="row" >
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            @include('perk.includes.home_nav')
                        </div>
                        <div class="col-md-7">

                            <div class="row">
                                <div class="col-md-12">
                                    @foreach($entities as $entity)
                                        @if($entity instanceof \App\Perk)
                                            @include('perk.includes.perk', array('perk' => $entity))
                                        @elseif($entity instanceof \App\Hug)
                                            @include('perk.includes.hug', array('hug' => $entity))
                                        @elseif($entity instanceof \App\Event)
                                            @include('event.includes.event', array('event' => $entity))
                                        @elseif($entity instanceof \App\BlogPost)
                                            @include('blog.includes.blog_post', array('blog' => $blogPost))
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="col-md-3">
                            @include('includes.right_block')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>


    @if(isset($showIntroVideo) && $showIntroVideo)
        @include('perk.includes.modal.intro_video')
    @elseif(isset($profileEditComplete) && $profileEditComplete)
        @include('perk.includes.modal.profile_edit_complete')
    @elseif(isset($achievement) && $achievement)
        @include('perk.includes.modal.achievement_unlocked')
    @elseif(isset($showExplanationVideo) && $showExplanationVideo)
        @include('perk.includes.modal.explanation_video')
    @endif


    @include('perk.includes.modal.hug_completers')
    @include('perk.includes.modal.hug_delete')

@stop


@section('scripts')
    @include('perk.includes.scripts.hug')
    @if((isset($showIntroVideo) && $showIntroVideo) || (isset($showExplanationVideo) && $showExplanationVideo))
        @include('perk.includes.scripts.video')
    @endif

    <script>
        $(document).ready(function(){

            @if(isset($showIntroVideo) && $showIntroVideo)
               $('#intro-video').modal('show');
            @elseif(isset($profileEditComplete) && $profileEditComplete)
                 $('#profile-edit-complete').modal('show');
            @elseif(isset($achievement) && $achievement)
                $('#achievement-unlocked').modal('show');
            @elseif(isset($showExplanationVideo) && $showExplanationVideo)
               $('#explanation-video').modal('show');
            @endif

            @include('perk.includes.scripts.home')

        });
    </script>
@endsection

