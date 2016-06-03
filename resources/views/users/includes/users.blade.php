@foreach($users as $user)
    <div class="row" style="padding: 10px;">
        <div class="col-md-12">
            <a class="float-left margin-right10 min-width225" href="{{route('perk::public_profile',array('username' => $user->username))}}">
                @if($user->avatar_original || $user->avatar)
                <img style="padding: 5px;" src="{{$user->avatar_original ? $user->avatar_original : $user->avatar}}" width="50px;" alt="">
                @else
                    <img src="{{url('assets/images/default_profile_pic.jpg')}}">
                @endif
                {{$user->username}}
            </a>
            <?php $profiles = $user->profiles; ?>
            @if($profiles)
                <?php
                $providers = $profiles->lists('provider')->toArray();
                ?>
                <ul class="list-inline margin-top10 float-left">
                    @foreach($profiles as $profile)
                        @if($profile->provider== 'twitter')
                            <li><a title="Twitter" target="_blank" href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-twitter social-icon twitter"></span></a></li>
                        @elseif($profile->provider== 'facebook')
                            <li><a title="Facebook" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-facebook social-icon facebook"></span></a></li>
                        @elseif($profile->provider== 'google')
                            <li><a title="Google+" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-googleplus social-icon googleplus"></span></a></li>
                        @elseif($profile->provider== 'linkedin')
                            <li><a title="Linkedin" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-linkedin-outline social-icon linkedin"></span></a></li>
                        @elseif($profile->provider== 'foursquare')
                            <li><a title="Foursquare" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-foursquare-outline social-icon foursquare"></span></a></li>
                        @elseif($profile->provider== 'flickr')
                            <li><a title="Flickr" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="icon-flickr social-icon flickr"></span></a></li>
                        @elseif($profile->provider == 'instagram')
                            <li><a title="Instagram" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-instagram-outline social-icon instagram"></span></a></li>
                        @elseif($profile->provider == 'youtube')
                            <li><a title="Youtube" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-youtube-outline social-icon youtube"></span></a></li>
                            {{--@elseif($profile->provider == 'blogger')--}}
                            {{--<li><a title="Blogger" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="icon-blogger social-icon blogger"></span></a></li>--}}
                        @elseif($profile->provider == 'facebookPage')
                            <li><a title="Facebook Page" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-flag social-icon facebook-page"></span></a></li>
                        @elseif($profile->provider == 'tumblr')
                            <li><a title="Tumblr" target="_blank"  href="{{ $profile->social_profile_url ?  $profile->social_profile_url : '#'}}"><span class="ion-social-tumblr social-icon tumblr"></span></a></li>
                        @endif
                    @endforeach
                </ul>
            @endif

            <div class="btn-group float-right margin-top9">
                @if($auth->check())
                    @if($auth->user()->isFollowing($user->id))
                        <button class="btn btn-success btn-following"  data-user-id="{{$user->id}}">Following</button>
                    @else
                        @if($auth->user()->id == $user->id)
                            <button class="btn btn-primary" disabled data-user-id="{{$user->id}}">&nbsp;&nbsp;Follow&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        @else
                            <button class="btn btn-primary btn-follow" data-user-id="{{$user->id}}">&nbsp;&nbsp;Follow&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        @endif
                    @endif
                @else
                    <button class="btn btn-default-outline" id="btn-follow" data-toggle="modal" data-target="#modal-auth">&nbsp;&nbsp;Follow&nbsp;&nbsp;&nbsp;&nbsp;</button>
                @endif
                <button class="btn btn-default btn-follower-count">{{count($user->followers)}}</button>
                    @if($auth->check())
                        @if($auth->user()->isAdmin())
                            @if($user->is_blocked)
                                <button class="btn btn-danger btn-un-block"  data-user-id="{{$user->id}}">Unblock</button>
                            @else
                                <button class="btn btn-default btn-do-block"  data-user-id="{{$user->id}}">Block</button>
                            @endif

                        @endif
                    @endif
            </div>

        </div>
    </div>
    <hr class="navhr"></hr>
@endforeach