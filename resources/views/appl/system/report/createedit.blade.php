@extends('layouts.app')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item " ><a href="{{ route('system')}}">System</a></li>
    <li class="breadcrumb-item " ><a href="{{ route('report.week')}}">Reports</a></li>
    <li class="breadcrumb-item active" aria-current="page">@if($stub=='Create') Create @else Update @endif</li>
  </ol>
</nav>

  @include('flash::message')
  <div class="card">
    <div class="card-body">

      <nav class="navbar navbar-light bg-light justify-content-between mb-3">
          <a class="navbar-brand"><i class="fa fa-bullhorn"></i> {{ $stub }} Report </a>
      </nav>    
      
      @if($stub=='Create')
      <form method="post" action="{{route('report.store')}}">
      @else
      <form method="post" action="{{route('report.update',$report->id)}}">
      @endif  
      
      <div class="form-group">
        <label for="formGroupExampleInput ">Type</label>
        <select class="form-control" name="type">
          
           @if(\Auth::user()->checkRole(['administrator']))
           <option value="0" @if(isset($report)) @if($report->type==0) selected @endif @endif >Today</option>
           <option value="1" @if(isset($report)) @if($report->type==1) selected @endif @endif >This Week</option>
           <option value="2" @if(isset($report)) @if($report->type==2) selected @endif @endif >This Month</option>
           @else
            <option value="0" @if(isset($report)) @if($report->type==0) selected @endif @endif >Today</option>
             @if(in_array(\Carbon\carbon::now()->format( 'l' ),['Friday','Saturday','Sunday']) && !in_array(\Carbon\carbon::now()->format( 'd' ),[28,29,30,31]))
              <option value="1" @if(isset($report)) @if($report->type==1) selected @endif @endif >This Week</option>
              @elseif(in_array(\Carbon\carbon::now()->format( 'd' ),[28,29,30,31]) )
              <option value="2" @if(isset($report)) @if($report->type==2) selected @endif @endif >This Month</option>
              @endif
           @endif
          
        </select>
      </div>

      <div class="form-group">
        <label for="formGroupExampleInput2">Content</label>
         <textarea class="form-control summernote" name="content"  rows="5">
            @if($stub=='Create')
            {{ (old('content')) ? old('content') : '' }}
            @else
            {{ $report->content }}
            @endif
        </textarea>
      </div>

      <div class="form-group">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="@if(isset($report->user_id)) {{$report->user_id}} @else {{auth::user()->id }} @endif">
      </div>

      
      
      @if($stub=='Update')
      <input type="hidden" name="_method" value="PUT">
      @endif
      <button type="submit" class="btn btn-info">Save</button>
       <a href="#" class="btn  btn-outline-danger" data-toggle="modal" data-target="#exampleModal"  title="Delete" ><i class="fa fa-trash"></i> Delete</a>
    </form>
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
        <h3 ><span class="badge badge-danger">Serious Warning !</span></h3>
        This following action will delete the update and this is permanent action and this cannot be reversed.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
        <form method="post" action="{{route('report.destroy',$report->id)}}">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-danger">Delete Permanently</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection