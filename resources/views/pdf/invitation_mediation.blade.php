<?php
$meddate = new DateTime($case->crated_at);

$meddate = $meddate->format('d-m-Y H:i:s');

$lastdate = new DateTime();

$lastdate = $lastdate->modify('+7 days');

$ldate = $lastdate->format('d-m-Y');
?>

<!DOCTYPE html>
<html>
    <head>
        <title> {{ config('app.name', 'Medtiator') }} | Invitation of mediation</title>
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

    <center><div class="text-center">
            <img src='{{URL("assert/img/plogo.png")}}' style='width: 120px;'>
            <br><br><br>
        </div>
    </center>


    <h2 class="text-center">Invitation to Mediate</h2>

    <h4 class="text-center">Enlisted by the Department of Justice, Government of India</h4>
    
    <p class="text-center">
        See Rule 6 of Section 3 of Presolv360’s Dispute Resolution Rules (“Rules”) read with the Mediators’ Code of Conduct and Disclosure Rules (“Code”)
    </p>
    <br>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%"><p>Case ID: M{{sprintf('%06d',$case->id)}}</p></td>
            <td ><p class="text-center">Date : {{date('d-m-Y')}}</p></td>
        </tr>
    </table>
    <br>

    <?php 
    use App\Models\User;
     $inparty = User::find($party[0]->userId); ?>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th width="50%" >
                Initiating Party:
            </th>
            <th>
                Responding Party:
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

    <br /><br /><br />
    <p><b>Date of Request for Mediation: {{date('d-m-Y')}}</b></p>
    <p><b>Last date to Respond to Invitation to Mediation: {{$ldate}} 23:59:59</b></p>
    <p>1.   Desirous of arriving at an amicable resolution, the Initiating Party has approached ‘Presolv360’ to facilitate a mutually acceptable resolution of your dispute via e-mediation.</p>
    <p>2. Presolv360 (recognised by the Department of Justice, Ministry of Law and Justice, Government of India) is a platform specializing in online dispute resolution through its ‘Arbitration360’ and ‘Mediation360’ module. It is simple to use, easily accessible and ensures that disputants are not entangled in protracted court battles.</p>
    <p>3. As per the Initiating Party:</p>
    <p style='margin-left:15px;'>{{$case->issue}}</p>

    <p>4.   The mediation shall be governed by and conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found 
        <a href='https://mediation.presolv360.com/login'>here</a></p>

    <p>5.   While the process is absolutely confidential, the mediation proceedings are ‘without prejudice’ to any legal remedies available in the event of non-participation or if the dispute remains unresolved. This has become one of the most rewarding processes, with a success rate of over 90% of all references being made.</p>

    <p>6.   The Responding Party shall, within seven (7) Working Days from the receipt of the Invitation to Mediate, accept or reject the said invitation by way of an email addressed to Presolv360 at info@presolv360.com, failing which, the mediation shall deemed to be a non-starter.
    </p>

    <p>7.   The Initiating Party and the Responding Party may choose to be represented or assisted by an authorized representative, in which case the appointing party shall submit a Letter of Authority, format of which is available <a href='https://mediation.presolv360.com/login'>here</a>. The appointing party shall submit the signed Letter of Authority by way of an email addressed to Presolv360 at  info@presolv360.com  with the subject “Letter of Authority | (Case ID) | (Name of the Appointing Party)”.</p>

    <p>8.  As per Rule 6 of Section 3 of its Dispute Resolution Rules, Presolv360 shall appoint a mediator from its panel of mediators, and such appointment shall be based on the mediator’s competence, knowledge and ability to deal with subject matter of the dispute between the parties.</p>

    <p>9.   Upon acceptance of the appointment by the mediator, the parties shall be notified of the appointment.</p>
    <br /><br /><br />

    <p><b>Note: This is a system generated notice and hence does not require signature.</b></p>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td ><p>To:</p><p>Respondent Party</p></td>
            <td ><p>Copy to:</p><p>Initiating Party</p></td>
        </tr>
    </table>

</body>
</html>