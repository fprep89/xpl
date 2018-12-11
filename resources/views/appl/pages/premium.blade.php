@extends('layouts.nowrap-product')
@section('content')
<div class=" p-4  bg-white mb-4 border-bottom">
  <div class="wrapper ">  
  <div class="container">
  <div class="row">
    <div class="col-12 col-md-8">
      <h1 class="mt-2 mb-4 mb-md-2">
      <i class="fa fa-bullseye"></i> &nbsp;Premuim Access
      </h1>
    </div>
    <div class="col-12 col-md-4">

   
      </div>

  </div>
  </div>
</div>
</div>

<div class="wrapper " >
    <div class="container" >  
    
      <div class="row ">
        <div class="col-12 ">
          <div class=" p-4  rounded mb-4" style="background: rgba(75, 192, 192, 0.2); border:1px solid rgba(75, 192, 192, 1);">
        <div class="row">
          <div class="col-12 col-md-12">
              <div class="display-3 mb-3">  Complete Access to</div>
              <div class="display-1 text-bold mb-3">  PacketPrep Services </div>
              <div class="display-3 pb-5">  for 2 years</div>
              @if(!$entry)
              <div class="display-4 pb-5">  <i class="fa fa-rupee"></i> 2000</div>
              
              <a href="{{ route('checkout')}}?product=premium-access">
                <button class="btn btn-outline-success btn-lg">Buy Now</button>
              </a>
              @else
              <div class="bg-light border p-3 rounded">You have Premium Access to content valid till
                 <span class="badge badge-primary">{{ date('d M Y', strtotime(\auth::user()->products->find('9')->pivot->valid_till)) }}</span>
                 
               </div>
              @endif
              

          </div>
          
        </div>
        </div>
      </div>
      </div>



      

     </div>   
</div>

@endsection           
