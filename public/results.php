<?php
$thelist=" ";
 if ($handle = opendir('temp')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {	  		
          	$thelist .= '<a href="temp/'.$file.'">'.$file.'</a> ('.round((filesize('temp/'.$file) / 1024), 2) . ' Kb)<br />';
          }
       }
  closedir($handle);
  }
?>
<p>List of files (files removed hourly):</p>
<p><?php echo $thelist; ?></p>
