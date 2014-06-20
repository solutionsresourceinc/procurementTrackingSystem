@extends('layouts.default')

@section('content')
    <h1 class="page-header">Dashboard</h1>

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

    <table id="table_id" class="table table-striped display">
        <thead>
        		<tr>
        	    	<th>Project/Purpose</th>
        	      <th>Status</th>
        	      <th>Date Created</th>
        	      <?php
        	        	$adm = Assigned::where('user_id', Auth::User()->id)->first();
        	        	$requests = new Purchase;
        				    $requests = DB::table('purchase_request')->get();
        			  ?>
        			  <th>Action</th>
        	  </tr>
    	  </thead>

      	<tbody>
      		  @foreach ($requests as $request)
      	        <tr>
      					    <td> {{ $request->projectPurpose; }}</td>
      			        <td> {{ $request->status; }}</td>
      			        <td> {{ $request->created_at; }}</td>
      			        <td>
      			        	  <div class='btn-group'>
      						          <button class='btn dropdown-toggle btn-primary' data-toggle='dropdown'>Action <span class='caret'></span></button>
      						
      						          <ul class='dropdown-menu'>
      		              		    <?php if($adm->role_id == 3) {?>
      								          <li><a class='iframe btn' href='edit/{{$request->id}}'>Edit</a></li>
      							            <?php } ?>
      		            			    @if($adm->role_id == 3)
                									<li>
                  										<form method="POST" action="delete"/ id="myForm_{{ $request->id }}" name="myForm">
                    											<input type="hidden" name="hide" value="{{ $request->id }}">
                    			  								  <center>
        			    							                  <button class="iframe btn" style="background-color:transparent" type="button" data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $request->id }})"  data-title="Delete User" data-message="Are you sure you want to disable account?">Disable</button>
        											                </center>
        										          </form>
        									        </li>
      								          @else
                									<li>
                										<form method="POST" action="activate"/ id="myForm_{{ $request->id }}" name="myForm">
                											<input type="hidden" name="hide" value="{{ $request->id }}">
                			  								<center>
                			    							<button class="iframe btn" style="background-color:transparent" type="button" data-toggle="modal" data-target="#confirmActivate" onclick="hello( {{ $request->id }})"  data-title="Activate User" data-message="Are you sure you want to activate account?">Activate</button>
                											</center>
                										</form>
                									</li>
      							            @endif
      						          </ul>
      					        </div>
      			        </td>
      			    </tr>
            @endforeach	    
      	</tbody>
    </table>  
@stop

@section('footer')
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