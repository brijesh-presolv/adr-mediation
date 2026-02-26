<?php
 
use App\Models\User;

$meddate = new DateTime($case->crated_at);

$meddate = $meddate->format('d-m-Y H:i:s');

$lastdate = new DateTime();

$lastdate = $lastdate->modify('+7 days');

$ldate = $lastdate->format('d-m-Y');

$itm_lang_arr = explode(",", $case->itm_lang);


?>

<!DOCTYPE html>
<html>  

<head>
    <title> {{ config('app.name', 'Medtiator') }} | Invitation of Mediation / Conciliation</title>

    <meta charset="utf-8">
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">

    <style type="text/css">
         body {
                font-family: 'freeserif', 'Open Sans', 'Arial', 'Arial', 'sans-serif', 'Verdana';
                /*font-family: 'Open Sans', 'Arial', 'sans-serif', 'Hind', 'DejaVu Sans', 'freeserif', 'lohitkannada', 'pothana2000' , 'Noto Sans Tamil';
                /* font-size: 14.5px;
                border: 1px solid;
                width: 107%;
                height: 106%;
                margin: 0;
                padding: 0;
                page-break-after: always;
                page-break-inside: avoid;
                box-sizing: border-box; */
            }

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

        .table_{
        page-break-inside: avoid;
        /* font-size: 14px; */
    }

    </style>

</head>

<body>

@foreach($itm_lang_arr as $key => $itm_lang) 

