
 <nav class="navbar navbar-expand-lg navbar-light " >
            <a class="navbar-brand abs" href="{{ url('/') }}">
                <img src="{{ asset('/img/logo-onlinelibrary-simple.png') }}" width="70px" class="logo-product ml-md-1" /> <span class="logo-text">&nbsp;&nbsp;Pragathi Degree college</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="font-weight: bold">
                <ul class="navbar-nav ">
                     
                 </ul>
                 <ul class="navbar-nav ml-auto">
                    
                    <li class="mr-3 mt-2 mt-lg-0"><a class="nav-link " href="{{ url('/') }}"><i class="fa fas fa-home"></i> Home</a></li>
                    <li class="mr-3  mt-lg-0"><a class="nav-link " href="{{ route('course.index') }}"><i class="fa fas fa-list-alt"></i> Courses</a></li>
                    

                    <!-- Authentication Links -->
                    @guest
                    
                   
                    <li class="mr-2"><a class="nav-link " href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                    
                    @else
                   
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{ Auth::user()->name }} <span class="caret"></span>
                       </a>
                       <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
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