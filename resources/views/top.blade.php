@if (Session::has('username'))
    <div class="top-right links">
        @auth
            <a href="{{ url('/home') }}">Home</a>
        @else
            <a href="{{ url('/logout') }}">Logout</a>
        @endauth
    </div>
@endif
