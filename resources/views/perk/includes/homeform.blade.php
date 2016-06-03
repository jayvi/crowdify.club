<div class="form-panel z1">
    <div class="form-body">
        <div class="profileimg-form">
            <a href="{{ route('perk::public_profile') }}" class="p-y-9"><img src="{{ $auth->user()->avatar ? $auth->user()->avatar : '' }}" class="formimg"></a>
        </div>
        {!! Form::open(array('url' => 'tasks/create', 'files' => true)) !!}

        <div class="home-form">
            {!! Form::text('title', 'Task Title', ['class' => 'homeform-control']) !!}
        </div>

        <div class="home-form">
            <textarea class="homeform-control" name="Description" cols="50" rows="2">Description *</textarea>
        </div>

        <div class="home-form">
            {!! Form::text('link', 'Link', ['class' => 'homeform-control']) !!}
        </div>

        <div class="home-form2">
            <input type="file" name="photo" id="photo" accept="image/*">
        </div>

        <div class="home-form3">
            Total Crowdify coins {!! Form::number('total_amount', 'Total', ['class' => 'homeform-control2']) !!}
        </div>

        <div class="home-form3">
            Reward {!! Form::number('reward', 'Reward', ['class' => 'homeform-control2']) !!}
        </div>

        <div class="home-form4">
            {!! Form::submit('Create Task', ['class' => 'btn bt-default btn-default-outline']) !!}
        </div>

        {!! Form::close() !!}
    </div>
</div>