@extends('layouts.default')

@section('content')

<h3>Dashboard</h3>


<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Disable User</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure about this ?</p>
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
        <h4 class="modal-title">Activate User</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure about this ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm">Activate</button>
      </div>
    </div>
  </div>
</div>




<table id="table_id" class="display">
    <thead>
		<tr>
	    	<th>Username</th>
	        <th>Firstname</th>
	        <th>Lastname</th>
	        <th>Role</th>
	        <th>Office</th>
            @if(Entrust::can('can_update') || Entrust::can('can_delete'))
	            <th>Action</th>
            @endif
	    </tr>
	</thead>

	<tbody>

		<?php
			$users= new User; $users = DB::table('users')->get();
		?>

		@foreach ($users as $user)
		<?php

			$assigned = Assigned::where('user_id', $user->id)->first();
		?>

	        <tr>
	        	@if($user->confirmed == 0)
	        		<td><strike> {{ $user->username; }} </strike></td>
			        <td><strike> {{ $user->firstname; }} </strike></td>
			        <td><strike> {{ $user->lastname; }} </strike></td>
			        
			        @if($assigned->role_id == 1)
						<td><strike>Administrator</strike></td>
					@else
						<td><strike>Member</strike></td>
					@endif
	       		@else
					<td> {{ $user->username; }}</td>
			        <td> {{ $user->firstname; }}</td>
			        <td> {{ $user->lastname; }}</td>

			        @if($assigned->role_id == 1)
						<td>Administrator</td>
					@else
						<td>Member</td>
					@endif
			    @endif
				
				<td>OFFICE</td>

				@if(Entrust::can('can_update') || Entrust::can('can_delete'))

             	@endif

	            @if(Entrust::can('can_update') ||Entrust::can('can_delete') )
		        <td>
		        	<div class='btn-group'>
						<button class='btn dropdown-toggle btn-primary' data-toggle='dropdown'>Action <span class='caret'></span></button>
						
						<ul class='dropdown-menu'>
		              		@if(Entrust::can('can_update'))
								<li><a class='iframe btn' href='edit/{{$user->id}}'>Edit</a></li>
		            		@endif

		            		@if(Entrust::can('can_delete'))
		            			@if($user->confirmed == 1)
									<li>
										<form method="POST" action="delete"/ id="myForm_{{ $user->id }}" name="myForm">
											<input type="hidden" name="hide" value="{{ $user->id }}">
			  								<center>
			    							<button class="iframe btn" style="background-color:transparent" type="button" data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $user->id }})"  data-title="Delete User" data-message="Are you sure you want to disable account?">Disable</button>
											</center>
										</form>
									</li>
								@else
									<li>
										<form method="POST" action="activate"/ id="myForm_{{ $user->id }}" name="myForm">
											<input type="hidden" name="hide" value="{{ $user->id }}">
			  								<center>
			    							<button class="iframe btn" style="background-color:transparent" type="button" data-toggle="modal" data-target="#confirmActivate" onclick="hello( {{ $user->id }})"  data-title="Activate User" data-message="Are you sure you want to activate account?">Activate</button>
											</center>
										</form>
									</li>
								@endif
							@endif
						</ul>
					</div>
	       		</td>
	        	@endif
			</tr>
        @endforeach	    
	</tbody>
</table>


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

     //$(this).data('form').submit();
      var name = "myForm_" + window.my_id; 
      document.getElementById(name).submit();
     //alert(name);
  });
  function hello(pass_id)
  {
      window.my_id = pass_id;
     // alert(window.my_id);
  }
  </script>


<script type="text/javascript">

  $('#confirmActivate').on('show.bs.modal', function (e) {

      $message = $(e.relatedTarget).attr('data-message');
  $(this).find('.modal-body p').text($message);

  $title = $(e.relatedTarget).attr('data-title');
  $(this).find('.modal-title').text($title);

      var form = $(e.relatedTarget).closest('form');

      $(this).find('.modal-footer #confirm').data('form', form);

  });

  $('#confirmActivate').find('.modal-footer #confirm').on('click', function(){

     //$(this).data('form').submit();
      var name = "myForm_" + window.my_id; 
      document.getElementById(name).submit();
     //alert(name);
  });
  function hello(pass_id)
  {
      window.my_id = pass_id;
     // alert(window.my_id);
  }
  </script>

  @stop