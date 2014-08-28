@extends('layouts.dashboard')

@section('content')

<h1 class="pull-left">User Management</h1>
<div class="pull-right options">
	<a href="{{ URL::to('user/create') }}" class="btn btn-success">Create New</a>
</div>

<hr class="clear" />

<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><b>Disable User Account</b></h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to disable this user account?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="confirm">Disable</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmActivate" role="dialog" aria-labelledby="confirmActivateLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><b>Activate User Account</b></h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to activate user account?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="confirm">Activate</button>
			</div>
		</div>
	</div>
</div>


@if(Session::get('notice'))
	<div class="alert alert-success"> {{ Session::get('notice') }}</div>
@endif

<table id="table_id" class="table table-striped display tablesorter">
	<thead>
		<tr>
			<th>Username</th>
			<th>Firstname</th>
			<th>Lastname</th>
			<th>Role</th>
			<th>Office</th>
			<?php
				$admin = Assigned::where('user_id', Auth::User()->id)->first();
				$users = DB::table('users');
                $userCounter = $users->count();
                $users = $users->paginate(10);
			?>
			@if($admin->role_id == 3)
				<th>Action</th>
			@endif
		</tr>
	</thead>

	<tbody>
		@foreach ($users as $user)
			<?php $assigned = Assigned::where('user_id', $user->id)->first(); ?>
			<tr>
				@if($user->confirmed == 0)
					<td><strike><font color="grey"> {{ $user->username; }} </font></strike></td>
					<td><strike><font color="grey"> {{ $user->firstname; }} </font></strike></td>
					<td><strike><font color="grey"> {{ $user->lastname; }} </font></strike></td>
						@if($assigned->role_id == 3)
							<td><strike><font color="grey"> Administrator </font></strike></td>
						@elseif ($assigned->role_id == 2)
							<td><strike><font color="grey"> Procurement Personnel </font></strike></td>
						@else
							<td><strike><font color="grey"> Requisitioner </font></strike></td>
						@endif
				@else
					<td> {{ $user->username; }}</td>
					<td> {{ $user->firstname; }}</td>
					<td> {{ $user->lastname; }}</td>

					@if($assigned->role_id == 3)
						<td>Administrator</strike></td>
					@elseif($assigned->role_id == 2)
						<td>Procurement Personnel</td>
					@else
						<td>Requisitioner</td>
					@endif
				@endif

				<?php $offices = Office::where('id',$user->office_id)->get(); ?>
				<td>
					@foreach($offices as $office)
						@if($user->confirmed == 0)
							<strike><font color="grey"> {{{ $office->officeName}}} </font></strike>
						@else
							{{{ $office->officeName }}}
						@endif
					@endforeach
				</td>

				@if($admin->role_id == 3)
					<td>
						@if($admin->role_id == 3)
							<a class='iframe btn btn-success' href='edit/{{$user->id}}' title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
						@endif

						@if($user->confirmed == 1)
							<form method="POST" action="delete" id="myForm_{{ $user->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="hide" value="{{ $user->id }}">
								<center>
									<button class="iframe btn btn-warning" type="button" data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $user->id }})"  data-toggle="tooltip" data-placement="top"  title="Deactivate User"><span class="glyphicon glyphicon-ban-circle"></span></button>
								</center>
							</form>
						@else
							<form method="POST" action="activate" id="myForm_{{ $user->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="hide" value="{{ $user->id }}">
								<center>
									<button class="iframe btn btn-primary" type="button" data-toggle="modal" data-target="#confirmActivate" onclick="hello( {{ $user->id }})"  data-toggle="tooltip" data-placement="top"  title="Activate User"><span class="glyphicon glyphicon-ok"></span></button>
								</center>
							</form>
						@endif
					</td>
				@endif
			</tr>
		@endforeach
	</tbody>
</table>
<div id="pages" align="center" class="no-print">
    @if($userCounter != 0)
    {{ $users->links() }}
    @else
    <p><i>No user accounts available</i></p>
    @endif
</div>


<script type="text/javascript">

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

	$('#confirmActivate').on('show.bs.modal', function (e) {
	 	$message = $(e.relatedTarget).attr('data-message');
	 	$(this).find('.modal-body p').text($message);

	 	$title = $(e.relatedTarget).attr('data-title');
	 	$(this).find('.modal-title').text($title);

	 	var form = $(e.relatedTarget).closest('form');

	 	$(this).find('.modal-footer #confirm').data('form', form);
	});

	$('#confirmActivate').find('.modal-footer #confirm').on('click', function(){
	     var name = "myForm_" + window.my_id;
        document.getElementById(name).submit();
	});
</script>
@stop