@if($key == 0)
@php
$page_break_css = ''
@endphp
@else
@php
$page_break_css = 'page-break-before: always;';
@endphp
@endif
    @if($itm_lang == "english")    

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <h2 class="text-center" style="margin-top: 0px !important;">Invitation to Mediate / Conciliate</h2>

    <h4 class="text-center" style="margin-bottom: 0px !important; margin-top: 0px !important;font-size:14px !important;">
        <!-- Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) and  -->
        Empaneled as a Mediation Institution by various Courts in India</h4>

    <p class="text-center" style="page-break-after:avoid;margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    

    <table cellspacing="0" cellpadding="10" width="100%" style="">
        <tr>
            <td width="60%">
                <p>Case ID: M{{ sprintf('%06d', $case->id) }} | Ref ID: {{$case->ref_id}}</p>
            </td>
            <td class="text-right">
                <p>Date : {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
   


    <table class="table_" cellspacing="0" cellpadding="10" width="100%" autosize="1" style="page-break-inside: avoid !important;">
        <tr style="page-break-after: avoid !important;">
            <th width="50%">
                Applicant(s) / Initiating Party:
            </th>
            <th>
                Opposite / Responding Party:
            </th>
        </tr>
        <tr>
            <td >
                @foreach ($party as $key => $p)
                    <?php
                    $inparty = User::find($p->userId); ?>
                    @if ($p->isClaimant == 0)
                        <p>{{ isset($inparty->organization) ? $inparty->organization . ' through its authorized representative ' . $p->name : $p->name }}
                        </p>
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

                        <!-- @if ($p->userEmail != null)
                        <p>{{$p->userEmail}}</p>
                        @endif

                        @if ($p->userPhone != null)
                        <p>{{$p->userPhone}}</p>
                        @endif -->
                        <br> <br>

                        @if ($p->userpname != null)
                        <p>{{$p->userpname}}</p>
                        @endif

                        @if ($p->userpemail != null)
                        <p>{{$p->userpemail}}</p>
                        @endif

                        @if ($p->userpcontact != null)
                        <p>{{$p->userpcontact}}</p>
                        @endif
                    @endif
                @endforeach
                {{-- <p>{{$party[0]->userEmail}}</p>
                <p>{{$party[0]->userPhone}}</p> --}}
            </td>
            <td style="page-break-inside: auto !important;">

                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name != '')
                                @if ($p->name != '')
                                    <p>{{ $p->name }}</p>
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
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                <!-- @if ($case->otherRespondentDetails != '' && $case->otherRespondentDetails != null)
                    <p>{{ $case->otherRespondentDetails }}</p>
                @endif -->
                <br>
                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name == '')
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                @if($case->otherRespondentDetails != "" && $case->otherRespondentDetails != null)
                <p>{{$case->otherRespondentDetails}}</p>
                @endif
            </td>
        </tr>

        @if ($case->discussion != '' && $case->discussion != null)
        <tr>
            <td>
                <p>Contact of discussion</p>
                <p>{{$case->discussion}}</p>
            </td>
        </tr>
        @endif


    </table>


    <p>1. Desirous of arriving at an amicable resolution, the Applicant(s) / Initiating Party has sought an amicable
        resolution of the dispute and registered a request with Presolv360.</p>
    {{-- <p>2. Presolv360 (recognised by the Department of Justice, Ministry of Law and Justice, Government of India) is a platform specializing in online dispute resolution through its ‘Arbitration360’ and ‘Mediation360’ module. It is simple to use, easily accessible and ensures that disputants are not entangled in protracted court battles.</p> --}}
    <p>2. As per the Applicant(s) / Initiating Party:</p>
    
    <!-- <p style='margin-left:15px;'>
        <?php //echo nl2br($case->issue); ?>
    </p>
 -->
    <p style='margin-left:15px;'>
    {{ $primary_rp_name }} availed {{ $case->disputeCategory }} bearing number {{ $case->ref_id }} from {{ $case->enterprise }} and has not
    cleared outstanding dues of Rs. {{ $case->amount }} till date. {{ $case->enterprise }} desires to resolve this matter amicably with
    the help of recognized independent institution, Presolv360, failing which, the matter shall be resolved by
    arbitration, administered electronically by Presolv360, in accordance with its Dispute Resolution Rules. To
    immediately resolve and close this matter, please feel free to contact {{ $case->poc_name }} on {{ $case->poc_contact }}.
    </p>

    <p style='margin-left:15px;'>
        DO NOT MISS THE OPPORTUNITY TO SETTLE YOUR LOAN BY ATTENDING THE MEETING THROUGH VIDEO CONFERENCE ON {{ $case->zoom_date }} {{ $case->zoom_time }} BY CLICKING ON
        THIS ZOOM LINK:
        {{ $case->zoom_link }}
    </p>

    {{-- <p>3. The mediation / conciliation shall be governed by and conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found 
        <a href='https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing'>here</a></p> --}}
    <p>3. Presolv360 is
        <!-- is included in the list of institutions offering Alternative Dispute Resolution ("ADR") services
        including through Online Dispute Resolution ("ODR") and is also  -->
        empaneled as a
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
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a>, failing which, the mediation / conciliation shall deemed to 
        be a non-starter.
    </p>

    <p>6. The parties may choose to be represented or assisted by an authorized representative, in which case the
        appointing party shall submit a Letter of Authority, format of which is available <a
            href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>here</a>. The
        appointing party shall submit the signed Letter of Authority by way of an email addressed to Presolv360 at
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> with the subject “Letter of Authority | (Case ID) | 
        (Name of the Appointing Party)”.</p>

    <p style="">7. The Institution can also be requested to provide a list of available mediators from its panel of mediators, by way of an email 
        addressed to admin@presolv360.com with the subject "Request for List of Available Mediators | (Case ID)". The parties shall mutually appoint a mediator from the said list, failing which, the aforesaid mediator shall be confirmed.</p>

    <p style="">8. To access the case management system, the Respondent(s) shall complete the following process: </p>
    <p style='margin-left:15px; margin-bottom: 0px !important; margin-top: 0px !important;'>
        a. Create your account using your registered email ID by <a href="https://mediation.presolv360.com/login">clicking here</a>.
    </p>

    <p style='margin-left:15px; margin-bottom: 0px !important; margin-top: 0px !important;'>
        b. For authentication purposes, a unique join code will be required. The same is provided separately.
    </p>

    <p style='margin-left:15px; margin-bottom: 0px !important; margin-top: 0px !important;'>
         c. For any assistance to access the case management system, kindly address an email to admin@presolv360.com and mention your Case ID in the subject line.
    </p>


    <p>9. If any party requires assistance of an Indian Sign Language (ISL) interpreter in case of hearing impairment, write an email 
        addressed to <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> with the subject "Request for Interpreter | (Case ID)", and this facility will be provided 
        by Presolv360.</p>

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

    @endif
    
        @if($itm_lang == "marathi")
    
    <!----------------- MARATHI ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">मध्यस्थी / सामंजस्य करण्यासाठी आमंत्रण </p>
    
    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">
    ऑनलॉइन विवाद निराकरणासह (ODR) पर्यायी विवाद निराकरण सेवा (ADR) प्रदान करणाऱ्या आणि विविध न्यायालयांद्वारे मध्यस्थी संस्था 
    <!-- म्हणून नामांकीत केलेल्या संस्थांचा यादीमध्ये  <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing"> (येथे माहिती उपलब्ध आहे) </a> 
            समावेश आहे. -->
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>प्रकरण आयडी: M{{ sprintf('%06d', $case->id) }} | संदर्भ क्रमांक: {{$case->ref_id}}</p>
            </td>
            <td class="text-right">
                <p>दिनांक: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>अर्जदार / प्रारंभिक पक्ष:</p>
            </td>
            <td>
                <p>सामनेवाले / प्रतिसाद देणारा पक्ष:</p>
            </td>
        </tr>
        <tr>
            <td>
                @foreach ($party as $key => $p)
                    <?php
                    $inparty = User::find($p->userId); ?>
                    @if ($p->isClaimant == 0)
                        <p>{{ isset($inparty->organization) ? $inparty->organization . ' through its authorized representative ' . $p->name : $p->name }}
                        </p>
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

                        <!-- @if ($p->userEmail != null)
                        <p>{{$p->userEmail}}</p>
                        @endif

                        @if ($p->userPhone != null)
                        <p>{{$p->userPhone}}</p>
                        @endif -->
                        <br> <br>

                        @if ($p->userpname != null)
                        <p>{{$p->userpname}}</p>
                        @endif

                        @if ($p->userpemail != null)
                        <p>{{$p->userpemail}}</p>
                        @endif

                        @if ($p->userpcontact != null)
                        <p>{{$p->userpcontact}}</p>
                        @endif
                    @endif
                @endforeach
                {{-- <p>{{$party[0]->userEmail}}</p>
                <p>{{$party[0]->userPhone}}</p> --}}
            </td>
            <td>

                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name != '')
                                @if ($p->name != '')
                                    <p>{{ $p->name }}</p>
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
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                <!-- @if ($case->otherRespondentDetails != '' && $case->otherRespondentDetails != null)
                    <p>{{ $case->otherRespondentDetails }}</p>
                @endif -->
                <br>
                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name == '')
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                @if($case->otherRespondentDetails != "" && $case->otherRespondentDetails != null)
                <p>{{$case->otherRespondentDetails}}</p>
                @endif
            </td>
        </tr>

        @if ($case->discussion != '' && $case->discussion != null)
        <tr>
            <td>
                <p>Contact of discussion</p>
                <p>{{$case->discussion}}</p>
            </td>
        </tr>
        @endif


    </table>

    
    
    <p>1. सौदार्हपूर्ण निराकरणावर पोहोचण्यास इच्छूक असलेल्या, अर्जदार / प्रारंभिक पक्षाने विवादाचे सौदार्हपूर्ण निराकरण करण्याची मागणी केली आहे आणि Presolv360 कडे वरील विनंती नोंदवली आहे.

    </p>
    
    <p>2. अर्जदार / प्रारंभिक पक्षाप्रमाणे:
    </p>
    
    <!-- <p style='margin-left:15px;'>
        <?php //echo nl2br($case->issue); ?>
    </p> -->

    <p style='margin-left:15px;'>{{ $primary_rp_name }} यांनी {{ $case->enterprise }} कडून {{ $case->disputeCategory }} (कर्ज क्रमांक: {{ $case->ref_id }}) घेतले असून, 
        आजपर्यंत रुपये {{ $case->amount }} इतकी थकबाकी रक्कम परतफेड केलेली नाही. {{ $case->enterprise }} 
            ही बाब Presolv360 या मान्यताप्राप्त स्वतंत्र संस्थेच्या मदतीने आपसात समजूत काढून सोडवू इच्छिते.
    </p>
    <p style='margin-left:15px;'>ही बाब तात्काळ सोडवून प्रकरण बंद करण्यासाठी कृपया {{ $case->poc_name }} {{ $case->poc_contact }} या क्रमांकावर संपर्क साधावा.
    </p>
    <p style='margin-left:15px;'>खाली दिलेल्या झूम लिंकवर क्लिक करून दिनांक {{ $case->zoom_date }} रोजी 
        {{ $case->zoom_time }} या वेळेत व्हिडिओ कॉन्फरन्सद्वारे होणाऱ्या बैठकीस उपस्थित राहून आपले कर्ज प्रकरण 
        सौहार्दपूर्णरीत्या निकाली काढण्याची ही संधी गमावू नका: {{ $case->zoom_link }}
    </p>

    <p>3. ऑनलाइन विवाद निराकरणासह ("ODR") पर्यायी विवाद निराकरण सेवा प्रदान करणाऱ्या ("ADR")आणि भारतातील विविध न्यायालयांद्वारे मध्यस्थी संस्था 
        <!-- म्हणून पॅनेलमध्ये सामिल केलेल्या संस्थांच्या यादीमध्ये Presolv360 समाविष्ट केले गेले आहे.  -->
        Presolv360 त्याच्या प्लॅटफॉर्मवर मध्यस्थी कार्यवाही प्रशासित करते आणि त्याच्या 
        मध्यस्थांच्या पॅनेलवर आवश्यक क्षमता, ज्ञान आणि कौशल्य असलेल्या स्वतंत्र, पात्र मध्यस्थांना समाविष्ट करते. 
        मध्यस्थी / सामंजस्य Presolv360 च्या विवाद निराकरण नियमांनुसार नियंत्रित आणि आयोजित केले जाईल, ज्याची प्रत इथे 
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">here</a> मिळू शकते. Presolv360 सर्व 
        संबंधित पक्षांना आणि मध्यस्थी प्रक्रिया करणाऱ्या मध्यस्थांना प्रशासकीय सेवा प्रदान करते आणि त्यांना विवादाच्या निकालामध्ये स्वारस्य नसते आणि तेथे स्वारस्यांचा कोणताही 
        संघर्ष नसतो.

    </p>

    
    <p>4.  प्रक्रिया पूर्णपणे गोपनिय असताना, सहभागी न झाल्यास किंवा विवाद निराकरण न झाल्यास, मध्यस्थी / समेटाची कार्यवाही ‘पूर्वग्रहाशिवाय’ 
        कोणत्याही कायदेशीर उपायांसाठी उपलब्ध आहे. ही सर्वात फायद्याची प्रक्रिया बनली आहे, जिचा यशाचा दर सर्व संदर्भांमध्ये 90% पेक्षा जास्त आहे.
    </p>

    <p>5. विरुद्ध / प्रतिसाद देणारा पक्षाने, मध्यस्थी / सामंजस्य करण्याचे निमंत्रण मिळाल्यापासून (७) कामकाजी दिवसांच्या आत, Presolv360 वरील 
    <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> या पत्त्यावर इमेलद्वारे निमंत्रण स्विकारणे किवा नाकारणे आवश्यक आहे. असे न केल्यास, मध्यस्थी / सामंजस्य बिना-सुरुवातीचे समजले जाईल.


    </p>

    <p>6. पक्ष अधिकृत प्रतिनिधीद्वारे प्रतिनिधीत्व किंवा सहाय्य निवडू शकतात, अशा परिस्थितीत नियुक्त करणारा पक्ष प्राधिकरणाचे पत्र सादर करेल, ज्याचा नमुना येथे<a
        href="https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing">here</a>
        उपलब्ध आहे. नियुक्ती करणाऱ्या पक्षाने स्वाक्षरी केलेले प्राधिकरणाचे पत्र Presolv360 वरील 
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> पत्त्यावर “अधिकृत पत्र”  | प्रकरण आयडी | (नियुक्त करणाऱ्या पक्षाचे नाव) अशा विषयासह पाठवणे आवश्यक आहे.

    </p>

    <p>7. संस्थेकडून त्यांच्या मध्यस्थांच्या पॅनेलमधील उपलब्ध मध्यस्थांची यादी प्रदान करण्याची विनंती देखील करता येऊ शकते. यासाठी admin@presolv360.com या ईमेल पत्त्यावर “Request for List of Available Mediators | (Case ID)” या विषयासह ईमेल पाठवावा. पक्षकार उक्त यादीतून परस्पर संमतीने एका मध्यस्थाची नियुक्ती करतील. असे करण्यात अपयश आल्यास, वरील मध्यस्थाची पुष्टी करण्यात येईल.
    </p>
    
    <p>8. केस व्यवस्थापन प्रणालीमध्ये प्रवेश मिळविण्यासाठी, प्रतिवादी (Respondent/Respondents) यांनी खालील प्रक्रिया पूर्ण करावी:
    </p>
    <p>a. “येथे क्लिक करा” या दुव्याद्वारे आपल्या नोंदणीकृत ईमेल आयडीचा वापर करून खाते तयार करा.</p>
    <p>b. प्रमाणीकरणासाठी एक अद्वितीय जॉइन कोड आवश्यक असेल. तो कोड स्वतंत्रपणे प्रदान करण्यात आला आहे.</p>
    <p>c. केस व्यवस्थापन प्रणालीमध्ये प्रवेशासंबंधी कोणत्याही सहाय्यासाठी, कृपया admin@presolv360.com वर ईमेल पाठवा आणि विषय ओळीत आपला Case ID नमूद करा.</p>

    
    <p>9. मध्यस्थ / समन्वयकर्त्यांच्या पॅनेलमधून मध्यस्थ / समन्वयकर्ता निवडला जाईल, आणि अशी नियुक्ती मध्यस्थ / समन्वयकर्त्याची क्षमता, 
        ज्ञान आणि पक्षांमधील विवादाच्या विषयाला सामोरे जाण्याची क्षमता यावर आधारित असेल.

    </p>

    <p>10. मध्यस्थ / समन्वयकर्त्याकडून नियुक्ती स्विकृत केल्यानंतर  पक्षांना नियुक्तीबाबत सूचित केले जाईल.
    </p>

    <p>11. श्रवणदोष असल्यास कोणत्याही पक्षाला भारतीय सांकेतिक भाषा (ISL) दुभाष्याची मदत हवी असल्यास, 
    <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> या पत्त्यावर "दुभाष्यासाठी विनंती | (प्रकरण आयडी)" अशा विषयासह इमेल पाठवा आणि ही सुविधा Presolv360 द्वारे पुरविली जाईल.
    </p>

    <p>सूचना: ही प्रणालीद्वाले बनवलेली सूचना आहे आणि म्हणून सहीची आवश्यकता नाही.

