<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Tarlac Procurement Tracking System</title>
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/bootstrap-theme.min.css') }}
		{{ HTML::style('css/theme.css') }}
		{{ HTML::style('css/signin.css') }}
		{{ HTML::style('css/custom.css') }}
		{{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}


		{{ HTML::style('colvix/css/jquery.dataTables.css')}}
		{{ HTML::style('colvix/css/dataTables.colVis.css')}}
		{{ HTML::style('colvix/css/shCore.css')}}
		

		{{ HTML::script('colvix/js/jquery.js')}}
		{{ HTML::script('colvix/js/jquery.dataTables.js')}}
		{{ HTML::script('colvix/js/dataTables.colVis.js')}}
		{{ HTML::script('colvix/js/shCore.js')}}
		{{ HTML::script('colvix/js/demo.js')}}
		{{ HTML::script('js/bootstrap-dropdown.js') }}

		{{ HTML::script('js/jquery.chained.min.js') }}
		{{ HTML::style('css/template.css')}}

	</head>
	<body role="document">
		 <div class="container theme-showcase" role="main">
			@yield('content')
		</div>
	</body>
</html>