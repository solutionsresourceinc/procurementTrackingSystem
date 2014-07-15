<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete Added Task</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete Added Task?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="confirm">Delete</button>
			</div>
		</div>
	</div>
</div>

<script>
	$('#confirmDelete').on('show.bs.modal', function (e) {
		$message = $(e.relatedTarget).attr('data-message');
		$(this).find('.modal-body p').text($message);

		$title = $(e.relatedTarget).attr('data-title');
		$(this).find('.modal-title').text($title);

		var form = $(e.relatedTarget).closest('form');

		$(this).find('.modal-footer #confirm').data('form', form);
	});

	$('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
	    var name = "myForm_" + window.my_id; 
	    document.getElementById(name).submit();
 	});

	function hello(pass_id)
	{
		window.my_id = pass_id;
 	}
</script>