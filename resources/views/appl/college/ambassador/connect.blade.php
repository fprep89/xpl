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
			<i class="fa fa-university"></i> &nbsp; Campus Connect
			</h1>
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
        <div class="col-12 col-md-4">
            <div class="card">
              <div class="card-body ">
                <div> My College</div>
                <h1 class="mb-5"><a href="{{ route('ambassador.college')}}">{{ $data['college']->name }}</a></h1>
                <div class="rounded text-white p-3 mb-3" style="background:#74b9ff">
                  <h3>College Score</h3>
                  <div class="display-1">{{ $data['college_score'] }}</div>
                </div>

                <div class=" rounded text-white p-3" style="background: #e17055">
                  <h3>My Score</h3>
                  <div class="display-1">{{  $data['my_score']}} </div>
                </div>
              </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="card mb-4">
              <div class="card-body " style="background: #f7f1e3; border:1px solid #fdcb6e;">
                <div> </div>
                <div class="">
                  <h3 class="mb-4">Top Colleges</h3>
                  <div class="table-responsive">
                  <table class="table  mb-0">
                    <thead>
                      <tr>
                        <th scope="col">Name </th>
                        <th scope="col">Score </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data['colleges'] as $college => $score)
                      <tr>
                        <td>{{ $college }}</td>
                        <td> {{ $score }}</td>
                      </tr>
                      @endforeach    
                    </tbody>
                  </table>
                </div>
                </div>
              </div>
            </div>


            <div class="card">
              <div class="card-body " style="background:#dfe6e9; border:1px solid #b2bec3;">
                <div> </div>
                <div class="">
                  <h3 class="mb-4">Top Ambassadors</h3>
                  <div class="table-responsive">
                  <table class="table  mb-0">
                    <thead>
                      <tr>
                        <th scope="col">Name </th>
                        <th scope="col">Score </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data['users'] as $user => $score)
                      <tr>
                        <td>{{ $user }}</td>
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