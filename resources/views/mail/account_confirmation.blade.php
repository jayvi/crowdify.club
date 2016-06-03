@include('mail.includes.header')

    <p><strong>Hi, {{ $user['first_name'] }}</strong></p>
    <p>You have a confirmation email to confirm your Crowdify Account. </p>
    <a href="{{route('auth::email::confirmation').'?code='.$mailConfirmation['confirmation_code']}}"> >>Please Click Here to confirm</a><br />

    <p><strong>If you don't know about this then please ignore this mail </strong></p>


@include('mail.includes.footer')