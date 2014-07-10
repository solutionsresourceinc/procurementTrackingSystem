<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Tarlac Procurement Tracking System</title>
        {{ HTML::style('css/bootstrap.css') }}
        {{ HTML::style('css/bootstrap.min.css') }}
        {{ HTML::style('css/bootstrap-theme.min.css') }}
        {{ HTML::style('css/theme.css') }}
        {{ HTML::style('css/signin.css') }}
        {{ HTML::style('css/custom.css') }}
        {{ HTML::style('css/sb-admin.css') }}
        {{ HTML::style('font-awesome/css/font-awesome.min.css') }}

        {{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}

        {{ HTML::script('js/bootstrap.min.js') }}
        {{ HTML::script('js/jquery-1.10.2.js') }}

        <!-- Agile Image Uploader-->
        {{ HTML::script('jquery-1.4.min.js')}}
        {{ HTML::script('jquery.flash.min.js')}}
        {{ HTML::script('agile-uploader-3.0.js')}}
        {{ HTML::style('agile-uploader.css') }}

        {{ HTML::style('colvix/css/jquery.dataTables.css')}}
        {{ HTML::style('colvix/css/dataTables.colVis.css')}}
        {{ HTML::style('colvix/css/shCore.css')}}
        

        {{ HTML::script('colvix/js/jquery.js')}}
        {{ HTML::script('colvix/js/jquery.dataTables.js')}}
        {{ HTML::script('colvix/js/dataTables.colVis.js')}}
        {{ HTML::script('colvix/js/shCore.js')}}
        {{ HTML::script('colvix/js/demo.js')}}

        {{ HTML::script('js/jquery.tablesorter.min.js')}}

        @yield('header')
        
        <style type="text/css">
        #confirmDelete {
        height: 400px;
        top: calc(50% - 200px) !important;
        overflow: hidden;
        }
         #confirmActivate {
        height: 400px;
        top: calc(50% - 200px) !important;
        overflow: hidden;
        }
        </style>
    </head>
    <body>

    <div id="wrapper">

        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">{{ HTML::image('img/logo-nav-abbrev.png', 'Tarlac Procurement Tracking System', array('id' => '')) }}</a>
            </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li class="{{Request::is('dashboard') ? 'active':''}}"><a href="/" style="border-bottom: solid 1px rgba(0, 0, 0, 0.2);"><!--i class="fa fa-dashboard"></i--> Dashboard</a></li>
            <li class="{{(Request::is('purchaseRequests') || Request::is('purchaseRequest/view') || Request::is('purchaseRequest/closed') || Request::is('purchaseRequest/overdue')) ? 'active' : ''}}"><a href="#" class="unlink">Purchase Requests</a>
                <ul class="side-submenu">
                    <li class="{{Request::is('purchaseRequest/view') ? 'active':''}}">
                        <a href="/purchaseRequest/view">
                            Active Purchase Requests
                            <span class="badge pull-right">
                            <?php
                                        $result=0;
                                        $cuser= Auth::user()->id;
                $date_today =date('Y-m-d H:i:s');
                                   $cpurchase= DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->get();
                                foreach ($cpurchase as $cpurchases ) {
                                    $doc=Document::where('pr_id', $cpurchases->id)->first();
                                    $count= DB::table('count')->where('doc_id', $doc->id)->where('user_id', $cuser)->count();
                                    if($count==0){

                                    }
                                    else
                                        {
                                        if ( Entrust::hasRole('Administrator')) {
                                            $result=$result+1; }
                                            else if (Entrust::hasRole('Procurement Personnel'))
                                            {
                                                if($cpurchases->created_by==$cuser)   $result=$result+1;
                                            }
                                            else if(Entrust::hasRole('Requisitioner'))
                                            {
                                                if($cpurchases->requisitioner==$cuser) $result=$result+1;
                                            }
                                        }
                                }
                                echo $result;

                                ?>
                            </span>
                        </a>
                    </li>
                    <li class="{{Request::is('purchaseRequest/closed') ? 'active':''}}">
                        <a href="/purchaseRequest/closed">
                            Closed Purchase Requests
                            <span class="badge pull-right">
                               <?php
                                        $result=0;
                                        $cuser= Auth::user()->id;
                                   $cpurchase= DB::table('purchase_request')->where('status', '=', 'Closed')->get();
                                foreach ($cpurchase as $cpurchases ) {
                                    $doc=Document::where('pr_id', $cpurchases->id)->first();
                                    $count= DB::table('count')->where('doc_id', $doc->id)->where('user_id', $cuser)->count();
                                    if($count==0){

                                    }
                                    else
                                        {if ( Entrust::hasRole('Administrator')) {
                                            $result=$result+1; }
                                            else if (Entrust::hasRole('Procurement Personnel'))
                                            {
                                                if($cpurchases->created_by==$cuser)   $result=$result+1;
                                            }
                                            else if(Entrust::hasRole('Requisitioner'))
                                            {
                                                if($cpurchases->requisitioner==$cuser) $result=$result+1;
                                            }}
                                }
                                echo $result;

                                ?>
                            </span></a>
                    </li>
                    <li class="{{Request::is('purchaseRequest/overdue') ? 'active':''}}">
                        <a href="/purchaseRequest/overdue">
                            Overdue Purchase Requests
                            <span class="badge pull-right">
                                      <?php
                                        $result=0;
                                        $cuser= Auth::user()->id;
                             $date_today =date('Y-m-d H:i:s');
                                   $cpurchase= DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->get();
                                foreach ($cpurchase as $cpurchases ) {
                                   $doc=Document::where('pr_id', $cpurchases->id)->first();
                                    $count= DB::table('count')->where('doc_id', $doc->id)->where('user_id', $cuser)->count();
                                    if($count==0){

                                    }
                                    else
                                        {if ( Entrust::hasRole('Administrator')) {
                                            $result=$result+1; }
                                            else if (Entrust::hasRole('Procurement Personnel'))
                                            {
                                                if($cpurchases->created_by==$cuser)   $result=$result+1;
                                            }
                                            else if(Entrust::hasRole('Requisitioner'))
                                            {
                                                if($cpurchases->requisitioner==$cuser) $result=$result+1;
                                            }}
                                }
                                echo $result;

                                ?>
                            </span>
                        </a>
                    </li>
                    <li class="{{Request::is('purchaseRequest/cancelled') ? 'active':''}}">
                        <a href="/purchaseRequest/cancelled">
                            Cancelled Purchase Requests
                            <span class="badge pull-right">
                                      <?php
                                        $result=0;
                                        $cuser= Auth::user()->id;
                                   $cpurchase= DB::table('purchase_request')->where('status', '=', 'Cancelled')->orWhere('status', '=', 'In progress')->get();
                                foreach ($cpurchase as $cpurchases ) {
                                  $doc=Document::where('pr_id', $cpurchases->id)->first();
                                    $count= DB::table('count')->where('doc_id', $doc->id)->where('user_id', $cuser)->count();
                                    if($count==0){

                                    }
                                    else
                                        {if ( Entrust::hasRole('Administrator')) {
                                            $result=$result+1; }
                                            else if (Entrust::hasRole('Procurement Personnel'))
                                            {
                                                if($cpurchases->created_by==$cuser)   $result=$result+1;
                                            }
                                            else if(Entrust::hasRole('Requisitioner'))
                                            {
                                                if($cpurchases->requisitioner==$cuser) $result=$result+1;
                                            }}
                                }
                                echo $result;

                                ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Change ID -->
             @if( Entrust::hasRole('Administrator') || Entrust::hasRole('Procurement Personnel') )
            <li class="{{(Request::is('task/new') || Request::is('task/active') || Request::is('task/overdue') || Request::is('task/task-id')) ? 'active' : ''}}"><a href="#" class="unlink">Tasks</a>
                <ul class="side-submenu">
                    <li class="{{Request::is('task/new') ? 'active':''}}">
                        <a href="/task/new">New Tasks<span class="badge pull-right">
                        <?php

                         $user_id = Auth::user()->id;
                 $desig=DB::table('user_has_designation')->where('users_id',$user_id)->get();
                 $counting=0;
                        $taskcount=Taskdetails::where('status', 'New'
                            )->get();
