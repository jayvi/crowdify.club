{{--<header class="site-header text-center">--}}
    {{--<div class="site-header-wrapper">--}}
        <main class="content">
            <div class="container">
                <div class="panel z1">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-4 text-left">
                                <h5>Crowdify Events</h5>
                            </div>
                            <div class="col-md-8 text-right">
                                {!! Form::open(array(
                                    'url' => route('event::home'),
                                    'class'=> 'form-inline'
                                )) !!}
                                <div class="form-group">
                                    <input name="title" type="search" class="form-control" placeholder="Search for events" autocomplete="off" >
                                </div>

                                <div class="form-group">
                                    <input name="location" type="search" class="form-control" placeholder="Enter city or location">
                                </div>

                                <div class="form-group">
                                    <select name="date" class="form-control date-select"><i class="icon ion-home"></i>
                                        <option value="all" selected="selected">All Dates <span></span></option>
                                        <option value="today">Today</option>
                                        <option value="tomorrow">Tomorrow</option>
                                        <option value="this_week">This Week</option>
                                        <option value="this_weekend">This Weekend</option>
                                        <option value="next_week">Next Week</option>
                                        <option value="next_month">Next Month</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn custom-btn btn-default">Search</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    {{--</div>--}}

{{--</header>--}}