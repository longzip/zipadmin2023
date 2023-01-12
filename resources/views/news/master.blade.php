<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>Zip</title>
<meta name="Description" content="Là trang Web tin tức nội bộ của công ty nội thất zip">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link href="{{asset('css/news/simple-sidebar.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="{{asset('css/news/animate.css')}}" rel="stylesheet"/>
<link href="{{asset('css/news/style.css')}}" rel="stylesheet"/>

<body>
    @include('news.layouts.menu')
    <div id="page-content-wrapper">
    	@include('news.layouts.header')
    	@yield('news')
    	@include('news.layouts.footer')
    </div>
<script src="{{asset('js/news/jquery-scrolltofixed-min.js')}}"></script>
<script src="{{asset('js/news/bootstrap.js')}}"></script>
<script src="{{asset('js/news/layout-scripts.js')}}"></script>
<script src="{{asset('js/news/autohidingnavbar.js')}}"></script>
<a href="#0" class="cd-top top-color">Top</a>
<script src="{{asset('js/news/merge.js')}}"></script>

@auth
	<script>
	    window.user = @json(auth()->user())
	</script>
@endauth
</body>
</html>