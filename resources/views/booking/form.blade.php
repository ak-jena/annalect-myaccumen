@extends('app')
@section('title', 'Home')
{{--@section('subtitle','Workflow Forms')--}}
@section('content')

    @if(in_array($product->id, [App\Product::RICH_MEDIA, App\Product::DISPLAY, App\Product::MOBILE]))
        @include('booking.rmd-booking')
    @elseif($product->id == App\Product::AUDIO)
        @include('booking.audio')
    @elseif($product->id == App\Product::VOD)
        @include('booking.vod')
    @endif

@endsection('content')

