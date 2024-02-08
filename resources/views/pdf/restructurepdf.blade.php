<?php

use App\Models\User;

$meddate = new DateTime($restructuredata->crated_at);

$meddate = $meddate->format('d-m-Y');

?>

<!DOCTYPE html>
<html>

<head>
    <title> {{ config('app.name', 'Medtiator') }} | Settlement Agreement </title>
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
    <p class="text-center">Case ID: M{{ $case_id }} | Date: {{ $meddate }}</p> <br>

    <p>Dear {{$respondent_name}},</p>
    <p>We are writing to acknowledge  the debt restructuring plan that you have chosen. We appreciate your commitment towards resolving the outstanding balance on your account and are pleased to work with you towards this goal.</p>
    <div>
	<p>As per your choice, the restructuring plan details are as follows:</p>
	<p> Restructured Balance: {{ $restructuredata->offer_name }} </p>
	<p>Number of Installments:  Testing</p>
	<p>Installment Amount: Testing</p>
	<p>Due Dates:  Testing</p> 
  </div>

    <p style="text-indent: 4em;">Please note that this restructuring plan is effective from {{ $restructuredata->created_at }}.
        We encourage you to make each installment payment by the respective due date to ensure the success of this plan. 
        Any deviation from the plan without prior communication and agreement may result in the termination of the restructuring offer.
    </p>
    <p style="text-indent: 4em;">Please retain this letter as acknowledgement and a reference to the terms of the debt restructuring plan you have chosen.
     If you have any queries or need further clarification regarding your account or the restructuring plan, do not hesitate to contact your bank. </p>
    <p style="text-indent: 4em;">Thank you for your proactive efforts in addressing this matter. We appreciate your cooperation and look forward to a successful resolution.</p>
    <br><br>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>Sincerely,</p>
                <p>Presolv360 Administrator</p>
            </td>
        </tr>
    </table>

</body>

</html>
