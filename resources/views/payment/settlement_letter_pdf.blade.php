<?php

use App\Models\User;

$meddate = new DateTime($paymentdata->success_at);

$meddate = $meddate->format('d-m-Y');

?>

<!DOCTYPE html>
<html>

<head>
    <title> Settlement Letter</title>
    <style type="text/css">
        @page {
            header: page-header;
            footer: page-footer;
        }

        .pt-5 {
            padding-top: 5rem;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .table_ {

            margin: 0 auto;
            width: 100%;
            border: solid;
            border-width: 1px;
            border-collapse: collapse;
            margin-bottom: 20px;

        }

        .table_ th {
            border: solid;
            border-width: 1px;
        }

        .table_ td {
            border-top: solid;
            border-width: 1px;
            padding: 10px;
            border: solid;
            border-width: 1px;
        }

        .table_ th {
            padding: 10px;
        }
    </style>
</head>

<body>

    <center>
        <div class="text-center mt-4">
            <img src='{{ URL('assert/img/plogo.png') }}' style='width: 120px;'>
            <br>

        </div>
    </center>
    <p class="text-center">Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) by the Ministry of Law & Justice and recognized as a Mediation Institution</p>
    <p class="text-center"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>
    <p class="text-center">Case ID: M{{ $caseid }} | Date: {{ $meddate }}</p> <br>

    <p><strong>{{ $paymentdata->customer_name }}</strong></p>
    <div>
	<<p> We are writing to confirm receipt of payment made by you on  {{ $paymentdata->success_at }} through our online payment system. The payment details are as follows:</p>
	<p> Creditor Name:  Testing  </p>
	<p>Transaction ID:  {{ $payment_id}}  </p>
	<p>Transaction Date: {{ $paymentdata->success_at }}  </p>
	<p>Payment Amount:  {{ $paymentdata->total_amt }} </p>
  </div>

    <p style="text-indent: 4em;">Please consider this letter as official acknowledgement that we have received the payment you made towards your Case Id M{{ $caseid }} .
	 We appreciate your efforts in making this payment.
    </p>
    <p style="text-indent: 4em;">Please note, this payment has been applied to your outstanding balance. Any remaining balance will continue to accrue interest as per the terms and conditions of your agreement with us. It is your responsibility to continue making payments to fulfill your total outstanding debt. </p>
    <p style="text-indent: 4em;">We suggest you to retain this acknowledgement letter for your records. Should you require any further information or assistance regarding your account, please feel free to contact your creditor. 
</p>
<p style="text-indent: 4em;">We value your promptness in settling this payment and look forward to your continued diligence in the future.</p>
    <br><br>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>Best Regards, </p>
            </td>
        </tr>
    </table>

</body>

</html>
