<div class="panel z1">
    @if($owner)
        <div class="dropdown dropdown-modify">
            <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a class="edit-link" data-perk-id="{{ $perk->id }}">Edit</a></li>
                <li><a class="delete-link" data-toggle="modal" data-target="#delete-perk" data-perk-id="{{ $perk->id }}">Delete</a></li>
            </ul>
        </div>
    @endif
    <div class="panel-body">
        <div class="row">
            @if(!$buyButtons)
                <div class="col-md-12">
                    <h5><a href="{{ route('perks::perk', array('id' => $perk->id)) }}">{{ $perk->title }}</a></h5>
                    <p>{{ $perk->description }}</p>
                    @if($perk->perkType && $perk->perkType->type == 'Paid')
                        <p>Value: {{ $perk->value }}</p>
                    @endif
                </div>
            @else
                <div class="col-md-8">
                    <h5><a href="{{ route('perks::perk', array('id' => $perk->id)) }}">{{ $perk->title }}</a></h5>
                    <p>{{ $perk->description }}</p>
                    <p>Value: {{ $perk->value }}</p>
                </div>
                <div class="col-md-4 text-right">
                    <p><button class="btn btn-primary">Cost Free</button></p>
                    <p><button class="btn btn-primary">Pay Pal</button></p>
                    <p><button class="btn btn-primary">Bit Coins</button></p>
                    <p><button class="btn btn-primary">Crowdify Coins</button></p>
                </div>
            @endif

        </div>
    </div>
</div>