<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Purchase Request Successfully Created</h2>

        <div>
            <?php
                $prID = DB::table('purchase_request')->where('id',$data)->first();
            ?>
            Your PR Control No. is {{{ $prID }}} 
        </div>
    </body>
</html>
