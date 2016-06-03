@foreach($events as $event)
    <div class="col-md-4">
        <div class="panel z1">
            <div class="panel-body">
                @include('event.includes.event',array('event' => $event))
            </div>
        </div>
    </div>
@endforeach