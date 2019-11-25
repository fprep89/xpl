@extends('layouts.app')
@section('title', $obj->name.' | Test Category | PacketPrep')
@section('description', 'Category Labels')
@section('keywords', 'label')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">{{ $obj->name }}</li>
  </ol>
</nav>

@include('flash::message')

  <div class="row">

    <div class="col-md-12">
      <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
          <p class="h2 mb-0"><i class="fa fa-th "></i> {{ $obj->name }} 

          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
          </p>
        </div>
      </div>

     
      <div class="card mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4"><b>Category</b></div>
            <div class="col-md-8"><span class="badge badge-secondary">{{ $obj->label }}</span></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Name</b></div>
            <div class="col-md-8">{{ $obj->name }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Slug</b></div>
            <div class="col-md-8">{{ $obj->slug }}</div>
          </div>
         <div class="row mb-2">
            <div class="col-md-4"><b>Image</b></div>
            <div class="col-md-8">
              @if(\Storage::disk('public')->exists($obj->image) && $obj->image )
              <div class="bg-light border  p-3 mb-3">
                <img src="{{ asset('storage/'.$obj->image)}}"  class="w-25 "/>
              </div>
              <form method="post" action="{{route($app->module.'.update',[$obj->id])}}" >
                 <input type="hidden" name="_method" value="PUT">
                 <input type="hidden" name="deletefile" value="1">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete File</button>
              </form>
              @else
               <span class="text-muted"><i class="fa fa-exclamation-triangle"></i> file path not found </span>
              @endif
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Description</b></div>
            <div class="col-md-8">{!! $obj->description !!}</div>
          </div>
          @if(count($obj->articles))
          <div class="row mb-2">
            <div class="col-md-4"><b>Articles</b></div>
            <div class="col-md-8">
              @foreach($obj->articles as $article)
                <a href="{{ route('article.show',$article->id)}}">
                  {{$article->name }}
                </a><br>
              @endforeach
            </div>
          </div>
          @endif
          <div class="row mb-2">
            <div class="col-md-4"><b>Status</b></div>
            <div class="col-md-8">@if($obj->status==0)
                    <span class="badge badge-warning">Inactive</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Active</span>
                  @endif</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Created At</b></div>
            <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
          </div>
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
        
        <form method="post" action="{{route($app->module.'.destroy',$obj->id)}}">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        	<button type="submit" class="btn btn-danger">Delete Permanently</button>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection