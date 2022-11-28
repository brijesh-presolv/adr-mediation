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
    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td class="text-left">
                <img src='{{ URL('assert/img/notice_logo1.png') }}' style='width: 200px;'>
            </td>
            <td class="text-center">
                <!-- <img src='{{ URL('assert/img/notice_logo2.png') }}'> -->
            </td>
            <td class="text-right">
                <img src='{{ URL('assert/img/plogo.png') }}' style='width: 120px;'>
            </td>
        </tr>
    </table>

    <!-- <center> 
        <div class="text-left">
            <img src='{{ URL('assert/img/notice_logo1.png') }}' style='width: 140px;'>
            <br><br><br>
        </div>

        <div class="text-center">
            <img src='{{ URL('assert/img/notice_logo2.png') }}' style='width: 200px;'>
            <br><br><br>
        </div>

        <div class="text-right">
            <img src='{{ URL('assert/img/plogo.png') }}' style='width: 120px;'>
            <br><br><br>
        </div>
    </center> -->


    <!-- <h2 class="text-center">Invitation to Mediate / Conciliate</h2> -->

    <h4 class="text-center">Invitation for National Lok Adalat - November 2022</h4>

    <!-- <p class="text-center"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p> -->

    <br>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>Case ID: C{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>Date : {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <br>


    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th class="text-left" width="50%">To:</th>
            <th width="50%" class="text-left" style="border-color: #fff !important;"></th>
        </tr>
        <tr>
           
            
            <td width="50%">

                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name != '')
                                @if ($p->name != '')
                                    <p>{{ $p->name }}</p>
                                @endif
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
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
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                @if ($case->otherRespondentDetails != '' && $case->otherRespondentDetails != null)
                    <p>{{ $case->otherRespondentDetails }}</p>
                @endif
                
            </td>

            <td style="border-color: #fff !important;">
                <!-- <p>Department of Consumer Affairs</p>
                <p>Ministry of Consumer Affairs, Food & Public Distribution</p>
                <p>Government of India</p>
                <p>Krishi Bhavan, New Delhi</p> -->
            </td>
        </tr>

    </table>

    <br />

    <p>
        <b>
        Subject:  Proposal of presenting your consumer case no. {{$case->ref_id}} in National Lok Adalat 
(November 2022) for settlement / determination.
        </b>
    </p>

    <p>Sir / Madam,</p>
    
    <p>1. In relation to the subject mentioned above, you are hereby informed that your consumer case 
        has been arranged to be presented before the National Lok Adalat ending on 12th November 
        2022.
    </p>
    
    <p>2. Take note that the National Legal Services Authority (“NALSA”) along with other Legal Services 
        Institutions conducts Lok Adalats. Lok Adalat is one of the alternative dispute redressal 
        mechanisms, and is a forum where disputes / cases pending in the court of law or at pre-litigation stage are settled / compromised amicably. Lok Adalats have been given statutory 
        status under the Legal Services Authorities Act, 1987. Under the said Act, the award (decision) 
        made by the Lok Adalats is deemed to be a decree of a civil court and is final and binding on 
        all parties. Watch this video to know more: https://www.youtube.com/watch?v=XDStcQ5XJTk
    </p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->

    
    <p>3. Considering the nature and sensitivity of your case, it is in your interest to participate in the 
        said proceedings to ensure just and speedy disposal of your case.
    </p>

    <p>4. In this regard, the Department of Consumer Affairs (“DoCA”), in collaboration with NALSA, has 
        selected Presolv360 to assist you in the said proceedings. 
    </p>

    <p>5. Presolv360 is an online dispute resolution institution that is working with the DoCA and NALSA
        to ensure that your case is successfully disposed of in the upcoming National Lok Adalat.
        Further, Presolv360’s role is to provide administrative and technical support throughout the 
        process, and has no interest in the outcome of the case and there exists no conflict of interest.
    </p>

    <p>6. You are required to submit your details by clicking here: https://forms.gle/dRYatvAzXbaLarnj9
        so that our Dispute Resolution Officer (“DRO”) can reach out to you to understand your stance 
        and inform you of the next steps.
    </p>

    <p>7. In the meanwhile, you are required to go through your case details and keep the case papers 
        handy.
    </p>
    
    <p><b>Note: This is a system notice and does not require signature.</b></p>
    
</body>

</html>
