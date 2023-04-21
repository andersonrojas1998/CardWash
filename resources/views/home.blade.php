@extends('layout.master')
@push('plugin-styles')
<style>  
  .carousel-inner img {
    width: 100%;
    height: 385px;
  }
  </style>
@endpush
@section('content')
<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Bienvenido  {{ auth()->user()->name }}</h3>
        
      </div>
      </div>
      </div>
      </div>


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
</div>
</div>
</div>
</div>
@endsection