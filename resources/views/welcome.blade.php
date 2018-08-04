@extends('layouts.nowrap-product')

@section('content')

<div class="bg-white p-4  mb-4 ">
	<div class="wrapper ">  
	<div class="container">
	<div class="row">
		<div class="col-12 col-md-8">
			<h1 class="mt-2 mb-4 mb-md-2">
			<img src="{{asset('img/logo_sample.png')}}" width="70px" >
       &nbsp;  Pragathi Degree College

			</h1>

		</div>
		<div class="col-12 col-md-4">
      <div class="mt-3">
    <form class="form" method="GET" action="{{ route('course.index') }}">
            <div class="input-group ">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-search"></i></div>
              </div>
              <input class="form-control form-control-lg" id="search" name="item" autocomplete="off" type="search" placeholder="Search" aria-label="Search" 
              value="{{Request::get('item')?Request::get('item'):'' }}">
            </div>
          </form>
    </div>
  		</div>
	</div>
	</div>
</div>
</div>

<div class="container">
<div class="jumbotron bg-white border mt-4">

	<div class="row">
		<div class="col-md-8 ">
			@if(auth::user())
			<div class="row mt-2 ">
				<div class="col-12 col-md-3">
					<img class="img-thumbnail rounded-circle mb-3" src="{{ Gravatar::src(auth::user()->email, 140) }}">
				</div>
				<div class="col-12 col-md-9">

					<h2>Hi, {{ auth::user()->name}}</h2>
			<p> Welcome aboard</p>

			<p class="lead">We are here to make the learning simple, interesting and effective.</p>

			<a class="btn border border-info text-info mt-2" href="{{ route('profile','@'.auth::user()->username) }}"> Profile</a>
			<a class="btn border border-success text-success mt-2" href="{{ route('logout') }}" onclick="event.preventDefault();
			document.getElementById('logout-form').submit();" role="button">Logout</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>

				</div>
			</div>
            
             
			
			@else

			
			
			<div class="ml-4">
			<h1 class="display-4">Online Library </h1>
			<p class="lead">One stop repository of educational content for self learners</p>
			<hr class="my-4">
			<p>If you believe in our mission and willing to contribute  then join us...<br> lets build an amazing product.</p>
			<p class="lead">
				<a class="btn border border-primary btn-lg" href="{{ route('job.index') }}" role="button">Apply Now</a>
				<a class="btn border border-success text-success btn-lg" href="{{ route('login') }}" role="button">Login</a>
			</p>
		</div>
			@endif

		</div>
		<div class="col-md-4 d-none d-md-block">
			<div class="text-center mt-3 mt-mb-0">
				<img src="{{ asset('/img/678.jpg')}}" width="500px"/>
			</div>
		</div>
	</div>
</div>

@if(auth::user())
<div class="row ">
	
	<div class="col-12 col-md-6 ">
		<div class="card mb-4 mr-md-1" style="min-height:220px;">
			<div class="card-body bg-light">
				<div class="row no-gutter">
					<div class="col-6">
						<h2>Vision</h2> 
						<p class="mb-0">
							To create a world-class learning platform for self study
						</p>
					</div>
					<div class="col-6">
						<h2>Mission</h2> 
						<p class="mb-0">
							To develop comprehensive content that makes learning simple, interesting and effective. 
						</p>

					</div>
				</div>
			</div>
		</div> 
	</div>
	<div class="col-12 col-md-6">
		<div class="card mb-md-3" style="min-height:220px;">
			<div class="card-body bg-success text-white">
				<h2><b>Prime Goals</b></h2>
				<p class="mb-0 pb-0">
					<ol class="mb-0 pb-0">
						@if(isset($goals))
						@foreach($goals as $goal)
						<li>{{ $goal->title }} <span class="s15 " style="color:rgb(37, 128, 102)">by {{\carbon\carbon::parse($goal->end_at)->format('M d Y')}}</span></li>
						@endforeach
						@endif
					</ol>
				</p>
			</div>
		</div>
	</div>
</div>

@endif

</div>

</div>
@endsection           