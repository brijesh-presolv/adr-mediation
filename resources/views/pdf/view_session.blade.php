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


    <h2 class="text-center">View Session PDF</h2>

    <h4 class="text-center">Case Id : M{{sprintf('%06d', $caseId)}}</h4>
    

    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th>
                Sr. No.
            </th>
            <th>
                Scheduling done on
            </th>
            <th>Session scheduled for</th>
            <th>Zoom Id</th>
            <th>Note</th>
            <th>Meeting User</th>
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
                        $user[] = $dd->name;
                    }
                }
            } ?>
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->session_date}}</td>
                <td>{{$value->zoom_id}}</td>
                <td>{{$value->note}}</td>
                <td>{{implode(", ", $user)}}</td>
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