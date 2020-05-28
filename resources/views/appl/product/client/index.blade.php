@extends('layouts.app')
@section('title', 'Clients')
@section('content')

@include('appl.product.snippets.breadcrumbs')
@include('flash::message')
<div  class="row ">

  <div class="col-md-12">
 
    <div class="card mb-3 mb-md-0">
      <div class="card-body mb-0">
        <nav class="navbar navbar-light bg-light justify-content-between border mb-3">
          <a class="navbar-brand"><i class="fa fa-university"></i> Clients </a>

          <form class="form-inline" method="GET" action="{{ route('client.index') }}">

            @can('create',$client)
            <a href="{{route('client.create')}}">
              <button type="button" class="btn btn-outline-success my-2 my-sm-2 mr-sm-3">Create client</button>
            </a>
            @endcan
            <div class="input-group ">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-search"></i></div>
              </div>
              <input class="form-control " id="search" name="item" autocomplete="off" type="search" placeholder="Search" aria-label="Search" 
              value="{{Request::get('item')?Request::get('item'):'' }}">
            </div>
            
          </form>
        </nav>

        <div id="search-items">
         @include('appl.product.client.list')
       </div>

     </div>
   </div>
 </div>

</div>

@endsection


