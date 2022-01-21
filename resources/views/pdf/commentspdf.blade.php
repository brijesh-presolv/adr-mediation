<!DOCTYPE html>
<html lang="en">

<head>
    <title> {{ config('app.name', 'Medtiator') }} | Comments</title>

    <style>
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
        .main-div {
            border: 1px solid black;
            width: auto;
            height: auto;
            padding: 20px;
        }
        .user {
            text-align: right;
            margin-top: -20px;
            font-size: 13px;
        }

        .date {
            margin-top: 10px;
            font-size: 13px;
        }
        .row {
            display: flex;
        }
        .comment {
            border-bottom: 1px solid darkgray;
            /* text-align: left; */
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
    </style>
</head>

<body>
    <center><div class="text-center">
        <img src='{{URL("assert/img/plogo.png")}}' style='width: 120px;'>
        <br><br><br>
    </div>
    </center>
    <h2 class="text-center">@if($type == 1) Private @else Share @endif Comments Details</h2>

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
            <td >
                <p>{{isset($inparty->organization) ? $inparty->organization : $party[0]->name}}</p>
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

    <div class="main-div">
        @foreach ($comment as $item)
            <div class="row">
                @if ($item->username === Auth::user()->username)
                    <div class="date">{{ $item->created }}</div>
                    <div class="user">{{ $item->username }}</div>

                @else

                    <div style="text-align: right" class="date">{{ $item->created }}</div>
                    <div style="text-align: left" class="user">{{ $item->username }}</div>
                @endif
            </div>

            <div class="comment">
                @if ($item->username === Auth::user()->username)

                    <p style="text-align: right">{{ $item->comment }}</p>

                @else
                    <p style="text-align: left">{{ $item->comment }}</p>
                @endif
            </div>
        @endforeach
    </div>

</body>
</html>
