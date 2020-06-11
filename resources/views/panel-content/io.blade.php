@if($campaign !== null && $booking_details !== null)

    @if(\Baselib::canCreateIo())
        <div class="panel panel-default">
            <div class="panel-body">
                {!! Form::open([
                    'route' => 'process-io',
                    'id' => 'io_form',
                    'files' => true,
                ]) !!}

                <div class="row vertical-align-center">
                    <div class="col-md-9">
                        <div class="alert alert-warning" role="alert">
                            Please note: uploading an IO file will overwrite the corresponding IO file for the DSP Budget (if it exists).
                        </div>
                    </div>
                    <div class="col-md-3">
                        @if ($campaign != null)
                            <div class="btn-toolbar">
                                {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                            </div>
                        @endif
                    </div>
                </div>
            @php
                $is_disabled_host_links = '';
                $is_disabled_io_dds_code = '';

                if(\Baselib::isAgencyUser()){
                    $is_disabled_io_dds_code = ', disabled';
                }elseif (\Baselib::isActivationUser() || \Baselib::isActivationLineManager()){
                    $is_disabled_host_links = ', disabled';
                }

            @endphp
            @foreach($booking_details as $booking)
                    <legend>{{ $booking->product->name }}</legend>


                    @foreach($booking->dspBudgets as $dsp_budget)

                        <div class="row">
                            <div class="form-group col-sm-2">
                                <h4>{{ $dsp_budget->dsp->dsp_name }} </h4>
                            </div>
                            <div class="form-group col-sm-2">
                                {!! Form::label('dsp_budget['.$dsp_budget->id.'][host_links]', 'Host Links', array('class' => 'control-label')) !!}
                                {!! Form::text('dsp_budget['.$dsp_budget->id.'][host_links]', $dsp_budget->io_host_links, array('id' => 'dsp_budget_'.$dsp_budget->id.'_host_links', 'class' => 'form-control', $is_disabled_host_links)) !!}
                            </div>

                            <div class="form-group col-sm-6">

                                <div id="existing-file-{{ $dsp_budget->id }}" style="@if($dsp_budget->io_file_name == null) display: none; @endif">
                                    <p><a target="_blank" href="{{ Storage::disk('public')->url($dsp_budget->io_location) }}">{{ $dsp_budget->io_file_name }}</a></p>
                                    <p><a href="#" id="edit-file-{{ $dsp_budget->id }}" class="edit-file">Edit</a></p>

                                </div>

                                <div id="upload-file-{{ $dsp_budget->id }}" style="@if($dsp_budget->io_file_name !== null) display: none; @endif">
                                    {!! Form::label('dsp_budget['.$dsp_budget->id.'][io_file]', 'IO File', array('class' => 'control-label')) !!}
                                    {!! Form::file('dsp_budget['.$dsp_budget->id.'][io_file]', array('id' =>'dsp_budget_'.$dsp_budget->id.'_io_file', 'class' => 'file form-control', $is_disabled_io_dds_code, 'data-show-upload' => 'false', 'data-show-preview' => 'false')) !!}
                                    @if($dsp_budget->io_file_name !== null) <p><a href="#" id="cancel-file-{{ $dsp_budget->id }}" class="cancel-file">Cancel</a></p> @endif
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                {!! Form::label('dsp_budget['.$dsp_budget->id.'][dds_code]', 'DDS Code', array('class' => 'control-label')) !!}
                                {!! Form::text('dsp_budget['.$dsp_budget->id.'][dds_code]', $dsp_budget->dds_code, array('id' => 'dsp_budget_'.$dsp_budget->id.'_dds_code', 'class' => 'form-control', $is_disabled_io_dds_code)) !!}
                            </div>
                        </div>
                    @endforeach

                    <input type="hidden" id="campaign_id" name="campaign_id" value="{{ $campaign->id }}">

                @endforeach
                <div class="box-footer">
                    @if(in_array($status_id, array(\App\Status::IO_UPLOADED, \App\Status::UPLOADED_CREATIVE_TAGS, \App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER, \App\Status::ADDED_IO_HOST_LINKS, \App\Status::CAMPAIGN_LIVE )))
                        @if(\Baselib::isAgencyUser())
                            <button type="submit" id="save-links" class="btn btn-default" value="save-links">
                                <i class="fa fa-plus"></i> Save IO Host Links
                            </button>
                        @elseif (\Baselib::isActivationUser()|| \Baselib::isActivationLineManager())
                            <button type="submit" class="btn btn-default" value="save-ddscode-io">
                                <i class="fa fa-plus"></i> Save DDS Codes and IO Files
                            </button>
                        @elseif (\Baselib::isVodUser())
                            <button type="submit" id="save-links" class="btn btn-default" value="save-links">
                                <i class="fa fa-plus"></i> Save IO Host Links
                            </button>
                            <button type="submit" class="btn btn-default" value="save-ddscode-io">
                                <i class="fa fa-plus"></i> Save DDS Codes and IO Files
                            </button>
                        @endif
                    @endif
                    @if(in_array($status_id, array(\App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER, \App\Status::ADDED_IO_HOST_LINKS)))

                            <button id="submit-io" @if($campaign->ioDdsCodesFilesComplete == false) style="display:none;" @endif  value="submit-io" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                <b>Submit IO</b>
                            </button>
                    @endif

                    <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
                </div>

            </div>
        </div>
        {!! Form::close() !!}
    @endif
@endif