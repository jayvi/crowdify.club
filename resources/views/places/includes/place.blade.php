<div class="panel z1 single-place-post">
    <div class="panel-heading">
        <h4><a href="{{route('places::show',array('id' => $place->id))}}">{{$place->title}}</a>
            @if($auth->check() && $auth->user()->isAdmin())
                <div class="dropdown dropdown-modify">
                    <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a class="edit-link" href="" data-place-id="{{$place->id}}">Edit</a></li>
                        {{--@if($place->status == 'Published')--}}
                            {{--<li><a class="un-publish-link" href="" data-place-id="{{$place->id}}">Un-publish</a></li>--}}
                        {{--@else--}}
                            {{--<li><a class="publish-link" href="" data-place-id="{{$place->id}}">Publish</a></li>--}}
                        {{--@endif--}}
                        <li><a class="delete-link" data-place-id="{{$place->id}}">Delete</a></li>
                    </ul>
                </div>
            @endif
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <img src="{{$place->cover_photo}}" alt="" width="100%">
            </div>
            <div class="col-md-10">
                <p>{{ substr(strip_tags($place->description), 0, 250) }}</p>
                 <span class="post-meta" style="color: #64BEFF;">
                     @if($auth->check() && $auth->user()->isAdmin())
                         Status: <a>{{ $place->status }}</a>
                     @endif
                    </span>
                <a href="{{route('places::show',array('id' => $place->id))}}" class="btn btn-default-outline pull-right">View Details</a>
            </div>
        </div>

    </div>
</div>