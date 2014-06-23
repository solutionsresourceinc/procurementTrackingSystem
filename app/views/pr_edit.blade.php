@extends('layouts.login')

@section('content')
    <?php $p_id = Purchase::find($id); ?>

    <h1 class="page-header">Edit Purchase Request</h1>  

    <form method="POST" action="edit"  class = "form-create">
        <fieldset>
            <input type="hidden" name="id" value="">
           
            @if(Session::get('notice'))
                <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
            @endif

            @if(Session::get('main_error'))
                <div class="alert alert-danger"> {{ Session::get('main_error') }}</div> 
            @endif

            <div class="form-group">
                <label for="projectPurpose">Project/Purpose</label>
                <input class="form-control"  type="text" name="projectPurpose" id="projectPurpose" value="{{{ $p_id->projectPurpose }}}" required>
                @if (Session::get('m1'))
                    <font color="red"><i>{{ Session::get('m1') }}</i></font>
                @endif
            </div>

            <div class="form-group">
                <label for="sourceOfFund">Source of Funds</label>
                <input class="form-control" type="text" name="sourceOfFund" id="sourceOfFund" value="{{{ $p_id->sourceOfFund }}}" required>
                @if (Session::get('m2'))
                    <font color="red"><i>{{ Session::get('m2') }}</i></font>
                @endif
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input class="form-control"  type="text" name="amount" id="amount" value="{{{ $p_id->amount }}}" required>
                @if (Session::get('m3'))
                    <font color="red"><i>{{ Session::get('m3') }}</i></font>
                @endif
            </div>
            
            <div class="form-group" id="template">
                <label for="office">Office</label>
                <select id="office" name="office" class="form-control">
                <?php 
                    //$offices = DB::table('offices')->lists('officeName');
                    $offices = DB::table('offices')->get();
                ?>
                @foreach($offices as $office)
                    @if($p_id->office ==  $office)
                        <option value="{{{ $office->id }}}" selected>{{$office->officeName;}}</option>
                    @else
                        <option value="{{{ $office->id }}}">{{$office->officeName;}}</option>
                    @endif
                @endforeach
            </select>
            </div>

            <div class="form-group" id="template">
                <label for="requisitioner">Requisitioner</label>
                <select id="requisitioner" name="requisitioner" class="form-control">
                    <?php $requi = DB::table('users')->get(); ?>
                    @foreach ($requi as $requis)
                        @if($requis->office_id != 0)
                            @if($requis->id == $p_id->requisitioner )
                                <option value="{{{ $requis->firstname }}}" class="{{{ $requis->office_id }}}" selected> {{{ $requis-> firstname }}} </option>
                            @else
                                <option value="{{{ $requis->firstname }}}" class="{{{ $requis->office_id }}}" > {{{ $requis-> firstname }}} </option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
             
            <div class="form-group">
                <label for="modeOfProcurement">Mode of Procurement</label>
                <select class="form-control" name="modeOfProcurement" id="modeOfProcurement">
                    <option value="Mode 1"> Below 50,000 </option>
                    <option value="Mode 2"> Between 50,00 and 500,000 </option>
                    <option value="Mode 3"> Above 500,000 </option>
                </select>
                @if (Session::get('m4'))
                    <font color="red"><i>{{ Session::get('m4') }}</i></font>
                @endif
            </div>

            <div class="form-group">
                <label for="ControlNo">Control No.</label>
                <input class="form-control"  type="text" name="ControlNo" id="ControlNo" value="{{{ $p_id->ControlNo }}}" required>
                @if (Session::get('m5'))
                    <font color="red"><i>{{ Session::get('m5') }}</i></font>
                @endif
            </div>
          
            <div class="form-actions form-group">
                <button type="submit" class="btn btn-default" name="submit">Save</button>
            </div>
        </fieldset>
    </form>

    <?php
        {{ Session::forget('notice'); }}
        {{ Session::forget('main_error'); }}
        {{ Session::forget('m1'); }}
        {{ Session::forget('m2'); }}
        {{ Session::forget('m3'); }}
        {{ Session::forget('m4'); }}
        {{ Session::forget('m5'); }}
    ?>
    <script type="text/javascript">
        $("#requisitioner").chained("#office");
    </script>

@stop

@section('footer')
@stop