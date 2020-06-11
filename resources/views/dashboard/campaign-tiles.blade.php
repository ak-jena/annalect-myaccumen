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
        \App\Status::UPLOADED_CREATIVE_TAGS => '#0B615E'
    );

    $next_status = [
        \App\Status::NEW_BRIEF => [
            'next_status'   => 'Agency to Submit Brief',
            'colour'        => '#696969'
            ],

        \App\Status::BRIEF_SUBMITTED => [
            'next_status'   => 'Activation To Respond with TG',
            'colour'        => '#C0C0C0'
            ],

        \App\Status::TARGETING_GRID_UPLOADED => [
            'next_status'   => 'Activation Line Manager To Approve',
            'colour'        => '#C0C0C0'
            ],

        \App\Status::TG_APPROVED_BY_LINE_MANAGER => [
            'next_status'   => 'Agency To Approve TG',
            'colour'        => '#696969'
            ],

        \App\Status::TG_APPROVED_BY_HEAD_OF_ACTIVATION => [
            'next_status'   => 'Agency To Approve TG',
            'colour'        => '#696969'
            ],

        \App\Status::TG_REJECTED_BY_LINE_MANAGER => [
             'next_status'  => 'Activation Team To change TG',
             'colour'       => '#C0C0C0'
            ],

        \App\Status::TG_REJECTED_BY_HEAD_OF_ACTIVATION => [
            'next_status'  => 'Activation Team To change TG',
            'colour'        => '#C0C0C0'
            ],

        \App\Status::TG_REJECTED_BY_AGENCY_USER => [
            'next_status'  => 'Activation Team To change TG',
            'colour'        => '#C0C0C0'
            ],

        \App\Status::TARGETING_GRID_APPROVED => [
            'next_status'   => 'Agency To Complete BF',
            'colour'        => '#696969'
            ],

        \App\Status::BOOKING_FORM_SUBMITTED => [
            'next_status'   => 'Activation Team To Approve BF',
            'colour'        => '#C0C0C0'
            ],

        \App\Status::BF_APPROVED_BY_ACT_TEAM => [
            'next_status'   => 'Activation Line Manager To Approve',
            'colour'        => '#C0C0C0'
            ],
        \App\Status::BF_REJECTED_BY_ACT_TEAM => [
            'next_status'   => 'Agency To Resubmit BF',
            'colour'        => '#696969'
        ],
        \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER => [
            'next_status'   => 'Agency To Resubmit BF',
            'colour'        => '#696969'
        ],
        \App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER => [
            'next_status'   => 'Submit IO Info',
            'colour'        => '#696969'
        ],
        \App\Status::ADDED_IO_HOST_LINKS => [
            'next_status'   => 'Activation Team To upload IO',
            'colour'        => '#C0C0C0'
        ],
        \App\Status::IO_UPLOADED => [
            'next_status'   => 'Agency To Upload Creative Tags',
            'colour'        => '#696969'
        ],
        \App\Status::UPLOADED_CREATIVE_TAGS => [
            'next_status'   => 'Ready To Go LIVE ',
            'colour'        => '#7B68EE'
        ]
    ];

    $current_datetime = new DateTimeImmutable();
    //dump($current_datetime);
@endphp


