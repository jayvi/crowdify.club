<div class="panel-body">
    <h5>Earnings:</h5>
    @if($affiliate->references && count($affiliate->references) > 0)
        <table class="table table-bordered table-responsive">
            <thead>
            <th>From</th>
            <th>Payment Details</th>
            </thead>
            <tbody>
            <?php $totalEarnings= 0 ?>
            @foreach($affiliate->references as $reference)
                <tr>
                    <td>
                        <a href="{{route('perk::public_profile',array('username' => $reference->referenceUser->username))}}">{{$reference->referenceUser->username}}</a>
                    </td>
                    <td>
                        @if($reference->earnings && count($reference->earnings) > 0)
                            <table class="table">
                                <thead>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Payment Status</th>
                                </thead>
                                <tbody>
                                <?php $totalByUser = 0 ?>
                                @foreach($reference->earnings as $earning)
                                    <tr>
                                        <td>${{$earning->amount}}</td>
                                        <td>{{$earning->payment_date->toFormattedDateString()}}</td>
                                        <td>{{$earning->status}}</td>
                                    </tr>
                                    <?php
                                    $totalByUser += $earning->amount;
                                    $totalEarnings += $earning->amount;
                                    ?>
                                @endforeach
                                @if(count($reference->earnings) > 0)
                                    <tr>
                                        <td><strong>Total Earnings from <code>{{$reference->referenceUser->username}}</code> =  ${{$totalByUser}}</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endif
                                </tbody>

                            </table>
                        @else
                            <p>No earnings from this user</p>
                        @endif
                    </td>

                </tr>
            @endforeach
            <tr>
                <td></td>
                <td><strong>Total Earnings =  ${{$totalEarnings}}</strong></td>
            </tr>
            </tbody>
            <tfoot></tfoot>
        </table>
    @endif
</div>