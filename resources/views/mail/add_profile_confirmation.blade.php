@include('mail.includes.header')

    @if($type == 'LinkProfile')
        <p>You have a confirmation mail to link your social profile to an existing Crowdify account with your email. </p>
        <p>Please Click <a href="{{url('auth/link-email-confirmation').'?code='.$confirmation_code}}">Here</a> to confirm</p><br />

        <p><strong>If you don't know about this then please ignore this mail </strong></p>
    @elseif($type == 'CreateUser')
        <p>You have a confirmation mail from Crowdify. </p>
        <p>Please Click <a href="{{url('auth/create-email-confirmation').'?code='.$confirmation_code}}">Here</a> to confirm</p><br />

        <p><strong>If you don't know about this then please ignore this mail </strong></p>
    @elseif($type == 'TwitterEmail')
        <p>You have a confirmation mail from Crowdify. </p>
        <p>Please Click <a href="{{url('auth/twitter-email-confirmation').'?code='.$confirmation_code}}">Here</a> to confirm</p><br />

        <p><strong>If you don't know about this then please ignore this mail </strong></p>
    @elseif($type == 'LinkTwitter')
        <p>You have a confirmation mail to link your Twitetr account to an existing Crowdify account with your email. </p>
        <p>Please Click <a href="{{url('auth/twitter-link-email-confirmation').'?code='.$confirmation_code}}">Here</a> to confirm</p><br />

        <p><strong>If you don't know about this then please ignore this mail </strong></p>
    @endif

@include('mail.includes.footer')