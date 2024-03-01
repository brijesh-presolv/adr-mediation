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
    <title> {{ config('app.name', 'Medtiator') }} | Invitation of Mediation / Conciliation</title>
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
            <br><br><br>
        </div>
    </center>


    <h2 class="text-center">Invitation to Mediate / Conciliate</h2>

    <h4 class="text-center">Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) and empaneled as a Mediation Institution by various Courts in India</h4>

    <p class="text-center"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <br>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>Case ID: M{{ sprintf('%06d', $case->id) }}</p>
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
                Applicant(s) / Initiating Party:
            </th>
            <th>
                Opposite / Responding Party:
            </th>
        </tr>
        <tr>
            <td>
                <!-- New info -->
                <p>ICICI Bank Ltd.</p>
                <p>ICICI Bank Towers, North Tower,</p>
                <p>7th Floor, Bandra Kurla Complex,</p>
                <p>Bandra (East), Mumbai - 400 051</p>
                <br>

                @foreach ($party as $key => $p)
                    <?php
                    $inparty = User::find($p->userId); ?>
                    @if ($p->isClaimant == 0)
                        <!-- <p>{{ isset($inparty->organization) ? $inparty->organization . ' through its authorized representative ' . $p->name : $p->name }}
                        </p> -->

                        <p>Through {{ isset($inparty->organization) ? $inparty->organization . $p->name : $p->name }}
                        </p>
                        <!-- @if ($p->address1 != null)
                            <p>{{ $p->address1 }} {{ $p->address2 }}, {{ $p->city }}, {{ $p->pincode }}</p>
                            <p>{{ $p->state }} {{ $p->country }}</p>
                        @elseif($p->fulladdress != null)
                            <p>{{ $p->fulladdress }}</p>
                        @else
                            <p>{{ $p->useraddress }} {{ $p->useraddress1 }}, {{ $p->usercity }},
                                {{ $p->userpincode }}</p>
                            <p>{{ $p->userstate }} {{ $p->usercountry }}</p>
                        @endif -->

                        @if ($p->userEmail != null)
                        <p>{{$p->userEmail}}</p>
                        @endif

                        @if ($p->userPhone != null)
                        <p>{{$p->userPhone}}</p>
                        @endif
                        <br> <br>

                        @if ($p->userpname != null)
                        <p>{{$p->userpname}}</p>
                        @endif

                        @if ($p->userpemail != null)
                        <p>{{$p->userpemail}}</p>
                        @endif

                        @if ($p->userpcontact != null)
                        <p>{{$p->userpcontact}}</p>
                        @endif
                    @endif
                @endforeach
                {{-- <p>{{$party[0]->userEmail}}</p>
                <p>{{$party[0]->userPhone}}</p> --}}
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
                <!-- @if ($case->otherRespondentDetails != '' && $case->otherRespondentDetails != null)
                    <p>{{ $case->otherRespondentDetails }}</p>
                @endif -->
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

        <!-- @if ($case->discussion != '' && $case->discussion != null)
        <tr>
            <td>
                <p>Contact of discussion</p>
                <p>{{$case->discussion}}</p>
            </td>
        </tr>
        @endif -->


    </table>

    <br />
    {{-- <p><b>Date of Request for Mediation / Conciliation: {{date('d-m-Y')}}</b></p>
    <p><b>Last date to Respond to Invitation to Mediate / Conciliate: {{$ldate}} 23:59:59</b></p> --}}
    <p>1. Presolv360 is a neutral and independent Online Dispute Resolution (“ODR”) Institution, included in the notification bearing F.No. A-60011/97/2018-Admn.III (LA) dated 18/09/2020 for hosting of institutions offering ADR mechanisms (including ODR) on the website by the Department of Legal Affairs, Ministry of Law & Justice, Government of India,  and is also empaneled as a Mediation Institution by various Courts in India. Presolv360 facilitates fair, fast and accessible dispute resolution through a secure online platform, administrative support and a panel of expert neutrals. Advisory Council constituted by Presolv360 comprises of the former Chief Justice of India, former Supreme Court and High Court judges. Further,  the Board of Advisors include former High Court judges, prominent mediators and conciliators. </p>
    {{-- <p>2. Presolv360 (recognised by the Department of Justice, Ministry of Law and Justice, Government of India) is a platform specializing in online dispute resolution through its ‘Arbitration360’ and ‘Mediation360’ module. It is simple to use, easily accessible and ensures that disputants are not entangled in protracted court battles.</p> --}}
    <p>2. Desirous of arriving at an amicable resolution, ICICI Bank (“the Applicant”) has now approached and registered a request with Presolv360 for resolution of dispute through Conciliation and this correspondence is a formal invitation to participate in a conciliation process initiated by ICICI Bank, a company incorporated under the Companies Act, 1956 and a banking company licensed under the Banking Regulation Act, 1949 having its registered office at ICICI Bank Limited, Near Chakli Circle, Old Padra Road, Vadodara 390 007, its corporate office at ICICI Bank Towers, Bandra Kurla Complex, Mumbai 400 051.</p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
    
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        //echo nl2br($case->issue); ?>
    </p>

    {{-- <p>3. The mediation / conciliation shall be governed by and conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found 
        <a href='https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing'>here</a></p> --}}
    <p>3. Conciliation is a way of settling disputes in a cost-effective, speedy and amicable manner with the assistance of a neutral expert who is called a ‘Conciliator’. The parties to a dispute get together to amicably discuss and settle the dispute in the presence of a Conciliator in a fair and transparent manner. If the parties settle during the conciliation process, the terms of settlement will be recorded in a ‘Conciliatory Award cum Settlement Agreement’ by the Conciliator, which is binding and enforceable like a court decree according to Section 74 of the Arbitration and Conciliation, 1996. </p>

    <p>4. <?php
        echo nl2br($case->issue); ?></p>

    <p>5. Presolv360 administers conciliation proceedings on its platform, and empanels independent, qualified mediators with the required competence, knowledge and expertise on its panel of mediators. The conciliation shall be governed by and conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found here. Presolv360 provides administrative support to all the parties concerned and the conciliator for conducting the conciliation proceedings and has no interest in the outcome of the dispute and there exists no conflict of interest.</p>

    <p>6. While Presolv360 ensures complete confidentiality of the entire process, the conciliation proceedings are ‘without prejudice’ to any legal remedies available in the event of non-participation or if the dispute remains unresolved. This has become one of the most rewarding processes, with a success rate of over 90% of all references being made.</p>

    <p>7. The parties may choose to be represented or assisted by an authorized representative, in which case the appointing party shall submit a Letter of Authority, format of which is available here. The appointing party shall submit the signed Letter of Authority by way of an email addressed to Presolv360 at admin@presolv360.com with the subject “Letter of Authority | (Case ID) | (Name of the Appointing Party)”.</p>

    <p>8. A Conciliator from the panel of conciliators will be appointed, and such appointment shall be based on the Conciliator’s competence, knowledge and ability to deal with subject matter of the dispute between the parties.</p>

    <p>9. Upon acceptance of the appointment by the Conciliator, the parties shall be notified of the appointment.</p>

    <!-- New info -->
    <p>10. You are requested to communicate your acceptance or rejection to this invitation for participation in conciliation proceedings by way of an email addressed to Presolv360 at admin@presolv360.com within Fourteen (14) Working Days  from the receipt of this invitation, failing which, the conciliation shall deemed to be a non-starter.</p>
    
    <p>11. If any party requires assistance of an Indian Sign Language (ISL) interpreter in case of hearing impairment, write an email addressed to admin@presolv360.com with the subject "Request for Interpreter | (Case ID)", and this facility will be provided by Presolv360.</p>
    <!-- New info -->

    <p><b>Note: This is a system generated notice and hence does not require signature.</b></p>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>To:</p>
                <p>Opposite / Responding Party</p>
            </td>
            <td>
                <p>Copy to:</p>
                <p>Applicant(s) / Initiating Party</p>
            </td>
        </tr>
    </table>

</body>

</html>
