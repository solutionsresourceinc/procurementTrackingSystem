@extends('layouts.login')

@section('content')
    <?php $p_id = Purchase::find($id); ?>

    <h1 class="page-header">Purchase Request Edit</h1>  

    <form method="POST" action="edit"  class = "form-create">
        <fieldset>
            <input type="hidden" name="id" value="">

            <div class="form-group">
                <label for="project">Project/Purpose</label>
                <input class="form-control"  type="text" name="project" id="project" value="{{{ $p_id->projectPurpose }}}" required>
            </div>

            <div class="form-group">
                <label for="funds">Source of Funds</label>
                <input class="form-control" type="text" name="funds" id="funds" value="{{{ $p_id->sourceOfFund }}}" required>
            </div>

            <div class="form-group">
                <label for="amt">Amount</label>
                <input class="form-control"  type="text" name="amt" id="amt" value="{{{ $p_id->amount }}}" required>
            </div>
            
            <div class="form-group" id="template">
                <select id="mark" name="mark" class="form-control">
                <!--
                <?php $offices = DB::table('offices')->lists('officeName') ?>
                @foreach($offices as $office)
                    @if($p_id->office ==  $office)
                        <option value="{{{ $office }}}" selected>{{$office;}}</option>
                    @else
                        <option value="{{{ $office }}}">{{$office;}}</option>
                    @endif
                @endforeach
                -->
                <option value="">--</option>
                <option value="bmw">BMW</option>
                <option value="audi">Audi</option>
            </select>
            </div>

            <div class="form-group" id="template">
                <label for="req">Requisitioner</label>
                <select id="series" name="series" class="form-control">
                    <option value="">--</option>
                    <option value="series-3" class="bmw">3 series</option>
                    <option value="series-5" class="bmw">5 series</option>
                    <option value="series-6" class="bmw">6 series</option>
                    <option value="a3" class="audi">A3</option>
                    <option value="a4" class="audi">A4</option>
                    <option value="a5" class="audi">A5</option>
                </select>
            </div>
             
            <div class="form-group">
                <label for="mop">Mode of Procurement</label>
                <select class="form-control" name="mop" id="mop">
                    <option value="Mode 1"> Mode 1 </option>
                    <option value="Mode 2"> Mode 2 </option>
                    <option value="Mode 3"> Mode 3 </option>
                </select>
            </div>

            <div class="form-group">
                <label for="cnum">Control No.</label>
                <input class="form-control"  type="text" name="cnum" id="cnum" value="{{{ $p_id->ControlNo }}}" required>
            </div>
          
            <div class="form-actions form-group">
                <button type="submit" class="btn btn-default" name="submit">Save</button>
            </div>
        </fieldset>
    </form>

    <?php
    /*
    Session::forget('firstname_error');
    Session::forget('lastname_error');
    Session::forget('password_error');
    Session::forget('email_error');
    Session::forget('msg');
    */
    ?>

    <script type="text/javascript">
        $("#series").chained("#mark");
    </script>
@stop

@section('footer')
@stop