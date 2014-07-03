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
            <li class="{{Request::is('dashboard') ? 'active':''}}"><a href="/"><!--i class="fa fa-dashboard"></i--> Dashboard</a></li>
            <li class="{{(Request::is('purchaseRequests') || Request::is('purchaseRequest/view') || Request::is('purchaseRequest/closed') || Request::is('purchaseRequest/overdue')) ? 'active' : ''}}"><a href="/purchaseRequests">Purchase Requests</a>
                <ul class="side-submenu">
                    <li class="{{Request::is('purchaseRequest/view') ? 'active':''}}">
                        <a href="/purchaseRequest/view">
                            Active Purchase Requests
                            <span class="badge pull-right">
                                {{DB::table('purchase_request')->where('status', '=', 'New')->orWhere('status', '=', 'In progress')->count()}}
                            </span>
                        </a>
                    </li>
                    <li class="{{Request::is('purchaseRequest/closed') ? 'active':''}}">
                        <a href="/purchaseRequest/closed">
                            Closed Purchase Requests
                            <span class="badge pull-right">
                                {{DB::table('purchase_request')->where('status', '=', 'Closed')->count()}}
                            </span></a>
                    </li>
                    <li class="{{Request::is('purchaseRequest/overdue') ? 'active':''}}">
                        <a href="/purchaseRequest/overdue">
                            Overdue Purchase Requests
                            <span class="badge pull-right">
                                {{DB::table('purchase_request')->where('status', '=', 'Overdue')->count()}}
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="{{(Request::is('task/active') || Request::is('task/overdue')) ? 'active' : ''}}"><a href="/task/active">Tasks</a>
                <ul class="side-submenu">
                    <li class="{{Request::is('task/new') ? 'active':''}}">
                        <a href="/task/new">New Tasks<span class="badge pull-right">10</span></a>
                    </li>
                    <li class="{{Request::is('task/active') ? 'active':''}}">
                        <a href="/task/active">Active Tasks<span class="badge pull-right">10</span></a>
                    </li>
                    <li class="{{Request::is('task/overdue') ? 'active':''}}">
                        <a href="/task/overdue">Overdue Tasks<span class="badge pull-right">10</span></a>
                    </li>
                </ul>
            </li>
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
            $('.purpose').tooltip(); 
            
            $('.navbar .dropdown').hover(function() {
                $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
            }, function() {
                $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
            });
        });

        $(document).ready(function(){ // FOR TABLESORTER
        $(function(){
        $("#table_id").tablesorter();
        });
        });
    </script>
    @yield('footer')
  </body>
</html>