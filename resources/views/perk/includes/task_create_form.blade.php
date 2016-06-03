<div class="form-panel task-form z1">
    <div class="form-body">
        <div class="row form-row">
            <div class="profileimg-form">
                <a href="{{ route('perk::public_profile') }}" class="p-y-9"><img src="{{ $auth->user()->avatar_original ? $auth->user()->avatar_original : '' }}" class="formimg"></a>
            </div>
            <div class="home-form">
                <input type="text" name="title" value="" placeholder="Task Title" class="homeform-control">
            </div>
            <div class="home-form">
                <textarea class="homeform-control description"  name="description" rows="2" placeholder="Task Description"></textarea>
            </div>
            <div class="home-form">
                <input type="text" name="link" class="homeform-control" placeholder="Task Link">
            </div>
            <div class="home-form3">
                <input type="number" name="reward" placeholder="Reward" value="" class="homeform-control2">
            </div>
            <div class="home-form3">
                <input type="number" name="total_amount" placeholder="Total Crowdify Coins" class="homeform-control2">
            </div>
            <div class="home-form3">
                <button type="button" class="btn btn-default btn-default-outline pull-right" id="btn-create-task">Create</button>
            </div>
        </div>
    </div>
