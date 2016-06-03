<?php
use Jenssegers\Agent\Agent as Agent;
$Agent = new Agent();
?>
<?php
if ($Agent->isMobile()) {

}
else {
?>

<img class="nav-img" src="{{url('assets/images/talent_icon.png')}}">
<a href="{{ route('talent::home') }}">Find Talent</a><br>
<li style="margin-left: 35px"><a href="{{ route('talent::alltalent') }}">All Talents</a></li>
<li style="margin-left: 35px"><a href="{{ route('talent::allreq') }}">All Request</a></li>

<img class="nav-img" src="{{url('assets/images/task_icon.png')}}">
<a href="{{ route('perk::tasks') }}">Run Tasks</a><br>

<img class="nav-img" src="{{url('assets/images/blog_icon.png')}}">
<a href="{{ route('blog::home') }}">Write Blog</a><br>

<img class="nav-img" src="{{url('assets/images/perk_icon.png')}}">
<a href="{{ route('perk::perks') }}">Earn Perks</a><br>

<img class="nav-img" src="{{url('assets/images/events_icon.png')}}">
<a href="{{ route('event::home') }}">Attend Events</a><br>

<img class="nav-img" src="{{url('assets/images/coworking_icon.png')}}">
<a href="{{route('places::home')}}">Coworking Places</a><br>

<img class="nav-img" src="{{url('assets/images/cities_icon.png')}}">
<a href="{{route('cities::home')}}">Crowdify Cities</a><br>

<img class="nav-img" src="{{url('assets/images/tools_icon.png')}}">
<a href="{{route('Tools::home')}}">Online Tools</a><br>

<img class="nav-img" src="{{url('assets/images/shop_icon.png')}}">
<a href="http://shop.crowdify.tech">Tech Shop</a><br>

<img class="nav-img" src="{{url('assets/images/video_icon.png')}}">
<a href="{{route('howto::videos')}}">How To Videos</a><br>
@if($auth->check())
    <img class="nav-img" src="{{url('assets/images/folder_icon.png')}}">
    <a href="{{ route('profile::portfolio', ['username' => $auth->user()->username]) }}">Portfolio</a><br>
    <img class="nav-img" src="{{url('assets/images/money_tree_icon.png')}}">
    <a href="{{ route('money::tree', array('username' => $auth->user()->username)) }}">My Money Tree</a><br>
    <img class="nav-img" src="{{url('assets/images/communities_icon.png')}}">
    <a href="{{ route('community::dashboard') }}">Communities</a><br>
    <img class="nav-img" src="{{url('assets/images/followers_icon.png')}}">
    <a href="{{ route('profile::followers') }}">My Followers</a><br>
    <img class="nav-img" src="{{url('assets/images/users_icon.png')}}">
    <a href="{{route('users::list')}}">All Users</a><br>
@endif
<hr class="navhr"></hr>
<img class="nav-img" src="{{url('assets/images/facebook_icon.png')}}">
<p class="navp">Crowdify FB Groups</p>
<a target="_blank" href="https://www.facebook.com/groups/crowdifyconnecting/">General Group</a>
<a target="_blank" href="https://www.facebook.com/groups/108785324695/">Cleantech Group</a>
<a target="_blank" href="https://www.facebook.com/groups/getbitcoindaily/">Bitcoin Group</a>
<a target="_blank" href=" https://www.facebook.com/groups/197056753708451/">Online Tools Group</a>
<a target="_blank" href="https://www.facebook.com/groups/monthlypagefocus/">Crowdify TV Group</a>
<a href="https://www.youtube.com/channel/UCwcIwtifAOQ0LbHwoNHcAUQ">CrowdifyTV</a>

<hr class="navhr"></hr>
@if($auth->check())
    @if($auth->user()->usertype_id > 1)
        <a href="{{ route('subscriptions::home') }}">Memberships</a>
    @else
        <a href="{{ route('subscriptions::home') }}">Become a Premium or Founding Member</a>
    @endif
@else
    <a href="{{ route('subscriptions::home') }}">Become a Premium or Founding Member</a>
@endif

@if($auth->check() && $auth->user()->isAdmin())
    <hr class="navhr"></hr>
    <p class="navp">Admin Area</p>
    <a href="{{route('affiliates::admin-dashboard')}}">Affiliates</a>
    <a href="{{route('broadcaster::home')}}">Email Broadcaster</a>
    @endif
    </ul>
    <?php
    }
    ?>
