@extends('app')
@section('title', 'Home')
@section('subtitle','Targeting Grid')
@section('content')

<script type="text/javascript">
    // ajax URL
    var retrieve_grid_url = '{{ route('retrieve-grid', ['campaign_id' => $brief->campaign->id]) }}';

</script>

<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($product_names as $product_name)
            @php $formatted_product_name = str_replace(' ','-',strtolower($product_name)); @endphp
            <li role="presentation" @if($loop->first)class="active" @endif><a id="{{ $formatted_product_name }}" href="#{{ $formatted_product_name }}" aria-controls="home" role="tab" data-toggle="tab">{{ $product_name }}</a></li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach ($product_names as $product_name)
            @php $formatted_product_name = str_replace(' ','-',strtolower($product_name)); @endphp
            <div role="tabpanel" class="tab-pane fade in @if($loop->first) active @endif" id="{{ $formatted_product_name }}">

            </div>
        @endforeach

    </div>

</div>



@endsection