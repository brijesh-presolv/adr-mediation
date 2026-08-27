<?php
$meddate = new DateTime($case->updated_at);

$meddate = $meddate->format('d-m-Y');

// IP data
$ip_name = "";
if($ini->organization != null) {
 $ip_name = $ini->organization;   
} else {
 $ip_name = $ini->name;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title> Request Letter</title>
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


    <p>Date: {{ $meddate }} </p>


    <p>
        {{$ip_name}}
        <br>
        @if ($ini->address1 != null)
            {{ $ini->address1 }}
            <br> {{ $ini->address2 }} <br> {{ $ini->city }}, {{ $ini->pincode }}
            <br> {{ $ini->state }}, {{ $ini->country }}
        @endif
        <br>
        @if ($ini->userEmail != null)
            {{ $ini->userEmail }}
            <br>
            @endif @if ($ini->userPhone != null)
                {{ $ini->userPhone }}
            @endif
    </p>
    

    <p>To: [AOI Administrator] </p>
    
    <h4>Subject: Request Letter</h4>

    <p>Dear [Name],</p>

    <p>We write to you regarding the dispute that has arisen between {{$ip_name}} and <b>{{ $res->name }}</b> concerning 
        [Brief Description]. In the interests of providing for each party the 
        opportunity to resolve the dispute amicably, we request that the parties attempt to resolve this matter through an 
        online alternative dispute resolution (ADR) process administered via AOI (the “Platform”).
    </p>

    <p>The {{$ip_name}} preferred process is [Mediation/Arbitration/Conciliation], and the details of both parties are as follows: 
    </p>

    <p>
        @if ($ini->address1 != null)
            {{ $ini->address1 }}
            <br> {{ $ini->address2 }} <br> {{ $ini->city }}, {{ $ini->pincode }}
            <br> {{ $ini->state }}, {{ $ini->country }}
        @endif
        <br>
        @if ($ini->userEmail != null)
            {{ $ini->userEmail }}
            <br>
            @endif @if ($ini->userPhone != null)
                {{ $ini->userPhone }}
            @endif
    </p>

    <p>
        @if ($res->address1 != null)
            {{ $res->address1 }}
            <br> {{ $res->address2 }} <br> {{ $res->city }}, {{ $res->pincode }}
            <br> {{ $res->state }}, {{ $res->country }}
        @endif
        <br>
        @if ($res->userEmail != null)
            {{ $res->userEmail }}
            <br>
            @endif @if ($res->userPhone != null)
                {{ $res->userPhone }}
            @endif
    </p>

    <p>The {{$ip_name}} agrees to engage in the resolution of the dispute in good faith and to comply with the 
        Platform’s rules and the laws and regulations of the relevant overseeing body. 
    </p>

    <p>This request is made without prejudice to the {{$ip_name}} rights and remedies arising from the dispute.</p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>Sincerely,</p>
                @if ($ini->signature_photo != null)
                    <p><img width="10%"
                        src="{{ Config::get('constants.user_path') }}/{{ $ini->userId }}/signature/{{ $ini->signature_photo }}" /> </p>
                @else
                    <p>Sd/-</p>
                @endif
                <p>[Name]</p>
                <p>Authorised Representative of {{$ip_name}}</p>
            </td>
        </tr>
    </table>

</body>

</html>
