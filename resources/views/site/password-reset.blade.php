<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Reset Password</title>
</head>
<body>

<div class="container p-lg-5">
    <div class="row">

    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8 ">
            <h2 class="text-center" style="font-family: 'Century Gothic'">Reset Password</h2>
            @if ($errors->any())
                <div class="alert alert-danger text-center">
                        @foreach ($errors->all() as $error)
                            {{ $error }} <br/>
                        @endforeach
                </div>
            @endif

        @if (session()->has('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @elseif(session()->has('success'))
                <div class="alert alert-success text-center">
                        {{ session('success') }}
                </div>
            @elseif(session()->has('email'))
                <div class="alert alert-success text-center">

                       {{ session('email') }}
                </div>
            @endif
            <div class="jumbotron">
                <form action="{{route('updatePassword')}}" method="post">
                    <div class="form-group">
                        <label for="">Enter New Password</label>
                        <input type="password" class="form-control" placeholder="Enter New Password" name="password">
                    </div>

                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                    </div>
                    <input type="hidden" name="email" value="{{$email}}">
                    <input type="hidden" name="token" value="{{$token}}">
                    {{csrf_field()}}
                    <input type="hidden" name="broker" value="{{$broker}}">
                    <div class="form-group text-center">
                        <input type="submit" class="btn btn-primary"  value="Reset Password">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2"></div>

    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
