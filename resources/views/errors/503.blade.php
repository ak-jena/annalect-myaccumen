<?php
 $breadcrumbs = array(['name' => 'Error 503']);
?>

@extends('app')
@section('title','Minerva')
@section('subtitle','503 | Service Unavailable')
@section('content')

<div class="body-500">
  <section class="error-wrapper">
      <i class="icon-503"></i>
      <p><br>Please try again later or report to Analytic Team for instant support!</p>
  </section>
</div>

@endsection('content')
