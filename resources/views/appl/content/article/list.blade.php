
@if(count($objs)!=0)
<div class="row ">
@foreach($objs as $obj)
<div class="col-12 col-md-6 col-lg-4">
 <div class="card mb-3" >
   @if(Storage::disk('public')->exists($obj->image))
     <a href="{{ route('page',$obj->slug) }}" >
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
      src="{{ asset('/storage/articles/'.$obj->slug.'_1200.jpg') }} " class="w-100 card-img-top" alt="{{  $obj->name }}">
</picture>
    </a>
      @endif
  <div class="card-body">
    <a href="{{ route('page',$obj->slug) }}" ><h2 class="card-title article">{{ $obj->name }}</h2></a>
    <p class="card-text article">
      {!! 
      substr(strip_tags($obj->description),0,200) !!}@if(strlen(strip_tags($obj->description))>200) ... @endif</p>
    <a href="{{ route('page',$obj->slug) }}" class="btn btn-success"><i class="fa fa-align-right"></i> read more</a>
  </div>
</div>
</div>
@endforeach
</div>
<div class="p-2"></div>
@else
<div class="card card-body bg-light">
  No {{ $app->module }} listed
</div>
@endif
<div class="">
<nav aria-label="Page navigation  " class="card-nav ">
        {{$objs->appends(request()->except(['page','search']))->links('vendor.pagination.bootstrap-4') }}
      </nav>
      <div class="p-3"></div>
    </div>

