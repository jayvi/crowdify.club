<tr>
    <td>
        <a href="{{route('perk::public_profile',array('username' => $user->username))}}">
            <img style="padding: 5px;" src="{{$user->avatar_original ? $user->avatar_original : $user->avatar}}" width="50px;" alt="">
            {{$user->username}}
        </a>
    </td>
    <td>
        {{ucwords($user->payment_type)}}
    </td>
    <td>Will expire on <strong>{{$user->last_payment_date ? $user->last_payment_date->addMonth(1)->toDateString() : ''}}</strong></td>
</tr>