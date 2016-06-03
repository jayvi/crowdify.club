<ul class="homenav">
    <li>
        <a href="{{ route('perk::public_profile') }}" class="p-y-9"><img src="{{ $auth->user()->avatar ? $auth->user()->avatar : '' }}" class="navimg"> {{$auth->user()->username}}</a>
    </li>
    <li><a href="{{ route('profile::edit') }}" class="p-y-9"><img src="/assets/images/edit.png" class="navimg"> Edit Profile</a></li>
    <hr class="navhr"></hr>
    <li><a href="{{ route('perk::tasks') }}">Tasks</a></li>
    <li><a href="{{ route('perk::perks') }}">Perks</a></li>
    <li><a href="{{ route('event::home') }}">Events</a></li>
    <li><a href="{{ route('blog::home') }}">Blog Posts</a></li>
    <li role="presentation" class="{{ isset($active_tab_my_blog) ? 'active' : '' }}" style="border-bottom: 1px solid #F2F2F2;"><a href="{{ route('blog::my-blogs') }}">My Blogs</a></li>
    <li role="presentation" class="{{ isset($active_tab_create_blog) ? 'active' : '' }}"><a href="{{ route('blog::create') }}">Create Blog</a></li>

    <hr class="navhr"></hr>
    <p class="navp">Apps</p>
    <li><a href="http://tools.crowdify.tech">Web Tools Wiki</a></li>
    {{--<li role="presentation" class="{{ $activeTab == 'completion-history' ? 'active': '' }}"><a href="{{ route('hugs::completion-history') }}">My Completion History</a></li>--}}
</ul>