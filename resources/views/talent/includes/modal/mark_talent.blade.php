<div class="modal fade" id="mark-talent">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">
                    <img src="{{url('assets/images/logo.png')}}" alt=""class="popup-logo">
                    Book Talent
                </h5>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-body">
                        <h1 align="center">{{ $talent->title }}</h1><br>
                        <p>If you are happy with the job done; Rate job and Mark finished</p>
                        {!! Form::open(array('method' => 'PUT')) !!}
                        {!! Form::hidden('status', 3) !!}
                        {!! Form::hidden('stars', null, array('id' => 'stars')) !!}
                        {!! Form::textarea('feedback', 'Leave your feedback here') !!}
                        <div class='movie_choice'>
                            Rating
                            <div id="r1" class="rate_widget">
                                <div class="star_1 ratings_star"></div>
                                <div class="star_2 ratings_star"></div>
                                <div class="star_3 ratings_star"></div>
                                <div class="star_4 ratings_star"></div>
                                <div class="star_5 ratings_star"></div>
                                <div class="total_votes">Total ratings</div>
                            </div>
                        </div>

                        {!! Form::submit('Mark Finished',['class' => 'btn btn-default-outline btn-sm pull-right']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
