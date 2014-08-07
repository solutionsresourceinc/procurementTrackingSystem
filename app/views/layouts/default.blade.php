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
		@yield('header')
		<style>
			body { background-image:url('../bg.png'); }
		</style>
	</head>
	<body role="document">
	    <div class="container theme-showcase" role="main">
		@yield('content')
		</div>
		
		
		@yield('footer')
		<footer class="bs-docs-footer" role="contentinfo">
			<p class="text-muted" style="text-align: center; font-size: 11px;">Developed by 
            <a href="http://solutionsresource.com/" title="Solutions Resource Inc. - Web Design and Development Seattle Wa, Mobile Apps, Internet and Social Media Marketing">
            Solutions Resource, Inc.</a><br/>
            Powered by <a href="http://laravel.com/" style="color: #f47063">Laravel</a>.
        </p>
		</footer>
	</body>
</html>