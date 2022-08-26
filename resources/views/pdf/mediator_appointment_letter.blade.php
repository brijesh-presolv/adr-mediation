<?php

use App\Models\User;

$meddate = new DateTime($case->crated_at);

$meddate = $meddate->format('d-m-Y');

?>

<!DOCTYPE html>
<html>

<head>
    <title> {{ config('app.name', 'Medtiator') }} | Appointment Letter</title>
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
    <p class="text-center">Case ID: M{{ sprintf('%06d', $case->id) }} | Date: {{ $meddate }}</p> <br>
    <h2 class="text-center">Appointment Letter</h2><br>
    {{-- <p class="text-center">(See Rule 6 of Section 3 of Presolv360’s Dispute Resolution Rules)</p><br> --}}
    <p>Dear {{ $mediator->first_name }} {{ $mediator->last_name }},</p>
    <h4>Sub: Appointment to act as Mediator </h4>

    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th width="50%">
                Applicant(s) / Initiating Party:
            </th>
            <th>
                Opposite / Responding Party:
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
                            <p></p>
                        @endif
                        <p>{{ $p->userEmail }}</p>
                        <p>{{ $p->userPhone }}</p>
                        <br><br>
                    @endif
                @endforeach
            </td>
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

    <p style="text-indent: 4em;">Desirous of arriving at an amicable resolution,
        <b>{{ isset($inparty->organization) ? $inparty->organization : $party[0]->name }}</b> has approached Presolv360
        to facilitate a mutually acceptable resolution through online mediation.
    </p>
    <p style="text-indent: 4em;">Presolv360 is included in the list of institutions offering Alternative Dispute
        Resolution ("ADR") services including through Online Dispute Resolution ("ODR") by the Ministry of Law & Justice
        and is also empaneled as a Mediation Institution by various Courts in India. Presolv360 administers mediation
        proceedings on its platform, and empanels independent, qualified mediators with the required competence,
        knowledge and expertise on its panel of mediators. The mediation / conciliation shall be governed by and
        conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found <a
            href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">here.</a>
        Presolv360 provides administrative support to all the parties concerned and the mediator for conducting the
        mediation proceedings and has no interest in the outcome of the dispute and there exists no conflict of
        interest.</p>
    <p style="text-indent: 4em;">We have been requested to provide administrative assistance in respect of the dispute
        between the aforesaid parties. The mediation proceedings shall be carried out by an independent, qualified and
        competent mediator from the panel of mediators on behalf of all the parties.</p>
    <p style="text-indent: 4em;">Accordingly, you have been appointed as the Mediator and we request you to intimate
        your acceptance and consent, alongwith the requisite disclosures, or refusal to act as a mediator as per the
        Arbitrators’ and Mediators’ Code of Conduct and Disclosure Rules within the prescribed time limit.</p>
    {{-- <p style="text-indent: 4em;">Kindly note that your appointment shall be governed by the Code and the proceedings shall be carried out in accordance with the Rules. </p> --}}
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
