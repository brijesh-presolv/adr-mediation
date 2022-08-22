<?php
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
            .pt-5{
                padding-top: 5rem;
            }
            .text-center{
                text-align: center;
            }
            .text-left{
                text-align: left;
            }
            .text-right{
                text-align: right;
            }
            .table_{

                margin:0 auto;
                width: 100%;
                border:solid;
                border-width:1px;
                border-collapse:collapse;
                margin-bottom:20px;

            }
            .table_ th{
                border:solid;
                border-width:1px;
            }

            .table_ td{
                border-top:solid;
                border-width:1px;
                padding:10px;
                border:solid;
                border-width:1px;
            }

            .table_ th{
                padding:10px;
            }
        </style>
    </head>
    <body>

    <center><div class="text-center mt-4">
            <img src='{{URL("assert/img/plogo.png")}}' style='width: 120px;'>
            <br>
             
        </div>
    </center>
    <p class="text-center">Included in the list of institutions <a href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution (ODR) by the Ministry of Law & Justice and recognized as a Mediation Institution</p>
    <p class="text-center"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>
    <p class="text-center">Case ID: M{{sprintf('%06d', $case->id)}}  |  Date: {{$meddate}}</p> <br>
    <h2 class="text-center">Appointment Letter</h2><br>
    {{-- <p class="text-center">(See Rule 6 of Section 3 of Presolv360’s Dispute Resolution Rules)</p><br> --}}
    <p>Dear {{$mediator->first_name}} {{$mediator->last_name}},</p>
    <h4>Sub: Appointment to act as Mediator </h4>
    <?php 
    use App\Models\User;
     $inparty = User::find($party[0]->userId); ?>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th width="50%" >
                Applicant(s) / Initiating Party:
            </th>
            <th>
                Opposite / Responding Party:
            </th>
        </tr>
        <tr>
            <td >
                <p>{{isset($inparty->organization) ? $inparty->organization . " through its authorized representative " . $party[0]->name : $party[0]->name}}</p>
                <p>{{$party[0]->address1}} {{$party[0]->address2}}, {{$party[0]->city}}, {{$party[0]->pincode}}</p>
                <p>{{$party[0]->state}} {{$party[0]->country}}</p>
                <p>{{$party[0]->userEmail}}</p>
                <p>{{$party[0]->userPhone}}</p>
            </td>
            <td >

                @foreach($party as $key=>$p)
                @if($key!=0)
                @if ($p->name != "")  
                @if($p->name != "")
                <p>{{$p->name}}</p>
                @endif
                @if($p->address1 != "")
                <p>{{$p->address1}} {{$p->address2}}, {{$p->city}}, {{$p->pincode}}</p>
                <p>{{$p->state}} {{$p->country}}</p>
                @endif
                @if($p->fulladdress != "")
                <p>{{$p->fulladdress}} </p>
                @endif
                @if($p->userEmail != "")
                <p>{{$p->userEmail}}</p>
                @endif
                @if($p->userPhone != "")
                <p>{{$p->userPhone}}</p>
                @endif
                <br>
                @endif
                @endif
                @endforeach
                @if($case->otherRespondentDetails != "" && $case->otherRespondentDetails != null)
                <p>{{$case->otherRespondentDetails}}</p>
                @endif
                <br>
                @foreach($party as $key=>$p)
                @if($key!=0)
                @if ($p->name == "") 
                @if($p->userEmail != "")
                <p>{{$p->userEmail}}</p>
                @endif
                @if($p->userPhone != "")
                <p>{{$p->userPhone}}</p>
                @endif
                <br>
                @endif
                @endif
                @endforeach
            </td>
        </tr>

    </table>

    <p style="text-indent: 4em;">Desirous of arriving at an amicable resolution, <b>{{isset($inparty->organization) ? $inparty->organization : $party[0]->name}}</b> has approached Presolv360 to facilitate a mutually acceptable resolution through online mediation.</p>
    <p style="text-indent: 4em;">Presolv360 is included in the list of institutions offering Alternative Dispute Resolution ("ADR") services including through Online Dispute Resolution ("ODR") by the Ministry of Law & Justice and is also empaneled as a Mediation Institution by various Courts in India. Presolv360 administers mediation proceedings on its platform, and empanels independent, qualified mediators with the required competence, knowledge and expertise on its panel of mediators. The mediation / conciliation shall be governed by and conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found <a>here.</a> Presolv360 provides administrative support to all the parties concerned and the mediator for conducting the mediation proceedings and has no interest in the outcome of the dispute and there exists no conflict of interest.</p>
    <p style="text-indent: 4em;">We have been requested to provide administrative assistance in respect of the dispute between the aforesaid parties. The mediation proceedings shall be carried out by an independent, qualified and competent mediator from the panel of mediators on behalf of all the parties.</p>
    <p style="text-indent: 4em;">Accordingly, you have been appointed as the Mediator and we request you to intimate your acceptance and consent, alongwith the requisite disclosures, or refusal to act as a mediator as per the Arbitrators’ and Mediators’ Code of Conduct and Disclosure Rules within the prescribed time limit.</p>
    {{-- <p style="text-indent: 4em;">Kindly note that your appointment shall be governed by the Code and the proceedings shall be carried out in accordance with the Rules. </p> --}}
    <br><br>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td ><p>Sincerely,</p><p>Presolv360 Administrator</p></td>
        </tr>
    </table>

</body>
</html>