</p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>प्रति:
                </p>
                <p> सामनेवाले / प्रतिसाद देणारा पक्ष
                </p>
            </td>
            <td>
                <p>यांना प्रत:
                </p>
                <p>आवेदक / प्रारंभिक पक्ष
                </p>
            </td>
        </tr>
    </table>

    <!----------------- MARATHI ------------------------------------------------------->
    @endif

    @if($itm_lang == "odiya")
    
    <!----------------- ODIYA ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">ମଧ୍ୟସ୍ଥ / ସମନ୍ୱୟ ପାଇଁ ନିମନ୍ତ୍ରଣ</p>
    
    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">
    <!-- ଅନଲାଇନ୍ ବିବାଦ ସମାଧାନ (ODR) ମାଧ୍ୟମରେ ବିକଳ୍ପ ବିବାଦ ସମାଧାନ (ADR) ସେବା ପ୍ରଦାନ କରୁଥିବା ଅନୁଷ୍ଠାନ    
    <a href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing"> (ଏଠାରେ ଉପଲବ୍ଧ) </a> 
    ତାଲିକାରେ ଅନ୍ତର୍ଭୂକ୍ତ କରାଯାଇଛି ଏବଂ ଭାରତର ବିଭିନ୍ନ କୋର୍ଟ ଦ୍ୱାରା ମଧ୍ୟସ୍ଥତା ପ୍ରତିଷ୍ଠାନ ଭାବରେ ସାମଞ୍ଜସ୍ୟ ପ୍ରାପ୍ତ ହୋଇଛି  -->
    ଭାରତର ବିଭିନ୍ନ କୋର୍ଟ ଦ୍ୱାରା ମଧ୍ୟସ୍ଥତା ପ୍ରତିଷ୍ଠାନ ଭାବରେ ତାଲିକାଭୁକ୍ତ।
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>କେସ ଆଇଡି: M{{ sprintf('%06d', $case->id) }} | Ref ID: {{$case->ref_id}}</p>
            </td>
            <td class="text-right">
                <p>ତାରିଖ: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>ଆବେଦନକାରୀ (ଗୁଡିକ) / ପ୍ରାରମ୍ଭ ପାର୍ଟି:</p>
            </td>
            <td>
                <p>ବିରୋଧୀ / ପ୍ରତିକ୍ରିୟାଶୀଳ ପାର୍ଟୀ:</p>
            </td>
        </tr>
        <tr>
            <td>
                @foreach ($party as $key => $p)
                    <?php
                    $inparty = User::find($p->userId); ?>
                    @if ($p->isClaimant == 0)
                        <p>{{ isset($inparty->organization) ? $inparty->organization . ' through its authorized representative ' . $p->name : $p->name }}
                        </p>
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

                        <!-- @if ($p->userEmail != null)
                        <p>{{$p->userEmail}}</p>
                        @endif

                        @if ($p->userPhone != null)
                        <p>{{$p->userPhone}}</p>
                        @endif -->
                        <br> <br>

                        @if ($p->userpname != null)
                        <p>{{$p->userpname}}</p>
                        @endif

                        @if ($p->userpemail != null)
                        <p>{{$p->userpemail}}</p>
                        @endif

                        @if ($p->userpcontact != null)
                        <p>{{$p->userpcontact}}</p>
                        @endif
                    @endif
                @endforeach
                {{-- <p>{{$party[0]->userEmail}}</p>
                <p>{{$party[0]->userPhone}}</p> --}}
            </td>
            <td>

                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name != '')
                                @if ($p->name != '')
                                    <p>{{ $p->name }}</p>
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
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                <!-- @if ($case->otherRespondentDetails != '' && $case->otherRespondentDetails != null)
                    <p>{{ $case->otherRespondentDetails }}</p>
                @endif -->
                <br>
                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name == '')
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                @if($case->otherRespondentDetails != "" && $case->otherRespondentDetails != null)
                <p>{{$case->otherRespondentDetails}}</p>
                @endif
            </td>
        </tr>

        @if ($case->discussion != '' && $case->discussion != null)
        <tr>
            <td>
                <p>Contact of discussion</p>
                <p>{{$case->discussion}}</p>
            </td>
        </tr>
        @endif


    </table>

    
    
    <p>1. ଏକ ବନ୍ଧୁତ୍ୱପୂର୍ଣ୍ଣ ସମାଧାନ ରେ ପହଞ୍ଚିବାକୁ ଇଚ୍ଛା କରି ଆବେଦନକାରୀ (ଗୁଡିକ) / ପ୍ରାରମ୍ଭ ପାର୍ଟି ଏହି ବିବାଦର ସମାଧାନ ପାଇଁ ଚେଷ୍ଟା କରିଛନ୍ତି ଏବଂ Presolv360 ରେ ଏକ ଅନୁରୋଧ ପଞ୍ଜିକରଣ କରିଛନ୍ତି |</p>
    
    <p>2. ଆବେଦନକାରୀ (ଗୁଡିକ) / ପ୍ରାରମ୍ଭ ପାର୍ଟି ଅନୁଯାୟୀ:
    </p>
    
    <!-- <p style='margin-left:15px;'> -->
        <?php 
        //echo nl2br($case->issue); ?>
    <!-- </p> -->

    <p style='margin-left:15px;'>{{ $primary_rp_name }}{{ $case->enterprise }}ଲିମିଟେଡରୁ {{ $case->disputeCategory }} ଋଣ ନମ୍ବର {{ $case->ref_id }} ପାଇଛନ୍ତି ଏବଂ ଆଜି ପର୍ଯ୍ୟନ୍ତ {{ $case->amount }} ଟଙ୍କା ବକେୟା ପାଉଣା ପରିଶୋଧ କରିନାହାଁନ୍ତି। ସ୍ୱତନ୍ତ୍ର ମାଇକ୍ରୋ ହାଉସିଂ ଫାଇନାନ୍ସ କର୍ପୋରେସନ ଲିମିଟେଡ ସ୍ୱୀକୃତିପ୍ରାପ୍ତ ସ୍ୱାଧୀନ ସଂସ୍ଥା, 
        Presolv360 ସାହାଯ୍ୟରେ ଏହି ମାମଲାକୁ ସୌହାର୍ଦ୍ଦପୂର୍ଣ୍ଣ ଭାବରେ ସମାଧାନ କରିବାକୁ ଚାହୁଁଛି, ଯଦି ଏହା ବିଫଳ ହୁଏ, ତେବେ ଏହାର ବିବାଦ ସମାଧାନ ନିୟମ ଅନୁଯାୟୀ, Presolv360 ଦ୍ୱାରା ଇଲେକ୍ଟ୍ରୋନିକ୍ ଭାବରେ ପରିଚାଳିତ ମଧ୍ୟସ୍ଥତା ଦ୍ୱାରା ମାମଲାଟି ସମାଧାନ କରାଯିବ। ଏହି ମାମଲାକୁ ତୁରନ୍ତ ସମାଧାନ ଏବଂ ବନ୍ଦ କରିବା ପାଇଁ, 
        ଦୟାକରି {{ $case->poc_name }} ରେ {{ $case->poc_contact }} ସହିତ ଯୋଗାଯୋଗ କରିବାକୁ ମୁକ୍ତ ହୁଅନ୍ତୁ।
    </p>
    <p style='margin-left:15px;'>ଆପଣ ଭିଡିଓ କନଫରେନ୍ସ ମାଧ୍ୟମରେ ବୈଠକରେ ଅଂଶଗ୍ରହଣ କରି ଆପଣଙ୍କର ଋଣ ସମାଧାନ କରିପାରିବେ
    {{ $case->zoom_date }} ଏହି ଜୁମ୍ ଲିଙ୍କ୍ ଉପରେ କ୍ଲିକ୍ କରି {{ $case->zoom_time }} ସକାଳ ଏବଂ {{ $case->zoom_link }} ଅପରାହ୍ନ ମଧ୍ୟରେ:
    </p>

    <p>
        <!-- 3. ଅନ୍ଲାଇନ୍ ବିବାଦ ସମାଧାନ ("ODR") ସହିତ ବିକଳ୍ପ ବିବାଦ ସମାଧାନ ("ADR") ସେବା ପ୍ରଦାନ କରୁଥିବା ଅନୁଷ୍ଠାନ ତାଲିକାରେ Presolv360 ଅନ୍ତର୍ଭୂକ୍ତ କରାଯାଇଛି ଏବଂ ଏହା ମଧ୍ୟ ଭାରତର ବିଭିନ୍ନ କୋର୍ଟ ଦ୍ୱାରା ମଧ୍ୟସ୍ଥତା ପ୍ରତିଷ୍ଠାନ ଭାବରେ ସ୍ଥାନିତ ହୋଇଛି |  Presolv360 ଏହାର ପ୍ଲାଟଫର୍ମରେ ମଧ୍ୟସ୍ଥତା ପ୍ରକ୍ରିୟା ପରିଚାଳନା କରେ, ଏବଂ ଏହାର ମଧ୍ୟସ୍ଥତା ପ୍ୟାନେଲରେ ଆବଶ୍ୟକ ଦକ୍ଷତା, ଜ୍ଞାନ ଏବଂ ପାରଦର୍ଶୀତା ସହିତ ସ୍ୱାଧୀନ, ଯୋଗ୍ୟ ମଧ୍ୟସ୍ଥିମାନଙ୍କୁ ଗ୍ରହଣ କରେ | ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟ Presolv360 ର ବିବାଦର ସମାଧାନ ନିୟମ ଅନୁଯାୟୀ ପରିଚାଳିତ ହେବ ଏବଂ ଏହାର ଏକ ନକଲ -->
        3. Presolv360 ଭାରତର ବିଭିନ୍ନ କୋର୍ଟ ଦ୍ୱାରା ମଧ୍ୟସ୍ଥତା ପ୍ରତିଷ୍ଠାନ ଭାବରେ ସ୍ଥାନିତ ହୋଇଛି |  Presolv360 ଏହାର ପ୍ଲାଟଫର୍ମରେ ମଧ୍ୟସ୍ଥତା ପ୍ରକ୍ରିୟା ପରିଚାଳନା କରେ, ଏବଂ ଏହାର ମଧ୍ୟସ୍ଥତା ପ୍ୟାନେଲରେ ଆବଶ୍ୟକ ଦକ୍ଷତା, ଜ୍ଞାନ ଏବଂ ପାରଦର୍ଶୀତା ସହିତ ସ୍ୱାଧୀନ, ଯୋଗ୍ୟ ମଧ୍ୟସ୍ଥିମାନଙ୍କୁ ଗ୍ରହଣ କରେ | ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟ Presolv360 ର ବିବାଦର ସମାଧାନ ନିୟମ ଅନୁଯାୟୀ ପରିଚାଳିତ ହେବ ଏବଂ ଏହାର ଏକ ନକଲ
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">ଏଠାରେ</a> 
        ମିଳିପାରିବ |Presolv360 ସଂପୃକ୍ତ ସମସ୍ତ ପକ୍ଷ ଏବଂ ମଧ୍ୟସ୍ଥତା ପ୍ରକ୍ରିୟା ପରିଚାଳନା ପାଇଁ ମଧ୍ୟସ୍ଥତାଙ୍କୁ ପ୍ରଶାସନିକ ସହାୟତା ଯୋଗାଇଥାଏ ଏବଂ ବିବାଦର ଫଳାଫଳ ପାଇଁ କୌଣସି ଆଗ୍ରହ ନାହିଁ ଏବଂ ସେଠାରେ କୌଣସି ଆଗ୍ରହ ନାହିଁ |
        <!-- ମିଳିପାରିବ |Presolv360 ସଂପୃକ୍ତ ସମସ୍ତ ପକ୍ଷ ଏବଂ ମଧ୍ୟସ୍ଥତା ପ୍ରକ୍ରିୟା ପରିଚାଳନା ପାଇଁ ମଧ୍ୟସ୍ଥତାଙ୍କୁ ପ୍ରଶାସନିକ ସହାୟତା ଯୋଗାଇଥାଏ ଏବଂ ବିବାଦର ଫଳାଫଳ ପାଇଁ କୌଣସି ଆଗ୍ରହ ନାହିଁ ଏବଂ ସେଠାରେ କୌଣସି ଆଗ୍ରହ ନାହିଁ | -->
    </p>

    
    <p>4. ଯଦି ବିବାଦର ସମାଧାନ ହୋଇନଥାଏ କିମ୍ବା ଏହି ପ୍ରକ୍ରିୟାଟି ସମ୍ପୂର୍ଣ୍ଣ ଗୋପନୀୟ ରଖିଥିବାବେଳେ ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟ ପ୍ରକ୍ରିୟା କୌଣସି ଆଇନଗତ ପ୍ରତିକାରରେ ଅଂଶଗ୍ରହଣ କରିବେ ନାହିଁ |  ସମସ୍ତ ରେଫରେନ୍ସଗୁଡିକର ୯୦% ରୁ ଅଧିକ ସଫଳତା ହାର ସହିତ ଏହା ଏକ ପୁରସ୍କାରପ୍ରଦ ପ୍ରକ୍ରିୟା ମଧ୍ୟରୁ ଗୋଟିଏ ହୋଇପାରିଛି |</p>

    <p>5. ବିରୋଧୀ / ପ୍ରତିକ୍ରିୟାଶୀଳ ପାର୍ଟି, ମଧ୍ୟସ୍ଥ / ସମନ୍ୱୟ ନିମନ୍ତ୍ରଣ ପାଇବା ଠାରୁ ସାତ (7) କାର୍ଯ୍ୟ ଦିବସ ମଧ୍ୟରେ, <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
    ରେ presolv360 କୁ ଏକ ଇମେଲ୍ ମାଧ୍ୟମରେ ଉକ୍ତ ନିମନ୍ତ୍ରଣକୁ ଗ୍ରହଣ କିମ୍ବା ପ୍ରତ୍ୟାଖ୍ୟାନ କରିବ, ଯାହା ବିଫଳ ହେବ,  ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟ ଏକ ଅଣ-ଷ୍ଟାର୍ଟର ବୋଲି ଧରାଯିବ |  
    </p>

    <p>
        6. ପକ୍ଷଗୁଡିକ ଏକ ପ୍ରାଧିକୃତ ପ୍ରତିନିଧୀଙ୍କ ଦ୍ ରା ପ୍ରତିନିଧିତ୍ କିମ୍ବା ସହାୟତା କରିବାକୁ ବାଛିପାରନ୍ତି, ଯେଉଁ କ୍ଷେତ୍ରରେ ନିଯୁକ୍ତ ଦଳ ଏକ ଅଥରିଟି ଅଫ୍ ଅଥରିଟି ଦାଖଲ କରିବେ, ଯାହାର ଫର୍ମାଟ୍
        <a href="https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing">ଏଠାରେ</a>
        ଉପଲବ୍ଧ | ନିଯୁକ୍ତ ଦଳ ଏହି ବିଷୟ ସହିତ 
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
        ରେ Presolv360 କୁ ପଠାଯାଇଥିବା ଏକ ଇମେଲ ମାଧ୍ୟମରେ ସ୍ୱାକ୍ଷରିତ ଅଥରିଟି ଦାଖଲ କରିବେ  
        "Letter of Authority | (Case ID) | (Name of the Appointing Party)".
    </p>

    <p>7. ସଂସ୍ଥାର ମଧ୍ୟସ୍ଥମାନଙ୍କ ପ୍ୟାନେଲରେ ଉପଲବ୍ଧ ମଧ୍ୟସ୍ଥମାନଙ୍କର ଏକ ତାଲିକା ପ୍ରଦାନ କରିବା ପାଇଁ ସଂସ୍ଥାଙ୍କୁ ଅନୁରୋଧ କରାଯାଇପାରେ। ଏହି ଉଦ୍ଦେଶ୍ୟରେ admin@presolv360.com କୁ “Request for List of Available Mediators | (Case ID)” ବିଷୟ ସହ ଇମେଲ୍ ପଠାଯିବ। ପକ୍ଷଗୁଡ଼ିକ ଉକ୍ତ ତାଲିକାରୁ ପରସ୍ପର ସମ୍ମତିରେ ଗୋଟିଏ ମଧ୍ୟସ୍ଥଙ୍କୁ ନିଯୁକ୍ତ କରିବେ। ଯଦି ଏହା ସମ୍ଭବ ନ ହୁଏ, ତେବେ ଉପରୋକ୍ତ ମଧ୍ୟସ୍ଥଙ୍କୁ ନିଶ୍ଚିତ କରାଯିବ।
    </p>

    <p>8. କେସ୍ ପରିଚାଳନା ପ୍ରଣାଳୀକୁ ପ୍ରବେଶ ଲାଭ କରିବା ପାଇଁ, ପ୍ରତିବାଦୀ (Respondent/Respondents) ନିମ୍ନଲିଖିତ ପ୍ରକ୍ରିୟାଟି ସମ୍ପୂର୍ଣ୍ଣ କରିବେ:
    </p>
    <p>a. “ଏଠାରେ କ୍ଲିକ୍ କରନ୍ତୁ” ବିକଳ୍ପ ମାଧ୍ୟମରେ ଆପଣଙ୍କ ପଞ୍ଜିକୃତ ଇମେଲ୍ ଆଇଡି ବ୍ୟବହାର କରି ଏକ ଖାତା ସୃଷ୍ଟି କରନ୍ତୁ।</p>
    <p>b. ପ୍ରମାଣିକରଣ ଉଦ୍ଦେଶ୍ୟରେ ଗୋଟିଏ ବିଶେଷ ଜୋଇନ୍ କୋଡ୍ ଆବଶ୍ୟକ ହେବ, ଯାହା ଅଲଗାଭାବେ ପ୍ରଦାନ କରାଯାଇଛି।</p>
    <p>c. କେସ୍ ପରିଚାଳନା ପ୍ରଣାଳୀକୁ ପ୍ରବେଶ ସମ୍ପର୍କିତ କୌଣସି ସହାୟତା ପାଇଁ, admin@presolv360.com କୁ ଇମେଲ୍ ପଠାଇ ବିଷୟ ପଙ୍କ୍ତିରେ ଆପଣଙ୍କ Case ID ଉଲ୍ଲେଖ କରନ୍ତୁ।</p>
    
    <p>9. ମଧ୍ୟସ୍ଥି / ସମନ୍ୱୟକାରୀଙ୍କ ପ୍ୟାନେଲରୁ ଜଣେ ମଧ୍ୟସ୍ଥି / ସଂଯୋଜକ ନିଯୁକ୍ତ ହେବେ ଏବଂ ଏହିପରି ନିଯୁକ୍ତି ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟର ଦକ୍ଷତା, ଜ୍ଞାନ ଏବଂ ପକ୍ଷ ମଧ୍ୟରେ ବିବାଦର ବିଷୟବସ୍ତୁକୁ ମୁକାବିଲା କରିବାର କ୍ଷମତା ଉପରେ ଆଧାରିତ ହେବ |</p>

    <p>10. ମଧ୍ୟସ୍ଥତା / ସଂଯୋଜକ ଦ୍ୱାରା ନିଯୁକ୍ତି ଗ୍ରହଣ କରାଯିବା ପରେ, ପକ୍ଷମାନଙ୍କୁ ନିଯୁକ୍ତି ବିଷୟରେ ଅବଗତ କରାଯିବ |</p>

    <p>11. ଯଦି କୌଣସି ଦଳ ଶ୍ରବଣ ଦୁର୍ବଳତା କ୍ଷେତ୍ରରେ ଭାରତୀୟ ସଙ୍କେତ ଭାଷା (ISL) ଅନୁବାଦକଙ୍କ ସହାୟତା ଆବଶ୍ୟକ କରନ୍ତି, ତେବେ
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
        କୁ ଏକ ଇମେଲ୍ ଲେଖନ୍ତୁ ଏହି ବିଷୟ ସହିତ  "ଅନୁବାଦକ ପାଇଁ ଅନୁରୋଧ | (Case ID)", ଏବଂ ଏହି ସୁବିଧା Presolv360 ଦ୍ୱାରା ପ୍ରଦାନ କରାଯିବ |
    </p>

    <p>ମନେ ରଖନ୍ତୁ: ଏହା ଏକ ସିଷ୍ଟମ୍ ଉତ୍ପାଦିତ ବିଜ୍ଞପ୍ତି ଏବଂ ତେଣୁ ଏହା ଦସ୍ତଖତ ଆବଶ୍ୟକ କରେ ନାହିଁ |</p>
    

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>To:
                </p>
                <p>ବିରୋଧୀ / ପ୍ରତିକ୍ରିୟାଶୀଳ ପାର୍ଟୀ |
                </p>
            </td>
            <td>
                <p>Copy to:
                </p>
                <p>ଆବେଦନକାରୀ (ଗୁଡିକ) / ପ୍ରାରମ୍ଭ ପାର୍ଟି
                </p>
            </td>
        </tr>
    </table>

    <!----------------- ODIYA ------------------------------------------------------->
   
    @endif

    @if($itm_lang == "hindi")

    <!-------------- HINDI ------------------------------------------------------------------------------------->
    @php
        $langfamilyfont = 'freeserif, Open Sans, Arial, Arial, sans-serif, Verdana';
    @endphp
    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <h2 class="text-center" style="font-family:{{ $langfamilyfont }};">मध्यस्थता/समझौता (Mediation/Conciliation)   करने का निमंत्रण</h2>

    <h4 class="text-center">
        <!-- Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) and  -->
        Empaneled as a Mediation Institution by various Courts in India</h4>

    <p class="text-center"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <br>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>Case ID: M{{ sprintf('%06d', $case->id) }} | Ref ID: {{$case->ref_id}}</p>
            </td>
            <td class="text-right">
                <p style="font-family:{{ $langfamilyfont }};">तारीख : {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <br>

    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <th width="50%">
                Applicant(s) / Initiating Party:
            </th>
            <th>
                Opposite / Responding Party:
            </th>
        </tr>
        <tr>
            <td>
                @foreach ($party as $key => $p)
                    <?php
                    $inparty = User::find($p->userId); ?>
                    @if ($p->isClaimant == 0)
                        <p>{{ isset($inparty->organization) ? $inparty->organization . ' through its authorized representative ' . $p->name : $p->name }}
                        </p>
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

                        <!-- @if ($p->userEmail != null)
                        <p>{{$p->userEmail}}</p>
                        @endif

                        @if ($p->userPhone != null)
                        <p>{{$p->userPhone}}</p>
                        @endif -->
                        <br> <br>

                        @if ($p->userpname != null)
                        <p>{{$p->userpname}}</p>
                        @endif

                        @if ($p->userpemail != null)
                        <p>{{$p->userpemail}}</p>
                        @endif

                        @if ($p->userpcontact != null)
                        <p>{{$p->userpcontact}}</p>
                        @endif
                    @endif
                @endforeach
                {{-- <p>{{$party[0]->userEmail}}</p>
                <p>{{$party[0]->userPhone}}</p> --}}
            </td>
            <td>

                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name != '')
                                @if ($p->name != '')
                                    <p>{{ $p->name }}</p>
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
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                <!-- @if ($case->otherRespondentDetails != '' && $case->otherRespondentDetails != null)
                    <p>{{ $case->otherRespondentDetails }}</p>
                @endif -->
                <br>
                @foreach ($party as $key => $p)
                    @if ($key != 0)
                        @if ($p->isClaimant != 0)
                            @if ($p->name == '')
                                @if ($p->userEmail != '')
                                    <p>{{ $p->userEmail }}</p>
                                @endif
                                @if ($p->userPhone != '')
                                    <p>{{ $p->userPhone }}</p>
                                @endif
                                <br>
                            @endif
                        @endif
                    @endif
                @endforeach
                @if($case->otherRespondentDetails != "" && $case->otherRespondentDetails != null)
                <p>{{$case->otherRespondentDetails}}</p>
                @endif
            </td>
        </tr>

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
    
    <p style="font-family:{{ $langfamilyfont }};">1. सौहार्दपूर्ण समाधान पर पहुंचने की इच्छा रखते हुए, आवेदक/आरंभकर्ता पक्ष ने विवाद का सौहार्दपूर्ण समाधान मांगा है और Presolv360 के साथ अनुरोध पंजीकृत किया है।</p>
  
    <p>2. As per the Applicant(s) / Initiating Party:</p>
    
    <!-- <p style='margin-left:15px;'> -->
        <?php //echo nl2br($case->issue); ?>
    <!-- </p> -->

    <p style='font-family:{{ $langfamilyfont }}; margin-left:15px;'>
    </p>

    <p style="font-family:{{ $langfamilyfont }};">3. Presolv360 
        <!-- ऑनलाइन Dispute समाधान  (“ODR”) सहित वैकल्पिक Dispute समाधान (“ADR”) सेवाएं प्रदान करने वाली संस्थाओं की सूची में शामिल है और इसे  -->
        भारत में विभिन्न न्यायालयों द्वारा मध्यस्थता (Mediation) संस्थान के रूप में भी सूचीबद्ध किया गया है। Presolv360 अपने प्लेटफ़ॉर्म पर मध्यस्थता (Mediation) कार्यवाही का प्रबंधन करता है, और मध्यस्थों (Mediators) के अपने पैनल पर आवश्यक योग्यता, ज्ञान और विशेषज्ञता वाले स्वतंत्र, योग्य मध्यस्थों (Mediators)को सूचीबद्ध करता है। मध्यस्थता / सुलह (Mediation/Conciliation) Presolv360 के Dispute समाधान नियमों के अनुसार संचालित   की जाएगी, जिसकी एक प्रति यहाँ पाई जा सकती है। Presolv360 मध्यस्थता कार्यवाही के संचालन के लिए सभी संबंधित पक्षों और मध्यस्थ (Mediators)  को प्रशासनिक सहायता प्रदान करता है और विवाद के परिणाम में इसकी कोई रुचि नहीं है और इसमें कोई हितों का टकराव नहीं है।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">4. जबकि यह प्रक्रिया पूरी तरह से गोपनीय है, मध्यस्थता/सुलह (Mediation/Conciliation)  की कार्यवाही गैर-भागीदारी की स्थिति में या विवाद के अनसुलझे रहने की स्थिति में उपलब्ध किसी भी कानूनी उपाय के प्रति ‘बिना किसी पूर्वाग्रह के’ है। यह सबसे अधिक लाभकारी प्रक्रियाओं में से एक बन गई है, जिसमें सभी संदर्भों की सफलता दर 90% से अधिक है।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">5. विपक्षी/प्रतिसाद देने वाला पक्ष मध्यस्थता/समाधान के लिए आमंत्रण प्राप्त होने के सात (7) 
        कार्य दिवसों के भीतर Presolv360 को <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> पर ईमेल के माध्यम से उक्त आमंत्रण को स्वीकार या अस्वीकार करेगा, 
        अन्यथा मध्यस्थता/समाधान (Mediation/Conciliation) को नॉन-स्टार्टर माना जाएगा।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">6. दावेदार और प्रतिवादी एक अधिकृत प्रतिनिधि द्वारा प्रतिनिधित्व या सहायता के लिए चुन सकते हैं, 
        इस मामले में नियुक्ति करने वाली पार्टी प्राधिकरण का एक पत्र प्रस्तुत करेगी, जिसका प्रारूप यहां उपलब्ध है। नियुक्त करने वाली पार्टी Presolv360 को संबोधित एक 
        ईमेल के माध्यम से <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> पर “Letter of Authority | (Case ID) | (Name of the Appointing Party)”|</p>
    
    <p style="font-family:{{ $langfamilyfont }};">7. संस्था से अपने मध्यस्थों के पैनल में उपलब्ध मध्यस्थों की सूची प्रदान करने का अनुरोध भी किया जा सकता है। इसके लिए admin@presolv360.com पर “Request for List of Available Mediators | (Case ID)” विषय (subject) के साथ ईमेल भेजना होगा। पक्षकार उक्त सूची में से आपसी सहमति से एक मध्यस्थ (Mediator) की नियुक्ति करेंगे। यदि ऐसा करने में विफल रहते हैं, तो उपर्युक्त मध्यस्थ (Mediator) की पुष्टि कर दी जाएगी।</p>

    <p style="font-family:{{ $langfamilyfont }};">8. केस प्रबंधन प्रणाली तक पहुँच प्राप्त करने के लिए, प्रतिवादी (Respondent/Respondents) को निम्नलिखित प्रक्रिया पूर्ण करनी होगी: </p>
    <p style="font-family:{{ $langfamilyfont }};">a. “यहाँ क्लिक करें” के माध्यम से अपने पंजीकृत ईमेल आईडी का उपयोग करके अपना खाता बनाएँ। </p>
    <p style="font-family:{{ $langfamilyfont }};">b. प्रमाणीकरण के उद्देश्य से एक विशिष्ट जॉइन कोड आवश्यक होगा। यह कोड अलग से प्रदान किया गया है। </p>
    <p style="font-family:{{ $langfamilyfont }};">c. केस प्रबंधन प्रणाली तक पहुँच प्राप्त करने में किसी भी प्रकार की सहायता के लिए, कृपया admin@presolv360.com पर ईमेल भेजें और विषय पंक्ति में अपना Case ID अवश्य उल्लेख करें।</p>



    <p style="font-family:{{ $langfamilyfont }};">9. मध्यस्थों/समाधानकर्ताओं  (mediator / conciliator) के पैनल से एक मध्यस्थ/समाधानकर्ता की नियुक्ति की जाएगी, और ऐसी नियुक्ति मध्यस्थ/समाधानकर्ता (mediator / conciliator)  की योग्यता, ज्ञान और पक्षों के बीच विवाद के विषय-वस्तु से निपटने की क्षमता पर आधारित होगी।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">10. मध्यस्थ/समाधानकर्ता द्वारा नियुक्ति स्वीकार किए जाने पर, पक्षों को नियुक्ति की सूचना दी जाएगी।</p>

    <p style="font-family:{{ $langfamilyfont }};">11. यदि किसी पक्ष को श्रवण दोष के मामले में भारतीय सांकेतिक भाषा (आईएसएल) दुभाषिया की सहायता की 
        आवश्यकता है, तो <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> पर "Request for Interpreter | (Case ID)"   विषय के साथ एक ईमेल लिखें, और यह सुविधा Presolv360 
        द्वारा प्रदान की जाएगी।</p>
    
    <p style="font-family:{{ $langfamilyfont }};"><b>नोट: यह सिस्टम द्वारा जनरेटेड नोटिस है, इसलिए इस पर हस्ताक्षर की आवश्यकता नहीं है।</b></p>
    
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
    
    <!-------------- HINDI ------------------------------------------------------------------------------------->

    @endif


    @endforeach
    

    </body>

</html>