@extends('app')
@section('title', 'Home')
@section('subtitle','Targeting Grid POC')
@section('content')

<script type="text/javascript">
    // ajax URL
    var load_targeting_grid_data_url = '{{ route('retrieve-grid-data') }}';

</script>

<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a id="rich-media" href="#rich-media-content" aria-controls="home" role="tab" data-toggle="tab">Rich Media</a></li>
        <li role="presentation"><a id="mobile" href="#mobile-content" aria-controls=
            "profile" role="tab" data-toggle="tab">Mobile</a></li>
        <li role="presentation"><a id="display" href="#display-content" aria-controls="messages" role="tab" data-toggle="tab">Display</a></li>
        <li role="presentation"><a id="audio" href="#audio-content" aria-controls="settings" role="tab" data-toggle="tab">Audio</a></li>
        <li role="presentation"><a id="vod" href="#vod-content" aria-controls="settings" role="tab" data-toggle="tab">VOD</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="rich-media-content">
            <form class="margin-top-standard">
                    <p>
                        <label for="budget">Budget</label> <input type="number" min="1" step="any" name="budget" id="budget" value=50.50>
                    </p>
            </form>

            <div id="rich-media-targeting-grid" class="margin-top-standard"></div>

            <div class="row">
                <div class="col-md-2">
                    <button data-product="rich-media" class="add-row btn btn-default btn-sm" type="button">
                        Add row
                    </button>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="mobile-content">
            <div id="mobile-targeting-grid" class="margin-top-standard"></div>

            <div class="row">
                <div class="col-md-2">
                    <button data-product="mobile" class="add-row btn-default btn-sm" type="button">
                        Add row
                    </button>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="display-content" >
            <div id="display-targeting-grid" class="margin-top-standard"></div>

            <div class="row">
                <div class="col-md-2">
                    <button data-product="display" class="add-row btn btn-default btn-sm" type="button">
                        Add row
                    </button>
                </div>
            </div>

        </div>
        <div role="tabpanel" class="tab-pane fade" id="audio-content">
            <div id="audio-targeting-grid" class="margin-top-standard"></div>

            <div class="row">
                <div class="col-md-2">
                    <button data-product="audio" class="add-row btn btn-default btn-sm" type="button">
                        Add row
                    </button>
                </div>
            </div>

        </div>
        <div role="tabpanel" class="tab-pane fade" id="vod-content">
            <div id="vod-targeting-grid" class="margin-top-standard"></div>

            <div class="row">
                <div class="col-md-2">
                    <button data-product="vod" class="add-row btn btn-default btn-sm" type="button">
                        Add row
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>



@endsection