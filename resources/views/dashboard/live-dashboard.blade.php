@extends('app')
@section('title', 'Home')
@section('content')
    <script type="text/javascript">
        // ajax URL
        var load_live_campaigns_tiles_url = '{{ route('live-campaigns-tiles') }}';

    </script>

    @php
        $logged_in_user = Baselib::getUser(Baselib::getUserID());
        $clients        = $logged_in_user->permittedClients->sortBy('name')->pluck('name', 'id')->toArray();

        $clients_all = array(0 => 'All clients')+$clients;

        $products = DB::table('products')->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $products_all = array(0 => 'All products')+$products;

    @endphp

    <div id="campaign-tiles" class="row padding-top-large" style="padding-left: 5px; padding-right: 5px;">

    </div>

@endsection