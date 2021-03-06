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
			<i class="fa fa-university"></i> &nbsp; Campus Connect <span class="badge badge-warning">{{ ($data['course']) ? $data['course']->name : '-NA-' }} Score</span>
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
      <div class="col-12 col-md-3">
        <div class="bg-info rounded text-white p-4 mb-4">
          <h1>My Score</h1>
          <div class="display-1">{{ $data['total_score']}}</div>
        </div>
        <div class="bg-light rounded  border p-4">
          <h1><i class="fa fa-trophy"></i> Leaderboard</h1>
          <a href="{{ route('ambassador.leaderboard')}}"><button class="btn btn-sm btn-success mb-4">view</button></a>

          <hr>
          <p>For score 6000 and above</p>
          <h3>Cash Awards</h3>
          <div> Top 1 - Rs.3000</div>
          <div> Top 2 - Rs.2000</div>
          <div> Top 3 - Rs.1000</div>
          <div> Top 4 - Rs.500</div>
          <div> Top 5 - Rs.500</div>
          <hr>
          <p> For score 3000 and above</p>
          <div class="h5">We will right an exclusive LinkedIn recommendation</div>
          <hr>
          <p> For score 1000 and above</p>
          <div class="h5">Internship Certificate</div>
          <p> Where we will highlight your leadership skills and motivation abilities</p>

        </div>

      </div>
      <div class="col-12 col-md-9 ">
        <div class="bg-white rounded p-4 border mb-4">
            <h3 class="mb-4"> Practice score</h3>
            <hr>
            <div class="display-1 mb-4">{{ $data['practice_score']}}</div>
            <p class="bg-light border rounded p-2"> Practice score is the aggregate of questions attempted by the referral</p>
            @if(count($data['practice_top']))
            <div class="table table-responsive">
              <table class="table">
                <thead>
                  <tr class="border">
                    <th scope="col" class="border border-dark">#</th>
                    <th scope="col" class="border border-dark">Top Referral</th>
                    <th scope="col" class="border border-dark">College</th>
                    <th scope="col" class="border border-dark">Score</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data['practice_top'] as $k=>$p) 
                  <tr>
                    <td scope="row">{{ ($k+1) }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>@if($p->user()->first()->colleges()->first())
                      {{ $p->user()->first()->colleges()->first()->name }}
                        @endif - 
                        @if($p->user()->first()->branches()->first())
                        {{$p->user()->first()->branches()->first()->name}}
                        @endif</td>
                    <td>{{ $p->attempted }}</td>
                  </tr>
                  @endforeach
                 
                </tbody>
              </table>
            </div>
            @else
              <div class=" border p-3">No data to show</div>
            @endif
        </div>

        <div class="bg-white rounded p-4 border mb-3">
            <h3 class="mb-4"> Tests score</h3>
            <hr>
            <div class="display-1 mb-4">{{ $data['tests_score']}}</div>
            <p class="bg-light border rounded p-2"> Tests Score is the aggregate of questions correctly solved by the referral</p>
            @if($data['tests_top'])
            <div class="table table-responsive">
              <table class="table">
                <thead>
                  <tr class="border">
                    <th scope="col" class="border border-dark">#</th>
                    <th scope="col" class="border border-dark">Top Referral</th>
                    <th scope="col" class="border border-dark">College</th>
                    <th scope="col" class="border border-dark">Score</th>
                  </tr>
                </thead>
                <tbody>
                  
                  @foreach($data['tests_top'] as $k=>$t) 
                  <tr>
                    <td scope="row">{{ ($k+1) }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td>@if($t->user->colleges()->first())
                      {{ $t->user->colleges()->first()->name }}
                        @endif - 
                        @if($t->user->branches()->first())
                        {{$t->user->branches()->first()->name}}
                        @endif</td>
                    <td>{{ $t->sum }}</td>
                  </tr>
                  @endforeach
                  
                 
                </tbody>
              </table>
            </div>
            @else
              <div class=" border p-3">No data to show</div>
            @endif
        </div>

            <div class="bg-light rounded p-4 border ">
            <h3 class="mb-4"> Top Ambassadors</h3>
            <div class="bg-light border rounded p-2 mb-3"> Updated on : <i>{{ $data_amb->now }}</i>
              <div><small>Data will be refreshed for every 24 hours</small></div>
            </div>
            @if($data_amb)
            <div class="table table-responsive">
              <table class="table">
                <thead>
                  <tr class="border">
                    <th scope="col" class="border border-dark">#</th>
                    <th scope="col" class="border border-dark">Top Ambassador</th>
                    <th scope="col" class="border border-dark">College</th>
                    <th scope="col" class="border border-dark {{$m=0}}">Score</th>
                  </tr>
                </thead>
                <tbody>
                  
                  @foreach($data_amb->amb_scores as $k=>$t) 
                  <tr>
                    <td scope="row">{{ (++$m) }}</td>
                    <td>{{ $data_amb->amb_user->$k->name }}</td>
                    <td>{{ $data_amb->amb_user->$k->college}} - {{$data_amb->amb_user->$k->branch}} </td>
                    <td>{{ $t }}</td>
                  </tr>
                  @endforeach
                  
                 
                </tbody>
              </table>
            </div>
            @else
              <div class=" border p-3">No data to show</div>
            @endif
        </div>

      </div>
    </div>
  </div>
</div>

@endsection           