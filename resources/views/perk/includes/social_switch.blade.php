<div class="panel-body">
    <div class="row">
        <div class="col-xs-8">
            <b>
                {{$label}}
            </b>
        </div>
        <div class="col-xs-4">
            <div class="mat-switch">
                <input class="social-switch" data-provider="{{$provider}}" id="{{$provider}}" type="checkbox" hidden="hidden" {{ in_array($provider, $providers) ? 'checked' :'' }}>
                <label for="{{$provider}}" class="switch"></label>
            </div>
        </div>
    </div>
</div>