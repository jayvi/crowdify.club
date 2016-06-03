{!! Form::open([
                'url' => $perk->id ? route('perks::edit',array('id' => $perk->id)) :route('perks::create'),
                'files' => true
            ]) !!}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h5 class="modal-title" id="myModalLabel">
        <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">
    </h5>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="title">Perk Title</label>
        <input type="text" name="title" value="{{$perk->title}}" class="form-control" id="title" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" rows="5" required>{{ $perk->description  }}</textarea>
    </div>
    <div class="form-group">
        <label for="link">Perk Url</label>
        <input type="text" id="link" class="form-control" name="link" value="{{$perk->link}}" required>
    </div>
    <div class="form-group">
        <label for="perk-type">Perk Type</label>
        {!! Form::select('type_id', $perkTypes, $perk->type_id, array('class' => 'form-control perk-type-input', 'id'=> 'perk-type','required'=> true)) !!}
    </div>
    <div class="perk-value form-group {{$perk->perkType ? ($perk->perkType->type != 'Paid' ? 'hidden' : '') : 'hidden'}}">
        <label for="value">Value</label>
        <input type="number" name="value" class="form-control" id="value" min="0" value="{{$perk->value}}">
    </div>
    @if($perk->logo_url)
        <div class="row">
            <div class="col-md-6">
                <img src="{{$perk->logo_url}}" width="100%" alt="Logo">
            </div>
        </div>
    @endif
    <div class="form-group">
        <label for="logo">Logo</label>
        <input type="file" name="logo" class="form-control" id="logo" {{$perk->id ? '':'required'}}>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default-outline" data-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">{{ $perk->id ? 'Update' : 'Create' }}</button>
</div>
{!! Form::close() !!}