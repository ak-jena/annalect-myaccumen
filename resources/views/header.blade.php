<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ asset("/img/logo_mn.png") }}"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{ asset("/img/logo_mn.png") }}"><b>accuen</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-fixed-top" role="navigation">

        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bullhorn"></i>
                        <span class="label bg-purple">{{\Session::get('announcements')>0?\Session::get('announcements'):''}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header text-purple">There are <b>{{\Session::get('announcements')>0?\Session::get('announcements'):'no'}}</b> announcements</li>
                            <?php if(\Session::get('announcements')>0): ?>
                        <li>
                            <ul class="menu">
                            <?php
                            foreach(Session::get('announcement_msg') as $msg){
                                echo "<li><a href='" .url($msg['url']). "'><i class='fa " .$msg['icon']." text-aqua'></i> ".$msg['text']."</a></li>";
                            }
                            ?>
                    </ul>
                </li>
                            <?php endif; ?>
                    </ul>
                </li>

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label bg-fuchsia-active">{{\Session::get('notifications')>0?\Session::get('notifications'):''}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header text-fuchsia">There are <b>{{\Session::get('notifications')>0?\Session::get('notifications'):'no'}}</b> notifications</li>
                        <?php if(\Session::get('notifications')>0): ?>
                            <li>
                                <ul class="menu">
                                <?php
                                foreach(Session::get('notification_msg') as $msg){
                                    echo "<li><a href='" .url($msg['url']). "'>" .$msg['icon'].$msg['text'] . "</a></li>";
                                }
                        ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ Baselib::get_gravatar(Auth::user()->email) }}" class="user-image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{Auth::user()->username}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ Baselib::get_gravatar(Auth::user()->email) }}" class="img-circle"/>
                            <p>
                                {{Auth::user()->name}}
                                <small>Last login: {{\Carbon\Carbon::createFromTimeStamp(strtotime(Auth::user()->last_login))->diffForHumans()}}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url('/profile') }}" class="btn btn-info btn-sm btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/logout') }}" class="btn btn-danger btn-sm btn-flat">Sign out</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url('/auth/lock') }}" class="btn btn-warning btn-sm btn-flat">Lock screen</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>

        <div class="clearfix"></div>

        @if(in_array(Route::currentRouteName(), array('dashboard', 'live-campaigns', 'completed-campaigns')))
            <div class="filter-bar">
                <div class="med-margin-bottom">
                    {!! Form::open([
                        'class' => 'form-inline',
                        'id' => 'dashboard_filter_form'
                    ]) !!}
                    <div class="pull-left">
                        @if(Route::currentRouteName() == 'dashboard')
                            @if(\Baselib::canCreateBrief())
                                <a class="btn btn-default btn-sm" href="{{ route('workflow') }}">Create New Campaign</a>
                            @endif
                        @endif
                        @if(Route::currentRouteName() == 'live-campaigns')
                            <a class="small-margin-bottom btn btn-default" href="{{ route('live-campaigns-check') }}">Check for live campaigns</a>
                        @endif
                        @if(Route::currentRouteName() == 'completed-campaigns')
                            <a class="small-margin-bottom btn btn-default" href="{{ route('completed-campaigns-check') }}">Check for completed campaigns</a>
                        @endif
                    </div>

                    <div class="pull-right">
                        <div id="date-filter" class="form-group has-feedback">
                            {!! Form::label('date_range_filter', 'Client', array('class' => 'sr-only')) !!}
                            {!! Form::text('date_range_filter', null,array('class' => 'input-sm form-control')) !!}
                            <i class="glyphicon glyphicon-calendar form-control-feedback"></i>
                        </div>

                        <div id="client-filter" class="form-group">
                            {!! Form::label('client_id', 'Client', array('class' => 'sr-only')) !!}
                            {!! Form::select('client_id', $clients_all, 0, array('class' => 'input-sm form-control')) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('product_id', 'Product', array('class' => 'sr-only')) !!}
                            {!! Form::select('product_id', $products_all, null, array('class' => 'input-sm form-control')) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>

            <div class="clearfix"></div>

            @if(Route::currentRouteName() == 'dashboard')
                <div class="row padding-top-small">
                <div class="col-md-5ths">
                    <div class="col-md-6">
                        <h4 class="col-heading">New Brief</h4>
                    </div>
                    <div class="col-md-6">

                        <div class="pull-right">
                            <span id="new-briefs_des" class="sort glyphicon glyphicon-triangle-top" aria-hidden="true"></span>
                            <span id="new-briefs_asc" class="sort glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                        </div>
                        {{--<div class="pull-right">--}}
                            {{--<a style="padding: 0;"  class="sort" href="#"><span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span></a>--}}
                            {{--<a style="padding: 0;"  class="sort" href="#"><span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></a>--}}
                        {{--</div>--}}

                    </div>
                </div>
                <div class="col-md-5ths">
                    <div class="col-md-6">
                        <h4 class="col-heading">Targeting Grid</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <span id="targeting-grids_des" class="sort glyphicon glyphicon-triangle-top" aria-hidden="true"></span>
                            <span id="targeting-grids_asc"class="sort glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5ths">
                    <div class="col-md-6">
                        <h4 class="col-heading">Booking Form</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <span id="booking-forms_des" class="sort glyphicon glyphicon-triangle-top" aria-hidden="true"></span>
                            <span id="booking-forms_asc" class="sort glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5ths">
                    <div class="col-md-6">
                        <h4 class="col-heading">IO</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <span id="io_des" class="sort glyphicon glyphicon-triangle-top" aria-hidden="true"></span>
                            <span id="io_asc" class="sort glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5ths">
                    <div class="col-md-6">
                        <h4 class="col-heading">Creative Tags</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <span id="creative-tags_des" class="sort glyphicon glyphicon-triangle-top" aria-hidden="true"></span>
                            <span id="creative-tags_asc" class="sort glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
                </div>
            @endif
        </div>
        @endif


    </nav>
</header>