@foreach($affiliates as $affiliate)
    <div class="panel z1">
        <div class="panel-heading">
            <h4>Affiliate name: <a href="{{route('perk::public_profile',array('username' => $affiliate->username))}}">{{$affiliate->username}}</a></h4>
        </div>
        @include('affiliate.includes.affiliate', array('affiliate' => $affiliate))
    </div>
@endforeach