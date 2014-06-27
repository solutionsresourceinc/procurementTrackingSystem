@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">List of Overdue Purchase Requests</h1>
    
    @if ( Entrust::hasRole('Administrator') || Entrust::hasRole('Procurement Personnel'))
      <div align="right">
          <a href="{{ URL::to('purchaseRequest/create') }}" class="btn btn-success">Create New</a>
          <br><br><br>
      </div>
    @endif

    <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete Purchase Request</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete purchase request?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm">Delete</button>
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
        				$requests = DB::table('purchase_request')->get(); //change this to get overdue PRs
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
      			        	  <a class='iframe btn btn-primary' href="{{ URL::to('purchaseRequest/vieweach/'. $request->id) }}" title="View Purchase Request"><span class="glyphicon glyphicon-file"></span></a></li>
                        <?php
                          $adm = Assigned::where('user_id', Auth::User()->id)->first();
                          if($adm->role_id == 3) {
                        ?>
                            <a class='iframe btn btn-success' href='edit/{{$request->id}}' title="Edit Purchase Request"><span class="glyphicon glyphicon-edit"></span></a>
                            <form method="POST" action="delete" id="myForm_{{ $request->id }}" name="myForm" style="display: -webkit-inline-box;">
                                 <input type="hidden" name="del_pr" value="{{ $request->id }}">
                                 <center><button class="iframe btn btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $request->id }})"  data-title="Delete Purchase Request" title="Delete Purchase Request" data-message="Are you sure you want to delete purchase request?"><span class="glyphicon glyphicon-trash"></span></button></center>
                            </form>
                        <?php } ?>
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
@stop