@include('mail.includes.header')

<p style="color: #565656; font-family: Georgia,serif; font-size: 16px;">
    Hi, {{ $register['first_name'] }}
</p>
<p>You have been registered with a Crowdify event.</p>
<p>Click the link below to see the details</p>
<p><a href="{{url($event['id'].'/show')}}">{{ $event['title'] }}</a></p>


@include('mail.includes.footer')