<div class="col-md-5ths">
    <div class="dashboard-column col-md-12" id="new-briefs">
        <h3></h3>
        @foreach ($new_brief_campaigns as $brief)
            @php
                $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();
            @endphp

            <div class="panel" data-date="{{ $brief->start_date }}T00:00:00.000Z">
                <div class="panel-body">
                    <a href="{{ route('workflow', ['campaign_id' => $brief->campaign->id]) }}">
                        <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                    </a>

                    @if($brief->client->logo != null)
                        <img src="{{ Storage::disk('public')->url($brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                    @endif

                    @php
                        $tooltip = false;

                        // split by space
                        $campaign_name_array = explode(' ', $brief->campaign_name);

                        // check length of characters in first word
                        if(strlen($campaign_name_array[0]) > 23){
                            $tooltip                    = true;
                            $truncated_campaign_name    = substr($campaign_name_array[0], 0, 30);
                        }
                    @endphp

                    <p>
                        <b>Campaign Name: </b>
                        @if($tooltip)
                            {{ $truncated_campaign_name }}
                            <span class="text-primary" data-toggle="popover" data-html="true" data-trigger="hover" data-container="body" data-content="<span style='word-wrap: break-word;'>{{ $brief->campaign_name}}</span> ">&hellip;</span>
                        @else
                            {{ $brief->campaign_name }}
                        @endif
                    </p>
                    <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($brief->start_date)) }} - {{ date('d/m/Y', strtotime($brief->end_date)) }}</p>
                    @if($brief->client->logo == null)
                        <p><b>Client:</b> {{ $brief->client->name }}</p>
                    @endif

                    <p><b>Budget: </b>&pound;{{ number_format($brief->latestTotalBudget, 2, '.', ',') }}</p>


                    <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$latest_log->status->id] }}">{{ $latest_log->status->name }}</span></p>
                    <p><b>Next Steps:</b> <span class="label" style="background-color: {{ $next_status[$latest_log->status->id]['colour'] }}">{{ $next_status[$latest_log->status->id]['next_status'] }}</span></p>

                    <a href="{{ route('comments', ['brief_id'=>$brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $brief->comments_count }}</span></a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="col-md-5ths">
    <div class="dashboard-column col-md-12" id="targeting-grids">
        <h3></h3>
        @foreach ($targeting_grid_campaigns as $brief)

            @php
                $latest_log             = $brief->campaign->logs->sortByDesc('created_at')->first();
                $campaign_start_date    = new \DateTimeImmutable(date('Y-m-d', strtotime($brief->start_date)));

                $three_days_to_start_date   = $campaign_start_date->sub(new \DateInterval("P3D"));
                $five_days_to_start_date    = $campaign_start_date->sub(new \DateInterval("P5D"));

                $difference_campaign_start_days     = (int)$current_datetime->diff($campaign_start_date)->format('%R%a');
                $difference_in_days_to_three_days   = (int)$current_datetime->diff($three_days_to_start_date)->format('%R%a');
                $difference_in_days_to_five_days    = (int)$current_datetime->diff($five_days_to_start_date)->format('%R%a');
            @endphp

            <div class="
                @if($brief->hasTargetingGrid($latest_log) == false)
                    @if(($difference_in_days_to_five_days >= -2) && ($difference_in_days_to_five_days <= 0))
                        orange-glow
                    @elseif(($difference_in_days_to_three_days >= -3) && ($difference_in_days_to_three_days <= 0))
                        red-glow
                    @elseif($difference_campaign_start_days <= 0)
                        due
                    @endif
                @endif panel"data-date="{{ $brief->start_date }}T00:00:00.000Z">
                <div class="panel-body ">
                    <a href="{{ route('workflow', ['campaign_id' => $brief->campaign->id]) }}">
                        <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                    </a>

                    @if($brief->client->logo != null)
                        <img src="{{ Storage::disk('public')->url($brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                    @endif

                    @php
                        $tooltip = false;

                        // split by space
                        $campaign_name_array = explode(' ', $brief->campaign_name);

                        // check length of characters in first word
                        if(strlen($campaign_name_array[0]) > 23){
                            $tooltip                    = true;
                            $truncated_campaign_name    = substr($campaign_name_array[0], 0, 30);
                        }
                    @endphp

                    <p>
                        <b>Campaign Name: </b>
                        @if($tooltip)
                            {{ $truncated_campaign_name }}
                            <span class="text-primary" data-toggle="popover" data-html="true" data-trigger="hover" data-container="body" data-content="<span style='word-wrap: break-word;'>{{ $brief->campaign_name}}</span> ">&hellip;</span>
                        @else
                            {{ $brief->campaign_name }}
                        @endif
                    </p>
                    <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($brief->start_date)) }} - {{ date('d/m/Y', strtotime($brief->end_date)) }}</p>
                    @if($brief->client->logo == null)
                        <p><b>Client:</b> {{ $brief->client->name }}</p>
                    @endif

                    <p><b>Budget: </b>&pound;{{ number_format($brief->latestTotalBudget, 2, '.', ',') }}</p>

                    @php
                        $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();
                    @endphp

                    <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$latest_log->status->id] }}">{{ $latest_log->status->name }}</span></p>
                    @if($latest_log->status->id == \App\Status::TG_APPROVED_BY_LINE_MANAGER)
                        @if($brief->campaign->requiresHeadOfActivationApproval())
                            <p><b>Next Steps:</b> <span class="label" style="background-color: #C0C0C0;">Head of Activation to Approve TG</span></p>
                        @else
                            <p><b>Next Steps:</b> <span class="label" style="background-color: {{ $next_status[$latest_log->status->id]['colour'] }}">{{ $next_status[$latest_log->status->id]['next_status'] }}</span></p>
                        @endif
                    @else
                        <p><b>Next Steps:</b> <span class="label" style="background-color: {{ $next_status[$latest_log->status->id]['colour'] }}">{{ $next_status[$latest_log->status->id]['next_status'] }}</span></p>
                    @endif

                    <a href="{{ route('export-brief', ['brief_id'=>$brief->id]) }}"><span class="glyphicon glyphicon-download-alt pull-left"></span></a>

                    <a href="{{ route('comments', ['brief_id'=>$brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $brief->comments_count }}</span></a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="col-md-5ths">
    <div class="dashboard-column col-md-12" id="booking-forms">
        <h3></h3>
        @foreach ($booking_form_campaigns as $brief)
            @php
                $latest_log             = $brief->campaign->logs->sortByDesc('created_at')->first();
                $campaign_start_date    = new \DateTimeImmutable(date('Y-m-d', strtotime($brief->start_date)));

                $three_days_to_start_date   = $campaign_start_date->sub(new \DateInterval("P3D"));
                $five_days_to_start_date    = $campaign_start_date->sub(new \DateInterval("P5D"));

                $difference_campaign_start_days     = (int)$current_datetime->diff($campaign_start_date)->format('%R%a');
                $difference_in_days_to_three_days   = (int)$current_datetime->diff($three_days_to_start_date)->format('%R%a');
                $difference_in_days_to_five_days    = (int)$current_datetime->diff($five_days_to_start_date)->format('%R%a');
            @endphp

            <div class="
                @if($brief->hasBookingForm($latest_log) == false)
                    @if(($difference_in_days_to_five_days >= -2) && ($difference_in_days_to_five_days <= 0))
                        orange-glow
                    @elseif(($difference_in_days_to_three_days >= -3) && ($difference_in_days_to_three_days <= 0))
                        red-glow
                    @elseif($difference_campaign_start_days <= 0)
                        due
                    @endif
                @endif
                panel" data-date="{{ $brief->start_date }}T00:00:00.000Z">
                <div class="panel-body ">
                    <a href="{{ route('workflow', ['campaign_id' => $brief->campaign->id]) }}">
                        <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                    </a>

                    @if($brief->client->logo != null)
                        <img src="{{ Storage::disk('public')->url($brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                    @endif

                    @php
                        $tooltip = false;

                        // split by space
                        $campaign_name_array = explode(' ', $brief->campaign_name);

                        // check length of characters in first word
                        if(strlen($campaign_name_array[0]) > 23){
                            $tooltip                    = true;
                            $truncated_campaign_name    = substr($campaign_name_array[0], 0, 30);
                        }
                    @endphp

                    <p>
                        <b>Campaign Name: </b>
                        @if($tooltip)
                            {{ $truncated_campaign_name }}
                            <span class="text-primary" data-toggle="popover" data-html="true" data-trigger="hover" data-container="body" data-content="<span style='word-wrap: break-word;'>{{ $brief->campaign_name}}</span> ">&hellip;</span>
                        @else
                            {{ $brief->campaign_name }}
                        @endif
                    </p>
                    <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($brief->start_date)) }} - {{ date('d/m/Y', strtotime($brief->end_date)) }}</p>
                    @if($brief->client->logo == null)
                        <p><b>Client:</b> {{ $brief->client->name }}</p>
                    @endif

                    <p><b>Budget: </b>&pound;{{ number_format($brief->latestTotalBudget, 2, '.', ',') }}</p>

                    @php
                        $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();
                    @endphp
                    <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$latest_log->status->id] }}">{{ $latest_log->status->name }}</span></p>
                    <p><b>Next Steps:</b> <span class="label" style="background-color: {{ $next_status[$latest_log->status->id]['colour'] }}">{{ $next_status[$latest_log->status->id]['next_status'] }}</span></p>

                    @if($latest_log->status->id < \App\Status::BOOKING_FORM_SUBMITTED)
                        <a href="{{ route('export-brief', ['brief_id'=>$brief->id]) }}"><span class="glyphicon glyphicon-download-alt pull-left"></span></a>
                    @elseif($latest_log->status->id >= \App\Status::BOOKING_FORM_SUBMITTED)
                        <a href="{{ route('export-booking', ['brief_id'=>$brief->id])}}"><span class="glyphicon glyphicon-download-alt pull-left"></span></a>
                    @endif

                    <a href="{{ route('comments', ['brief_id'=>$brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $brief->comments_count }}</span></a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="col-md-5ths">
    <div class="dashboard-column col-md-12"  id="io">
        <h3></h3>
        @foreach ($io_campaigns as $brief)

            @php
                $latest_log             = $brief->campaign->logs->sortByDesc('created_at')->first();
                $campaign_start_date    = new \DateTimeImmutable(date('Y-m-d', strtotime($brief->start_date)));

                $three_days_to_start_date   = $campaign_start_date->sub(new \DateInterval("P3D"));
                $five_days_to_start_date    = $campaign_start_date->sub(new \DateInterval("P5D"));

                $difference_campaign_start_days     = (int)$current_datetime->diff($campaign_start_date)->format('%R%a');
                $difference_in_days_to_three_days  = (int)$current_datetime->diff($three_days_to_start_date)->format('%R%a');
                $difference_in_days_to_five_days   = (int)$current_datetime->diff($five_days_to_start_date)->format('%R%a');
            @endphp

            <div class="
                @if($brief->hasDdsCodes($latest_log) == false)
                    @if(($difference_in_days_to_five_days >= -2) && ($difference_in_days_to_five_days <= 0))
                        orange-glow
                    @elseif(($difference_in_days_to_three_days >= -3) && ($difference_in_days_to_three_days <= 0))
                        red-glow
                    @elseif($difference_campaign_start_days <= 0)
                        due
                    @endif
                @endif
                    panel" data-date="{{ $brief->start_date }}T00:00:00.000Z">
                <div class="panel-body">
                    <a href="{{ route('workflow', ['campaign_id' => $brief->campaign->id]) }}">
                        <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                    </a>

                    @if($brief->client->logo != null)
                        <img src="{{ Storage::disk('public')->url($brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                    @endif

                    @php
                        $tooltip = false;

                        // split by space
                        $campaign_name_array = explode(' ', $brief->campaign_name);

                        // check length of characters in first word
                        if(strlen($campaign_name_array[0]) > 23){
                            $tooltip                    = true;
                            $truncated_campaign_name    = substr($campaign_name_array[0], 0, 30);
                        }
                    @endphp

                    <p>
                        <b>Campaign Name: </b>
                        @if($tooltip)
                            {{ $truncated_campaign_name }}
                            <span class="text-primary" data-toggle="popover" data-html="true" data-trigger="hover" data-container="body" data-content="<span style='word-wrap: break-word;'>{{ $brief->campaign_name}}</span> ">&hellip;</span>
                        @else
                            {{ $brief->campaign_name }}
                        @endif
                    </p>
                    <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($brief->start_date)) }} - {{ date('d/m/Y', strtotime($brief->end_date)) }}</p>
                    @if($brief->client->logo == null)
                        <p><b>Client:</b> {{ $brief->client->name }}</p>
                    @endif

                    <p><b>Budget: </b>&pound;{{ number_format($brief->latestTotalBudget, 2, '.', ',') }}</p>

                    @php
                        $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();
                    @endphp

                    <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$latest_log->status->id] }}">{{ $latest_log->status->name }}</span></p>
                    <p><b>Next Steps:</b> <span class="label" style="background-color: {{ $next_status[$latest_log->status->id]['colour'] }}">{{ $next_status[$latest_log->status->id]['next_status'] }}</span></p>

                    <a href="{{ route('export-booking', ['brief_id'=>$brief->id])}}"><span class="glyphicon glyphicon-download-alt pull-left"></span></a>

                    <a href="{{ route('comments', ['brief_id'=>$brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $brief->comments_count }}</span></a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="col-md-5ths">
    <div class="dashboard-column col-md-12"  id="creative-tags">
        <h3></h3>
        @foreach ($creative_tags_campaigns as $brief)
            <div class="panel" data-date="{{ $brief->start_date }}T00:00:00.000Z">
                <div class="panel-body">
                    <a href="{{ route('workflow', ['campaign_id' => $brief->campaign->id]) }}">
                        <span class="glyphicon glyphicon-pencil pull-right" aria-hidden="true"></span>
                    </a>

                    @if($brief->client->logo != null)
                        <img src="{{ Storage::disk('public')->url($brief->client->logo) }}"  style="display:block; margin:auto; height:75px; margin-bottom: 15px;"/>
                    @endif

                    @php
                        $tooltip = false;

                        // split by space
                        $campaign_name_array = explode(' ', $brief->campaign_name);

                        // check length of characters in first word
                        if(strlen($campaign_name_array[0]) > 23){
                            $tooltip                    = true;
                            $truncated_campaign_name    = substr($campaign_name_array[0], 0, 30);
                        }
                    @endphp

                    <p>
                        <b>Campaign Name: </b>
                        @if($tooltip)
                            {{ $truncated_campaign_name }}
                            <span class="text-primary" data-toggle="popover" data-html="true" data-trigger="hover" data-container="body" data-content="<span style='word-wrap: break-word;'>{{ $brief->campaign_name}}</span> ">&hellip;</span>
                        @else
                            {{ $brief->campaign_name }}
                        @endif
                    </p>
                    <p><b>Date Range: </b> {{ date('d/m/Y', strtotime($brief->start_date)) }} - {{ date('d/m/Y', strtotime($brief->end_date)) }}</p>
                    @if($brief->client->logo == null)
                        <p><b>Client:</b> {{ $brief->client->name }}</p>
                    @endif

                    <p><b>Budget: </b>&pound;{{ number_format($brief->latestTotalBudget, 2, '.', ',') }}</p>

                    @php
                        $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();
                    @endphp

                    <p><b>Status:</b> <span class="label" style="background-color: {{ $status_colour[$latest_log->status->id] }}">{{ $latest_log->status->name }}</span></p>
                    <p><b>Next Steps:</b> <span class="label" style="background-color: {{ $next_status[$latest_log->status->id]['colour'] }}">{{ $next_status[$latest_log->status->id]['next_status'] }}</span></p>

                    <a href="{{ route('export-booking', ['brief_id'=>$brief->id])}}"><span class="glyphicon glyphicon-download-alt pull-left"></span></a>

                    <a href="{{ route('comments', ['brief_id'=>$brief->id, 'redirect' => 'dashboard'])}}"><span class="badge pull-right">{{ $brief->comments_count }}</span></a>
                </div>
            </div>
        @endforeach
    </div>
</div>

