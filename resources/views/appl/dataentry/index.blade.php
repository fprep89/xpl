@extends('layouts.app')
@section('content')

@include('appl.dataentry.snippets.breadcrumbs')
@include('flash::message')    
<div  class="row ">

  <div class="col-md-9">

    
    @include('flash::message')  

  <div class="card mb-4">
        <div class="card-body bg-light">
          <div  class="row ">
            <div class="col-md-3 col-lg-2 d-none d-md-block">
            <div class="text-center"><i class="fa fa-telegram fa-5x"></i> </div>
            </div>
            <div class="col-12 col-md-9 col-lg-10">
               <h1 class=" mb-2"> Material App</h1>
              <blockquote class="blockquote mb-0">
                <p class="mb-0">Data is a precious thing and will last longer than the systems themselves.</p>
                <footer class="blockquote-footer"><cite title="Source Title">Tim Berners-Lee</cite></footer>
              </blockquote>
            </div>
         </div>
        </div>
    </div>
    
 </div>

  <div class="col-md-3 pl-md-0">
      @include('appl.dataentry.snippets.material_menu')
    </div>
</div>

@endsection


