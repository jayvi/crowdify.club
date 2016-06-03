<div class="event-card" style="margin-bottom: 10px;">
    <header>
        <img class="img-responsive" src="{{$event->logo}}" alt="Image">
        <div class="image-wrapper">
            <div class="time">
                <span><i class="ion-ios-calendar"></i>{{ $event->start_date->format('Y/m/d') }}</span>
                <span><i class="ion-ios-time"></i>{{ $event->start_date->format('g:i A') }}</span>
            </div>
            <a href="{{ route('event::show', ['id' => $event->id]) }}" class="for-detail">
                <span class="text-center"><i class="ion-ios-eye"></i></span>
                View Details
            </a>
        </div>
    </header>
    <div class="event-card-body text-center">
        <h3 class="text-capitalize">{{$event->title}}</h3>
        <div class="">{{ $event->location }}</div>
    </div>
    <footer>
        <div class="event-card-tags text-center">
            @foreach($event->categories as $category)
                <a href="{{route('event::home').'?category='.$category->id}}">{{ $category->name }}</a>
            @endforeach
        </div>
    </footer>
</div>