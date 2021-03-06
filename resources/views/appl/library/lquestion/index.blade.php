@extends('layouts.app')
@section('content')

@include('appl.library.snippets.breadcrumbs')
@include('flash::message')

<div  class="row ">

  <div class="col-md-9">
 
    <div class="card mb-3 mb-md-0">
      <div class="card-body mb-0">
        <nav class="navbar navbar-light bg-light justify-content-between border mb-3 p-3">
          <a class="navbar-brand"><i class="fa fa-comments"></i> Questions</a>

          <form class="form-inline" method="GET" action="{{ route('lquestion.index',$repo->slug) }}">
           
           @can('create',$question)
             <div class="btn-group mr-3">
             
              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-plus"></i> New
            </button>

              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="{{ route('lquestion.create',['repo_id'=>$repo->slug])}}">Multiple Choice Question</a>
                <a class="dropdown-item" href="{{ route('lquestion.create',['repo_id'=>$repo->slug,'type'=>'naq'])}}">Numerical Answer Question</a>
                <a class="dropdown-item" href="{{ route('lquestion.create',['repo_id'=>$repo->slug,'type'=>'maq'])}}">Multi Answer Question</a>
                <a class="dropdown-item" href="{{ route('lquestion.create',['repo_id'=>$repo->slug,'type'=>'eq'])}}">Explanation Question</a>
              </div>
            </div>
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
         @include('appl.library.lquestion.list')
       </div>

     </div>
   </div>
 </div>
  <div class="col-md-3 pl-md-0">
      @include('appl.library.snippets.menu')
  </div>
</div>

@endsection


