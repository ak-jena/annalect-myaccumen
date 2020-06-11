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

@foreach ($completed_briefs as $brief)
    <div class="col-md-5ths">
        <div class="panel">
            <div class="panel-body">
                <a href="{{ route('workflow', ['campaign_id' => $brief->campaign->id]) }}">
                    <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                </a>

                @if($brief->client->logo != null)
                    <img src="{{ Storage::disk('public')->url($brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                @endif

                <p><b>Campaign Name: </b> {{ $brief->campaign_name }}</p>
                <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($brief->start_date)) }} - {{ date('d/m/Y', strtotime($brief->end_date)) }}</p>
                @if($brief->client->logo == null)
                    <p><b>Client:</b> {{ $brief->client->name }}</p>
                @endif

                <p><b>Budget: </b>&pound;{{ number_format($brief->latestTotalBudget, 2, '.', ',') }}</p>

                {{--<p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$campaign->status->id] }}">{{ $campaign->status->name }}</span></p>--}}
                <a href="{{ route('comments', ['brief_id'=>$brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $brief->comments->count() }}</span></a>
            </div>
        </div>
    </div>
@endforeach

