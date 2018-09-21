@extends('layouts.head')
@section('content-main')
    <div class="wrapper-bg wrapper-bg-3">
    <div class="nav-bg-black">
        <div class="wrapper ">
        <div id="app " class="p-2">
            @include('snippets.topmenu-product')
        </div>
        </div>
    </div>    
      
    
    <div class="container"> 
    <div class="border mt-3 mb-3 ">
    @yield('content')
    </div>
    </div>
       
    </div>
@endsection


  
