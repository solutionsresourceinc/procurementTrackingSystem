<html>
<head>
	<script src="jquery-1.4.min.js" type="text/javascript"></script>
	<script src="jquery.flash.min.js" type="text/javascript"></script>
	<link type="text/css" rel="stylesheet" href="unrelated.css" />
	
	<script src="agile-uploader-3.0.js" type="text/javascript"></script>
	<link type="text/css" rel="stylesheet" href="agile-uploader.css" />
</head>
<body>

<div id="demo">
<h1>Resize Before Upload Demo (multiple)</h1>



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
    		submitRedirect: 'results.php',
    		formId: 'multipleDemo',
		flashVars: {
			firebug: false,
    			form_action: 'process.php',
			file_limit: 5,
			max_post_size: (10000 * 10240)
    		}
    	});	
    </script>

</body>
</html>
