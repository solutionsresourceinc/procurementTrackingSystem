<?php
/**
 * Note: This is purely an example script. It will not do a whole lot and probably won't suit your needs. 
 * Please keep in mind that if you allow certain types of files to be uploaded to your server you could create a security risk.
 * Take note of the switches below that check file types.
*/

// CHANGE THIS ACCORDINGLY
$target_path = "temp/";

$myFile = $target_path."post_data.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, "Title: ".$_POST['title']."\n");
fwrite($fh, "Another Field: ".$_POST['testdata']."\n");
fclose($fh);

$uploads_dir = $target_path;

if(count($_FILES["Filedata"]["error"]) < 2) {
	// Single file
	$tmp_name = $_FILES["Filedata"]["tmp_name"];
	$name = $_FILES["Filedata"]["name"];
	$ext = substr(strrchr($name, '.'), 1);
	switch(strtolower($ext)) {
		case 'jpg':	
		case 'jpeg':
		case 'png':
		case 'gif':
		case 'png':
		case 'doc':
		case 'txt':
			move_uploaded_file($tmp_name, "$uploads_dir/$name");
		break;
		default:
			exit();
		break;
	}
} else {
	// Multiple files
	foreach ($_FILES["Filedata"]["error"] as $key => $error) {
	    if ($error == UPLOAD_ERR_OK) {
	        $tmp_name = $_FILES["Filedata"]["tmp_name"][$key];
	        $name = $_FILES["Filedata"]["name"][$key];
	        $ext = substr(strrchr($name, '.'), 1);
	        switch(strtolower($ext)) {
				case 'jpg':	
				case 'jpeg':
				case 'png':
				case 'gif':
				case 'png':
				case 'doc':
				case 'txt':
					move_uploaded_file($tmp_name, "$uploads_dir/$name");
				break;
				default:
					exit();
				break;
			}
	    }
	}
}

echo 'RETURN DATA!';

?>
