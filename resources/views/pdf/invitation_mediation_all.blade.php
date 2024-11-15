<?php
 
use App\Models\User;

$meddate = new DateTime($case->crated_at);

$meddate = $meddate->format('d-m-Y H:i:s');

$lastdate = new DateTime();

$lastdate = $lastdate->modify('+7 days');

$ldate = $lastdate->format('d-m-Y');

$itm_lang_arr = explode(",", $case->itm_lang);


//echo "<pre>";print_R($itm_lang_arr);exit;

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

    <h4 class="text-center" style="margin-bottom: 0px !important; margin-top: 0px !important;font-size:14px !important;">Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) and empaneled as a Mediation Institution by various Courts in India</h4>

    <p class="text-center" style="page-break-after:avoid;margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    

    <table cellspacing="0" cellpadding="10" width="100%" style="">
        <tr>
            <td width="60%">
                <p>Case ID: M{{ sprintf('%06d', $case->id) }}</p>
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
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    {{-- <p>3. The mediation / conciliation shall be governed by and conducted in accordance with Presolv360’s Dispute Resolution Rules, a copy of which can be found 
        <a href='https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing'>here</a></p> --}}
    <p>3. Presolv360 is included in the list of institutions offering Alternative Dispute Resolution ("ADR") services
        including through Online Dispute Resolution ("ODR") and is also empaneled as a
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

    <p>7. A mediator / conciliator from the panel of mediators / conciliators will be appointed, and such appointment
        shall be based on the mediator’s / conciliator’s competence, knowledge and ability to deal with subject matter
        of the dispute between the parties.</p>

    <p>8. Upon acceptance of the appointment by the mediator / conciliator, the parties shall be notified of the
        appointment.</p>

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
        @if($itm_lang == "tamil")
   



    <!----------------- TAMIL ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">மத்தியஸ்தம்/சமரசம் </p>
    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">செய்துகொள்வதற்கானஅழைப்பு</p>

    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">ஆன்லைன் தகராறு தீர்வு (ODR) மூலம் உள்ளிட்ட மாற்று தகராறு தீர்வு (ADR) சேவைகளை நோக்குநிலைப்படுத்தும் நிறுவனங்களின் பட்டியலில் <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(இங்கே விரிவாக காணலாம்)</a> 
            சேர்க்கப்பட்டுள்ளது மற்றும் இந்தியாவில் உள்ள பல்வேறு நீதிமன்றங்களால் ஒரு மத்தியஸ்த நிறுவனமாக இணைக்கப்பட்டுள்ளது</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>Case ID: M{{ sprintf('%06d', $case->id) }}</p>
                <p>வழக்கு ஐடி: எம்{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>Date: {{ date('d-m-Y') }}</p>
                <p>தேதி: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p><b>Applicant(s) / Initiating Party:</b></p>
                <p>விண்ணப்பதாரர்(கள்) /துவங்கும் தரப்பு:</p>
            </td>
            <td>
                <p><b>Opposite / Responding Party:</b></p>
                <p>எதிர்தரப்பு / பதிலளிக்கும் தரப்பு:</p>
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

    
    
    <p>1. ஒரு இணக்கமான தீர்வை அடையும் நோக்கத்துடன், விண்ணப்பதாரர்/துவங்கும் தரப்பானது சர்ச்சைக்கு இணக்கமான தீர்வைக் கோரியுள்ளது மற்றும் கோரிக்கையை Presolv360 இல் பதிவு செய்துள்ளது.</p>
    
    <p>2. விண்ணப்பதாரர்(கள்)/ துவங்கும் தரப்பின் படி:</p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. Presolv360 ஆனது ஆன்லைன் டிஸ்புட் தீர்வு ("ODR") உட்பட மாற்று டிஸ்புட்  தீர்வு ("ADR") சேவைகளை 
        வழங்குபவராக எம்பனேல் செய்யப்பட்டு, இந்தியாவில் உள்ள பல்வேறு நீதிமன்றங்களால் மத்தியஸ்த  நிறுவனமாகவும் 
        பட்டியலிடப்பட்டுள்ளது. Presolv360 அதன் தளத்தில் மத்தியஸ்த நடவடிக்கைகளை நிர்வகிக்கிறது, மேலும் அதன் 
        மத்தியஸ்தர்கள் (Mediators) குழுவில் தேவையான தகுதிகள், திறன் மற்றும் நிபுணத்துவத்துடன் சுயாதீனமான, 
        தகுதியான மத்தியஸ்தர்களை (Mediators) பட்டியலிடுகிறது. மத்தியஸ்தம்/சமரசம் (Mediation/Conciliation) 
        Presolv360 இன் தகராறு தீர்வு விதிகளின்படி நடத்தப்படும், அதன் நகலை <a
        href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">இங்கே</a>காணலாம். Presolv360 சமரச நடவடிக்கைகளை 
        நடத்துவதற்கு சம்பந்தப்பட்ட அனைத்து தரப்பினருக்கும், மத்தியஸ்த நடவடிக்கைகளை நடத்துவதற்காக மத்தியஸ்தர்களுக்கும் 
        நிர்வாக ஆதரவை வழங்குகிறது, மேலும் வாதத்தின் முடிவில் இதற்கு எந்தவித விருப்பமும், முரண்பாடுகளும் இல்லை. </p>

    
    <p>4. செயல்முறை முற்றுலும்  ரகசியமானதாக இருக்கும் போது, 	மத்தியஸ்தம்/சமரச (Mediation/Conciliation)  நடவடிக்கைகள், பங்கேற்காத 
        பட்சத்தில் அல்லது தகராறு தீர்க்கப்படாமல் இருக்கும் பட்சத்தில் கிடைக்கும் எந்தவொரு சட்டப்பூர்வ தீர்வுகளுக்கும் 'பாரபட்சம் 
        இல்லாமல்' இருக்கும். இது மிகவும் நன்மைபயக்கும் செயல்முறைகளில் ஒன்றாக மாறியுள்ளது, இதில் அனைத்து குறிப்புகளின் 
        வெற்றி விகிதம் 90% க்கும் அதிகமாக உள்ளது.</p>

    <p>5. எதிர்தரப்பினர் / பதிலளிக்கும் தரப்பினர், மத்தியஸ்தம் / சமரசம் செய்வதற்கான அழைப்பைப் பெற்றதிலிருந்து ஏழு (7) வேலை நாட்களுக்குள், 
    <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> இல் Presolv360 என்ற மின்னஞ்சல் முகவரிக்கு அனுப்பப்பட்ட மின்னஞ்சலின் மூலம் அந்த அழைப்பை 
        ஏற்கவும் அல்லது நிராகரிக்கவும் வேண்டும்.  
        அவ்வாறு செய்யத்தவரும் பட்சத்தில், மத்தியஸ்தம் / சமரசம் ஒரு தொடக்கமற்றதாகக் கருதப்படும்.
    </p>

    <p>6. தரப்பினர் அங்கீகரிக்கப்பட்ட பிரதிநிதியால் பிரதிநிதித்துவம் செய்ய அல்லது உதவி செய்ய தேர்வு செய்யலாம். இந்த விசயத்தில் 
        நியமனம் செய்யும் தரப்பினர் அங்கீகாரக் கடிதத்தை சமர்ப்பிக்க வேண்டும், அதன் வடிவம் <a
        href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>இங்கே</a>
        கொடுக்கப்பட்டுள்ளது. நியமிக்கும் தரப்பினர் Presolv360  க்கு <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> என்ற முகவரிக்கு மின்னஞ்சல் வழியாக “அதிகாரக் கடிதம் 
        “Letter of Authority | (வழக்கு ஐடி) (Case ID) | (நியமிக்கும் கட்சியின் பெயர்)”. (Name of the Appointing Party)”|போன்றவற்றை 
        அனுப்பலாம்.
    </p>

    
    <p>7. நடுவர்கள்/சமரசம் செய்பவர்கள் குழுவில் இருந்து சமரசம் செய்பவர் நியமிக்கப்படுவார், மேலும் அத்தகைய நியமனம் 
        மத்தியஸ்தரின்/சமரசம் செய்பவரின் திறமை, அறிவு மற்றும் இருதரப்பு பிரச்சினைகளுக்கு இடையேயான விவகாரம் 
        ஆகியவற்றின் அடிப்படையில் இருக்கும்.</p>

    <p>8. நடுவர்/ சமரசம் செய்பவர் நியமனத்தை ஏற்றுக்கொண்டால், தரப்பினருக்கு நியமனம் குறித்து அறிவிக்கப்படும்.</p>

    <p>9. ஏதேனும் ஒரு தரப்பினருக்கு செவித்திறன் குறைபாடு  இருக்கும் பட்சத்தில் அவருக்கு இந்திய சைகை மொழி (ISL) 
        மொழிபெயர்ப்பாளரின் உதவி தேவைப்பட்டால், <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> க்கு "மொழிபெயர்ப்பாளருக்கான கோரிக்கை | (வழக்கு ஐடி)" 
        என்ற தலைப்பின் கீழ் மின்னஞ்சல் எழுதவும், இந்த வசதி Presolv360 மூலம் வழங்கப்படும்.</p>

    <p>குறிப்பு: இது கணினி ஜனரேட் செய்யப்பட்ட நோட்டீஸ், எனவே
 இதற்கு கையொப்பம் தேவையில்லை.
</p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>பெறுநர்:</p>
                <p>எதிர் தரப்பு / பதிலளிக்கும் தரப்பு</p>
            </td>
            <td>
                <p>நகல்:</p>
                <p>விண்ணப்பதாரர்(கள்) /துவங்கும் தரப்பு</p>
            </td>
        </tr>
    </table>

    <!----------------- TAMIL ------------------------------------------------------->
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

    <h4 class="text-center">Included in the list of institutions <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available
            here)</a> offering Alternative Dispute Resolution (ADR) services including through Online Dispute Resolution
        (ODR) and empaneled as a Mediation Institution by various Courts in India</h4>

    <p class="text-center"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <br>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>Case ID: M{{ sprintf('%06d', $case->id) }}</p>
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
    
    <p style='margin-left:15px;'>
        <?php echo nl2br($case->issue); ?>
    </p>

    <p style="font-family:{{ $langfamilyfont }};">3. Presolv360 ऑनलाइन Dispute समाधान  (“ODR”) सहित वैकल्पिक Dispute समाधान (“ADR”) सेवाएं प्रदान करने वाली संस्थाओं की सूची में शामिल है और इसे भारत में विभिन्न न्यायालयों द्वारा मध्यस्थता (Mediation) संस्थान के रूप में भी सूचीबद्ध किया गया है। Presolv360 अपने प्लेटफ़ॉर्म पर मध्यस्थता (Mediation) कार्यवाही का प्रबंधन करता है, और मध्यस्थों (Mediators) के अपने पैनल पर आवश्यक योग्यता, ज्ञान और विशेषज्ञता वाले स्वतंत्र, योग्य मध्यस्थों (Mediators)को सूचीबद्ध करता है। मध्यस्थता / सुलह (Mediation/Conciliation) Presolv360 के Dispute समाधान नियमों के अनुसार संचालित   की जाएगी, जिसकी एक प्रति यहाँ पाई जा सकती है। Presolv360 मध्यस्थता कार्यवाही के संचालन के लिए सभी संबंधित पक्षों और मध्यस्थ (Mediators)  को प्रशासनिक सहायता प्रदान करता है और विवाद के परिणाम में इसकी कोई रुचि नहीं है और इसमें कोई हितों का टकराव नहीं है।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">4. जबकि यह प्रक्रिया पूरी तरह से गोपनीय है, मध्यस्थता/सुलह (Mediation/Conciliation)  की कार्यवाही गैर-भागीदारी की स्थिति में या विवाद के अनसुलझे रहने की स्थिति में उपलब्ध किसी भी कानूनी उपाय के प्रति ‘बिना किसी पूर्वाग्रह के’ है। यह सबसे अधिक लाभकारी प्रक्रियाओं में से एक बन गई है, जिसमें सभी संदर्भों की सफलता दर 90% से अधिक है।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">5. विपक्षी/प्रतिसाद देने वाला पक्ष मध्यस्थता/समाधान के लिए आमंत्रण प्राप्त होने के सात (7) 
        कार्य दिवसों के भीतर Presolv360 को <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> पर ईमेल के माध्यम से उक्त आमंत्रण को स्वीकार या अस्वीकार करेगा, 
        अन्यथा मध्यस्थता/समाधान (Mediation/Conciliation) को नॉन-स्टार्टर माना जाएगा।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">6. दावेदार और प्रतिवादी एक अधिकृत प्रतिनिधि द्वारा प्रतिनिधित्व या सहायता के लिए चुन सकते हैं, 
        इस मामले में नियुक्ति करने वाली पार्टी प्राधिकरण का एक पत्र प्रस्तुत करेगी, जिसका प्रारूप यहां उपलब्ध है। नियुक्त करने वाली पार्टी Presolv360 को संबोधित एक 
        ईमेल के माध्यम से <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> पर “Letter of Authority | (Case ID) | (Name of the Appointing Party)”|</p>
    
    <p style="font-family:{{ $langfamilyfont }};">7. मध्यस्थों/समाधानकर्ताओं  (mediator / conciliator) के पैनल से एक मध्यस्थ/समाधानकर्ता की नियुक्ति की जाएगी, और ऐसी नियुक्ति मध्यस्थ/समाधानकर्ता (mediator / conciliator)  की योग्यता, ज्ञान और पक्षों के बीच विवाद के विषय-वस्तु से निपटने की क्षमता पर आधारित होगी।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">8. मध्यस्थ/समाधानकर्ता द्वारा नियुक्ति स्वीकार किए जाने पर, पक्षों को नियुक्ति की सूचना दी जाएगी।</p>

    <p style="font-family:{{ $langfamilyfont }};">9. यदि किसी पक्ष को श्रवण दोष के मामले में भारतीय सांकेतिक भाषा (आईएसएल) दुभाषिया की सहायता की 
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
        @if($itm_lang == "gujarati")
    
            <!----------------- GUJARATI ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">મધ્યસ્થી / સમાધાન માટે આમંત્રણ </p>
    
    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">ઓનલાઈન ડિસ્પ્યુટ રિઝોલ્યુશન (ODR) સહિતની વૈકલ્પિક તકરાર નિરાકરણ (ADR) સેવાઓ પ્રદાન કરતી સંસ્થાઓની સૂચિમાં <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(અહીં ઉપલબ્ધ છે)</a> 
            સામેલ છે અને ભારતની વિવિધ અદાલતો દ્વારા મધ્યસ્થી સંસ્થા તરીકે સૂચિબદ્ધ છે.
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>કેસ ID: M{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>તારીખ: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>અરજદાર(ઓ) /શરૂઆતી પક્ષ:</p>
            </td>
            <td>
                <p>સામાવાળા / પ્રતિવાદી પક્ષ:</p>
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

    
    
    <p>1. સૌહાર્દપૂર્ણ ઠરાવ પર પહોંચવા ઇચ્છુક, અરજદાર(ઓ)/શરૂઆતી પક્ષે વિવાદના સૌહાર્દપૂર્ણ નિરાકરણની માંગ કરી છે અને પ્રિસોલ્વ360 માં વિનંતી નોંધાવી છે.
    </p>
    
    <p>2. અરજદાર(ઓ)/પ્રારંભ કરનાર પક્ષ મુજબ:</p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. PRESOLV360 સુચિમાં શામેલ છે ઓનલાઈન ડિસ્પ્યુટ રિઝોલ્યુશન ("ODR") સહિત વૈકલ્પિક વિવાદ નિરાકરણ ("ADR") સેવાઓ પ્રદાન કરતી સંસ્થાઓની સૂચિમાં 
        અને ભારતમાં વિવિધ અદાલતો દ્વારા મધ્યસ્થી સંસ્થા તરીકે પણ સૂચિબદ્ધ છે. Presolv360 તેના પ્લેટફોર્મ પર મધ્યસ્થી પ્રક્રિયાઓનું સંચાલન કરે છે, અને તેના 
        મધ્યસ્થીઓની પેનલ પર જરૂરી યોગ્યતા, જ્ઞાન અને કુશળતા સાથે સ્વતંત્ર, લાયક મધ્યસ્થીઓની પસંદગી કરે છે. મધ્યસ્થી / સમાધાન Presolv360 ના વિવાદ 
        નિરાકરણ નિયમો દ્વારા સંચાલિત અને હાથ ધરવામાં આવશે, જેની એક નકલ 
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">અહીં</a> મળી શકે છે. 
        Presolv360 તમામ સંબંધિત પક્ષકારો અને મધ્યસ્થી કાર્યવાહી 
        હાથ ધરવા માટે વહિવટી આધાર પૂરો પાડે છે અને તેને વિવાદના પરિણામમાં કોઈ રસ નથી અને હિતોનો કોઈ સંઘર્ષ નથી.
    </p>

    
    <p>4. જ્યારે પ્રક્રિયા સંપૂર્ણપણે ગોપનીય હોય છે, મધ્યસ્થી / સમાધાનની કાર્યવાહી બિન-ભાગીદારીના કિસ્સામાં અથવા જો વિવાદ વણઉકેલાયેલ રહે તો ઉપલબ્ધ કોઈપણ કાનૂની ઉપાયો માટે 'પૂર્વાગ્રહ વિના' છે. તમામ સંદર્ભોમાંથી 90% થી વધુના સફળતા દર સાથે, આ સૌથી લાભદાયી પ્રક્રિયાઓમાંની એક બની ગઈ છે.

</p>

    <p>5. સામાવાળા/પ્રતિવાદી પક્ષ, મધ્યસ્થી/સમાધાન માટેના આમંત્રણની પ્રાપ્તિના સાત (7) કાર્યકારી દિવસોની અંદર, પ્રિસોલ્વ360 ને <a href="mailto:admin@presolv360.com">admin@presolv360.com</a>  પર સંબોધિત ઇમેઇલ દ્વારા આ આમંત્રણ સ્વીકારશે અથવા નકારશે, જે નિષ્ફળ જશે તો, મધ્યસ્થી / સમાધાનને બિન-સ્ટાર્ટર માનવામાં આવશે.

    </p>

    <p>6. પક્ષો અધિકૃત પ્રતિનિધિ દ્વારા પ્રતિનિધિત્વ અથવા સહાયતા કરવાનું પસંદ કરી શકે છે, આ કિસ્સામાં નિમણૂક કરનાર પક્ષ સત્તાધિકારનો પત્ર રજુ કરશે, જેનું ફોર્મેટ અહીં ઉપલબ્ધ છે. નિમણૂક કરનાર પક્ષે પ્રિસોલ્વ360 ને<a
        href="mailto:admin@presolv360.com">admin@presolv360.com</a>
        પર “લેટર ઓફ ઓથોરિટી | (કેસ ID) | (નિયુક્ત કરાયેલા પક્ષનું નામ)”.
    </p>

    
    <p>7. મધ્યસ્થી / સમાધાનકારોની પેનલમાંથી મધ્યસ્થી / સમાધાનકર્તાની નિમણૂક કરવામાં આવશે, અને આવી નિમણૂક મધ્યસ્થી / સમાધાનકર્તાની યોગ્યતા, જ્ઞાન અને પક્ષકારો વચ્ચેના વિવાદની વિષયવસ્તુ સાથે વ્યવહાર કરવાની ક્ષમતા પર આધારિત હશે.
    </p>

    <p>8. મધ્યસ્થી/સમાધાનકર્તા દ્વારા નિમણૂકની સ્વીકૃતિ પર, પક્ષકારોને નિમણૂક વિશે સૂચિત કરવામાં આવશે.</p>

    <p>9. જો કોઈ પક્ષકારને સાંભળવાની ક્ષતિના કિસ્સામાં ભારતીય સાઇન લેંગ્વેજ (ISL) દુભાષિયાની સહાયની જરૂર હોય, તો <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> પર "દુભાષિયા માટેની વિનંતી | (કેસ ID)" ના વિષય થી એક ઇ-મેઇલ લખે અને આ સુવિધા પ્રોસિલ્વ360 દ્વારા પ્રદાન કરવામાં આવશે.</p>

    <p>નોંધ: આ એક સિસ્ટમ જનરેટેડ નોટિસ છે અને તેથી તેને સહીની જરૂર નથી.

</p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>પ્રતિ: </p>
                <p> સામાવાળા / પ્રતિવાદી પક્ષ</p>
            </td>
            <td>
                <p>નકલ મોકલી:</p>
                <p>અરજદાર(ઓ) / શરૂઆતી પક્ષ</p>
            </td>
        </tr>
    </table>

    <!----------------- GUJARATI ------------------------------------------------------->


    @endif
   
        @if($itm_lang == "kannada")
   


        <!----------------- KANNAD ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">ಮಧ್ಯಸ್ಥಿಕೆ/ಸಂಧಾನಕ್ಕೆ ಆಹ್ವಾನ </p>

    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">
    ಆನ್ಲೈನ್ ವಿವಾದ ಪರಿಹಾರ (ಒ. ಡಿ. ಆರ್.) ಸೇರಿದಂತೆ ಪರ್ಯಾಯ ವಿವಾದ ಪರಿಹಾರ (ಎ. ಡಿ. ಆರ್.) ಸೇವೆಗಳನ್ನು ಒದಗಿಸುವ ಮತ್ತು ಭಾರತದ ವಿವಿಧ ನ್ಯಾಯಾಲಯಗಳಿಂದ 
    ಮಧ್ಯಸ್ಥಿಕೆ ಸಂಸ್ಥೆಯಾಗಿ ಗುರುತು  ಮಾಡಲಾದ ಸಂಸ್ಥೆಗಳ ಪಟ್ಟಿಯಲ್ಲಿ  <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(ವಿವರಗಳು ಇಲ್ಲಿ ಲಭ್ಯ)</a> 
            ಪ್ರೆಸೊಲ್ವ್360 ಅನ್ನು ಸೇರಿಸಲಾಗಿದೆ.
    </p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>ಕೇಸ್ ಐಡಿ: M{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>ದಿನಾಂಕ: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>ಅರ್ಜಿದಾರ (ರು)/ಆರಂಭಿಸುವ ಪಕ್ಷ:</p>
            </td>
            <td>
                <p>ಎದುರಾಳಿ/ಪ್ರತಿಕ್ರಿಯಿಸುವ ಪಕ್ಷ:</p>
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

    
    
    <p>1. ಸೌಹಾರ್ದಯುತ ಪರಿಹಾರಕ್ಕೆ ಬರುವ ಬಯಕೆಯಿಂದ, ಅರ್ಜಿದಾರ (ರು)/ ಆರಂಭಿಸುವ ಪಕ್ಷವು ವಿವಾದದ ಸೌಹಾರ್ದಯುತ ಪರಿಹಾರವನ್ನು 
        ಕೋರಿದೆ ಮತ್ತು ಪ್ರೆಸೊಲ್ವ್ 360 ನಲ್ಲಿ ವಿನಂತಿಯನ್ನು ನೋಂದಾಯಿಸಿದೆ.
    </p>
    
    <p>2. ಅರ್ಜಿದಾರ (ರು)/ ಆರಂಭಿಸುವ ಪಕ್ಷದ ಪ್ರಕಾರ:
    </p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. ಆನ್ಲೈನ್ ವಿವಾದ ಪರಿಹಾರ ("ಒಡಿಆರ್") ಸೇರಿದಂತೆ ಪರ್ಯಾಯ ವಿವಾದ ಪರಿಹಾರ ("ಎಡಿಆರ್") ಸೇವೆಗಳನ್ನು ಒದಗಿಸುವ  ಮತ್ತು ಭಾರತದ ವಿವಿಧ 
        ನ್ಯಾಯಾಲಯಗಳಿಂದ ಮಧ್ಯಸ್ಥಿಕೆ ಸಂಸ್ಥೆಯಾಗಿ ಗುರುತು ಮಾಡಲಾದ ಸಂಸ್ಥೆಗಳ ಪಟ್ಟಿಯಲ್ಲಿ  ಪ್ರೆಸೊಲ್ವ್360 ಅನ್ನು ಸೇರಿಸಲಾಗಿದೆ. ಪ್ರೆಸೊಲ್ವ್360 ತನ್ನ 
        ವೇದಿಕೆಯಲ್ಲಿ ಮಧ್ಯಸ್ಥಿಕೆ ಪ್ರಕ್ರಿಯೆಗಳನ್ನು ನಿರ್ವಹಿಸುತ್ತದೆ ಮತ್ತು ಅದರ ಮಧ್ಯವರ್ತಿಗಳ ಸಮಿತಿಗೆ ಅಗತ್ಯವಾದ ಕೌಶಲ್ಯ, ಜ್ಞಾನ ಮತ್ತು ಪರಿಣತಿ ಹೊಂದಿರುವ 
        ಸ್ವತಂತ್ರ, ಅರ್ಹ ಮಧ್ಯವರ್ತಿಗಳನ್ನು ನೇಮಿಸುತ್ತದೆ. ಮಧ್ಯಸ್ಥಿಕೆ ಅಥವಾ ಸಂಧಾನ ಪ್ರಕ್ರಿಯೆಗಳನ್ನು <a
        href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">ಇಲ್ಲಿ</a>ಲಭ್ಯವಿರುವ ಪ್ರೆಸೊಲ್ವ್360ರ 
        ವಿವಾದ ಪರಿಹಾರ ನಿಯಮಗಳ ಪ್ರಕಾರ ನಡೆಸಲಾಗುತ್ತದೆ. ವಿವಾದದ ಫಲಿತಾಂಶದಲ್ಲಿ ಯಾವುದೇ ಹಿತಾಸಕ್ತಿ ಇಲ್ಲ ಮತ್ತು ಹಿತಾಸಕ್ತಿ ಸಂಘರ್ಷವಿಲ್ಲ ಎಂದು 
        ಖಾತ್ರಿಪಡಿಸುವ ಮೂಲಕ ಮಧ್ಯಸ್ಥಿಕೆ ಪ್ರಕ್ರಿಯೆಯ ಸಮಯದಲ್ಲಿ ಒಳಗೊಂಡಿರುವ ಎಲ್ಲಾ ಪಕ್ಷಗಳಿಗೆ ಮತ್ತು ಮಧ್ಯವರ್ತಿಗೆ ಪ್ರೆಸೊಲ್ವ್360 ಆಡಳಿತಾತ್ಮಕ ಬೆಂಬಲವನ್ನು 
        ಒದಗಿಸುತ್ತದೆ. </p>

    
    <p>4. ಈ ಪ್ರಕ್ರಿಯೆಯು ಸಂಪೂರ್ಣವಾಗಿ ಗೌಪ್ಯವಾಗಿದ್ದು, ಪಕ್ಷಗಳು ಭಾಗವಹಿಸದಿದ್ದರೆ ಅಥವಾ ವಿವಾದವು ಬಗೆಹರಿಯದೆ ಉಳಿದಿದ್ದರೆ ಲಭ್ಯವಿರುವ ಯಾವುದೇ 
        ಕಾನೂನು ಪರಿಹಾರಗಳಿಗೆ ‘ಪರಿಣಾಮ ಬಿರದೆ’  ಮಧ್ಯಸ್ಥಿಕೆ ಅಥವಾ ಸಂಧಾನದ ಪ್ರಕ್ರಿಯೆಗಳನ್ನು  ನಡೆಸಲಾಗುತ್ತದೆ. ಈ ವಿಧಾನವು ಅತ್ಯಂತ ಪರಿಣಾಮಕಾರಿ 
        ಎಂದು ಸಾಬೀತಾಗಿದ್ದು, ಉಲ್ಲೇಖಿಸಲಾದ ಎಲ್ಲಾ ಪ್ರಕರಣಗಳಿಗೆ ಶೇ.90ಕ್ಕಿಂತ ಹೆಚ್ಚಿನ ಯಶಸ್ಸಿನ ಪ್ರಮಾಣವನ್ನು ಸಾಧಿಸಿದೆ.
    </p>

    <p>5. ಎದುರಾಳಿ ಅಥವಾ ಪ್ರತಿಕ್ರಿಯಿಸುವ ಪಕ್ಷವು ಮಧ್ಯಸ್ಥಿಕೆ ಅಥವಾ ಸಂಧಾನದ ಆಹ್ವಾನವನ್ನು ಸ್ವೀಕರಿಸಿದ ಏಳು (7) ಕೆಲಸದ ದಿನಗಳೊಳಗೆ 
    <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> ನಲ್ಲಿ ಪ್ರೆಸೊಲ್ವ್ 360 ಗೆ ಇಮೇಲ್ ಕಳುಹಿಸುವ ಮೂಲಕ ಸ್ವೀಕರಿಸಬೇಕು ಅಥವಾ ತಿರಸ್ಕರಿಸಬೇಕು. ಈ ಕಾಲಮಿತಿಯೊಳಗೆ ಯಾವುದೇ 
        ಪ್ರತಿಕ್ರಿಯೆಯನ್ನು ಸ್ವೀಕರಿಸದಿದ್ದರೆ, ಮಧ್ಯಸ್ಥಿಕೆ ಅಥವಾ ಸಂಧಾನವನ್ನು  ನೀವು ಆರಂಭಿಸುವದಿಲ್ಲ ಎಂದು ಪರಿಗಣಿಸಲಾಗುತ್ತದೆ.

    </p>

    <p>6. ಪಕ್ಷಗಳಿಗೆ ಅಧಿಕೃತ ಪ್ರತಿನಿಧಿಯಿಂದ ಪ್ರತಿನಿಧಿಸುವ ಅಥವಾ ಸಹಾಯ ಪಡೆಯುವ  ಆಯ್ಕೆ ಇರುತ್ತದೆ. ಅಂತಹ ಸಂದರ್ಭಗಳಲ್ಲಿ, ನೇಮಕ ಮಾಡುವ ಪಕ್ಷವು 
         <a
        href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>ಇಲ್ಲಿ</a>
        ಲಭ್ಯವಿರುವ ನಮೂನೆಯನ್ನು  ಬಳಸಿಕೊಂಡು ಅಧಿಕಾರ  ಪತ್ರವನ್ನು ಸಲ್ಲಿಸಬೇಕು. ಸಹಿ ಮಾಡಿದ ಅಧಿಕಾರ ಪತ್ರವನ್ನು ಇಮೇಲ್ ಮೂಲಕ "ಅಧಿಕಾರ ಪತ್ರ | 
        (ಕೇಸ್ ಐಡಿ) | (ನೇಮಕ ಮಾಡುವ ಪಕ್ಷದ ಹೆಸರು)" ಎಂಬ ವಿಷಯದ ಸಾಲಿನೊಂದಿಗೆ <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> ನಲ್ಲಿ ಪ್ರೆಸೊಲ್ವ್360 ಗೆ ಕಳುಹಿಸಬೇಕು.
    </p>

    
    <p>7. ಪೂರ್ವ ಅನುಮೋದಿಸಲಾದ ಮಧ್ಯವರ್ತಿಗಳು/ಸಂಧಾನಕಾರರ ಪಟ್ಟಿಯಿಂದ ಮಧ್ಯವರ್ತಿ/ಸಂಧಾನಕಾರರನ್ನು ನೇಮಿಸಲಾಗುತ್ತದೆ ಮತ್ತು ಅಂತಹ ನೇಮಕವು 
        ಮಧ್ಯವರ್ತಿ/ಸಂಧಾನಕಾರರ ಸಾಮರ್ಥ್ಯ, ಜ್ಞಾನ ಮತ್ತು ಪಕ್ಷಗಳ ನಡುವಿನ ವಿವಾದದ ವಿಷಯವನ್ನು ನಿಭಾಯಿಸುವ ಸಾಮರ್ಥ್ಯವನ್ನು ಆಧರಿಸಿರುತ್ತದೆ.
    </p>

    <p>8. ಮಧ್ಯವರ್ತಿ ಅಥವಾ ಸಂಧಾನಕಾರನು ನೇಮಕಾತಿಯನ್ನು ಸ್ವೀಕರಿಸಿದ ನಂತರ, ಈ ನಿರ್ಧಾರದ ಬಗ್ಗೆ ಪಕ್ಷಗಳಿಗೆ ತಿಳಿಸಲಾಗುತ್ತದೆ.
    </p>

    <p>9. ಶ್ರವಣ ದೋಷದಿಂದಾಗಿ ಒಂದು ಪಕ್ಷಕ್ಕೆ ಭಾರತೀಯ ಸಂಕೇತ ಭಾಷೆಯ (ಐಎಸ್ಎಲ್) ಇಂಟರ್ಪ್ರಿಟರ್ನ ಸಹಾಯ ಬೇಕಾದರೆ, ಅವರು 
        "ಇಂಟರ್ಪ್ರಿಟರ್ | (ಕೇಸ್ ಐಡಿ) ಗಾಗಿ ವಿನಂತಿ" ಎಂಬ ವಿಷಯದ ಸಾಲಿನೊಂದಿಗೆ <a href="mailto:admin@presolv360.com">admin@presolv360.com</a>
        ಗೆ  ಇಮೇಲ್ ಮಾಡಬೇಕು. ಈ ಸೇವೆಗೆ ಪ್ರೆಸೊಲ್ವ್360 ಅಗತ್ಯ ವ್ಯವಸ್ಥೆ ಮಾಡುತ್ತದೆ.
    </p>

    <p>ಗಮನಿಸಿಃ ಇದು ವ್ಯವಸ್ಥೆಯಿಂದ ರಚಿತವಾದ ಸೂಚನೆಯಾಗಿದ್ದರಿಂದ ಇದಕ್ಕೆ ಸಹಿಯ ಅಗತ್ಯವಿಲ್ಲ.</p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>ಇವರಿಗೆ :
                </p>
                <p>ಎದುರಾಳಿ/ಪ್ರತಿಕ್ರಿಯಿಸುವ ಪಕ್ಷ
                </p>
            </td>
            <td>
                <p>ಒಂದು ಪ್ರತಿ ಇವರಿಗೆ:</p>
                <p>ಅರ್ಜಿದಾರ (ರು)/ಆರಂಭಿಸುವ ಪಕ್ಷ
                </p>
            </td>
        </tr>
    </table>

    <!----------------- KANNAD ------------------------------------------------------->

    @endif
   
        @if($itm_lang == "malayalam")
   
        <!----------------- MALYALAM ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">മധ്യസ്ഥതയ്ക്ക് / അനുരഞ്ജനത്തിന് ഉള്ള ക്ഷണം</p>

    <p class="text-center" style="margin-bottom: 0px !important; margin-top: 0px !important;">
    ഓൺലൈൻ തർക്ക പരിഹാരം (ഒഡിആർ) ഉൾപ്പെടെ ഇതര തർക്ക പരിഹാര (എഡിആർ) സേവനങ്ങൾ വാഗ്ദാനം ചെയ്യുന്ന 
    സ്ഥാപനങ്ങളുടെ പട്ടികയിൽ
     <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(ഭാഗം ഇവിടെ ലഭ്യമാണ്) </a>
            ഉൾപ്പെടുത്തിയതും ഇന്ത്യയിലെ വിവിധ കോടതികൾ ഒരു മധ്യസ്ഥ സ്ഥാപനമായി നിയോഗിച്ചതും
    </p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>കേസ് ഐഡി: M{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>തീയതി: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>അപേക്ഷിക്കുന്ന / തുടക്ക കക്ഷി:</p>
            </td>
            <td>
                <p>എതിർ / പ്രതികരിക്കുന്ന കക്ഷി:</p>
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

    
    
    <p>1. സൗഹാർദ്ദപരമായ ഒരു പരിഹാരത്തിലെത്താൻ ആഗ്രഹിക്കുന്ന അപേക്ഷിക്കുന്ന / തുടക്ക കക്ഷി, തർക്കത്തിന് 
        സൗഹാർദ്ദപരമായ പരിഹാരം തേടുകയും പ്രെസോൾവ്360-ൽ ഒരു അഭ്യർത്ഥന രജിസ്റ്റർ ചെയ്യുകയും ചെയ്തു.
    </p>
    
    <p>2. അപേക്ഷിക്കുന്ന / തുടക്ക കക്ഷി പ്രകാരം:
    </p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. ഓൺലൈൻ തർക്ക പരിഹാരം ("ഒഡിആർ") ഉൾപ്പെടെ ഇതര തർക്ക പരിഹാര ("എഡിആർ") സേവനങ്ങൾ വാഗ്ദാനം 
        ചെയ്യുന്ന സ്ഥാപനങ്ങളുടെ പട്ടികയിൽ ഉള്ളത്, കൂടാതെ ഇന്ത്യയിലെ വിവിധ കോടതികൾ ഒരു മധ്യസ്ഥ സ്ഥാപനമായി 
        നിയോഗിക്കുകയും ചെയ്യുന്നു. പ്രെസോൾവ്360 അതിന്റെ പ്ലാറ്റ്ഫോമിൽ മധ്യസ്ഥ നടപടികൾ കൈകാര്യം ചെയ്യുന്നു, 
        കൂടാതെ മധ്യസ്ഥരുടെ പാനലിൽ ആവശ്യമായ കഴിവും അറിവും വൈദഗ്ധ്യവുമുള്ള സ്വതന്ത്രവും യോഗ്യതയുള്ളതുമായ 
        മധ്യസ്ഥരെ നിയോഗിക്കുന്നു. മധ്യസ്ഥത / അനുരഞ്ജനം പ്രിസോൾവ്360-ന്റെ തർക്ക പരിഹാര നിയമങ്ങൾക്കനുസൃതമായി 
        നിയന്ത്രിക്കുകയും നിർവഹിക്കുകയും ചെയ്യും, അതിന്റെ ഒരു പകർപ്പ് 
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">ഇവിടെ</a> കാണാം. മധ്യസ്ഥ നടപടികൾ 
        നിർവഹിക്കുന്നതിന് ബന്ധപ്പെട്ട എല്ലാ കക്ഷികൾക്കും മധ്യസ്ഥനും പ്രിസോൾവ്360 ഭരണപരമായ പിന്തുണ നൽകുന്നു, 
        തർക്കത്തിന്റെ ഫലത്തിൽ അതിന് താൽപ്പര്യമില്ല, താൽപ്പര്യ വൈരുദ്ധ്യവും നിലവിലില്ല.
    </p>

    
    <p>4. പ്രക്രിയ തികച്ചും രഹസ്യസ്വഭാവമുള്ളത് ആയിരിക്കെ, പങ്കെടുക്കാത്ത സാഹചര്യത്തിൽ അല്ലെങ്കിൽ തർക്കം 
        പരിഹരിക്കപ്പെടാതെ തുടരുകയാണെങ്കിൽ ലഭ്യമായ ഏതെങ്കിലും നിയമപരമായ പരിഹാരങ്ങൾക്ക് മധ്യസ്ഥത / 
        അനുരഞ്ജന നടപടികൾ 'മുൻവിധികളില്ലാതെ' ആണ്. ഇത് ഏറ്റവും പ്രതിഫലദായകമായ പ്രക്രിയകളിലൊന്നായി മാറി, 
        നൽകിയ എല്ലാ പരാമർശങ്ങളുടെയും 90% ത്തിലധികം വിജയ നിരക്ക് ഇതിനുണ്ട്.

    </p>

    <p>5. എതിർ / പ്രതികരിക്കുന്ന കക്ഷി, മധ്യസ്ഥതയ്ക്ക് / അനുരഞ്ജനത്തിന് ഉള്ള ക്ഷണം ലഭിച്ച് ഏഴ് (7) പ്രവൃത്തി 
        ദിവസത്തിനുള്ളിൽ, <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> എന്നതിൽ പ്രിസോൾവ്360-നെ അഭിസംബോധന ചെയ്ത ഒരു ഇമെയിൽ വഴി 
        പ്രസ്തുത ക്ഷണം സ്വീകരിക്കുകയോ നിരസിക്കുകയോ ചെയ്യണം, അല്ലാത്തപക്ഷം, മധ്യസ്ഥത / അനുരഞ്ജനം 
        ആരംഭിക്കാത്തതായി കണക്കാക്കും.
    </p>

    <p>6. ഒരു അംഗീകൃത പ്രതിനിധിയെ പ്രതിനിധീകരിക്കാനോ സഹായിക്കാനോ കക്ഷികൾക്ക് തിരഞ്ഞെടുക്കാം, ഈ 
        സാഹചര്യത്തിൽ നിയമിക്കുന്ന കക്ഷി ഒരു അധികാര പത്രം സമർപ്പിക്കേണ്ടതാണ്, അതിന്റെ ഫോർമാറ്റ് 
         <a
        href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>ഇവിടെ</a>
        ലഭ്യമാണ്. ഒപ്പിട്ട അധികാര പത്രം നിയമിത കക്ഷി പ്രിസോൾവ്360-ന് “അധികാര പത്രം (കേസ് ഐഡി) | (നിയമിത 
        കക്ഷിയുടെ പേര്)” എന്ന വിഷയത്തോടെ <a href="mailto:admin@presolv360.com">admin@presolv360.com</a>
         എന്ന വിലാസത്തിൽ ഇമെയിൽ വഴി സമർപ്പിക്കേണ്ടതാണ്..
    </p>

    
    <p>7. മധ്യസ്ഥരുടെ / അനുരഞ്ജകരുടെ പാനലിൽ നിന്ന് ഒരു മധ്യസ്ഥനെ / അനുരഞ്ജകനെ നിയമിക്കും, അത്തരം നിയമനം 
        മധ്യസ്ഥന്റെ / അനുരഞ്ജകന്റെ കഴിവ്, അറിവ്, കക്ഷികൾ തമ്മിലുള്ള തർക്ക വിഷയം കൈകാര്യം ചെയ്യാനുള്ള കഴിവ് 
        എന്നിവയെ അടിസ്ഥാനമാക്കിയുള്ളതായിരിക്കും.
    </p>

    <p>8. മധ്യസ്ഥൻ / അനുരഞ്ജകൻ നിയമനം അംഗീകരിച്ചുകഴിഞ്ഞാൽ, നിയമനത്തെക്കുറിച്ച് കക്ഷികളെ അറിയിക്കും.

    </p>

    <p>9. ശ്രവണ വൈകല്യമുള്ളപ്പോൾ ഏതെങ്കിലും കക്ഷിക്ക് ഒരു ഇന്ത്യൻ ആംഗ്യഭാഷ (ഐഎസ്എൽ) വ്യാഖ്യാതാവിന്റെ 
        സഹായം ആവശ്യമുണ്ടെങ്കിൽ, "ദ്വിഭാഷയ്ക്കുള്ള അഭ്യർത്ഥന (കേസ് ഐഡി)" എന്ന വിഷയത്തോടെ 
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> എന്നതിനെ അഭിസംബോധന ചെയ്ത് ഒരു ഇമെയിൽ എഴുതുക, 
        പ്രിസോൾവ്360 ഈ സൗകര്യം നൽകും.

    </p>

    <p>കുറിപ്പ്: ഇത് സിസ്റ്റം സൃഷ്ടിച്ച അറിയിപ്പാണ്, അതിനാൽ ഒപ്പ് ആവശ്യമില്ല.
    </p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>സ്വീകർത്താവ്:
                </p>
                <p>എതിർ / പ്രതികരിക്കുന്ന കക്ഷി
                </p>
            </td>
            <td>
                <p>പകർപ്പ് ലഭിക്കേണ്ടത്:
                </p>
                <p>അപേക്ഷിക്കുന്ന / തുടക്ക കക്ഷി
                </p>
            </td>
        </tr>
    </table>

    <!----------------- MALYALAM ------------------------------------------------------->

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
    ऑनलॉइन विवाद निराकरणासह (ODR) पर्यायी विवाद निराकरण सेवा (ADR) प्रदान करणाऱ्या आणि विविध न्यायालयांद्वारे मध्यस्थी संस्था म्हणून नामांकीत केलेल्या संस्थांचा यादीमध्ये  <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing"> (येथे माहिती उपलब्ध आहे) </a> 
            समावेश आहे.
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>प्रकरण आयडी: M{{ sprintf('%06d', $case->id) }}</p>
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
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. ऑनलाइन विवाद निराकरणासह ("ODR") पर्यायी विवाद निराकरण सेवा प्रदान करणाऱ्या ("ADR")आणि भारतातील विविध न्यायालयांद्वारे मध्यस्थी संस्था म्हणून 
        पॅनेलमध्ये सामिल केलेल्या संस्थांच्या यादीमध्ये Presolv360 समाविष्ट केले गेले आहे. Presolv360 त्याच्या प्लॅटफॉर्मवर मध्यस्थी कार्यवाही प्रशासित करते आणि त्याच्या 
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

    
    <p>7. मध्यस्थ / समन्वयकर्त्यांच्या पॅनेलमधून मध्यस्थ / समन्वयकर्ता निवडला जाईल, आणि अशी नियुक्ती मध्यस्थ / समन्वयकर्त्याची क्षमता, 
        ज्ञान आणि पक्षांमधील विवादाच्या विषयाला सामोरे जाण्याची क्षमता यावर आधारित असेल.

    </p>

    <p>8. मध्यस्थ / समन्वयकर्त्याकडून नियुक्ती स्विकृत केल्यानंतर  पक्षांना नियुक्तीबाबत सूचित केले जाईल.
    </p>

    <p>9. श्रवणदोष असल्यास कोणत्याही पक्षाला भारतीय सांकेतिक भाषा (ISL) दुभाष्याची मदत हवी असल्यास, 
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
        @if($itm_lang == "telugu")
   

     <!----------------- TELUGU ------------------------------------------------------->

     <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">మధ్యవర్తిత్వం/రాజీ కుదర్చడం కోసం ఆహ్వానం </p>
    

    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">ఆన్ؚలైన్ వివాద పరిష్కారంతో (ODR) పాటు ప్రత్యామ్నాయ వివాద పరిష్కార (ADR) సేవలను అందించే సంస్థల జాబితాలో <a
            href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(సారాంశం ఇక్కడ లభిస్తుంది)</a> 
            చేర్చబడింది మరియు భారతదేశంలోని వివిధ న్యాయస్థానాల ద్వారా మధ్యవర్తిత్వ సంస్థగా గుర్తింపు పొందింది 
    </p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>కేస్: M{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>తేదీ: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>దరఖాస్తుదారు(లు) / ఇనీషియేటింగ్ పార్టీ:</p>
            </td>
            <td>
                <p>ఆపోజిట్ / రెస్పాండింగ్ పార్టీ:</p>
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

    
    
    <p>1. వివాదాన్ని సామరస్యపూర్వకంగా పరిష్కరించుకోవాలనే కోరికతో, దరఖాస్తుదారు(లు) / ఇనీషియేటింగ్ పార్టీ వివాదానికి సామరస్య పరిష్కారం కోసం Presolv360లో అభ్యర్థనను నమోదు చేశారు.
    </p>
    
    <p>2. దరఖాస్తుదారు(లు) / ఇనీషియేటింగ్ పార్టీ ప్రకారం:
    </p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. ఆన్ؚలైన్ వివాద పరిష్కారంతో (“ODR”) పాటు ప్రత్యామ్నాయ వివాద పరిష్కార (“ADR”) సేవలను అందించే సంస్థల జాబితాలో చేర్చబడింది మరియు 
        భారతదేశంలోని వివిధ న్యాయస్థానాల ద్వారా మధ్యవర్తిత్వ సంస్థగా గుర్తింపు పొందింది. 
        Presolv360 తన ప్లాట్ؚఫారంపై మధ్యవర్తిత్వ ప్రొసీడింగ్ؚలను నిర్వహిస్తుంది మరియు అవసరమైన సామర్ధ్యం, అవగాహన మరియు నైపుణ్యం ఉన్న 
        అర్హులైన స్వతంత్ర మధ్యవర్తులను తన మధ్యవర్తుల ప్యానెల్ؚకు నియామిస్తుంది. మధ్యవర్తిత్వం / రాజీ చేయడం అనేది Presolv360 వివాద పరిష్కార 
        నిబంధనల ప్రకారం పర్యవేక్షించబడుతుంది మరియు నిర్వహించబడుతుంది, దీని కాపీని  <a
        href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">ఇక్కడ</a>చూడవచ్చు. 
        Presolv360 మధ్యవర్తిత్వ ప్రొసీడింగ్ؚలను నిర్వహించడానికి సంబంధిత పార్టీలు మరియు మధ్యవర్తులు అందరికి అడ్మినిస్ట్రేటివ్ సపోర్ట్ؚను అందిస్తుంది మరియు 
        వివాదం ఫలితంపై అది ఆసక్తిని కలిగి ఉండదు మరియు ప్రయోజన వైరుధ్యం తలెత్తదు. </p>

    
    <p>4. ఈ ప్రక్రియ గోప్యంగా ఉంటుంది, మధ్యవర్తిత్వం / రాజీ చేయడం అనే ప్రొసీడింగ్ؚలు, నాన్-పార్టిసిపేషన్ సందర్భంలో లేదా వివాదం 
        పరిష్కరించబడని సందర్భంలో లభించే ఎటువంటి న్యాయపరమైన పరిష్కారాల పట్ల ‘పక్షపాతం లేకుండా’ నిర్వహించబడతాయి. అన్ని 
        రిఫరెన్స్ؚలలో 90% కంటే ఎక్కువ విజయం శాతంతో, ఇది అత్యంత లాభదాయకమైన ప్రక్రియలలో ఒకటిగా నిలిచింది.
    </p>

    <p>5. ఆపోజిట్ / రెస్పాండింగ్ పార్టీ, మధ్యవర్తిత్వం / రాజీ కోసం ఆహ్వానాన్ని అందుకున్న ఏడు (7) రోజులలోగా, 
        Presolv360కి <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> కు ఈమెయిల్ పంపించడం ద్వారా ఆహ్వానాన్ని అంగీకరించాలి లేదా తిరస్కరించాలి, ఇలా చేయకపోతే, 
        మధ్యవర్తిత్వం / రాజీ చేయడం ప్రారంభం కాలేదని భావించబడుతుంది.
    </p>

    <p>6. ఒక అధీకృత ప్రతినిధి ప్రాతినిధ్యం వహించాలని లేదా సహాయం చేయాలని పార్టీలు కోరుకోవచ్చు, అటువంటి సందర్భంలో అపాయింటింగ్ 
        పార్టీ లెటర్ ఆఫ్ అథారిటీని సమర్పించాలి. దీని ఫార్మాట్ <a
        href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>ఇక్కడ</a>
        అందుబాటులో ఉంది. అపాయింటింగ్ పార్టీ సంతకం చేసిన లెటర్ ఆఫ్ ఆధారిటీని “లెటర్ ఆఫ్ ఆధారిటీ | (కేస్ ID) | అపాయింటింగ్ పార్టీ పేరు)” 
        అనే సబ్జెక్ట్ؚతో Presolv360కి <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> కు ఈమెయిల్ పంపించడం ద్వారా సమర్పించాలి.

    </p>

    
    <p>7. మధ్యవర్తులు / రాజీ చేసే వ్యక్తి ప్యానెల్ నుండి మధ్యవర్తి / రాజీ చేసే వ్యక్తిని నియమిస్తారు మరియు అటువంటి నియామకం, మధ్యవర్తి / రాజీ చేసే 
        వ్యక్తి సమర్థత, అవగాహన మరియు పార్టీల మధ్య ఉన్న వివాదం అంశాన్ని నిర్వహించగలిగిన సామర్ధ్యంపై ఆధారపడి ఉంటుంది. </p>

    <p>8. మధ్యవర్తి / రాజీ చేసే వ్యక్తి నియామకం ఆమోదించబడిన తరువాత, నియామకం గురించి పార్టీలకు తెలియజేస్తారు.
    </p>

    <p>9. ఏదైనా పార్టీకి వినికిడి లోపం ఉండి, ఇండియన్ సైన్ లాంగ్వేజ్ (ISL) ఇంటర్ؚప్రిటర్ అవసరం ఉంటే, “ఇంటర్ؚప్రిటర్ కోసం అభ్యర్థన | 
        (కేస్ ID)”ؚ అనే సబ్జెక్ట్ؚతో <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> కు ఈమెయిల్ చేయాలి, మరియు ఈ Presolv360 ఈ సౌకర్యాన్ని అందిస్తుంది.
    </p>

    <p>గమనిక: ఇది సిస్టమ్ ద్వారా జనరేట్ చేసిన నోటీస్ అందువలన సంతకం అవసరం లేదు.
    </p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>కు</p>
                <p>ఆపోజిట్ / రెస్పాండింగ్ పార్టీకి
                </p>
            </td>
            <td>
                <p>కాపీ:</p>
                <p>దరఖాస్తుదారు(లు) / ఇనీషియేటింగ్ పార్టీకి
                </p>
            </td>
        </tr>
    </table>

    <!----------------- TELUGU ------------------------------------------------------->




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
    ଅନଲାଇନ୍ ବିବାଦ ସମାଧାନ (ODR) ମାଧ୍ୟମରେ ବିକଳ୍ପ ବିବାଦ ସମାଧାନ (ADR) ସେବା ପ୍ରଦାନ କରୁଥିବା ଅନୁଷ୍ଠାନ    
    <a href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing"> (ଏଠାରେ ଉପଲବ୍ଧ) </a> 
    ତାଲିକାରେ ଅନ୍ତର୍ଭୂକ୍ତ କରାଯାଇଛି ଏବଂ ଭାରତର ବିଭିନ୍ନ କୋର୍ଟ ଦ୍ୱାରା ମଧ୍ୟସ୍ଥତା ପ୍ରତିଷ୍ଠାନ ଭାବରେ ସାମଞ୍ଜସ୍ୟ ପ୍ରାପ୍ତ ହୋଇଛି 
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>କେସ ଆଇଡି: M{{ sprintf('%06d', $case->id) }}</p>
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
    
    <p style='margin-left:15px;'>
        <?php 
        echo nl2br($case->issue); ?>
    </p>

    <p>3. ଅନ୍ଲାଇନ୍ ବିବାଦ ସମାଧାନ ("ODR") ସହିତ ବିକଳ୍ପ ବିବାଦ ସମାଧାନ ("ADR") ସେବା ପ୍ରଦାନ କରୁଥିବା ଅନୁଷ୍ଠାନ ତାଲିକାରେ Presolv360 ଅନ୍ତର୍ଭୂକ୍ତ କରାଯାଇଛି ଏବଂ ଏହା ମଧ୍ୟ ଭାରତର ବିଭିନ୍ନ କୋର୍ଟ ଦ୍ୱାରା ମଧ୍ୟସ୍ଥତା ପ୍ରତିଷ୍ଠାନ ଭାବରେ ସ୍ଥାନିତ ହୋଇଛି |  Presolv360 ଏହାର ପ୍ଲାଟଫର୍ମରେ ମଧ୍ୟସ୍ଥତା ପ୍ରକ୍ରିୟା ପରିଚାଳନା କରେ, ଏବଂ ଏହାର ମଧ୍ୟସ୍ଥତା ପ୍ୟାନେଲରେ ଆବଶ୍ୟକ ଦକ୍ଷତା, ଜ୍ଞାନ ଏବଂ ପାରଦର୍ଶୀତା ସହିତ ସ୍ୱାଧୀନ, ଯୋଗ୍ୟ ମଧ୍ୟସ୍ଥିମାନଙ୍କୁ ଗ୍ରହଣ କରେ | ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟ Presolv360 ର ବିବାଦର ସମାଧାନ ନିୟମ ଅନୁଯାୟୀ ପରିଚାଳିତ ହେବ ଏବଂ ଏହାର ଏକ ନକଲ
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">ଏଠାରେ</a>
        ମିଳିପାରିବ |Presolv360 ସଂପୃକ୍ତ ସମସ୍ତ ପକ୍ଷ ଏବଂ ମଧ୍ୟସ୍ଥତା ପ୍ରକ୍ରିୟା ପରିଚାଳନା ପାଇଁ ମଧ୍ୟସ୍ଥତାଙ୍କୁ ପ୍ରଶାସନିକ ସହାୟତା ଯୋଗାଇଥାଏ ଏବଂ ବିବାଦର ଫଳାଫଳ ପାଇଁ କୌଣସି ଆଗ୍ରହ ନାହିଁ ଏବଂ ସେଠାରେ କୌଣସି ଆଗ୍ରହ ନାହିଁ |
    </p>

    
    <p>4. ଯଦି ବିବାଦର ସମାଧାନ ହୋଇନଥାଏ କିମ୍ବା ଏହି ପ୍ରକ୍ରିୟାଟି ସମ୍ପୂର୍ଣ୍ଣ ଗୋପନୀୟ ରଖିଥିବାବେଳେ ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟ ପ୍ରକ୍ରିୟା କୌଣସି ଆଇନଗତ ପ୍ରତିକାରରେ ଅଂଶଗ୍ରହଣ କରିବେ ନାହିଁ |  ସମସ୍ତ ରେଫରେନ୍ସଗୁଡିକର ୯୦% ରୁ ଅଧିକ ସଫଳତା ହାର ସହିତ ଏହା ଏକ ପୁରସ୍କାରପ୍ରଦ ପ୍ରକ୍ରିୟା ମଧ୍ୟରୁ ଗୋଟିଏ ହୋଇପାରିଛି |</p>

    <p>5. ବିରୋଧୀ / ପ୍ରତିକ୍ରିୟାଶୀଳ ପାର୍ଟି, ମଧ୍ୟସ୍ଥ / ସମନ୍ୱୟ ନିମନ୍ତ୍ରଣ ପାଇବା ଠାରୁ ସାତ (7) କାର୍ଯ୍ୟ ଦିବସ ମଧ୍ୟରେ, <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
    ରେ presolv360 କୁ ଏକ ଇମେଲ୍ ମାଧ୍ୟମରେ ଉକ୍ତ ନିମନ୍ତ୍ରଣକୁ ଗ୍ରହଣ କିମ୍ବା ପ୍ରତ୍ୟାଖ୍ୟାନ କରିବ, ଯାହା ବିଫଳ ହେବ,  ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟ ଏକ ଅଣ-ଷ୍ଟାର୍ଟର ବୋଲି ଧରାଯିବ |  
    </p>

    <!-- <p>6. ପକ୍ଷଗୁଡିକ ଏକ ପ୍ରାଧିକୃତ ପ୍ରତିନିଧୀଙ୍କ ଦ୍ ପ୍ରତିନିଧିତ୍। | ରା ପ୍ରତିନିଧିତ୍ or କିମ୍ବା ସହାୟତା କରିବାକୁ ବାଛିପାରନ୍ତି, ଯେଉଁ କ୍ଷେତ୍ରରେ ନିଯୁକ୍ତ ଦଳ ଏକ ଅଥରିଟି ଅଫ୍ ଅଥରିଟି 
        ଦାଖଲ କରିବେ, ଯାହାର ଫର୍ମାଟ୍
        <a href="https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing">ଏଠାରେ</a>
        ଉପଲବ୍ଧ | ନିଯୁକ୍ତ ଦଳ ଏହି ବିଷୟ ସହିତ 
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
        ରେ Presolv360 କୁ ପଠାଯାଇଥିବା ଏକ ଇମେଲ ମାଧ୍ୟମରେ ସ୍ୱାକ୍ଷରିତ ଅଥରିଟି ଦାଖଲ କରିବେ  
        "Letter of Authority | (Case ID) | (Name of the Appointing Party)".
    </p> -->

    
    <p>7. ମଧ୍ୟସ୍ଥି / ସମନ୍ୱୟକାରୀଙ୍କ ପ୍ୟାନେଲରୁ ଜଣେ ମଧ୍ୟସ୍ଥି / ସଂଯୋଜକ ନିଯୁକ୍ତ ହେବେ ଏବଂ ଏହିପରି ନିଯୁକ୍ତି ମଧ୍ୟସ୍ଥତା / ସମନ୍ୱୟର ଦକ୍ଷତା, ଜ୍ଞାନ ଏବଂ ପକ୍ଷ ମଧ୍ୟରେ ବିବାଦର ବିଷୟବସ୍ତୁକୁ ମୁକାବିଲା କରିବାର କ୍ଷମତା ଉପରେ ଆଧାରିତ ହେବ |</p>

    <p>8. ମଧ୍ୟସ୍ଥତା / ସଂଯୋଜକ ଦ୍ୱାରା ନିଯୁକ୍ତି ଗ୍ରହଣ କରାଯିବା ପରେ, ପକ୍ଷମାନଙ୍କୁ ନିଯୁକ୍ତି ବିଷୟରେ ଅବଗତ କରାଯିବ |</p>

    <p>9. ଯଦି କୌଣସି ଦଳ ଶ୍ରବଣ ଦୁର୍ବଳତା କ୍ଷେତ୍ରରେ ଭାରତୀୟ ସଙ୍କେତ ଭାଷା (ISL) ଅନୁବାଦକଙ୍କ ସହାୟତା ଆବଶ୍ୟକ କରନ୍ତି, ତେବେ
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
        @if($itm_lang == "punjabi")
    
    <!----------------- PUNJABI ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">ਮੱਧਸਥਤਾ / ਸਹਿਮਤੀ ਲਈ ਨਿਮੰਤਰਣ</p>
    
    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">ਉਨ੍ਹਾਂ ਸੰਸਥਾਵਾਂ ਦੀ ਸੂਚੀ ਵਿੱਚ ਸ਼ਾਮਲ
        <a href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing"> (ਤੁਸੀਂ ਸੂਚੀ ਇੱਥੇ ਪ੍ਰਾਪਤ ਕਰ ਸਕਦੇ ਹੋ) </a> 
        ਜੋ ਬਦਲੀਆਂ ਵਿਵਾਦ ਸੰਮਾਧਾਨ (ADR) ਸੇਵਾਵਾਂ ਪ੍ਰਦਾਨ ਕਰਦੀਆਂ ਹਨ, ਜਿਨ੍ਹਾਂ ਵਿੱਚ Online Dispute Resolution (ODR) ਵੀ ਸ਼ਾਮਲ ਹੈ। Presolv360 ਨੂੰ ਭਾਰਤ ਦੇ ਵੱਖ-ਵੱਖ ਅਦਾਲਤਾਂ ਦੁਆਰਾ ਮੱਧਸਥਤਾ ਸੰਸਥਾ ਵਜੋਂ ਮੰਨਤਾ ਦਿੱਤੀ ਗਈ ਹੈ। ਤੁਸੀਂ Presolv360 ਨਾਲ ਸੰਪਰਕ ਕਰ ਸਕਦੇ ਹੋ: 
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>ਕੇਸ ID: M{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>ਮਿਤੀ: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>ਆਵਦਕ(ਆਂ) / ਸ਼ੁਰੂਆਤੀ ਪਾਰਟੀ:</p>
            </td>
            <td>
                <p>ਸਮੁਖ/ਪ੍ਰਤੀਪੱਖ ਪਾਰਟੀ:</p>
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

    
    
    <p>1. ਇੱਕ ਦੋਸਤਾਨਾ ਹੱਲ 'ਤੇ ਪਹੁੰਚਣ ਦੀ ਇੱਛਾ ਰੱਖਦੇ ਹੋਏ, ਬਿਨੈਕਾਰ(ਆਂ) / ਸ਼ੁਰੂਆਤ ਕਰਨ ਵਾਲੀ ਧਿਰ ਨੇ ਵਿਵਾਦ ਦੇ ਇੱਕ ਸੁਹਿਰਦ ਹੱਲ ਦੀ ਮੰਗ ਕੀਤੀ ਹੈ ਅਤੇ Presolv360 ਨਾਲ ਇੱਕ ਬੇਨਤੀ ਦਰਜ ਕੀਤੀ ਹੈ।</p>
    
    <p>2. ਆਵਦਕ(ਆਂ) / ਸ਼ੁਰੂਆਤੀ ਪਾਰਟੀ:
    </p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. Presolv360 ਇੱਕ ਮੰਨਿਆ ਹੋਇਆ ਸੰਸਥਾ ਹੈ ਜੋ ਬਦਲੀਆਂ ਵਿਵਾਦ ਸੰਮਾਧਾਨ (ADR) ਸੇਵਾਵਾਂ ਪ੍ਰਦਾਨ ਕਰਦਾ ਹੈ, ਜਿਸ ਵਿੱਚ Online Dispute Resolution (ODR) ਵੀ ਸ਼ਾਮਲ ਹੈ। ਇਹ ਸੰਸਥਾ ਭਾਰਤ ਦੀਆਂ ਕਈ ਅਦਾਲਤਾਂ ਦੁਆਰਾ ਮੱਧਸਥਤਾ ਸੰਸਥਾ ਵਜੋਂ ਸਵੀਕਾਰ ਕੀਤੀ ਗਈ ਹੈ। Presolv360 ਮੱਧਸਥਤਾ ਪ੍ਰਕਿਰਿਆ ਚਲਾਉਂਦਾ ਹੈ ਅਤੇ ਕੁਆਲੀਫਾਈਡ ਅਤੇ ਸੁਤੰਤਰ ਮੱਧਸਥਤਾਵਾਂ ਦੀ ਸਹਾਇਤਾ ਨਾਲ, ਜਿਹੜੇ ਜਰੂਰੀ ਕਾਬਲਿਯਤ, ਗਿਆਨ, ਅਤੇ ਤਜਰਬੇ ਰੱਖਦੇ ਮੱਧਸਥਤਾ ਜਾਂ ਸਹਿਮਤੀ ਦੀ ਪ੍ਰਕਿਰਿਆ Presolv360 ਦੇ ਵਿਵਾਦ ਸੰਮਾਧਾਨ ਨਿਯਮਾਂ ਅਨੁਸਾਰ ਹੋਵੇਗੀ, ਜਿਸ ਦੀ ਪ੍ਰਤੀ 
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">ਇੱਥੇ</a> ਪ੍ਰਾਪਤ ਕੀਤੀ ਜਾ ਸਕਦੀ ਹੈ।. Presolv360 ਵਿਚੋਲਗੀ ਦੀ ਕਾਰਵਾਈ ਕਰਨ ਲਈ ਸਬੰਧਤ ਸਾਰੀਆਂ ਧਿਰਾਂ ਅਤੇ ਵਿਚੋਲੇ ਨੂੰ ਪ੍ਰਸ਼ਾਸਕੀ ਸਹਾਇਤਾ ਪ੍ਰਦਾਨ ਕਰਦਾ ਹੈ ਅਤੇ ਵਿਵਾਦ ਦੇ ਨਤੀਜੇ ਵਿਚ ਕੋਈ ਦਿਲਚਸਪੀ ਨਹੀਂ ਰੱਖਦਾ ਹੈ ਅਤੇ ਹਿੱਤਾਂ ਦਾ ਕੋਈ ਟਕਰਾਅ ਮੌਜੂਦ ਨਹੀਂ ਹੈ।

    </p>

    
    <p>4. ਇਹ ਪ੍ਰਕਿਰਿਆ ਪੂਰੀ ਤਰ੍ਹਾਂ ਗੋਪਨੀਯਤਾ ਦੇ ਅਧੀਨ ਹੈ ਅਤੇ "ਬਿਨਾਂ ਪੱਖਪਾਤ" ਕਿਵੇਂ ਕਿ, ਕੋਈ ਵੀ ਪਾਰਟੀ ਭਾਗ ਨਹੀਂ ਲੈਂਦੀ ਜਾਂ ਜੇ ਵਿਵਾਦ ਹੱਲ ਨਹੀਂ ਹੁੰਦਾ ਤਾਂ ਕਾਨੂੰਨੀ ਰਾਹ ਮੌਜੂਦ ਰਹਿੰਦਾ ਹੈ। Presolv360 ਦੇ ਜ਼ਰੀਏ ਮੱਧਸਥਤਾ ਪ੍ਰਕਿਰਿਆ ਦੀ ਸਫਲਤਾ ਦਰ 90% ਤੋਂ ਵੱਧ ਹੈ।
    </p>

    <p>5. ਮੱਧਸਥਤਾ / ਸਹਿਮਤੀ ਲਈ ਇਸ ਸੱਦੇ ਨੂੰ ਪ੍ਰਾਪਤ ਕਰਨ ਦੇ ਸੱਤ (7) ਕਾਰੋਬਾਰੀ ਦਿਨਾਂ ਦੇ ਅੰਦਰ, ਸਮੁਖ/ਪ੍ਰਤੀਪੱਖ ਪਾਰਟੀ ਨੂੰ Presolv360 ਤੇ ਈਮੇਲ ਰਾਹੀਂ ਸੱਦਾ ਸਵੀਕਾਰ ਜਾਂ ਰੱਦ ਕਰਨਾ ਹੋਵੇਗਾ: 
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
        ਜੇ ਇਸ ਸਮੇਂ ਦੀ ਮਿਆਦ ਵਿੱਚ ਕੋਈ ਜਵਾਬ ਨਹੀਂ ਮਿਲਦਾ, ਤਾਂ ਮੱਧਸਥਤਾ / ਸਹਿਮਤੀ ਪ੍ਰਕਿਰਿਆ ਸ਼ੁਰੂ ਨਹੀਂ ਹੋਵੇਗੀ।  
    </p>

    <p>6. ਪਾਰਟੀਆਂ ਕਿਸੇ ਅਧਿਕਾਰਤ ਪ੍ਰਤੀਨਿਧੀ ਦੁਆਰਾ ਨੁਮਾਇੰਦਗੀ ਜਾਂ ਸਹਾਇਤਾ ਕਰਨ ਦੀ ਚੋਣ ਕਰ ਸਕਦੀਆਂ ਹਨ, ਇਸ ਸਥਿਤੀ ਵਿੱਚ ਨਿਯੁਕਤੀ ਕਰਨ ਵਾਲੀ ਧਿਰ ਅਥਾਰਟੀ ਦਾ ਇੱਕ ਪੱਤਰ ਜਮ੍ਹਾ ਕਰੇਗੀ, ਜਿਸਦਾ ਫਾਰਮੈਟ <a
        href="https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing">ਇੱਥੇ</a>
        ਉਪਲਬਧ ਹੈ। ਨਿਯੁਕਤੀ ਕਰਨ ਵਾਲੀ ਧਿਰ Presolv360 ਨੂੰ ਐਡਮਿਨ
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 'ਤੇ ਈਮੇਲ ਰਾਹੀਂ ਹਸਤਾਖਰਿਤ ਅਥਾਰਟੀ ਦੇ ਪੱਤਰ ਨੂੰ "ਅਥਾਰਟੀ ਦੇ ਪੱਤਰ | (ਕੇਸ ID) | (ਨਿਯੁਕਤ ਪਾਰਟੀ ਦਾ ਨਾਮ)
    </p>

    
    <p>7. ਵਿਚੋਲੇ/ਸੁਲਾਹ ਕਰਨ ਵਾਲਿਆਂ ਦੇ ਪੈਨਲ ਵਿਚੋਂ ਇਕ ਵਿਚੋਲੇ/ਸਲਾਹਕਾਰ ਦੀ ਨਿਯੁਕਤੀ ਕੀਤੀ ਜਾਵੇਗੀ, ਅਤੇ ਅਜਿਹੀ ਨਿਯੁਕਤੀ ਵਿਚੋਲੇ/ਸੁਲਾਹਕਾਰ ਦੀ ਯੋਗਤਾ, ਗਿਆਨ ਅਤੇ ਧਿਰਾਂ ਵਿਚਕਾਰ ਵਿਵਾਦ ਦੇ ਵਿਸ਼ੇ ਨਾਲ ਨਜਿੱਠਣ ਦੀ ਯੋਗਤਾ 'ਤੇ ਆਧਾਰਿਤ ਹੋਵੇਗੀ।

    </p>

    <p>8. ਵਿਚੋਲੇ/ਸਲਾਹਕਾਰ ਦੁਆਰਾ ਨਿਯੁਕਤੀ ਨੂੰ ਸਵੀਕਾਰ ਕਰਨ 'ਤੇ, ਪਾਰਟੀਆਂ ਨੂੰ ਨਿਯੁਕਤੀ ਬਾਰੇ ਸੂਚਿਤ ਕੀਤਾ ਜਾਵੇਗਾ।
    </p>

    <p>9. ਜੇਕਰ ਕਿਸੇ ਪਾਰਟੀ ਨੂੰ ਸੁਣਨ ਦੀ ਸਮੱਸਿਆ ਹੈ ਅਤੇ ਉਹ ਭਾਰਤੀ ਸੰਕੇਤ ਭਾਸ਼ਾ (ISL) ਦੂਭਾਸੀਏ ਦੀ ਸਹਾਇਤਾ ਦੀ ਲੋੜ ਰੱਖਦੀ ਹੈ, ਤਾਂ 
    <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> ਤੇ ਈਮੇਲ ਭੇਜੋ ਅਤੇ ਵਿਸ਼ਾ ਲਿਖੋ: "Request for Interpreter | (Case ID)". Presolv360 ਇਹ ਸਹੂਲਤ ਪ੍ਰਦਾਨ ਕਰੇਗਾ।.
    </p>

    <p>ਨੋਟ: ਇਹ ਇੱਕ ਸਿਸਟਮ ਦੁਆਰਾ ਜਨਰੇਟ ਕੀਤੀ ਗਈ ਸੂਚਨਾ ਹੈ ਅਤੇ ਇਸ ਲਈ ਹਸਤਾਖਰ ਦੀ ਲੋੜ ਨਹੀਂ ਹੈ।

</p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>ਸਮੁਖ:
                </p>
                <p>ਪ੍ਰਤੀਪੱਖ ਪਾਰਟੀ
                </p>
            </td>
            <td>
                <p>ਕਾਪੀ :
                </p>
                <p>ਆਵਦਕ(ਆਂ) / ਸ਼ੁਰੂਆਤੀ ਪਾਰਟੀ
                </p>
            </td>
        </tr>
    </table>

    <!----------------- PUNJABI ------------------------------------------------------->





    @endif
        @if($itm_lang == "assamese")
    
    <!----------------- ASSAMESE ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">মধ্যস্থতা / মিলাপ্ৰীতিৰ বাবে আমন্ত্ৰণ</p>
    
    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">অনলাইন বিবাদ নিষ্পত্তি (ODR) ৰ জৰিয়তে আৰু ভাৰতৰ বিভিন্ন আদালতৰ দ্বাৰা মধ্যস্থতাকাৰী প্ৰতিষ্ঠান হিচাপে মনোনীত অন্যান্য বিবাদ নিষ্পত্তি (ADR) সেৱাসমূহ প্ৰদানকে সামৰি প্ৰতিষ্ঠানসমূহৰ তালিকাত
        <a href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing"> (আহৰণ ইয়াত উপলব্ধ) </a> 
        অন্তৰ্ভুক্ত কৰা হৈছে 
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>গোচৰ আইডি: M{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>তাৰিখ: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>আবেদনকাৰী (সকল) / প্ৰাৰম্ভকাৰী পক্ষ:</p>
            </td>
            <td>
                <p>বিপৰীত / সঁহাৰি প্ৰদানকাৰী পক্ষ:</p>
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

    
    
    <p>1. এক সৌহাৰ্দ্যপূৰ্ণ নিষ্পত্তিত উপনীত হোৱা বাবে, আবেদনকাৰী (সকল) / প্ৰাৰম্ভকাৰী পক্ষই বিবাদৰ এক সৌহাৰ্দ্যপূৰ্ণ নিষ্পত্তিৰ বিচাৰি Presolv360 ত এক অনুৰোধ পঞ্জীয়ন কৰিছে৷</p>
    
    <p>2. আবেদনকাৰী (সকল) / প্ৰাৰম্ভকাৰী পক্ষৰ মতে:
    </p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. Presolv360 অনলাইন বিবাদ নিষ্পত্তি ("ODR")-ৰ জৰিয়তে অন্যান্য বিবাদ নিষ্পত্তি ("ADR") ৰ সেৱা প্ৰদানকাৰী প্ৰতিষ্ঠানসমূহৰ তালিকাত অন্তৰ্ভুক্ত লগতে ভাৰতৰ বিভিন্ন আদালতৰ দ্বাৰা মধ্যস্থতাকাৰী প্ৰতিষ্ঠান হিচাপে মনোনীত৷ Presolv360 এ তেওঁলোকৰ মঞ্চত মধ্যস্থতাকাৰী প্ৰক্ৰিয়া কাৰ্য্যবিধি পৰিচালনা কৰে, আৰু তেওঁলোকৰ মধ্যস্থতাকাৰী জুৰীত প্ৰয়োজনীয় কাৰ্য্যদক্ষতা, জ্ঞান আৰু দক্ষতা থকা স্বাধীন, অৰ্হতাসম্পন্ন যোগ্য মধ্যস্থতাকাৰীক মনোনীত কৰে৷ মধ্যস্থতা / মিলাপ্ৰীতি Presolv360-ৰ বিবাদ নিষ্পত্তি বিধিসমূহৰ দ্বাৰা নিয়ন্ত্ৰিত আৰু সেইসমূহৰ অনুসৰি পৰিচালিত হ’ব৷, যাৰ এটা প্ৰতিলিপি
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">ইয়াত</a> পাব৷ Presolv360 এ সংশ্লিষ্ট সকলো পক্ষকে মধ্যস্থতাৰ কাৰ্য্যবিধিসমূহৰ বাবে প্ৰসাশনিক সহায় আৰু মধ্যস্থতাকাৰী প্ৰদান কৰে আৰু ইয়াৰ ফলাফলৰ প্ৰতি তেওঁলোকৰ কোনো স্বাৰ্থ নিহিত আৰু কোনো আদৰ্শৰ সংঘাত জড়িত হৈ নাথাকে৷

    </p>

    
    <p>4. যদিওবা প্ৰক্ৰিয়াটো একেবাৰে গোপনীয়, মধ্যস্থতা / মিলাপ্ৰীতি প্ৰক্ৰিয়াসমূহ অংশগ্ৰহণ নকৰা বা বিবাদ সমাধান নোহোৱাকৈ ৰৈ যোৱাৰ ক্ষেত্ৰত উপলব্ধ যিকোনো আইনী প্ৰতিকাৰ ‘পক্ষপাত অবিহনে’ প্ৰদান কৰা হয়৷ এইটো উল্লেখ কৰা সকলো প্ৰসংগৰ 90% তকৈ অধিক সফলতাৰ হাৰৰ সৈতে আটাইতকৈ পুৰস্কাৰ প্ৰাপ্ত এক অন্যতম প্ৰক্ৰিয়াত পৰিগণিত হৈছে৷
    </p>

    <p>5. বিপৰীত / সঁহাৰি প্ৰদানকাৰী পক্ষই, Presolv360 ৰ ইমেইল আইডি <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
    ত ইমেইল প্ৰেৰণৰ জৰিয়তে, মধ্যস্থতা / মিলাপ্ৰীতিৰ নিমন্ত্ৰণ প্ৰাপ্ত কৰাৰ সাত (7) কৰ্মদিনৰ ভিতৰত গ্ৰহণ বা অগ্ৰাহ্য কৰিব লাগিব, যিটো নকৰিলে মধ্যস্থতা / মিলাপ্ৰীতি আৰম্ভণি নোহোৱা বুলি গণ্য কৰা হ’ব৷ 
    </p>

    <p>6. পক্ষসমূহে প্ৰতিনিধিত্ব কৰাৰ বাবে বা এগৰাকী কৰ্তৃত্বপ্ৰাপ্ত প্ৰতিনিধিৰ দ্বাৰা সহায় গ্ৰহণ কৰিবলৈ বাচনি কৰিব পাৰে, তেনে ক্ষেত্ৰত নিয়োগকাৰী পক্ষই এখন কৰ্তৃত্ব প্ৰদান পত্ৰ প্ৰদান কৰিব লাগিব, যিখনৰ আৰ্হি <a
        href="https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing">ইয়াত </a>
        উপলব্ধ৷ নিয়োগকাৰী পক্ষই স্বাক্ষৰ কৰা কৰ্তৃত্ব প্ৰদান পত্ৰ ইমেইলৰ জৰিয়তে Presolv360 ক
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> ত বিষয়বস্তু “কৰ্তৃত্ব প্ৰদান পত্ৰ | (গোচৰ আইডি) | (নিয়োগকাৰী পক্ষৰ নাম)” বুলি উল্লেখ কৰি প্ৰদান কৰিব লাগিব৷
    </p>

    
    <p>7. মধ্যস্থতাকাৰী / মিলাপ্ৰীতিকাৰী জুৰীৰ পৰা এগৰাকী মধ্যস্থতাকাৰী / মিলাপ্ৰীতিকাৰী নিয়োগ কৰা হ’ব, আৰু তেনে নিয়োগ মধ্যস্থতাকাৰী / মিলাপ্ৰীতিকাৰীৰ কাৰ্য্যদক্ষতা, জ্ঞান আৰু পক্ষসমূহৰ মাজত সৃষ্টি হোৱা বিবাদৰ বিষয়বস্তুৰ সৈতে মোকাবিলা কৰাৰ সমৰ্থৰ ওপৰত ভিত্তি কৰি প্ৰদান কৰা হ’ব৷

    </p>

    <p>8. মধ্যস্থতাকাৰী / মিলাপ্ৰীতিকাৰীৰ দ্বাৰা প্ৰদান কৰা নিয়োগ গ্ৰহণ কৰাৰ পিছত, পক্ষসমূহক নিয়োগৰ বিষয়ে অৱগত কৰা হ’ব৷
    </p>

    <p>9. যদি কোনো পক্ষৰ শ্ৰৱণ ক্ষমতাহীনতাৰ বাবে ভাৰতীয় সাংকেতিক ভাষা (ISL) ভাষান্তৰিকৰ সহায়ৰ প্ৰয়োজন হয়, বিষয়বস্তু "ভাষান্তৰিকৰ বাবে অনুৰোধ | (গোচৰ আইডি)" বুলি 
    <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> লৈ, এক ইমেইল প্ৰেৰণ কৰক, আৰু Presolv360 ৰ দ্বাৰা এই সুবিধা প্ৰদান কৰা হ’ব৷
    </p>

    <p>টোকা: এইখন এখন কম্পিউটাৰ প্ৰণালীৰ দ্বাৰা প্ৰস্তুত কৰা জাননী আৰু সেয়েহে ইয়াত স্বাক্ষৰৰ প্ৰয়োজন নায়৷

</p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>প্ৰতি:
                </p>
                <p>বিপৰীত / সঁহাৰি প্ৰদানকাৰী পক্ষ
                </p>
            </td>
            <td>
                <p>প্ৰতিলিপি:
                </p>
                <p>আবেদনকাৰী (সকল) / প্ৰাৰম্ভকাৰী পক্ষ
                </p>
            </td>
        </tr>
    </table>

    <!----------------- ASSAMESE ------------------------------------------------------->







    @endif
        @if($itm_lang == "bengali")
    
    <!----------------- BENGALI ------------------------------------------------------->

    <center style="{{$page_break_css}}">
        <div class="text-center">
            <img src='{{ URL("assert/img/Logo1.png") }}' style='width: 120px;'>
        </div>
    </center>


    <p class="text-center" style="font-size:30px; margin-bottom: 0px; margin-top: 0px !important;">মধ্যস্থতা/মীমাংসা করার আমন্ত্রণপত্র</p>
    
    <p class="text-center" style="font-size:14px; margin-bottom: 0px !important; margin-top: 0px !important;">অনলাইন বিরোধ সমাধান (ODR) সহ বিকল্প বিরোধ নিষ্পত্তি (ADR) পরিষেবা প্রদানকারী প্রতিষ্ঠানগুলির তালিকায় অন্তর্ভুক্ত<a href="https://drive.google.com/file/d/1T7D2z6Y0eeRuXCdCHjiYjg2AQ4qYEQ6P/view?usp=sharing">(extract available here) </a> 
    এবং ভারতের বিভিন্ন আদালত দ্বারা জারি করা মীমাংসাকারী প্রতিষ্ঠান হিসাবে তালিকাভুক্ত করা হয়েছে।
</p>

    <p class="text-center" style="margin-top: 0px !important;font-size:14px !important; margin-bottom: 0px !important;"><a href="https://mediation.presolv360.com/">https://mediation.presolv360.com/</a> | <a
            href="mailto:admin@presolv360.com">admin@presolv360.com</a></p>

    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td width="60%">
                <p>কেস আইডি: M{{ sprintf('%06d', $case->id) }}</p>
            </td>
            <td class="text-right">
                <p>তারিখ: {{ date('d-m-Y') }}</p>
            </td>
        </tr>
    </table>
    <table class="table_" cellspacing="0" cellpadding="10" width="100%">
        <tr style="page-break-after: avoid !important;">
            <td width="50%">
                <p>আবেদনকারী/ মক্কেল:</p>
            </td>
            <td>
                <p>বিরোধী / অপর পক্ষ:</p>
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

    
    
    <p>1. একটি বন্ধুত্বপূর্ণ রেজোলিউশনে পৌঁছাতে আগ্রহী, আবেদনকারী(গুলি)/প্রবর্তক পক্ষ বিরোধের একটি বন্ধুত্বপূর্ণ সমাধান চেয়েছে এবং Presolv360-এর সাথে একটি অনুরোধ নিবন্ধিত করেছে৷</p>
    
    <p>2. আবেদনকারী(গুলি)/মক্কেল অনুসারে:
    </p>
    <!-- <p style='margin-left:15px;'>{{ $case->issue }}</p> -->
    <p style='margin-left:15px;'>
        <?php 
        //$issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_text = nl2br(htmlspecialchars($case->issue));
       // $issue_desc = str_replace('<br>', '', $issue_text);
        echo nl2br($case->issue); ?>
    </p>

    <p>3. Presolv360 অনলাইন বিরোধ সমাধান ("ODR") সহ বিকল্প বিরোধ সমাধান ("ADR") পরিষেবা প্রদানকারী প্রতিষ্ঠানগুলির তালিকায় অন্তর্ভুক্ত করা 
        হয়েছে এবং ভারতের বিভিন্ন আদালত দ্বারা একটি মীমাংসাকারী প্রতিষ্ঠান হিসাবে তালিকাভুক্ত করা হয়েছে ৷ Presolv360 এর প্ল্যাটফর্মে মধ্যস্থতা কার্যক্রম 
        পরিচালনা করে এবং এর মধ্যস্থতাকারীদের প্যানেলে প্রয়োজনীয় যোগ্যতা, জ্ঞান এবং দক্ষতা সহ নিরপেক্ষ, যোগ্য মধ্যস্থতাকারীদের তালিকাভুক্ত করে। 
        মধ্যস্থতা / মীমাংসা Presolv360-এর বিরোধ নিষ্পত্তির নিয়ম অনুসারে পরিচালিত হবে এবং পরিচালিত হবে, যার একটি অনুলিপি পাওয়া যাবে 
        <a href="https://drive.google.com/file/d/1a5GkQA0KX_4-gUDf25D0uU7Rt_1S8DDl/view?usp=sharing">here</a> 
        । Presolv360 মধ্যস্থতা কার্যক্রম পরিচালনার জন্য সংশ্লিষ্ট সকল পক্ষ এবং মধ্যস্থতাকারীকে প্রশাসনিক সহায়তা প্রদান করে এবং বিরোধের ফলাফল সম্পর্কে কোনোরকম ভাবে জড়িত থাকবে না এবং এর কোনো দায়ভার নেই।

    </p>

    
    <p>4. প্রক্রিয়াটি সম্পূর্ণ গোপনীয় হলেও, এতে অংশগ্রহণ না করার ক্ষেত্রে যে কোনো আইনি সমাধানের জন্য মধ্যস্থতা / মীমাংসার প্রক্রিয়াগুলি কোনরকম 'সংঘাত ছাড়াই' অমীমাংসিত থেকে যায়। সমস্ত রেফারেন্সের 90% এর বেশি সাফল্যের হার সহ এটি সবচেয়ে ফলপ্রসূ প্রক্রিয়াগুলির মধ্যে অন্যতম হয়ে উঠেছে।</p>

    <p>5. বিরোধী / অপর পক্ষ, মধ্যস্থতা / মীমাংসার আমন্ত্রণ প্রাপ্তির সাত (7) কার্যদিবসের মধ্যে, <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> 
    -এ Presolv360 ঠিকানায় পাঠানো একটি ইমেলের মাধ্যমে উল্লিখিত আমন্ত্রণটি গ্রহণ বা প্রত্যাখ্যান করবে, যেটি ফলপ্রসূ না হলে, মধ্যস্থতা / মীমাংসা একটি নন-স্টার্টার হিসাবে বিবেচিত হবে। </p>

    <p>6. উভয়পক্ষই একজন অনুমোদিত প্রতিনিধি দ্বারা প্রতিনিধিত্ব করা বা সহায়তা করা বেছে নিতে পারে, এই ক্ষেত্রে নিয়োগকারী পক্ষ কর্তৃপক্ষের একটি চিঠি জমা দেবে, যার নিয়মাবলী উপলব্ধ  <a
        href="https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing">here </a>
        । নিয়োগকারী পক্ষ স্বাক্ষরিত কর্তৃপক্ষের চিঠিটি একটি ইমেলের মাধ্যমে “কর্তৃপক্ষের চিঠি | (কেস আইডি) | (নিয়োগকারী দলের নাম)” ইত্যাদি বিষয় সহ Presolv360 
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a> এখানে জমা দিতে হবে।
    </p>

    
    <p>7. মধ্যস্থতাকারী / মীমাংসাকারীদের প্যানেল থেকে একজন মধ্যস্থতাকারী / মীমাংসাকারী নিয়োগ করা হবে, এবং এই ধরনের নিয়োগ মধ্যস্থতাকারী / মীমাংসার অভিজ্ঞতা, জ্ঞান এবং উভয়পক্ষের মধ্যে বিরোধ সম্পর্কিত বিষয়বস্তু মোকাবেলার দক্ষতার উপর ভিত্তি করে হবে৷

    </p>

    <p>8. মধ্যস্থতাকারী / মীমাংসাকারীর দ্বারা অ্যাপয়েন্টমেন্ট গ্রহণ করার পরে, উভয়পক্ষকে নিয়োগের বিষয়ে অবহিত করা হবে৷
    </p>

    <p>9. শ্রবণ প্রতিবন্ধকতার ক্ষেত্রে যদি কোনো পক্ষের ভারতীয় সাংকেতিক ভাষার (ISL) দোভাষীর সহায়তার প্রয়োজন হয়, তাহলে
        <a href="mailto:admin@presolv360.com">admin@presolv360.com</a>-এখানে "দোভাষীর অনুরোধ | (কেস আইডি)" বিষয় সহ একটি ইমেল লিখুন এবং এই সুবিধাটি Presolv360 দ্বারা আপনাকে সরবরাহ করা হবে।
    </p>

    <p>দ্রষ্টব্য: এটি একটি সিস্টেম জেনারেটেড নোটিশ এবং এক্ষেত্রে স্বাক্ষরের প্রয়োজন নেই।</p>


    <table cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td>
                <p>প্রতি:
                </p>
                <p>বিরোধী / অপর পক্ষ
                </p>
            </td>
            <td>
                <p>এখানে অনুলিপি করুন:
                </p>
                <p>আবেদনকারী(গুলি) / মক্কেল
                </p>
            </td>
        </tr>
    </table>

    <!----------------- BENGALI ------------------------------------------------------->







   
        @endif
    @endforeach
    

    </body>

</html>