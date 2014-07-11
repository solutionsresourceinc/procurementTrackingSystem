<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <style>
            table {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid black;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <center>
        <h2>Your purchase request has been processed.</h2>
        </center>
        <div align="center">
            <?php
                $purch = DB::table('purchase_request')->where('id',$id)->first();
                //$user = DB::table('users')->where('id',$p_id->requisitioner)->first();
            ?>
            <table width="700px" border=1>
                <tr><th height="40px" colspan="2" bgcolor='ccff99'><font size="5" style='calibri'>PURCHASE REQUEST DETAILS</font></th></tr>
                <tr>
                    <td width="25%"><strong>PR Control No :</strong></td>
                    <td width="75%">{{{ $purch->controlNo }}}</td>
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
