



 <?php
$id=$_REQUEST['id'];
  $attachments= new Attachments; 
 $attachments = DB::table('attachments')->find($id); 

$image= $attachments->data;

?>



<?php
function data_uri($image, $mime) 
{  

  $base64   = base64_encode($image); 
  return ('data:' . $mime . ';base64,' . $base64);
}
?>
<img src="<?php echo data_uri( $image, 'image/png'); ?>" alt="An elephant" />