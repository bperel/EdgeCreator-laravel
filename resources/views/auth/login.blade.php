<!doctype html>
<html lang="{{ app()->getLocale() }}">
    @include('head')
    <body>
        <div class="flex-center position-ref full-height">
            @include('top')

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
                    <form method="post" action="{{ url('/login') }}">
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
