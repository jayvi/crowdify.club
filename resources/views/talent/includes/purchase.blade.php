<div class="feed-panel z1">
    {{--<div class="card-block">--}}
    <div class="home-feed">
        <a href="{{ route('talent::purchase', array('id' => $order->id)) }}">{{ $talent->title }}</a>
        <p class="card-text">{{ substr($talent->metatag, 0, 50).( strlen($talent->metatag) > 50 ? '...' : '')}}</p>
        <span class="label label-deafult-outline"><i class="crowd-coin"></i> {{ $talent->crowdcoins }}</span>
        <span class="label label-deafult-outline"><i class="bit-coin"></i> {{ $talent->bitcoins }}</span>
    </div>
</div>