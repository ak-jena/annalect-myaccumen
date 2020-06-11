<ol class="breadcrumb">
<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
@if(!empty($breadcrumbs))
    @foreach($breadcrumbs as $bread)
        @if(isset($bread['url']))
            <li>{!! link_to($bread['url'], $bread['name']) !!}</li>
        @else
            <li>{!! $bread['name'] !!}</li>
        @endif
    @endforeach
@endif
</ol>
