<nav class="navbar navbar-expand-lg navbar-light " >
    @guest
    <a class="navbar-brand abs" href="{{ url('/') }}" aria-label="Homepage">
        <img 
        src="{{ request()->session()->get('client')->logo }} " height="80px" class="ml-md-0"  alt="logo " type="image/png">
    </a>
    @else
    <a class="navbar-brand abs" href="{{ url('/dashboard') }}" aria-label="Dashboard">
        <img 
        src="{{ request()->session()->get('client')->logo }} " height="80px" class="ml-md-0"  alt="logo " type="image/png">
    </a>   
    @endguest
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav" style="font-weight: bold">
    <ul class="navbar-nav ">

    </ul>
    <ul class="navbar-nav ml-auto mt-4 mt-lg-0">
        @guest
        
        @else


        <li class="mr-3"><a class="nav-link" href="{{ route('dashboard') }}" aria-label="Dashboard page"
            ><i class="fa fa-dashboard"></i>
            Dashboard
        </a></li>
        @if(\Auth::user()->checkRole(['administrator','manager','investor','patron','promoter','employee','client-manager','tpo','hr-manager']))

        
        <li class="mr-3"><a class="nav-link" href="{{ route('user.list') }}" aria-label="exams page"
            ><i class="fa fa-user"></i>
            Users
        </a></li>

        @endif

        <li class="mr-3"><a class="nav-link" href="{{ route('logout') }}" aria-label="Logout page" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();"
            ><i class="fa fa-sign-out"></i>
            Logout
        </a></li>

        
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
       

        

        @endguest
       
                    <!--
                        <li class="mr-3 "><a class="nav-link " href="{{ url('tracks') }}"><i class="fa fa fa-spotify"></i> Tracks</a></li>   -->
                        

                        
            <!-- Authentication Links -->
            @guest
            <!--<li class="mr-2"><a class="nav-link active" href="{{ route('login') }}" aria-label="Login page"><i class="fa fa-gg-circle"></i> for companies</a></li>
            <li class="mr-2"><a class="nav-link " href="{{ route('login') }}" aria-label="Login page"><i class="fa fa-black-tie"></i> for students</a></li>-->
            <li class="mr-2"><a class="nav-link " href="{{ route('login') }}" aria-label="Login page"><i class="fa fa-sign-in"></i> Login</a></li>
            <li class="mr-2"><a class="nav-link " href="{{ route('register') }}" aria-label="Registration page"><i class="fa fa-user-plus"></i> Register</a></li>

            @else

            
@endguest
</ul>

</div>
</nav>  