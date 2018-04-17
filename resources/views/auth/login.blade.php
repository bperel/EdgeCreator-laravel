<!doctype html>
<html lang="{{ app()->getLocale() }}">
    @include('head')
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Login page
                </div>

                @if ($errorMessage = session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ $errorMessage }}
                    </div>
                @endif

                @if ($message = session('message'))
                    <div class="alert alert-info" role="alert">
                        {{ $message }}
                    </div>
                @endif

                <div>
                    <form method="post">
                        {{ csrf_field() }}
                        <input type="text" name="username" /><br />
                        <input type="password" name="password" /><br />
                        <input type="submit" value="OK"/>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
