@extends('layouts.app2')
@section('title', $obj->name.' | PacketPrep')
@section('description', strip_tags($obj->description))
@section('keywords', $obj->keywords)
@section('image', asset('/storage/company/'.$obj->slug.'_900.jpg'))
@section('content')


@include('flash::message')
  <div class="row">

    <div class="col-md-12">
    
      @if(Storage::disk('public')->exists($obj->image))
      
      <img srcset="{{ asset('/storage/company/'.$obj->slug.'_300.jpg') }} 320w,
             {{ asset('/storage/company/'.$obj->slug.'_600.jpg') }}  480w,
             {{ asset('/storage/company/'.$obj->slug.'_900.jpg') }}  800w"
      sizes="(max-width: 320px) 280px,
            (max-width: 480px) 440px,
            800px"
      src="{{ asset('/storage/company/'.$obj->slug.'_900.jpg') }} " class="w-100" alt="{{  $obj->name }}">
      @endif
      <div class="p-3 p-md-4 p-lg-5 bg-white company">
        
          <h1 class=""> {{ $obj->name }} 

          @if(\auth::user())
          @if(\Auth::user()->checkRole(['administrator','manager','investor','patron','promoter']))
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->slug) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endif
          @endif

          </h1>

        
          <div class="row">
            <div class="col-12 col-md-8">
              <div class="mb-4">
              {!! $obj->description !!}
            </div>
              <div class="mb-4">
              {!! $obj->details !!}
            </div>

            @include('appl.content.company.blocks.share')
            </div>
            <div class="col-12 col-md-4">
              @include('snippets.adsense')
            </div>
          </div>
          


     
      
  
    </div>
      </div>

    </div>

    @if($questions)
    @if(count($questions)!=0)
    <div class="bg-white p-3 p-md-4 p-lg-5 mt-3">
      @include('appl.content.company.questions')
    </div>
    @endif
    @endif

    
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