<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Tarlac Procurement Tracking System</title>
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

        @yield('header')
        
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
            <li class="{{(Request::is('purchaseRequest/view') || Request::is('purchaseRequest/view/{id}')) ? 'active':''}}">{{ link_to('/purchaseRequest/view', 'Purchase Requests') }}</li>
            @if ( Entrust::hasRole('Administrator') || Entrust::hasRole('Procurement Personnel'))
                <li class="{{Request::is('user/view') ? 'active':''}}">{{ link_to('/user/view', 'Users') }}</li>
                <li class="{{Request::is('offices') ? 'active':''}}">{{ link_to('/offices', 'Offices') }}</li>
                <!--li class="{{Request::is('workflow') ? 'active':''}}">{{ link_to('/workflow', 'Workflow') }}</li-->
                <li class="divider"></li>
                <li class="{{Request::is('workflow') ? 'active':''}} dropdown">
                    <a href="#" class="dropdown" data-toggle="dropdown">Workflow <b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
                      <li>{{ link_to('/workflow/belowFifty', 'Below P50,000') }}</li>
                      <li>{{ link_to('/workflow/aboveFifty', 'Between P50,000 and P500,000') }}</li>
                      <li>{{ link_to('/workflow/aboveFive', 'Above P500,000') }}</li>
                    </ul>
                </li>
                <li class="{{Request::is('designation') ? 'active':''}}">{{ link_to('/designation', 'Designations') }}</li>
            @endif
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
            <li class="dropdown user-dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Administrator <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#"><i class="fa fa-user"></i> Edit Profile</a></li>
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
    @yield('footer')
  </body>
</html>