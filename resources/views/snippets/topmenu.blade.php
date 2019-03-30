
 <nav class="navbar navbar-expand-lg navbar-dark " >
            @guest
            <a class="navbar-brand abs" href="{{ url('/') }}">
                <img src="{{ asset('img/packetprep-logo-small.png') }}" width="60px" class="logo-main ml-md-1" />
            </a>
            @else
            <a class="navbar-brand abs" href="{{ url('/dashboard') }}">
                <img src="{{ asset('img/packetprep-logo-small.png') }}" width="60px" class="logo-main ml-md-1" />
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
                    @if(\Auth::user()->checkRole(['administrator','manager','investor','patron','promoter','employee']))
                    <li class="mr-3"><a class="nav-link" href="{{ route('admin.index') }}"><i class="fa fa-home"></i> Admin</a></li>
                    @endif

                    <li class="mr-3"><a class="nav-link" href="{{ route('dashboard') }}"
                            ><i class="fa fa-dashboard"></i>
                           Dashboard
                             </a></li>
                    
                    @endguest
                    <li class="mr-3 "><a class="nav-link " href="{{ url('course') }}"><i class="fa fas fa-youtube-play"></i> Courses</a></li>
                    <!--
                    <li class="mr-3 "><a class="nav-link " href="{{ url('tracks') }}"><i class="fa fa fa-spotify"></i> Tracks</a></li>   -->
                    <li class="mr-3 "><a class="nav-link " href="{{ url('targettcs') }}"><i class="fa fa-ravelry"></i> Target TCS</a></li>
                   <li class="mr-3 "><a class="nav-link " href="{{ url('proficiency-test') }}"><i class="fa fa-gg"></i> Proficiency Test</a></li>
                    
                    <li class="mr-3 "><a class="nav-link " href="{{ url('bootcamp') }}"><span class="premium"><i class="fa fa-bullseye"></i> Coding Bootcamp</span></a></li>

                    <!-- Authentication Links -->
                    @guest
                    <li class="mr-2"><a class="nav-link " href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                    
                    @else
                   
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{ Auth::user()->name }} <span class="caret"></span>
                       </a>
                       <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item"  href="{{ route('profile','@'.\auth::user()->username)}}"
                            >
                           Profile
                             </a>
                             @if(\Auth::user()->checkRole(['administrator','manager','investor','patron','promoter','employee','ambassador']))
                             <a class="dropdown-item"  href="{{ route('ambassador.connect')}}"
                            >
                           Campus Connect
                             </a>
                             @endif

                             @if(\Auth::user()->checkRole(['administrator','manager','investor','patron','promoter','employee','intern-generalist']))
                             <a class="dropdown-item"  href="{{ route('intern.connect')}}"
                            >
                           Interns Connect
                             </a>
                             @endif
                             
                            <a class="dropdown-item"  href="{{ route('order.transactions') }}"
                            >
                            Transactions
                             </a>
                            <a class="dropdown-item"  href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                             </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>  
                     </li>
                    @endguest
                </ul>

            </div>
        </nav>  