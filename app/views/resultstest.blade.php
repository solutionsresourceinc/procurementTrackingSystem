<?php
$thelist=" ";
$source= public_path()."\\temp\\";
 if ($handle = opendir($source)) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {	  


          	$thelist .= '<a href="'.$source.$file.'">'.$file.'</a> ('.round((filesize($source.$file) / 1024), 2) . ' Kb)<br />';
          

          }
       }
  closedir($handle);
  }
 

$document= new Document;
$document->pr_id= Session::get('pr_id');
//$document->doctitle= Session::get('doc_title');
$sho=Session::get('doc_title');
echo $sho;
$document->work_id=1;
$document->save();

Session::put('doc_id', $document->id);
 if ($handle = opendir($source)) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {	  
$attachments= new Attachments;
$attachments->doc_id = $document->id;
$pathfile=$source.$file;
$attachments->data= file_get_contents($pathfile);
$attachments->save();
Session::put('imageidentity', $attachments->update_at);
 unlink($source.$file);}}


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

<a href="back">Go back</a>



</div>
	</body>
</html>

