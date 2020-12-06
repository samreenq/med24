<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="shortcut icon" href="images/favicon.png">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>MED24</title>
<base href="{{asset('/public/frontend/assets').'/'}}">
@include('site.layouts.partials.stylesheet')

@yield('styles')

</head>
<body>

@include('site.layouts.partials.header')
@yield('content')
@include('site.layouts.partials.footer')
@include('site.layouts.partials.script')
@yield('scripts')
</body>
</html>
