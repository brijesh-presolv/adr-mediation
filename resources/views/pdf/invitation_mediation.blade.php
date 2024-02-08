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


    <table class="table_" width="100%" style="border-collapse: collapse;">
        <tr>
            <th width="50%">
                Applicant(s) / Initiating Party:
            </th>
            <th>
                Opposite / Responding Party:
            </th>
        </tr>


        <!-- New Format -------->
        
        
        <!-- <tr> -->
        <tr>
        <td>
        <table class="table_" width="100%" style="border-collapse: collapse;">
        @foreach ($party as $key => $p)
            <?php
        
            $inparty = User::find($p->userId); ?>
            @if ($p->isClaimant == 0)
                <tr>
                    <td>
                    <p>{{ isset($inparty->organization) ? $inparty->organization . ' through its authorized representative ' . $p->name : $p->name }}</p>
                    </td>
                </tr>

                <tr>
                    <td>
                            @if ($p->address1 != null)
                                <p>{{ $p->address1 }} {{ $p->address2 }}, {{ $p->city }}, {{ $p->pincode }}</p>
                                <p>{{ $p->state }} {{ $p->country }}</p>
                            @elseif($p->fulladdress != null)
                                <p>{{ $p->fulladdress }}</p>
                            @else
                                <p>{{ $p->useraddress }} {{ $p->useraddress1 }}, {{ $p->usercity }},
                                    {{ $p->userpincode }}</p>
                                <p>{{ $p->userstate }} {{ $p->usercountry }}</p>
                            @endif
                    </td>
                </tr>

                <tr>
                    <td>
                        @if ($p->userEmail != '')
                            <p>{{ $p->userEmail }}</p>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>
                    @if ($p->userPhone != '')
                    
                    <p>{{ $p->userPhone }}</p>
                    @endif
                    </td>
                </tr>
            @endif 
        @endforeach
        </table>
        </td>
    
        
        <td>
        <table class="table_" width="100%" style="border-collapse: collapse;">
        @foreach ($party as $key => $p)
            @if ($key != 0 && $p->isClaimant != 0)
                <!-- @if ($p->isClaimant != 0) -->
                    <tr>
                        <td>
                        @if ($p->name != '')
                            <p>{{ $p->name }}</p>
                        @endif 
                        </td>
                    </tr>

                    <tr>
                        <td>
                                @if ($p->address1 != '')
                                    <p>{{ $p->address1 }} {{ $p->address2 }}, {{ $p->city }},
                                        {{ $p->pincode }}
                                    </p>
                                    <p>{{ $p->state }} {{ $p->country }}</p>
                                @endif($p->fulladdress != '')
                                    <p>{{ $p->fulladdress }} </p>
                                @else
                                    <p></p>
                                @endif
                        </td>
                    </tr>


                    <tr>
                        <td>
                            @if ($p->userEmail != '')
                            <p>{{ $p->userEmail }}</p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td>
                            @if ($p->userPhone != '')
                            <p>{{ $p->userPhone }}</p>
                            @endif
                        </td>
                    </tr>
                @endif
        @endforeach
        </table>
        </td>
        </tr>

            @if($case->otherRespondentDetails != "" && $case->otherRespondentDetails != null)
            <tr>
                <td></td>
                <td><p>{{$case->otherRespondentDetails}}</p></td>
            </tr>
            @endif
        
        <!-- New Format -------->



        

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
    <p>1. Desirous of arriving at an amicable resolution, the Applicant(s) / Initiating Party has sought an amicable
        resolution of the dispute and registered a request with Presolv360.</p>
    {{-- <p>2. Presolv360 (recognised by the Department of Justice, Ministry of Law and Justice, Government of India) is a platform specializing in online dispute resolution through its ‘Arbitration360’ and ‘Mediation360’ module. It is simple to use, easily accessible and ensures that disputants are not entangled in protracted court battles.</p> --}}
    <p>2. As per the Applicant(s) / Initiating Party:</p>
    <p style='margin-left:15px;'>{{ $case->issue }}</p>

    {{-- <p>3. The mediation / conciliation shall be governed by and conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found 
        <a href='https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing'>here</a></p> --}}
    <p>3. Presolv360 is included in the list of institutions offering Alternative Dispute Resolution ("ADR") services
        including through Online Dispute Resolution ("ODR") and is also empaneled as a
        Mediation Institution by various Courts in India. Presolv360 administers mediation proceedings on its platform,
        and empanels independent, qualified mediators with the required competence, knowledge and expertise on its panel
        of mediators. The mediation / conciliation shall be governed by and conducted in accordance with Presolv360’s
        Dispute Resolution Rules, a copy of which can be found <a
            href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">here</a>.
        Presolv360 provides administrative support to all the parties concerned and the mediator for conducting the
        mediation proceedings and has no interest in the outcome of the dispute and there exists no conflict of
        interest.</p>

    <p>4. While the process is absolutely confidential, the mediation / conciliation proceedings are ‘without prejudice’
        to any legal remedies available in the event of non-participation or if the dispute remains unresolved. This has
        become one of the most rewarding processes, with a success rate of over 90% of all references being made.</p>

    <p>5. The Opposite / Responding Party shall, within seven (7) Working Days from the receipt of the Invitation to
        Mediate / Conciliate, accept or reject the said invitation by way of an email addressed to Presolv360 at
        admin@presolv360.com, failing which, the mediation / conciliation shall deemed to be a non-starter.
    </p>

    <p>6. The parties may choose to be represented or assisted by an authorized representative, in which case the
        appointing party shall submit a Letter of Authority, format of which is available <a
            href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>here</a>. The
        appointing party shall submit the signed Letter of Authority by way of an email addressed to Presolv360 at
        admin@presolv360.com with the subject “Letter of Authority | (Case ID) | (Name of the Appointing Party)”.</p>

    <p>7. A mediator / conciliator from the panel of mediators / conciliators will be appointed, and such appointment
        shall be based on the mediator’s / conciliator’s competence, knowledge and ability to deal with subject matter
        of the dispute between the parties.</p>

    <p>8. Upon acceptance of the appointment by the mediator / conciliator, the parties shall be notified of the
        appointment.</p>

    <p>9. If any party requires assistance of an Indian Sign Language (ISL) interpreter in case of hearing impairment, write an email addressed to admin@presolv360.com with the subject "Request for Interpreter | (Case ID)", and this facility will be provided by Presolv360.</p>

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
