@extends('layouts.dashboard')

@section('content')
    
    <h1 class="pull-left">List of Cancelled Purchase Requests</h1>
    
  

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
                        <button type="button" class="btn btn-success" onClick="submitForm()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(Session::get('notice'))
        <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
    @endif
    
    <!-- START OF SEARCH BOX -->
    <div align="center" class="col-md-8"></div>   
    <div align="center" class="col-md-4">   
        <input type="text" class="form-control filter" placeholder="Enter Seach Keywords"> 
    </div>
    <br/>
    <br/>
    <br/>
    <!-- END OF SEARCH BOX -->

    <table id="table_id" class="table table-striped display ">
        <thead>
            <tr>
                <th>Control No.</th>
                <th>Project/Purpose</th>
                <th>Mode</th>
                <th style="text-align: center">Amount</th>
                <th>Date Received</th>
              </tr>
        </thead>

        <?php
           //Query Restrictions
            $date_today =date('Y-m-d H:i:s');
            $requests = new Purchase;
           
            $user_selected=Auth::user()->id;
            if(Entrust::hasRole('Requisitioner'))
                $requests = DB::table('purchase_request')->where('status', '=', 'Cancelled')->where('requisitioner',$user_selected)->paginate(10); 
            else
                $requests = DB::table('purchase_request')->where('status', '=', 'Cancelled')->paginate(10); 
            //End Query Restrictions
        ?>

        <tbody class="searchable">
            @if(count($requests))
                @foreach ($requests as $request)
                    <tr
                    <?php 
                        //Office restriction
                        if (Entrust::hasRole('Administrator')){}
                        else if(Entrust::hasRole('Procurement Personnel')){}
                        
                        else
                        {
                            $useroffice=Auth::user()->office_id;
                            $maker= User::find( $request->requisitioner);
                            if ($useroffice!=$maker->office_id)
                                continue;
                        }
                        //End Office restriction

                        $doc = new Document; $doc = DB::table('document')->where('pr_id', $request->id)->first();  
                        $doc_id= $doc->id;
                        $user_selected= Auth::User()->id;
                        $counter=0;
                        $counter=Count::where('user_id', $user_selected)->where('doc_id', $doc_id)->count();
                        if ($counter!=0){
                            echo "class ='success'";
                        }
                    ?>
                        >
                        <td width="10%">{{ $request->controlNo; }}</td>
                        <td width="30%">{{ $request->projectPurpose; }}</td>
                        <?php 
                            $docs = new Purchase; 
                            $docs = DB::table('document')->where('pr_id', $request->id)->get(); 
                        ?>
                        <td width="18%">
                            @foreach ($docs as $doc) {{ Workflow::find($doc->work_id)->workFlowName; }} @endforeach
                        </td>
                        <td width="17%" style="text-align: center">P{{{ $request->amount }}}</td>
                        <td width="15%">{{ $request->dateReceived; }}</td>

                       
                   </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="<?php if(Entrust::hasRole('Administrator') ) echo "6"; else echo "5";?>">
                        <span class="error-view">No data available.</span>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>  
    
    <div id="pages" align="center">
        <center> {{ $requests->links(); }} </center>
    </div>

    <div id="searchTable">
        <table id="table_id2" class="table table-striped display " style="display:none">
        <thead>
            <tr>
                <th>Control No.</th>
                <th>Project/Purpose</th>
                <th>Mode</th>
                <th style="text-align: center">Amount</th>
                <th>Date Received</th>
              </tr>
        </thead>

        <?php
           //Query Restrictions
            $date_today =date('Y-m-d H:i:s');
            $requests = new Purchase;
           
            $user_selected=Auth::user()->id;
              $requests = DB::table('purchase_request')->where('status', '=', 'Cancelled')->get(); 
            //End Query Restrictions
        ?>

        <tbody class="searchable">
            @if(count($requests))
                @foreach ($requests as $request)
                    <tr
                    <?php 
                        //Office restriction
                        if (Entrust::hasRole('Administrator')){}
                        else if(Entrust::hasRole('Procurement Personnel')){    
                                $useroffice=Auth::user()->office_id;
                                $maker= User::find( $request->requisitioner);
                                $docget=Document::where('pr_id', $request->id)->first();
                                $taskd = TaskDetails::where('doc_id',$docget->id)->where('assignee_id',$user_selected)->count();
                                if($taskd!=0){}
                                else if ($user_selected==$request->created_by){}
                                else if ($useroffice!=$maker->office_id)
                                    continue;
                        }
                        else
                        {
                            $useroffice=Auth::user()->office_id;
                            $maker= User::find( $request->requisitioner);
                            if ($useroffice!=$maker->office_id)
                                continue;
                        }
                        //End Office restriction

                        $doc = new Document; $doc = DB::table('document')->where('pr_id', $request->id)->first();  
                        $doc_id= $doc->id;
                        $user_selected= Auth::User()->id;
                        $counter=0;
                        $counter=Count::where('user_id', $user_selected)->where('doc_id', $doc_id)->count();
                        if ($counter!=0){
                            echo "class ='success'";
                        }
                    ?>
                        >
                        <td width="10%">{{ $request->controlNo; }}</td>
                        <td width="30%"><a data-toggle="tooltip" data-placement="top" class="purpose" href="{{ URL::to('purchaseRequest/vieweach/'. $request->id) }}" title="View Project Details">{{ $request->projectPurpose; }}</a></td>
                        <?php 
                            $docs = new Purchase; 
                            $docs = DB::table('document')->where('pr_id', $request->id)->get(); 
                        ?>
                        <td width="18%">
                            @foreach ($docs as $doc) {{ Workflow::find($doc->work_id)->workFlowName; }} @endforeach
                        </td>
                        <td width="17%" style="text-align: center">P{{{ $request->amount }}}</td>
                        <td width="15%">{{ $request->dateReceived; }}</td>

                       
                   </tr>
                @endforeach
            @endif
        </tbody>
    </table> 


    </div>

    <div class="row" id="table_id3" style="display:none">
        <span class="error-view">No data available.</span>
    </div>

    {{ Session::forget('notice'); }}
