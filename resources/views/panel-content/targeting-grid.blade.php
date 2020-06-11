@if($campaign !== null)
    @php
        $existing_grids = $campaign->grids;
        $existing_grids_arr = $campaign->grids->toArray();

        $number_of_grid_fields = array(0,1,2,3);

    @endphp

    <div class="row vertical-align-center">
        <div class="col-md-6">
            <h3 class="med-margin-bottom">Targeting Grid(s) Upload</h3>
        </div>
        <div class="col-md-6">
            @if ($campaign != null)
                <div class="btn-toolbar">
                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                </div>
            @endif
        </div>
    </div>

    <div class="alert alert-danger alert-dismissible" id="grid-errors-alert" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-ban"></i> Error detected:</h4>
    </div>

    {!! Form::open([
        'route' => 'process-grid',
        'class' => 'form-horizontal',
        'id'    => 'grid_upload_form',
        'files' => true
    ]) !!}

        @foreach($number_of_grid_fields as $field_number)
            <div class="form-group">
                @php $upload_visiblity = ''; @endphp
                @if(array_key_exists($field_number, $existing_grids_arr))
                    @php $upload_visiblity = 'display: none;'; @endphp
                    <div class="col-md-offset-3 col-sm-6" id="existing-grid-{{ $field_number }}">
                        <p><a target="_blank" href="{{ Storage::disk('public')->url($existing_grids_arr[$field_number]['location']) }}">{{ $existing_grids_arr[$field_number]['file_name'] }}</a></p>

                        @if(\Baselib::canUploadGrid())
                            @if(in_array($status_id, array(\App\Status::TG_REJECTED_BY_AGENCY_USER, \App\Status::TG_REJECTED_BY_HEAD_OF_ACTIVATION, \App\Status::TG_REJECTED_BY_LINE_MANAGER)))
                                <p><a href="#" id="edit-grid-{{ $field_number }}" class="edit-grid">Edit</a></p>
                            @endif
                        @endif
                    </div>
                @endif

                <div id="upload-grid-{{ $field_number }}" style="{{ $upload_visiblity }}">

                    @php
                        $grid_number = $field_number+1;
                        $label = 'Targeting Grid '.$grid_number;
                    @endphp
                    {!! Form::label('targeting_grid[]', $label, array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-6">
                        {!! Form::file('targeting_grid[]', array('class' => 'file form-control', 'data-show-upload' => 'false', 'data-show-preview' => 'false')) !!}
                    </div>
                    @if(array_key_exists($field_number, $existing_grids_arr)) <p><a href="#" id="cancel-grid-{{$field_number}}" class="cancel-grid">Cancel</a></p> @endif
                </div>

            </div>
        @endforeach


        <input type="hidden" name="campaign_id" value="{{ $campaign_id }}">

        <!-- Add Buttons -->
        <div class="form-group">
            @if(in_array($status_id, array(\App\Status::BRIEF_SUBMITTED, \App\Status::TG_REJECTED_BY_AGENCY_USER, \App\Status::TG_REJECTED_BY_HEAD_OF_ACTIVATION, \App\Status::TG_REJECTED_BY_LINE_MANAGER)))
                @if(\Baselib::canUploadGrid())
                    <div class="col-sm-offset-3 col-sm-9">
                        <button id="grid-submit" disabled="disabled" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                        Save
                        </button>
                    </div>
                @endif
            @endif
        </div>

    {!! Form::close() !!}


    {!! Form::open([
        'route' => 'grid-approval',
        'class' => 'form-horizontal',
        'id'    => 'grid_approval_form',
    ]) !!}
        <div class="col-sm-offset-1 col-sm-11">
            <input type="hidden" name="campaign_id" value="{{ $campaign_id }}">

            {{--if status >= uploaded--}}
            @if($status_id >= \App\Status::TARGETING_GRID_UPLOADED)

                    {{--if status == uploaded--}}
                @if($status_id == \App\Status::TARGETING_GRID_UPLOADED)
                    {{--then show approve and reject buttons for activation line manager--}}
                    @if(\Baselib::isActivationLineManager() || \Baselib::isVodUser())
                        <button type="submit" class="btn btn-default" name="targeting-grid" value="lm-approve-grid">
                            <i class="fa fa-plus"></i> Approve Targeting Grid (LM)
                        </button>

                        <a class="btn btn-default" href="{{ route('reject-tg', ['campaign_id' => $campaign->id]) }}" role="button">Reject Targeting Grid</a>
                        {{--<button type="submit" class="btn btn-default" name="targeting-grid" value="lm-reject-grid">--}}
                            {{--<i class="fa fa-minus"></i> Reject Targeting Grid (LM)--}}
                        {{--</button>--}}
                    @elseif(\Baselib::isHeadOfActivation()) {{--HoA can upload and approve straightaway--}}
                        <button type="submit" class="btn btn-default" name="targeting-grid" value="hoa-approve-grid">
                            <i class="fa fa-plus"></i> Approve Targeting Grid (HoA)
                        </button>

                        <a class="btn btn-default" href="{{ route('reject-tg', ['campaign_id' => $campaign->id]) }}" role="button">Reject Targeting Grid</a>
                        {{--<button type="submit" class="btn btn-default" name="targeting-grid" value="hoa-reject-grid">--}}
                            {{--<i class="fa fa-minus"></i> Reject Targeting Grid (HoA)--}}
                        {{--</button>--}}
                    @endif

                {{--else if status == line manager has approved it--}}
                @elseif($status_id == \App\Status::TG_APPROVED_BY_LINE_MANAGER)

                    {{-- check if HoA approval is required --}}

                    @if($campaign->requiresHeadOfActivationApproval())
                        @if(\Baselib::isHeadOfActivation() || \Baselib::isVodUser())
                            {{--show hoa approval and reject buttons--}}
                            <button type="submit" class="btn btn-default" name="targeting-grid" value="hoa-approve-grid">
                                <i class="fa fa-plus"></i> Approve Targeting Grid (HoA)
                            </button>

                            <a class="btn btn-default" href="{{ route('reject-tg', ['campaign_id' => $campaign->id]) }}" role="button">Reject Targeting Grid</a>
                            {{--<button type="submit" class="btn btn-default" name="targeting-grid" value="hoa-reject-grid">--}}
                                {{--<i class="fa fa-minus"></i> Reject Targeting Grid (HoA)--}}
                            {{--</button>--}}
                        @endif
                    @else
                        @if(\Baselib::isAgencyUser())
                            <button type="submit" class="btn btn-default" name="targeting-grid" value="agency-approve-grid">
                                <i class="fa fa-plus"></i> Approve Targeting Grid (A)
                            </button>

                            <a class="btn btn-default" href="{{ route('reject-tg', ['campaign_id' => $campaign->id]) }}" role="button">Reject Targeting Grid</a>
                            {{--<button type="submit" class="btn btn-default" name="targeting-grid" value="agency-reject-grid">--}}
                                {{--<i class="fa fa-minus"></i> Reject Targeting Grid (A)--}}
                            {{--</button>--}}
                        @endif
                    @endif

                @endif

                {{-- if status == line manager approved || status == hoa approved--}}
                @if(in_array($status_id, array(\App\Status::TG_APPROVED_BY_HEAD_OF_ACTIVATION)))
                    {{--show agency user approval button--}}
                    @if(\Baselib::isAgencyUser())
                        <button type="submit" class="btn btn-default" name="targeting-grid" value="agency-approve-grid">
                            <i class="fa fa-plus"></i> Approve Targeting Grid (A)
                        </button>

                        <a class="btn btn-default" href="{{ route('reject-tg', ['campaign_id' => $campaign->id]) }}" role="button">Reject Targeting Grid</a>
                        {{--<button type="submit" class="btn btn-default" name="targeting-grid" value="agency-reject-grid">--}}
                            {{--<i class="fa fa-minus"></i> Reject Targeting Grid (A)--}}
                        {{--</button>--}}
                    @endif
                @endif
            @endif

        </div>
    {!! Form::close() !!}


@endif