@extends('layouts.nowrap-product')

@section('title', 'Dashboard | PacketPrep')

@section('description', 'Know you packetprep products and services here.')

@section('keywords', 'quantitative aptitude, mental ability, learning, simple, interresting, logical reasoning, general english, interview skills, bankpo, sbi po, ibps po, sbi clerk, ibps clerk, government job preparation, bank job preparation, campus recruitment training, crt, online lectures, gate preparation, gate lectures')

@section('content')


<div class="container mt-4">

<div class="mb-4  p-3 pt-5 bg-white ">

	<div class="row">
		<div class="col-md-7 ">
			@if(auth::user())
			<div class="row mt-0 mt-mb-4">
				<div class="col-12 col-md-3">
					<img class="img-thumbnail rounded-circle mb-3" src="{{ Gravatar::src(auth::user()->email, 140) }}">
				</div>
				<div class="col-12 col-md-9">

					<h2>Hi, {{ auth::user()->name}}</h2>
			<p> Welcome aboard</p>

			<p class="lead">Develop a passion for learning. If you do, you will never cease to grow - 
            Anthony J Dangelo</p>



			
			<a class="btn border border-success text-success mt-2" href="{{ route('logout') }}" onclick="event.preventDefault();
			document.getElementById('logout-form').submit();" role="button">Logout</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>

            <br><br>

        @if(count(auth::user()->products)!=0)
            <table class="table table-responsive ">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Product</th>
                  <th scope="col">Valid till</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach(auth::user()->products as $k=>$product)
                 <tr>
                  <th scope="row">{{ $k+1}}</th>
                  <td><a href="{{ route('productpage',$product->slug) }}">{{$product->name}}</a></td>
                  <td>{{date('d M Y', strtotime($product->pivot->valid_till))}}</td>
                  <td> 
                    @if(strtotime($product->pivot->valid_till) > strtotime(date('Y-m-d')))
                      @if($product->pivot->status==1)
                      <span class="badge badge-success">Active</span>
                      @else
                      <span class="badge badge-secondary">Disabled</span>
                      @endif
                    @else
                        <span class="badge badge-danger">Expired</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
        @endif
            

				</div>


			</div>


            
             
			
			@else

			
			

			<div class="pt-0 mt-0 mt-md-4 pt-md-4">
				
				 @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif
                    
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-6 control-label">E-Mail Address / Username</label>

                            <div class="col-md-10">
                                <input type="text" class="form-control" name="email" placeholder="" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-10">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                                <a class="btn btn-outline-success" href="{{ url('/register') }}">
                                    Sign up
                                </a>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>

			
		</div>
			@endif

		</div>
		<div class="col-12 col-md-5 ">
			<div class="text-center mt-5 mt-md-1">
                @if(!auth::user())
				<img src="{{ asset('/img/678.jpg')}}" class="w-100 p-4"/>
                @else
                <img src="{{ asset('/img/student_front.png')}}" class="w-100 p-4"/>
                @endif
			</div>
		</div>
	</div>
</div>


</div>

</div>
@endsection           