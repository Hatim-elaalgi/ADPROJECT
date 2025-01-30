<nav>
    <div class="navbar">
      <div class="container nav-container">
          <input class="checkbox" type="checkbox" name="" id="" />
          <div class="hamburger-lines">
            <span class="line line1"></span>
            <span class="line line2"></span>
            <span class="line line3"></span>
          </div>  
        <div class="logo">
          @if (Auth::check())
          Hello 
          @if(Auth::user()->role == "admin")
          M.
          @endif
          {{Auth::user()->name}} 
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">logout</button>
        </form>
          @else
          <h1><a href="{{route('Auth')}}">Connexion</a></h1>
          @endif
        </div>
        <div class="menu-items">
          <li><a href="{{route('hometest')}}">Home</a></li>
          <li><a href="{{route('magasines')}}">Magasines</a></li>
          <li><a href="{{route('themes')}}">Themes</a></li>
          <li><a href="{{route('mes_themes')}}">Mes theames</a></li>
          @if (Auth::check() && Auth::user()->role == "admin") <!-- check est ce qu'il est connecter apres check est ce qu'il est admin  -->
          <li><a href="{{route('dash_board')}}">Dash board</a></li>
          @endif

        </div>
      </div>
    </div>
</nav>