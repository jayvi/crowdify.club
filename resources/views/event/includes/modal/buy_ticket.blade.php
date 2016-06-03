<div class="modal fade" id="buy-ticket">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                @if($auth->id() != $event->user_id)
                    @if($event->type == 'Paid')
    @if($event->tickets && count($event->tickets))
        <table class="table table-striped table-responsive">
            <thead>
            <th>Name</th>
            <th>Price</th>
            <th>Payment</th>
            <th>Quantity</th>
            </thead>
            <tbody>
            @foreach($event->tickets as $ticket)
                <?php
                $url = "https://bitpay.com/api/rates";
                $json = file_get_contents($url);
                $data = json_decode($json, TRUE);
                $rate = $data[1]["rate"];
                $bitcoin = round($ticket->price / $rate, 8);
                ?>
                {!! Form::open([
                            'url' => route('event::tickets::buy', ['event_id' => $event->id, 'ticket_id' => $ticket->id]),
                            'class' => 'form-inline',
                            'method' => 'POST'
                        ]) !!}
                <input type="hidden" name="bitcoin" value="{{$bitcoin}}">
                <input type="hidden" name="euser" value="{{$event->user_id}}">
                <tr>
                    <td>{{$ticket->name}}</td>
                    <td>${{$ticket->price}}<br>
                        ฿{{$bitcoin}}</td>
                    <td>
                        {!! Form::label('payment', 'PayPal') !!}
                        {!! Form::radio('payment', 'PayPal') !!}
                        <br>
                        {!! Form::label('payment', 'BitCoin') !!}
                        {!! Form::radio('payment', 'BitCoin') !!}
                    </td>
                    <td>

                        <div class="form-group">
                            <div class="input-group">
                                {!! Form::select('ticket_amount', $ticketQuantities,1 ,['class' => 'form-control ticket-amount']) !!}
                            </div>
                        </div>
                        @if($auth->check())
                            <button type="submit" class="btn btn-success">Buy</button>
                        @else
                            <a data-toggle="modal" data-target="#modal-auth" href="" class="btn btn-success">Buy</a>
                        @endif
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot></tfoot>
        </table>
    @endif
                    @else
                        <div class="panel z1">
                            <div class="panel-body">
                                {!! Form::open(['url' => route('event::register', ['id' => $event->id])]) !!}
                                @if( ! $auth->check())
                                    <div class="text-center join-btn-div">
                                        <button class="btn btn-default-outline form-control" id="button-join">Join this event</button>
                                    </div>

                                    <div id="event-registration" class="hidden">
                                        <div class="form-group">
                                            {!! Form::label('first_name', 'First Name *') !!}
                                            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => '','required']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('last_name', 'Last Name *') !!}
                                            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => '','required']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('email', 'Email Address *') !!}
                                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => '','required']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('contact', 'Contact No *') !!}
                                            {!! Form::text('contact', null, ['class' => 'form-control', 'placeholder' => '','required']) !!}
                                        </div>
                                        {!! Form::submit('Join', ['class' => 'btn btn-default-outline form-control']) !!}
                                    </div>
                                @else
                                    @if($isRegistered)
                                        <p class="text-center">You are registered on this event.</p>
                                    @else
                                        {!! Form::submit('Join', ['class' => 'btn btn-default-outline form-control']) !!}
                                    @endif
                                @endif
                                @endif
                                @endif
                            </div>
            </div>
        </div>
    </div>