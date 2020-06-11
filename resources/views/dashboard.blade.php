@extends('app')
@section('title', 'Home')
{{--@section('subtitle','Dashboard')--}}
@section('content')
    <?php if(\Baselib::canCreateBrief()): ?>
        <a class="small-margin-bottom btn btn-default" href="{{ route('workflow') }}">Create New Campaign</a>
    <?php endif; ?>

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

                        <p><b>Budget: </b>&pound;{{ $campaign->totalBudget }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <span class="badge pull-right">0</span>
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

                        <p><b>Budget: </b>&pound;{{ $campaign->totalBudget }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <span class="badge pull-right">0</span>
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

                        <p><b>Budget: </b>&pound;{{ $campaign->totalBudget }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <span class="badge pull-right">0</span>
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

                        <p><b>Budget: </b>&pound;{{ $campaign->totalBudget }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <span class="badge pull-right">0</span>
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

                        <p><b>Budget: </b> &pound;{{ $campaign->totalBudget }}</p>

                        <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>
                        <span class="badge pull-right">0</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    </div>
    <!-- /.row -->
@endsection