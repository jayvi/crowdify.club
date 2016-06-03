 <?php $profiles = $user->profiles; ?>
        @if($profiles)
            <?php
            $providers = $profiles->lists('provider')->toArray();
            ?>
            <ul class="list-inline margin-top10">
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