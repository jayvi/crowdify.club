@include('mail.includes.header')

<p style="color: #565656; font-family: Georgia,serif; font-size: 16px;">
    Hi, {{ $firstName }}
</p>
<p> Welcome to <a href="{{url('/')}}">Crowdify</a></p>

@include('mail.includes.footer')