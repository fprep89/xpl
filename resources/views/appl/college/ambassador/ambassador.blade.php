@extends('layouts.nowrap-product')
@section('title', 'Campus Connect | PacketPrep')
@section('description', 'This page is about campus connect')
@section('keywords', 'college,packetprep,campus connect')

@section('content')
<div class="line" style="padding:1px;background:#eee"></div>
<div class=" p-4  mb-3 mb-md-4 border-bottom bg-white">
	<div class="wrapper ">  
	<div class="container">
	<div class="row">
		<div class="col-12 col-md-8">
			<h1 class="mt-2 mb-4 mb-md-2">
			<i class="fa fa-trophy"></i> &nbsp; All Ambassadors
			</h1>
      <a href="{{ route('ambassador.connect') }}"><i class="fa fa-angle-double-left"></i> return to campus connect</a>

		</div>
		<div class="col-12 col-md-4">
      
  		</div>
	</div>
	</div>
</div>
</div>

<div class="wrapper " >
    <div class="container pb-5" >  
      <div class="row">
     
        <div class="col-12 col-md-12">
            

            <div class="card">
              <div class="card-body " >
                <div> </div>
                <div class="">
                  <div class="table-responsive">
                  <table class="table  mb-0">
                    <thead>
                      <tr>
                        <th scope="col">Name </th>
                        <th scope="col">College </th>
                        <th scope="col">Score </th>
                      </tr>
                    </thead>
                    <tbody class="{{ $j}}">
                      @foreach($data['users'] as $user => $score)
                      <tr>
                        <td class="{{ $j++}}">{{ $user }}</td>
                        <td> {{ $data['colleges'][$user] }}</td>
                        <td> {{ $score }}</td>
                      </tr>
                      
                      @endforeach    
                    </tbody>
                  </table>
                </div>
                </div>
              </div>
            </div>
        </div>
      </div>
     </div>   
</div>

@endsection           