foreach ($taskcount as $taskcounter) {
    $task=Task::find($taskcounter->task_id);


foreach ($desig as $desigs) {


    if ($task->designation_id==$desigs->designation_id)
        {
    if($task->designation_id!=0)
        {
            $counting=$counting+1;
        }
        }
}
}
                        echo $counting;


                        ?>
                        </span></a>
                    </li>
                    <li class="{{Request::is('task/active') ? 'active':''}}">
                        <a href="/task/active">Active Tasks<span class="badge pull-right">
                        <?php
                            $user_id = Auth::user()->id;
                            $date_today =date('Y-m-d H:i:s');
                            $taskcount=Taskdetails::where('status', 'Active')->where("dueDate",">",$date_today)->whereAssigneeId($user_id)->count();
                            echo $taskcount;
                        ?>
                        </span></a>
                    </li>
                    <li class="{{Request::is('task/overdue') ? 'active':''}}">
                        <a href="/task/overdue">Overdue Tasks<span class="badge pull-right">

                        <?php
                            $taskcount=0;
                            $date_today =date('Y-m-d H:i:s');
                            $taskd=Taskdetails::where('status', 'Active')->where("dueDate","<",$date_today)->whereAssigneeId($user_id)->count();
                            echo $taskd;
                        ?>

                        </span></a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            @if( Entrust::hasRole('Administrator') )
            <li class="dropdown user-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration  <b class="caret" style="margin-top: 0;"></b></a>
                <ul class="dropdown-menu">
                    <li>{{ link_to('/user/view', 'Users') }}</li>
                    <li class="divider"></li>
                    <li>{{ link_to('/offices', 'Offices') }}</li>
                    <li class="divider"></li>
                    <li>{{ link_to('/designation', 'Designations') }}</li>
                    <li class="divider"></li>
                    <li>{{ link_to('/workflow', 'Workflows') }}</li>
                </ul>
            </li>
            @endif
            <li class="dropdown user-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{Auth::user()->firstname}}  <b class="caret" style="margin-top: 0;"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="/user/editprof/{{{ Auth::user()->id }}}"><i class="fa fa-user"></i> Edit Profile</a></li>
                    <li class="divider"></li>
                    <li>
                        <a href="/logout"><i class="fa fa-power-off"></i> Log Out</a>
                    </li>
                </ul>
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <div>
                @yield('content')
            </div>
          </div>
        </div><!-- /.row -->

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- JavaScript -->
    {{ HTML::script('js/bootstrap.min.js') }}
    <script type="text/javascript">
        $(document).ready(function(){ 
            $('.btn').tooltip(); 

        });

        $(document).ready(function(){ // FOR TABLESORTER
        $(function(){
        $("#table_id").tablesorter();
        });
        });
    </script>

    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
   </script>  
   
   @yield('footer')
  </body>
</html>