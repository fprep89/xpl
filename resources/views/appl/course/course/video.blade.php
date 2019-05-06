@extends('layouts.app')

@if($category->pdf_link)
@section('title', 'Download '.$category->name.' | PacketPrep')
@elseif($category->test_link)
@section('title', 'Attempt '.$category->name.' | PacketPrep')
@else
@section('title', 'Learn '.$category->name.' | PacketPrep')
@endif

@if($category->video_desc )
@section('description', $category->video_desc )
@endif

@section('content')


<div class="d-none d-md-block">
  <nav aria-label="breadcrumb ">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('course.index')}}">Courses</a></li>
      <li class="breadcrumb-item"><a href="{{ route('course.show',$course->slug)}}">{{ $course->name }}</a></li>
      <li class="breadcrumb-item">{{ $category->name }}</li>
    </ol>
  </nav>
</div>

<div class="">
<div class="row ">
  <div class="col-12 col-md-3 d-none d-md-block">
    <div class="list-group">
  <a href="{{route('course.show',$course->slug)}}#{{$parent->slug}}" class="list-group-item list-group-item-action ">
    <h2><i class="fa fa-angle-double-left "></i>&nbsp; {{ $parent->name }}</h2>
  </a>
  @foreach($parent->descendants as $k => $item)
  @if($item->video_link || $item->pdf_link)
  <a href="{{ route('course.category.video',[$course->slug,$item->slug])}} " class="list-group-item list-group-item-action @if($item->slug == $category->slug) active @endif">
    @if(youtube_video_exists($videos[0]))
    <i class="fa fa-circle-o" aria-hidden="true"></i>
    @else
      <i class="fa fa-lock"></i>
    @endif
    &nbsp;{{ $item->name }}</a>
  @endif
  @endforeach
</div>
  </div>
  <div class="col-12 col-md-9">
    <h1 class="mb-4"> <div class="">
      @if($category->pdf_link)
      <i class="fa fa-file-pdf-o"></i> &nbsp;
      @elseif($category->test_link)
      <i class="fa fa-external-link"></i> &nbsp;
      @else
      <i class="fa fa-youtube-play"></i> &nbsp;
      @endif
      {{ $category->name }} </div></h1>



      @if($videos[0])

        @if(count($videos)>1)
          @foreach($videos as $k=>$video)
          <div class="border mb-3">
            
          @if(youtube_video_exists($video))
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
            <iframe src="https://www.youtube.com/embed/{{ $video }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          </div>
          @else
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
          <iframe src="//player.vimeo.com/video/{{ $video }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          </div>
          @endif
          <h3 class="bg-white p-3">Lecture {{($k+1)}}</h3>
          </div>


          @endforeach
        @else
          @if(youtube_video_exists($category->video_link))
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
            <iframe src="https://www.youtube.com/embed/{{ $category->video_link }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          </div>
          @else
          <div class="embed-responsive embed-responsive-16by9 border" style="background: #eee;">
          <iframe src="//player.vimeo.com/video/{{ $category->video_link }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          </div>
          @endif
        @endif  
      @endif

@if($category->pdf_link)
<div class="p-5 bg-white">
  <div class="row">
    <div class="col-12 col-md-2"><i class="fa fa-file-pdf-o fa-5x"></i></div>
    <div class="col-12 col-md-10">{!! $category->video_desc !!}<br>
      <a href="{{ $category->pdf_link }}">
      <button class="btn btn-primary mt-3 btn-lg">Download PDF</button>
      </a>
    </div>

  </div>

</div>


@endif    

@if($category->test_link)
<div class="p-5 bg-white">
  <div class="row">
    <div class="col-12 col-md-2"><i class="fa fa-external-link fa-5x"></i></div>
    <div class="col-12 col-md-10">{!! $category->video_desc !!}<br>
      <a href="{{ $category->test_link }}">
      <button class="btn btn-primary mt-3 btn-lg">
      @if(!isset($category->test_analysis))
      View Instruction
      @else
      View Analysis
      @endif
    </button>
      </a>
    </div>

  </div>

</div>


@endif        

<div class=" p-3 border" style="background: #eee; font-size: 20px;">
  <div class="row">
    <div class="col-12 col-md-4">
      @if($prev)
      <a href="{{ route('course.category.video',[$course->slug,$prev->slug])}}">
        <i class="fa fa-angle-double-left"></i> &nbsp;Previous Lesson
      </a>
      @else
      <a href="{{ route('course.show',[$course->slug])}}#{{$parent->slug}}">
        <i class="fa fa-angle-double-left"></i> &nbsp;Course Page
      </a>
      @endif
      
    </div>
    <div class="col-12 col-md-4">
      @if(count($category->category_tag_questions($category,session('exam')))!=0)
      <div class="text-md-center mt-3 mt-md-0 mb-3 mb-md-0">
        <a href="{{route('course.question',[$course->slug,$category->slug,''])}}"><i class="fa fa-bars"></i> &nbsp;Practice ({{ count($category->category_tag_questions($category,session('exam')))}}Q)</a>
    </div>
    @endif
    </div>
    <div class="col-12 col-md-4">
      @if($next)
      <a href="{{ route('course.category.video',[$course->slug,$next->slug])}}">
        <span class="float-md-right">Next Lesson&nbsp; <i class="fa fa-angle-double-right"></i></span>
      </a>
      @else
      <a href="{{ route('course.show',[$course->slug])}}#{{$parent->slug}}">
        <span class="float-md-right">Course Page&nbsp; <i class="fa fa-angle-double-right"></i></span>
      </a>
      @endif
      
   
    </div>
  </div>
  </div>

  @if($category->video_desc && $category->video_link)
  <div class="p-4 mt-3 bg-white border">
    {!! $category->video_desc !!}
  </div>
  @endif

  <div class=" mt-3 border p-4 rounded mb-3">
        @include('appl.pages.disqus')
      </div>
  </div>



</div>    
</div>
@endsection           