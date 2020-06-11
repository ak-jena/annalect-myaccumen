<?php
 $breadcrumbs = array(['name' => 'Error 403']);
?>

@extends('app')
@section('title','Minerva')
@section('subtitle','403 | Unauthorised Access')
@section('content')

<div class="body-500">
  <section class="error-wrapper">
      <i class="icon-403"></i>
      <div class="text-center">
          <h2 class="danger-bg">Access denied</h2>
      </div>
      <p>You do not have enough privilege to carry out requested action!</p>
  </section>
</div>

@endsection('content')
