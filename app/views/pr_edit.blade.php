@extends('layouts.login')

@section('content')
    <?php //$user = User::find($id); ?>

    <h1 class="page-header">Purchase Request Edit</h1>  

    <form method="POST" action="edit"  class = "form-create">
        <fieldset>
            <input type="hidden" name="id" value="">

            <div class="form-group">
                <label for="project">Project/Purpose</label>
                <input class="form-control"  type="text" name="project" id="project" value="" required>
            </div>

            <div class="form-group">
                <label for="funds">Source of Funds</label>
                <input class="form-control" type="text" name="funds" id="funds" value="" required>
            </div>

            <div class="form-group">
                <label for="amt">Amount</label>
                <input class="form-control"  type="text" name="amt" id="amt" value="" required>
            </div>
            
            <div class="form-group" id="template">
                <label for="ofc">Office</label>
                <select id="mark" name="mark" class="form-control">
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
                <input class="form-control"  type="text" name="cnum" id="cnum" value="" required>
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
@stop

@section('footer')
    <script type="text/javascript">
        $("#series").chained("#mark");
    </script>
@stop