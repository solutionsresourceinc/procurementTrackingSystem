@extends('layouts.dashboard')

@section('content')


<h1 class="pull-left">List of Active Purchase Requests</h1>
    
    @if ( Entrust::hasRole('Administrator') || Entrust::hasRole('Procurement Personnel'))
      <div class="pull-right options">
          <a href="{{ URL::to('purchaseRequest/create') }}" class="btn btn-success">Create New</a>
      </div>
    @endif

    <hr class="clear" />
<div id="pr_form">
    <form action="submitForm/" id="new_form" method="post" id="confirm">
</div>

    <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Cancel Purchase Request</h4>
                </div>
                <div class="modal-body">
                    <p>Reason for cancelling purchase request:</p>
                    <textarea id="reason" class="form-control" rows="3" maxlength="255", style="resize:vertical"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onClick="submitForm()">Cancel Purchase Request</button>
                    
                </div>
            
            </div>
        </div>
    </div>
</form>

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
@if(Session::get('notice'))
            <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
            @endif
    <table id="table_id" class="table table-striped display ">
        <thead>
    		<tr>
                <th>Control No.</th>
        	    <th>Project/Purpose</th>
                <th>Mode</th>
        	    <th>Status</th>
        	    <th>Date Requested</th>
                <?php
                    $adm = Assigned::where('user_id', Auth::User()->id)->first();
                    if($adm->role_id == 3) {
                ?>
        		  <th>Action</th>
                <?php } ?>
        	  </tr>
    	</thead>

        <?php
            $date_today =date('Y-m-d H:i:s');
            $requests = new Purchase;
            $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'New')->orWhere('status', '=', 'In progress')->get();
        ?>

      	<tbody>
            @if(count($requests))
                @foreach ($requests as $request)
                    <tr
                      <?php 
                        $doc = new Document; $doc = DB::table('document')->where('pr_id', $request->id)->first();  
                        $doc_id= $doc->id;
                    $userx= Auth::User()->id;
                    $counter=0;
                    $counter=Count::where('user_id', $userx)->where('doc_id', $doc_id)->count();
                    if ($counter!=0){
                        echo "class ='success'";
                    }

                    ?>
                        >
                        <td width="10%">{{ $request->controlNo; }}</td>
                        <td width="30%"><a data-toggle="tooltip" data-placement="top" class="purpose" href="{{ URL::to('purchaseRequest/vieweach/'. $request->id) }}" title="View Project Details">{{ $request->projectPurpose; }}</a></td>
                        <?php 
                            $doc = new Purchase; 
                            $doc = DB::table('document')->where('pr_id', $request->id)->get(); 
                        ?>
                        <td width="18%">
                            @foreach ($doc as $docs) {{ Workflow::find($docs->work_id)->workFlowName; }} @endforeach
                        </td>
                        <td width="12%"><span class="label {{($request->status == 'New') ? 'label-primary':'label-success'}}">{{ $request->status; }}</span></td>
                        <td width="20%">{{ $request->dateRequested; }}</td>

                        @if($adm->role_id == 3 || $adm->role_id == 2)
                            <td width="10%">
                                <a data-toggle="tooltip" data-placement="top" class='iframe btn btn-success' href='edit/{{$request->id}}' title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
                                <form method="POST" action="delete" id="myForm_{{ $request->id }}" name="myForm" style="display: -webkit-inline-box;">
                                   <input type="hidden" name="del_pr" value="{{ $request->id }}">
                                   <center> <a href="changeForm/{{ $request->id }}" class="btn ajax btn-danger" data-method="post" data-replace="#pr_form" data-toggle="modal" data-target="#confirmDelete" data-toggle="tooltip" title="Cancel"><span class="glyphicon glyphicon-remove"></span></a></center>
                               </form>
                            </td>
                        @else
                            <td width="10%">
                                <form method="POST" action="delete" id="myForm_{{ $request->id }}" name="myForm" style="display: -webkit-inline-box;">
                                   <input type="hidden" name="del_pr" value="{{ $request->id }}">
                                   <center> <a href="changeForm/{{ $request->id }}" class="btn ajax btn-danger" data-method="post" data-replace="#pr_form" data-toggle="modal" data-target="#confirmDelete" data-toggle="tooltip" title="Cancel"><span class="glyphicon glyphicon-remove"></span></a></center>
                               </form>  
                            </td> 
                        @endif
                   </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="<?php if($adm->role_id == 3) echo "6"; else echo "5";?>">
                        <span class="error-view">No data available.</span>
                    </td>
                </tr>
            @endif
      	</tbody>
    </table>  
                {{ Session::forget('notice'); }}
@stop

@section('footer')
    {{ HTML::script('js/bootstrap-ajax.js');}}
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

        function submitForm()
        {
            var reason = document.getElementById('reason').value;
            document.getElementById('hide_reason').value = reason;
            //alert(reason);
            document.getElementById("form").submit();
        }
        
    </script>

{{ Session::forget('main_error'); }}
{{ Session::forget('m1'); }}
{{ Session::forget('m2'); }}
{{ Session::forget('m3'); }}
{{ Session::forget('m4'); }}
{{ Session::forget('m5'); }}
{{ Session::forget('m6'); }}
{{ Session::forget('m7'); }}
{{ Session::forget('imgsuccess'); }}
{{ Session::forget('imgerror'); }}

@stop