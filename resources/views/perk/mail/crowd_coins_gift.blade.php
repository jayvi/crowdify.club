@include('mail.includes.header')

<h3>Hi {{ $recipient->first_name }}</h3>

<p>You have just received {{ $ammount }} Crowdify Coins from <a href="{{ route('perk::public_profile', array('username' => $sender->username)) }}">{{ $sender->username }}</a>.</p>

@include('mail.includes.footer')