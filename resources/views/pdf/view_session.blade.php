{{-- <?php
$meddate = new DateTime($case->crated_at);

$meddate = $meddate->format('d-m-Y H:i:s');

$lastdate = new DateTime();

$lastdate = $lastdate->modify('+7 days');

$ldate = $lastdate->format('d-m-Y');
?> --}}

<?php 
            use App\Models\InvoledUser;

            $dataArray = array();
 ?>

<!DOCTYPE html>
<html>
    <head>
        <title> {{ config('app.name', 'Medtiator') }} | Session</title>
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
            .table{

                margin:0 auto;
                width: 100%;
                border:solid;
                border-width:1px;
                border-collapse:collapse;
                margin-bottom:20px;

            }
            .table th{
                border:solid;
                border-width:1px;
                padding:10px;

            }

            .table td{
                border-width:1px;
                padding:10px;
                border:solid;
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
                padding:10px;
                /* overflow: hidden; */
            }

            .table_ td{
                border-width:1px;
                padding:10px;
                border:solid;
                /* overflow: hidden; */
            }
           

        </style>
    </head>
    <body>

    <center><div class="text-center">
            <img src='{{URL("assert/img/plogo.png")}}' style='width: 120px;'>
            <br><br><br>
        </div>
    </center>


    <h2 class="text-center">Session Scheduling Details</h2>

    <h4 class="text-center">Case Id : M{{sprintf('%06d', $caseId)}}</h4>
    <?php 
    use App\Models\User;
     $inparty = User::find($party[0]->userId); ?>
    <table class="table" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th width="50%" >
                Initiating Party:
            </th>
            <th>
                Responding Party:
            </th>
        </tr>
        <tr>
            <td>
                @foreach($party as $key=>$p)
                    @if ($p->isClaimant == 0)
                    <!-- <p>{{isset($inparty->organization) ? $inparty->organization : $p->name}}</p> -->
                    <p>{{$p->name}}</p>
                    <p>{{$p->address1}} {{$p->address2}}, {{$p->city}}, {{$p->pincode}}</p>
                    <p>{{$p->state}} {{$p->country}}</p>
                    <p>{{$p->userEmail}}</p>
                    <p>{{$p->userPhone}}</p>
                    <br>
                    @endif
                @endforeach
            </td>
            <td>

                @foreach($party as $key=>$p)
                    @if($key!=0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name != "")  
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
                        @if($p->name == "") 
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

    <table class="table_" >
        {{-- <colgroup>
            <col span="1" style="width: 10%;">
            <col span="1" style="width: 10%;">
            <col span="1" style="width: 10%;">
            <col span="1" style="width: 15%;">
            <col span="1" style="width: 10%;">
            <col span="1" style="width: 45%;">
         </colgroup> --}}
       

        <tr>
            <th>
                Sr. No.
            </th>
            <th>
                Scheduling done on
            </th>
            <th>Session scheduled for</th>
            <th>VC Details</th>
            <th>Notes</th>
            <th>Participants</th>
        </tr>
        @foreach ($sessionData as $key => $value)
            <?php
            if (!is_null($value->session_party_ids)) {
                $dataArray = json_decode($value->session_party_ids);
            }
            $user = array();
            foreach ($dataArray as $d) {
                $dd = InvoledUser::where('userId', $d)->where('userPlanId', $caseId)->first();
                if (isset($dd)) {
                    if ($dd->name != null) {
                    if($dd->isClaimant == 0){
                        $user[] = "Initiating Party: " . $dd->name;
                    } else {
                        $user[] = "Responding Party: " . $dd->name;
                    }
                    }
                } else {
                    $dd = InvoledUser::where('id', $d)->where('userPlanId', $caseId)->first();
                    if(isset($dd)) {
                        if ($dd->name != null) {
                        if($dd->isClaimant == 0){
                            $user[] = "Initiating Party: " . $dd->name;
                        } else {
                            $user[] = "Responding Party: " . $dd->name;
                        }
                        }
                    }
                }
            }
            if(isset($mediator)) {
                $user[] = "Mediator: " . $mediator->first_name . " " . $mediator->last_name;
            }
             ?>
            <tr>
                <td style="width: 7%">{{$key + 1}}</td>
                <td style="width: 15%">{{$value->created_at}}</td>
                <td style="width: 15%">{{$value->session_date}}</td>
                <td style="width: 25%">{{$value->zoom_id}}
                @if($value->zoom_link != '')
                <p><strong>Zoom Link -</strong></p>
                <p>{{$value->zoom_link}}</p>
                @endif
                </td>
                <td style="width: 24%">{{$value->note}}</td>
                <td>@foreach($user as $name) {{$name}} <br> @endforeach</td>
            </tr>
        @endforeach
        {{-- <tr>
            <td>
                
            </td>
            <td >

               
            </td>
        </tr> --}}

    </table>

    

</body>
</html>