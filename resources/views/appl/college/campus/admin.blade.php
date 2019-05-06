@extends('layouts.app')

@section('title', 'Campus | PacketPrep')
@section('description', 'Packetprep Campus Page')
@section('keywords', 'packetprep, campus page')


@section('content')

@include('flash::message')
<div  class="row ">
  <div class="col-12 col-md-10">
  <h1> Campus Admin Page</h1>
  <div class="w-100 p-3 bg-warning"></div>
  </div>
  <div class="col-12 col-md-2 pl-md-0 mb-3">
      @include('appl.college.snippets.menu')
    </div>
</div>

@endsection