@stop

@section('footer')
    {{ HTML::script('js/bootstrap-ajax.js');}}
    <script type="text/javascript">
        // START *code for search box
        $('input.filter').on('keyup', function() 
        {
            var rex = new RegExp($(this).val(), 'i');
            if(rex == '/(?:)/i')
            {
                document.getElementById('table_id2').style.display = 'table';
                $('.searchable tr').hide();
                $('.searchable tr').filter(function() {
                    return rex.test($(this).text());
                }).show();
                document.getElementById('table_id').style.display = 'table';
                document.getElementById('pages').style.display = 'table';
                document.getElementById('table_id2').style.display = 'none';
                document.getElementById('table_id3').style.display = 'none';

            }
            else
            {
                document.getElementById('table_id2').style.display = 'table';
                $('.searchable tr').hide();
                $('.searchable tr').filter(function() {
                    return rex.test($(this).text());
                }).show();

                var rowNum = $('#table_id2 tr:visible ').length;
                if(rowNum == 1)
                {
                    document.getElementById('table_id').style.display = 'none';
                    document.getElementById('pages').style.display = 'none';
                    document.getElementById('table_id2').style.display = 'none';
                    document.getElementById('table_id3').style.display = 'block';
                }
                else
                {
                    document.getElementById('table_id').style.display = 'none';
                    document.getElementById('pages').style.display = 'none';
                    document.getElementById('table_id2').style.display = 'table';
                    document.getElementById('table_id3').style.display = 'none';
                }
                

            }
            
        });
       
        // END

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

        function submitForm()
        {
            var reason = document.getElementById('reason').value;
            document.getElementById('hide_reason').value = reason;
            document.getElementById("form").submit();
        }
    </script>

    {{ Session::forget('main_error'); }}
    {{ Session::forget('imgsuccess'); }}
    {{ Session::forget('imgerror'); }}
@stop