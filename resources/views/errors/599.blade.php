<?php
 $breadcrumbs = array(['name' => 'Error 599']);
?>

@extends('app')
@section('title','Minerva')
@section('subtitle','599 | Accuen Custom Error Code')
@section('content')

<div class="body-500">
  <section class="error-wrapper">
      <i class="icon-500"></i>
      <p><br>{{ $exception->getMessage() }}</p>
  </section>
</div>

@endsection('content')