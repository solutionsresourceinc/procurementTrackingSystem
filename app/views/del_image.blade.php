<?php
	$id = Input::get('hide');
	$attach = DB::table('attachments')->where('id', $id)->delete();
	$notice="Attachment successfully deleted.";
	Redirect::back()->with( 'notice', $notice );
?>