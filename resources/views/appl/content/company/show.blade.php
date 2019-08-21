@extends('layouts.app')
@section('title', $obj->name.' | PacketPrep')
@section('description', $obj->description)
@section('keywords', $obj->keywords)
@section('content')


@include('flash::message')
  <div class="row">

    <div class="col-md-12">
      
      <img src="{{ asset('storage/'.$obj->image)}}" class="w-100 " alt="{{  $obj->name }}"/>
      <div class="p-5 bg-white company">
        
          <h1 class=""> {{ $obj->name }} 

          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->slug) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
          </h1>
     
          <div class="row">
            <div class="col-12 col-md-8">
              {!! $obj->description !!}
            </div>
            <div class="col-12 col-md-4">
              @include('appl.content.company.googleads')
            </div>
          </div>
          

          {!! $obj->details !!}
     
      
  
    </div>
      </div>

    </div>

     

  </div> 


  <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This following action is permanent and it cannot be reverted.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
        <form method="post" action="{{route($app->module.'.destroy',$obj->slug)}}">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        	<button type="submit" class="btn btn-danger">Delete Permanently</button>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection