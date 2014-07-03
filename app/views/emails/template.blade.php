<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <center>
        <h2><font color="white">Your Purchase Request has been submitted</font></h2>
        </center>
        <div align="center">
            <?php
                $purch = DB::table('purchase_request')->where('id',$id)->first();
                //$user = DB::table('users')->where('id',$p_id->requisitioner)->first();
            ?>
            <table width="50%">
                <tr><th colspan="2" bgcolor="006600">PURCHASE REQUEST DETAILS</th></tr>
                <tr>
                    <td width="30%"><strong>PR Control No :</strong></td>
                    <td width="70%">{{{ $purch->controlNo }}}</td>
                </tr>
                <tr>
                    <td><strong>Project/Purpose :</strong></td>
                    <td> {{{ $purch->projectPurpose }}} </td>
                </tr>
                <tr>
                    <td><strong>Source Of Fund :</strong></td>
                    <td> {{{ $purch->sourceOfFund }}} </td>
                </tr>
                <tr>
                    <td><strong>Amount :</strong></td>
                    <td> {{{ $purch->amount }}} </td>
                </tr>
                <tr>
                    <td><strong>Date Requested :</strong></td>
                    <td> {{{ $purch->dateRequested }}} </td>
                </tr>
            </table>
        </div>
    </body>
</html>
