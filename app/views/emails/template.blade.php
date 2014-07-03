<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Your Purchase Request has been submitted</h2>

        <div>
            <?php
                $purch = DB::table('purchase_request')->where('id',$id)->first();
                //$user = DB::table('users')->where('id',$p_id->requisitioner)->first();
            ?>
             
            <table>
                <tr>
                    <td>PR Control No :</td>
                    <td>{{{ $purch->controlNo }}}/td>
                </tr>
                <tr>
                    <td>Project Purpose :</td>
                    <td> {{{ $purch->projectPurpose }}} </td>
                </tr>
                <tr>
                    <td>Source of Fund :</td>
                    <td> {{{ $purch->sourceOfFund }}} </td>
                </tr>
                <tr>
                    <td>Amount :</td>
                    <td> {{{ $purch->amount }}} </td>
                </tr>
                <tr>
                    <td>Date requested :</td>
                    <td> {{{ $purch->dateRequested }}} </td>
                </tr>
            </table>
        </div>
    </body>
</html>
