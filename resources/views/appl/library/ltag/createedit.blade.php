@extends('layouts.app')
@section('content')

@include('appl.library.snippets.breadcrumbs')
@include('flash::message')

  <div class="card">
    <div class="card-body">
      <h1 class="bg-light p-3 mb-3 border">
        @if($stub=='Create')
          Create Tag
        @else
          Update Tag
        @endif  
       </h1>
      
      @if($stub=='Create')
      <form method="post" action="{{route('ltag.store',$repo->slug)}}" >
      @else
      <form method="post" action="{{route('ltag.update',[$repo->slug,$ltag->id])}}" >
      @endif  
      <div class="form-group">
        <label for="formGroupExampleInput ">Tag Name</label>
        <input type="text" class="form-control" name="name" id="formGroupExampleInput" placeholder="Enter the Tag Name" 
            @if($stub=='Create')
            value="{{ (old('name')) ? old('name') : '' }}"
            @else
            value = "{{ $ltag->name }}"
            @endif
          >
      </div>
      <div class="form-group">
        <label for="formGroupExampleInput2">Tag Value</label>
        <input type="text" class="form-control" name="value" id="formGroupExampleInput2" placeholder="Enter the tag value"
            @if($stub=='Create')
            value="{{ (old('value')) ? old('value') : '' }}"
            @else
            value = "{{ $ltag->value }}"
            @endif
          >

        @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        @endif

        <input type="hidden" name="user_id" value="{{ auth::user()->id }}">
        <input type="hidden" name="repository_id" value="{{ $repo->id }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      </div>

      <button type="submit" class="btn btn-outline-info">
          @if($stub=='Create')
            Save
          @else
            Update
          @endif
      </button>
    </form>
    </div>
  </div>
@endsection