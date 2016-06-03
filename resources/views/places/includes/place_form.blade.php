<div class="panel z1 place-form">
    <div class="panel-heading">
        @if($place->id)
            <h4>Edit Post
                <button style="top: 0px;"type="button" class="close"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </h4>
        @else
            <h4>Create Post
                <button style="top: 0px;" type="button" class="close"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </h4>
        @endif


    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <label for="">Cover Photo</label>
                <div class="image-upload" data-image-url="{{$place->cover_photo}}">
                </div>
                <input type="hidden" name="cover_photo" id="cover-photo" value="{{$place->cover_photo}}">
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('title', 'Title *') !!}
            {!! Form::text('title', $place->title, ['class' => 'form-control','id' =>'title']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description *') !!}
            <textarea name="description" id="summernote" class="form-control summernote note-editable" placeholder="Tell people what's this post about">{{$place->description}}</textarea>
        </div>


        <div class="form-group">
            {!! Form::label('status', 'Status') !!}
            {!! Form::select('status', array('Draft' => 'Draft', 'Published' => 'Published'), $place->status, ['class' => 'form-control', 'id' => 'status']) !!}
        </div>

        <div class="form-group">
            <button class="btn btn-primary pull-right {{$place->id ? 'btn-edit' : 'btn-create'}}">{{$place->id ? 'Update':'Create'}}</button>
        </div>
    </div>
</div>
