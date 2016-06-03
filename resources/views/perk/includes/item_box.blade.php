

            @if($item->key == 'main')
                <h3>{{$user->username}}</h3>
            @else
                <h3>{{ $item->value }}</h3>
            @endif




            @if($item->key != 'city' && $item->key != 'interest')
                <p class="share-price"><span class="crowd-coin">{{ sprintf('%0.2f',$item->share_price) }}</span></p>
                @if($auth->id() != $user->id)
                    <div class="btn-group" role="group" aria-label="...">
                        @if($auth->check())
                            @if($item->soldShares && count($item->soldShares) > 0 &&  !$item->soldShares[0]->refunded)
                                <button class="btn btn-default-outline btn-invested item-toast-info invest-btn" data-info="Sorry you can only buy shares in a user once every calendar month right now. We are interested in your ideas for a better system!">Invested</button>
                            @else
                                <button class="btn btn-default-outline btn-invest invest-btn" data-item-id="{{ $item->id }}" data-price="{{$item->share_price}}">Invest</button>
                            @endif
                        @else
                            <button class="btn btn-default-outline invest-btn" data-toggle="modal" data-target="#modal-auth">Invest</button>
                        @endif

                        @if($auth->check())
                            @if($item->soldShares && count($item->soldShares) > 0 && $item->soldShares && !$item->soldShares[0]->refunded)
                                <button class="btn btn-default-outline btn-sell sell-btn" data-amount="{{ $item->soldShares[0]->amount }}" data-item-id="{{ $item->id }}">Sell</button>
                            @else
                                <button class="btn btn-default-outline item-toast-info sell-btn" data-info="Sorry you have no share on this">Sell</button>
                            @endif
                        @else
                            <button class="btn btn-default-outline sell-btn" data-toggle="modal" data-target="#modal-auth">Sell</button>
                        @endif
                    </div>
                @endif
            @else
                <div class="image-upload col-md-12">
                    <div class="row item-row">
                    <img class="item-image" src="{{$item->photo ? $item->photo : '/assets/images/placeholder.png'}}" alt="">
                    </div>
                    @if($auth->id() == $user->id)
                        <a href="" class="upload-link">{{$item->photo ? 'Change Photo':'Upload Photo'}}</a>
                        <input type="file" class="hidden" data-item-id="{{$item->id}}" accept="image/*">
                        <div class="progress hidden">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                0%
                            </div>
                        </div>
                    @endif

                </div>
            @endif

