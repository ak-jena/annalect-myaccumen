<?php
 $breadcrumbs = array(['name' => 'Error 500']);
?>

@extends('app')
@section('title','Minerva')
@section('subtitle','500 | Internal Server Error')
@section('content')

<div class="body-500">
  <section class="error-wrapper">
      <i class="icon-500"></i>
      <div class="text-center">
          <h2 class="danger-bg">Oops, monster eats our router</h2>
      </div>
      <p>Please try again or report to Analytic Team for instant support!</p>
  </section>
</div>

@endsection('content')
