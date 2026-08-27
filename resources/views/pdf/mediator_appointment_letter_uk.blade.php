<?php

use App\Models\User;


//if(isset($case->crated_at) && $case->crated_at != ""){
if(isset($case->created_at) && $case->created_at != ""){
    $meddate = new DateTime($case->created_at);
    $meddate = $meddate->format('d-m-Y');
} else {
    $meddate = "";
}


?>

<!DOCTYPE html>
<html>

<head>
    <title> Appointment Letter</title>
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
            <img src='{{ URL("assert/img/adrlogo.png") }}' style='width: 120px;'>
            <br>

        </div>
    </center>


    <p>Date: {{ $meddate }}</p>
    <h4>PRIVATE AND CONFIDENTIAL</h4>
    <h4>Subject: Appointment to Act as Mediator</h4>
    <p>Dear {{ $mediator->first_name }} {{ $mediator->last_name }},</p>
    <p>We are pleased to confirm your appointment as Mediator in the dispute between:</p>


    <!-- <p class="text-center">Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) and empaneled as a Mediation Institution by various Courts in India</p>
   
    <p class="text-center">Case ID: M{{ sprintf('%06d', $case->id) }} | Date: {{ $meddate }}</p> <br>
    <h2 class="text-center">Appointment Letter</h2><br>
    
    <p>Dear {{ $mediator->first_name }} {{ $mediator->last_name }},</p>
    <h4>Sub: Appointment to act as Mediator </h4> -->

    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th width="50%">
                Initiating Party’s Name
            </th>
            <th>AND</th>
            <th>
                Responding Party’s Name
            </th>
        </tr>
        <tr>
            <td>
                @foreach ($party as $key => $p)
                    <?php
                    $inparty = User::find($p->userId); ?>
                    @if ($p->isClaimant == 0)
                        <p>{{ isset($inparty->organization) ? $inparty->organization . ' through its authorized representative ' . $p->name : $p->name }}
                        </p>
                        @if ($p->address1 != null)
                            <p>{{ $p->address1 }} {{ $p->address2 }}, {{ $p->city }},
                                {{ $p->pincode }}
                            </p>
                            <p>{{ $p->state }} {{ $p->country }}</p>
                        @elseif($p->fulladdress != null)
                            <p>{{ $p->fulladdress }}</p>
                        @else
                            <p>{{ $p->useraddress }} {{ $p->useraddress1 }}, {{ $p->usercity }},
                                {{ $p->userpincode }}</p>
                            <p>{{ $p->userstate }} {{ $p->usercountry }}</p>
                        @endif
                        <p>{{ $p->userEmail }}</p>
                        <p>{{ $p->userPhone }}</p>
                        <br><br>
                    @endif
                @endforeach
            </td>
            <td></td>
            <td>

                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name != '')
                                @if ($p->name != '')
                                    <p>{{ $p->name }}</p>
                                @endif
                                @if ($p->address1 != '')
                                    <p>{{ $p->address1 }} {{ $p->address2 }}, {{ $p->city }},
                                        {{ $p->pincode }}
                                    </p>
                                    <p>{{ $p->state }} {{ $p->country }}</p>
                                @endif
                                @if ($p->fulladdress != '')
                                    <p>{{ $p->fulladdress }} </p>
                                @endif
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                @if ($case->otherRespondentDetails != '' && $case->otherRespondentDetails != null)
                    <p>{{ $case->otherRespondentDetails }}</p>
                @endif
                <br>
                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name == '')
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
            </td>
        </tr>

    </table>

    <p>The mediation shall be administered by AOI (“the Platform”) and carried out in accordance with the Platform’s Dispute Resolution Rules and any relevant agreement between the parties. You are required to act in full impartiality over the entire duration of the mediation process to assist both parties in reaching a voluntary settlement. </p>

    <p>In order to proceed, please confirm the following:</p>

    <p style="text-indent: 2em;">1. Acceptance of this appointment. </p>
    <p style="text-indent: 2em;">2. Your independence and impartiality. </p>
    <p style="text-indent: 2em;">3. Any circumstances that may give rise to a conflict of interest or reasonable doubt as to your independence.
    </p>

    <p>Mediation is a fully confidential procedure, and any personal data arising from it shall be processed in accordance with the UK General Data Protection Regulation (GDPR), the Data Protection Act 2018, and related legislation.</p>

    <p>The Platform shall have no interest in the outcome of this dispute. Please confirm the above within [Number of Days].</p> 



    <!-- 
   
    <p style="text-indent: 4em;">We have been requested to provide administrative assistance in respect of the dispute
        between the aforesaid parties. The mediation proceedings shall be carried out by an independent, qualified and
        competent mediator from the panel of mediators on behalf of all the parties.</p>
    <p style="text-indent: 4em;">Accordingly, you have been appointed as the Mediator and we request you to intimate
        your acceptance and consent, alongwith the requisite disclosures, or refusal to act as a mediator as per the
        Arbitrators’ and Mediators’ Code of Conduct and Disclosure Rules within the prescribed time limit.</p>
    {{-- <p style="text-indent: 4em;">Kindly note that your appointment shall be governed by the Code and the proceedings shall be carried out in accordance with the Rules. </p> --}} -->
    <br><br>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>Yours sincerely,</p>
                <p>Signature:</p>
                <p>[Name]</p>
                <p>[Platform Administrator]</p>
            </td>
        </tr>
    </table>

</body>

</html>
