<html>
	<head>
		<title>Tarlac Procurement Tracking System</title>

		<script src="jquery-1.4.min.js" type="text/javascript"></script>
		<script src="jquery.flash.min.js" type="text/javascript"></script>
		<link type="text/css" rel="stylesheet" href="unrelated.css" />
		<script src="agile-uploader-3.0.js" type="text/javascript"></script>
		<link type="text/css" rel="stylesheet" href="agile-uploader.css" />
	</head>
	<body >
		<div id="demo"  >
			<h1>Upload Attachments</h1>


			@if ( Session::get('pr_id') )
            	{{ Session::get('pr_id'); }}        
			@endif

			<form id="multipleDemo" enctype="multipart/form-data">
				<label for="title"> Document Title</label><br />
				<input id="title" type="input" name="title" />
				<br style="clear: left;" />

				<div id="multiple"></div>
	    
				<br style="clear: left;" /><br />
			</form>

			<a href="#" onClick="document.getElementById('agileUploaderSWF').submit();"><button class ="btn btn-default">Submit</button></a>
	

		</div>

	    <script type="text/javascript">
	    	$('#multiple').agileUploader({
	    		submitRedirect: 'resultstest',
	    		formId: 'multipleDemo',
			flashVars: {
				firebug: false,
	    			form_action: 'process.blade.php',
				file_limit: 10,
				max_post_size: (100000 * 102400)
	    		}
	    	});	
	    </script>
	</body>
</html>
