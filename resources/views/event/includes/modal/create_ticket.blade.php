<div class="modal fade" id="create-ticket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['url' => route('event::tickets::create',['event_id' => $event->id]), 'method' => 'POST', 'id' => 'create-ticket-form']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">Create Ticket
                </h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ticket-name">Name</label>
                    <input type="text" name="name" value="" class="form-control" id="ticket-name">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="ticket-price">Price</label>
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="number" name="price" class="form-control" id="ticket-price" placeholder="Amount">
                        <div class="input-group-addon">.00</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="ticket-submit-button">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>