<?php

use App\Models\User;

$meddate = new DateTime($case->crated_at);

$meddate = $meddate->format('d-m-Y H:i:s');

$lastdate = new DateTime();

$lastdate = $lastdate->modify('+7 days');

$ldate = $lastdate->format('d-m-Y');
?>

<!DOCTYPE html>
<html>  

<head>
    <title> {{ config('app.name', 'Conciliator') }} | Invitation to Conciliate</title>
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
        <div class="text-center">
            <img src='{{ URL("assert/img/plogo.png") }}' style='width: 120px;'>
            <br>
        </div>
    </center>

    <h4 class="text-center">
        <!-- Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) and  -->
        Empanelled as a Mediation Institution by various Courts in India
    </h4>

    <h2 class="text-center">Invitation to Conciliate</h2>

    

    <!-- <p class="text-center"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p> -->

    <br>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>Case ID: {{env('PLATFORM_PREFIX')}}{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>Date : {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <br>


    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th width="50%">
           Initiating Party
            </th>
            <th>
           Responding Party
            </th>
        </tr>
        <tr>
            <td>
                @foreach ($party as $key => $p)
                    <?php
                    $inparty = User::find($p->userId); ?>
                    @if ($p->isClaimant == 0)
                        <p>{{ $p->name }}</p>
                        <p>{{ $p->userEmail }}</p>
                        <p>{{ $p->userPhone }}</p>
                        <p>{{ $p->userVua}}</p>
                        <br><br>
                    @endif
                @endforeach
            </td>
            <td>
                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant !== 0)
                            @if ($p->name != '')
                                @if ($p->name != '')
                                    <p>{{ $p->name }}</p>
                                @endif
                                <?php /*
                                @if ($p->address1 != '')
                                    <p>{{ $p->address1 }} {{ $p->address2 }}, {{ $p->city }},
                                        {{ $p->pincode }}
                                    </p>
                                    <p>{{ $p->state }} {{ $p->country }}</p>
                                @endif
                                @if ($p->fulladdress != '')
                                    <p>{{ $p->fulladdress }} </p>
                                @endif
                                */ ?>
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
                @if($case->otherRespondentDetails != "" && $case->otherRespondentDetails != null)
                <p>{{$case->otherRespondentDetails}}</p>
                @endif
            </td>
        </tr>

        @if ($case->discussion != '' && $case->discussion != null)
        <tr>
            <td>
                <p>Contact of discussion</p>
                <p>{{$case->discussion}}</p>
            </td>
        </tr>
        @endif


    </table>

    <br />
    {{-- <p><b>Date of Request for Mediation / Conciliation: {{date('d-m-Y')}}</b></p>
    <p><b>Last date to Respond to Invitation to Mediate / Conciliate: {{$ldate}} 23:59:59</b></p> --}}
    
    <div style="text-align: justify;">
        <ol>
        <li> In accordance with the dispute resolution framework laid down in the <a href="https://www.sebi.gov.in/legal/master-circulars/aug-2023/online-resolution-of-disputes-in-the-indian-securities-market_75220.html">circular dated 31-07-2023 bearing reference no. SEBI/HO/OIEA/OIEA_IAD-1/P/CIR/2023/145</a>, as amended from time to time (“the Circular”), issued by the Securities Exchange Board of India (“SEBI”), the dispute between the parties has been referred to Presolv360 for administering conciliation proceedings. Presolv360 is empanelled as an Online Dispute Resolution (“ODR”) institution under the Circular to administer resolution of disputes arising in the Indian Securities Market.</li>
        <br/>
        <li> Presolv360 is 
            <!-- included in the list of institutions offering Alternative Dispute Resolution ("ADR") services including through ODR and is also  -->
            empanelled as a Mediation Institution by various Courts in India. Presolv360 administers conciliation proceedings on its platform, and empanels independent, qualified conciliators with the required competence, knowledge and expertise on its panel of conciliators. The conciliation shall be governed by and conducted in accordance with the Circular read with Presolv360’s Dispute Resolution Rules, to the extent applicable, a copy of which can be found <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">here</a>. Presolv360 provides administrative support to all the parties concerned and the conciliator for conducting the conciliation proceedings and has no interest in the outcome of the dispute and there exists no conflict of interest. </li>
            <br/>
        <li> The Responding Party shall complete the onboarding process and participate at the earliest, failing which, the conciliation may be considered as unresolved, and the conciliator may ascertain the admissible claim value of the dispute.</li>
        <br/>
        <li> The parties may choose to be represented or assisted by an authorized representative, in which case the appointing party shall submit a Letter of Authority, format of which is available <a href="https://docs.google.com/document/d/1BmTdQJVZQPLDgXSeC01KyI8m-RuyrNb9/edit?usp=drive_link&ouid=116993539095927475516&rtpof=true&sd=true">here</a>. The appointing party shall submit the signed Letter of Authority by way of an email addressed to Presolv360 at <a href="#">smadmin@presolv360.com</a> with the subject “Letter of Authority | (Case ID) | (Name of the Appointing Party)”. </li>
            <br/>
        <li> A conciliator from the panel of conciliators will be appointed as per the Circular, and such appointment shall be based on the conciliator’s competence, knowledge and ability to deal with subject matter of the dispute between the parties.  
        </li>
        <br/>
        <li> Upon acceptance of the appointment by the conciliator, the parties shall be notified of the appointment. </li>
        <br/>
        <li>If any party requires assistance of an Indian Sign Language (ISL) interpreter in case of hearing impairment, write an email addressed  to <a href="#">smadmin@presolv360.com</a> with  the subject "Request for Interpreter | (Case  ID)", and this facility will be provided by Presolv360.</li>
        </ol>

    </div>
    <br />

    <p><b>Note: This is a system generated notice and hence does not require signature. </b></p>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>To:</p>
                <p>Responding Party</p>
            </td>
            <td>
                <p>Copy to:</p>
                <p>Initiating Party</p>
            </td>
        </tr>
    </table>

</body>

</html>
