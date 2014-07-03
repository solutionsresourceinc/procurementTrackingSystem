<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Purchase Request Successfully Created</h2>

        <div>
            <?php
                $p_id = DB::table('purchase_request')->where('id',$id)->first();
            ?>
            Your PR Control No. is {{{ $p_id->controlNo }}}
        </div>
    </body>
</html>
