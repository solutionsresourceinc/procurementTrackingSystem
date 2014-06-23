<?php
$thelist=" ";
 if ($handle = opendir('temp')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {	  
$source="temp\\";
$destination="uploads\\";
copy ($source.$file, $destination.$file);
          	$thelist .= '<a href="uploads/'.$file.'">'.$file.'</a> ('.round((filesize('temp/'.$file) / 1024), 2) . ' Kb)<br />';
          
 unlink($source.$file);
          }
       }
  closedir($handle);
  }

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Tarlac Procurement Tracking System</title>


		

<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" href="css/bootstrap-theme.min.css" />
	<link type="text/css" rel="stylesheet" href="css/theme.css" />
	<link type="text/css" rel="stylesheet" href="css/signin.css" />
	</head>
	<body role="document">
		 <div class="container theme-showcase" role="main">


<h2>The following files have been successfully been uploaded:</h2>
<p><?php echo $thelist ?></p>
<p>If a certain file is not within the list, it means that the file type of that file is invalid. The system only accept .png, jpeg, and .gif files.</p>
";




</div>
	</body>
</html>

