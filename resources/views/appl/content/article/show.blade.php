@extends('layouts.app2')
@section('title', $obj->name.' | PacketPrep')
@section('description', substr(strip_tags($obj->description),0,200))
@section('keywords', $obj->keywords)
@section('image', asset('/storage/company/'.$obj->slug.'_1200.jpg'))
@section('content')


  <div class="row">

    <div class="col-md-12">
    
      @if(Storage::disk('public')->exists($obj->image))
      
      <picture>
  <source srcset="{{ asset('/storage/articles/'.$obj->slug.'_300.webp') }} 320w,
             {{ asset('/storage/articles/'.$obj->slug.'_600.webp') }}  480w,
             {{ asset('/storage/articles/'.$obj->slug.'_900.webp') }}  800w,
             {{ asset('/storage/articles/'.$obj->slug.'_1200.webp') }}  1100w" type="image/webp" sizes="(max-width: 320px) 280px,
            (max-width: 480px) 440px,
            (max-width: 720px) 800px
            1200px" alt="{{  $obj->name }}">
  <source srcset="{{ asset('/storage/articles/'.$obj->slug.'_300.jpg') }} 320w,
             {{ asset('/storage/articles/'.$obj->slug.'_600.jpg') }}  480w,
             {{ asset('/storage/articles/'.$obj->slug.'_900.jpg') }}  800w,
             {{ asset('/storage/articles/'.$obj->slug.'_1200.jpg') }}  1100w," type="image/jpeg" sizes="(max-width: 320px) 280px,
            (max-width: 480px) 440px,
            (max-width: 720px) 800px
            1200px" alt="{{  $obj->name }}"> 
  <img srcset="{{ asset('/storage/articles/'.$obj->slug.'_300.jpg') }} 320w,
             {{ asset('/storage/articles/'.$obj->slug.'_600.jpg') }}  480w,
             {{ asset('/storage/articles/'.$obj->slug.'_900.jpg') }}  800w,
             {{ asset('/storage/articles/'.$obj->slug.'_1200.jpg') }}  1100w,"
      sizes="(max-width: 320px) 280px,
            (max-width: 480px) 440px,
            (max-width: 720px) 800px
            1200px"
      src="{{ asset('/storage/articles/'.$obj->slug.'_1200.jpg') }} " class="w-100 d-print-none" alt="{{  $obj->name }}">
</picture>

      @endif
      <div class="p-3 p-md-4 p-lg-5 bg-white company">
          
          @include('flash::message')
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
          @foreach($obj->labels as $label)
          <a href="{{ route('blog.label',$label->slug)}}"><span class="badge badge-info">{{$label->name }}</span></a>
          @endforeach
        
          <div class="row">
            <div class="col-12 col-md-8">
              <div class="mb-4" style="word-wrap: break-word;">
              {!! $obj->description !!}
            </div>
              <div class="" style="word-wrap: break-word;">
              {!! $obj->details !!}
            </div>

            </div>
            <div class="col-12 col-md-4 ">
              <div class="sticky-top pt-3">
                
              @if(isset($obj->related1))
              <h3 class="mb-3">Related Blogs</h3>
                @foreach($obj->related1 as $item)
                    @include('appl.content.article.blocks.related')
                @endforeach 
              @endif
              @include('snippets.adsense')
              </div>
            </div>
          </div>

          @if($questions)
          @if(count($questions)!=0)
          <div class="">
            @include('appl.content.article.questions')
          </div>
          @endif
          @endif

          <div class="row">
      
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