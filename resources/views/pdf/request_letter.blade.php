<?php
$meddate = new DateTime($case->updated_at);

$meddate = $meddate->format('d-m-Y');


?>

<!DOCTYPE html>
<html>
    <head>
        <title> {{ config('app.name', 'Medtiator') }} | Request Letter</title>
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

    
    <br><br><p>@if($ini->organization != null) {{$ini->organization}} @else {{$ini->name}}  @endif <br>@if($ini->address1 != null) {{$ini->address1}} <br> {{$ini->address2}} <br> {{$ini->city}}, {{$ini->pincode}} 
        <br> {{$ini->state}}, {{$ini->country}}
         @endif<br>@if($ini->userEmail != null) {{$ini->userEmail}} <br> @endif @if($ini->userPhone != null) {{$ini->userPhone}} @endif</p>
    <p>Date: {{$meddate}} </p>

    <p>To,
    <br>Presolv360 Administrator,
    <br>3rd Floor, Churchgate House,
    <br>32 Veer Nariman Road, Fort,
    <br>Mumbai – 400001
    <br><a href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <p>Dear Sir,</p>
    <h4>Sub: Request to administer electronic mediation</h4>


    <p style="text-indent: 4em;">Desirous of arriving at an amicable resolution of our dispute with <b>{{$res->name}}</b>, and with a view to provide each party full opportunity to participate in the resolution of the dispute, fairly and conveniently, we request Presolv360, an independent Online Dispute Resolution (“ODR”) platform enlisted by the Department of Justice, Government of India and recognized as a Mediation Institution, to do the needful and administer electronic mediation on the platform available at <a href="https://www.presolv360.com/">https://www.presolv360.com/</a> in accordance with its Dispute Resolution Rules ("Rules").</p>
    <p style="text-indent: 4em;">We acknowledge that Presolv360 is a neutral institution that provides complete administrative and technical support to the parties to conduct the proceedings online, has no interest in the outcome of the dispute and therefore, there exists no conflict of interest. We request you to appoint an independent, qualified and competent mediator from the panel of mediators on behalf of all the parties and administer the proceedings in accordance with the Rules.</p>
    <br>
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td ><p>Sincerely,</p> <p>Sd/-</p> <p>{{$ini->name}}</p> <p>Authorized Representative</p></td>
        </tr>
    </table>

</body>
</html>