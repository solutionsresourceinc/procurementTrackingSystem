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
	</head>
	<body role="document">
		 <div class="container theme-showcase" role="main">
			@yield('content')
		</div>
	</body>
</html>