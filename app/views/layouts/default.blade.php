<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Tarlac Procurement Tracking System</title>
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/bootstrap-theme.min.css') }}
		{{ HTML::style('css/theme.css') }}
		{{ HTML::style('css/signin.css') }}
		{{ HTML::script('js/bootstrap.min.js') }}
		@yield('header')
	</head>
	<body role="document">
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	    	<div class="container">
	    		<div class="navbar-header">
			        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        </button>
			        <a class="navbar-brand" href="#">PTS</a>
	        	</div>
	        	<div class="navbar-collapse collapse">
          			<ul class="nav navbar-nav">
          				@yield('menu')
          			</ul>
          		</div>
	        </div>
	    </div>
	    <div class="container theme-showcase" role="main">
			@yield('content')
		</div>
	</body>
</body>
</html>