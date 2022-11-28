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
{{ URL('/resources/views/pdf/FAQs_on_Lok_Adalat.pdf') }}

<head>
    <title> FAQs on Lok Adalat </title>
    <style type="text/css">
        @page {
            header: page-header;
            footer: page-footer;
            border: 1px solid black;
            
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

        div {
            margin-bottom: 15px;
        }
    </style>
</head>

<body style="border: 1px solid #000 !important;">
    

    <h4 class="text-center"><u>FAQs on Lok Adalat</u></h4>
    <br>

    <div><strong>1. What is Lok Adalat?</strong></div>
    <div style="margin-left: 20px;">
        Lok Adalat is one of the alternative dispute redressal mechanisms, it is a forum 
        where disputes/cases pending in the court of law or at pre-litigation stage are 
        mutually settled/compromised amicably.
    </div>

    <div><strong>1. What is Lok Adalat?</strong></div>
    <div style="margin-left: 20px;">
        Lok Adalat is one of the alternative dispute redressal mechanisms, it is a forum 
        where disputes/cases pending in the court of law or at pre-litigation stage are 
        mutually settled/compromised amicably.
    </div>

    <div><strong>2. Who conducts Lok Adalat?</strong></div>
    <div style="margin-left: 20px;">NALSA along with other Legal Services Institutions conducts Lok Adalats.
    </div>

    <div><strong>3. How is Department of Consumer Affairs involved with National Lok Adalat 
        being held on 12th November 2022?</strong></div>
    <div style="margin-left: 20px;">Department of Consumer Affairs has asked all Consumer Commissions to identify 
        and refer pending cases to National Lok Adalat being organized on 12th November 
        2022. Consumers can also approach Consumer Commissions or submit their 
        consent to participate.
    </div>

    <div><strong>4. Court fee to participate in National Lok Adalat?</strong></div>
    <div style="margin-left: 20px;">There is no court fee payable when a matter is filed in a Lok Adalat. If a matter 
        pending in the court of law is referred to the Lok Adalat and is settled subsequently, 
        the court fee originally paid in the court on the complaints/petition is also refunded 
        back to the parties.
    </div>

    <div><strong>5. What is the Role of ODR Agencies Recognized?</strong></div>
    <div style="margin-left: 20px;">The Department of Consumer Affairs has collaborated with ODR Agencies to assist 
        the Consumers, Consumer Commissions, Companies and other organizations in 
        the process of Lok Adalat. And to provide their ODR platform for the conduct of 
        pre-counseling sessions.
    </div>

    <div><strong>6. Are the awards binding on both parties?</strong></div>
    <div style="margin-left: 20px;">The award (decision) made by the Lok Adalats is deemed to be a decree of a civil 
        court and is final and binding on all parties and no appeal against such an award 
        lies before any court of law. If the parties are not satisfied with the award of the Lok 
        Adalat though there is no provision for an appeal against such an award.
    </div>
    
    
    
</body>

</html>
