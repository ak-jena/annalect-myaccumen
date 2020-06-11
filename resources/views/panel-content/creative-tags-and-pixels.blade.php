@if($campaign !== null)
    @if(\Baselib::canCreateTags())
        <div class="row vertical-align-center">
            <div class="col-md-6">
                <h3 class="med-margin-bottom">Creative Tags Upload</h3>
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

            {!! Form::open([
                'route' => 'process-tags',
                'class' => 'form-horizontal',
                'id'    => 'tags_upload_form',
                'files' => true
            ]) !!}

            <div class="form-group">
                {!! Form::label('creative_tag[1]', 'Creative Tag 1', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-6">
                    {!! Form::file('creative_tag[1]', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('creative_tag[2]', 'Creative Tag 2', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-6">
                    {!! Form::file('creative_tag[2]', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('creative_tag[3]', 'Creative Tag 3', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-6">
                    {!! Form::file('creative_tag[3]', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('creative_tag[4]', 'Creative Tag 4', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-6">
                    {!! Form::file('creative_tag[4]', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('fileshare_links', 'Fileshare Links', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-6">
                    {!! Form::textarea('fileshare_links', $campaign->brief->ct_file_share_links, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-sm-offset-3">
                    {!! Form::checkbox('declaration', 1, null, array('id' => 'declaration')) !!} I declare the information submitted is correct and hereby confirm the usage of the creative tags
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('pixel_info', 'Pixel Information', array('class' => 'col-sm-3 control-label')) !!}
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    {!! Form::file('pixel_info_1', null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    {!! Form::textarea('pixel_info_2', $campaign->brief->ct_pixel_info, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                </div>
            </div>

            <input type="hidden" name="campaign_id" value="{{ $campaign_id }}">

            <!-- Add Buttons -->

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    @if($status_id >= \App\Status::BOOKING_FORM_SUBMITTED && $status_id < \App\Status::UPLOADED_CREATIVE_TAGS)
                        <button id="tag-save" type="submit" value="save-ct" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                            Save Creative Tags
                        </button>
                    @endif

                    @if(in_array($status_id, array(\App\Status::IO_UPLOADED)))
                        <button id="tag-submit" disabled="disabled" type="submit" value="submit-campaign" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                            Submit Campaign
                        </button>
                    @endif
                </div>
            </div>

        {!! Form::close() !!}
    @endif

    @if($campaign->tags !== null)
        <div class="row">
            <div class="col-md-6">
                <h3 class="med-margin-bottom">Existing Creative tags</h3>

                @foreach ($campaign->tags as $tag)
                    <p><a target="_blank" href="{{ Storage::disk('public')->url($tag->location) }}">{{ $tag->file_name }}</a></p>
                @endforeach

            </div>

        </div>
    @endif
@endif