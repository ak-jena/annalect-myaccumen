@extends('app')
@section('title', 'Home')
{{--@section('subtitle','Dashboard')--}}
@section('content')

    @php
        // colours for status labels

        $status_colour = array(
            \App\Status::NEW_BRIEF => '#00BFFF',
            \App\Status::BRIEF_SUBMITTED => '#F781F3',
            \App\Status::TARGETING_GRID_UPLOADED => '#FE2EF7',
            \App\Status::TG_APPROVED_BY_LINE_MANAGER => '#DF01D7',
            \App\Status::TG_APPROVED_BY_HEAD_OF_ACTIVATION => '#8A0886',
            \App\Status::TG_REJECTED_BY_LINE_MANAGER => '#FF0040',
            \App\Status::TG_REJECTED_BY_HEAD_OF_ACTIVATION => '#FF0040',
            \App\Status::TG_REJECTED_BY_AGENCY_USER => '#FF0040',
            \App\Status::TARGETING_GRID_APPROVED => '#01DF01',
            \App\Status::BOOKING_FORM_SUBMITTED => '#04B404',
            \App\Status::BF_APPROVED_BY_ACT_TEAM => '#088A08',
            \App\Status::BF_REJECTED_BY_ACT_TEAM => '#FF0040',
            \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER => '#FF0040',
            \App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER => '#DF7401',
            \App\Status::ADDED_IO_HOST_LINKS => '#BF45F04',
            \App\Status::IO_UPLOADED => '#088A85',
            \App\Status::UPLOADED_CREATIVE_TAGS => '#0B615E',
            \App\Status::CAMPAIGN_CANCELLED => '#000000'
        )
    @endphp

    <div class="row">
        <div class="col-md-5ths">
            <div class="col-md-12">
                <h4>New Brief</h4>
            </div>
        </div>
        <div class="col-md-5ths">
            <div class="col-md-12">
                <h4>Targeting Grid</h4>
            </div>
        </div>
        <div class="col-md-5ths">
            <div class="col-md-12">
                <h4>Booking Form</h4>
            </div>
        </div>
        <div class="col-md-5ths">
            <div class="col-md-12">
                <h4>IO</h4>
            </div>
        </div>
        <div class="col-md-5ths">
            <div class="col-md-12">
                <h4>Creative Tags</h4>
            </div>
        </div>
    </div>

    <!-- Main row -->
    <div class="row">

    <div class="col-md-5ths">
        <div class="dashboard-column col-md-12">
            <h3></h3>
            @foreach ($new_brief_campaigns as $campaign)
                <div class="panel">
                    <div class="panel-body">
                        <a href="{{ route('workflow', ['campaign_id' => $campaign->id]) }}">
                            <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                        </a>

                        @if($campaign->brief->client->logo != null)
                            <img src="{{ Storage::disk('public')->url($campaign->brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                        @endif

                        <p><b>Campaign Name: </b> {{ $campaign->brief->campaign_name }}</p>
                        <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($campaign->brief->start_date)) }} - {{ date('d/m/Y', strtotime($campaign->brief->end_date)) }}</p>
                        @if($campaign->brief->client->logo == null)
                            <p><b>Client:</b> {{ $campaign->brief->client->name }}</p>
                        @endif

                        <p><b>Budget: </b>&pound;{{ number_format($campaign->brief->latestTotalBudget, 2, '.', ',') }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <a href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $campaign->brief->comments->count() }}</span></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-5ths">
        <div class="dashboard-column col-md-12">
            <h3></h3>
            @foreach ($targeting_grid_campaigns as $campaign)
                <div class="panel ">
                    <div class="panel-body ">
                        <a href="{{ route('workflow', ['campaign_id' => $campaign->id]) }}">
                            <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                        </a>

                        @if($campaign->brief->client->logo != null)
                            <img src="{{ Storage::disk('public')->url($campaign->brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                        @endif

                        <p><b>Campaign Name: </b> {{ $campaign->brief->campaign_name }}</p>
                        <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($campaign->brief->start_date)) }} - {{ date('d/m/Y', strtotime($campaign->brief->end_date)) }}</p>
                        @if($campaign->brief->client->logo == null)
                            <p><b>Client:</b> {{ $campaign->brief->client->name }}</p>
                        @endif

                        <p><b>Budget: </b>&pound;{{ number_format($campaign->brief->latestTotalBudget, 2, '.', ',') }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <a href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $campaign->brief->comments->count() }}</span></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-5ths">
        <div class="dashboard-column col-md-12">
            <h3></h3>
            @foreach ($booking_form_campaigns as $campaign)
                <div class="panel ">
                    <div class="panel-body ">
                        <a href="{{ route('workflow', ['campaign_id' => $campaign->id]) }}">
                            <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                        </a>

                        @if($campaign->brief->client->logo != null)
                            <img src="{{ Storage::disk('public')->url($campaign->brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                        @endif

                        <p><b>Campaign Name: </b> {{ $campaign->brief->campaign_name }}</p>
                        <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($campaign->brief->start_date)) }} - {{ date('d/m/Y', strtotime($campaign->brief->end_date)) }}</p>
                        @if($campaign->brief->client->logo == null)
                            <p><b>Client:</b> {{ $campaign->brief->client->name }}</p>
                        @endif

                        <p><b>Budget: </b>&pound;{{ number_format($campaign->brief->latestTotalBudget, 2, '.', ',') }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <a href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $campaign->brief->comments->count() }}</span></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-5ths">
        <div class="dashboard-column col-md-12">
            <h3></h3>
            @foreach ($io_campaigns as $campaign)
                <div class="panel ">
                    <div class="panel-body">
                        <a href="{{ route('workflow', ['campaign_id' => $campaign->id]) }}">
                            <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                        </a>

                        @if($campaign->brief->client->logo != null)
                            <img src="{{ Storage::disk('public')->url($campaign->brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                        @endif

                        <p><b>Campaign Name: </b> {{ $campaign->brief->campaign_name }}</p>
                        <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($campaign->brief->start_date)) }} - {{ date('d/m/Y', strtotime($campaign->brief->end_date)) }}</p>
                        @if($campaign->brief->client->logo == null)
                            <p><b>Client:</b> {{ $campaign->brief->client->name }}</p>
                        @endif

                        <p><b>Budget: </b>&pound;{{ number_format($campaign->brief->latestTotalBudget, 2, '.', ',') }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <a href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $campaign->brief->comments->count() }}</span></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-5ths">
        <div class="dashboard-column col-md-12">
            <h3></h3>
            @foreach ($creative_tags_campaigns as $campaign)
                <div class="panel ">
                    <div class="panel-body">
                        <a href="{{ route('workflow', ['campaign_id' => $campaign->id]) }}">
                            <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                        </a>

                        @if($campaign->brief->client->logo != null)
                            <img src="{{ Storage::disk('public')->url($campaign->brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                        @endif

                        <p><b>Campaign Name: </b> {{ $campaign->brief->campaign_name }}</p>
                        <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($campaign->brief->start_date)) }} - {{ date('d/m/Y', strtotime($campaign->brief->end_date)) }}</p>
                        @if($campaign->brief->client->logo == null)
                            <p><b>Client:</b> {{ $campaign->brief->client->name }}</p>
                        @endif

                        <p><b>Budget: </b>&pound;{{ number_format($campaign->brief->latestTotalBudget, 2, '.', ',') }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <a href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $campaign->brief->comments->count() }}</span></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    </div>
    <!-- /.row -->
@endsection