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



<center>
        <div class="text-center">
            <img src='{{ URL("assert/img/plogo.png") }}' style='width: 120px;'>
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
        admin@presolv360.com, failing which, the mediation / conciliation shall deemed to be a non-starter.
    </p>

    <p>6. The parties may choose to be represented or assisted by an authorized representative, in which case the
        appointing party shall submit a Letter of Authority, format of which is available <a
            href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>here</a>. The
        appointing party shall submit the signed Letter of Authority by way of an email addressed to Presolv360 at
        admin@presolv360.com with the subject “Letter of Authority | (Case ID) | (Name of the Appointing Party)”.</p>

    <p>7. A mediator / conciliator from the panel of mediators / conciliators will be appointed, and such appointment
        shall be based on the mediator’s / conciliator’s competence, knowledge and ability to deal with subject matter
        of the dispute between the parties.</p>

    <p>8. Upon acceptance of the appointment by the mediator / conciliator, the parties shall be notified of the
        appointment.</p>

    <p>9. If any party requires assistance of an Indian Sign Language (ISL) interpreter in case of hearing impairment, write an email addressed to admin@presolv360.com with the subject "Request for Interpreter | (Case ID)", and this facility will be provided by Presolv360.</p>

    <p><b>Note: This is a system generated notice and hence does not require signature.</b></p>
    <table cellspacing="0" cellpadding="10" width="100%" style="page-break-after: always;">
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





    <!----------------- TAMIL ------------------------------------------------------->

    <center>
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
        admin@presolv360.com இல் Presolv360 என்ற மின்னஞ்சல் முகவரிக்கு அனுப்பப்பட்ட மின்னஞ்சலின் மூலம் அந்த அழைப்பை ஏற்கவும் அல்லது நிராகரிக்கவும் வேண்டும்.  
        அவ்வாறு செய்யத்தவரும் பட்சத்தில், மத்தியஸ்தம் / சமரசம் ஒரு தொடக்கமற்றதாகக் கருதப்படும்.
    </p>

    <p>6. தரப்பினர் அங்கீகரிக்கப்பட்ட பிரதிநிதியால் பிரதிநிதித்துவம் செய்ய அல்லது உதவி செய்ய தேர்வு செய்யலாம். இந்த விசயத்தில் 
        நியமனம் செய்யும் தரப்பினர் அங்கீகாரக் கடிதத்தை சமர்ப்பிக்க வேண்டும், அதன் வடிவம் <a
        href='https://drive.google.com/file/d/1Q1d6_3n3R1QimVhtb1eGFC2jG3cWk7pS/view?usp=sharing'>இங்கே</a>
        கொடுக்கப்பட்டுள்ளது. நியமிக்கும் தரப்பினர் Presolv360  க்கு 	admin@presolv360.com என்ற முகவரிக்கு மின்னஞ்சல் வழியாக “அதிகாரக் கடிதம் 
        “Letter of Authority | (வழக்கு ஐடி) (Case ID) | (நியமிக்கும் கட்சியின் பெயர்)”. (Name of the Appointing Party)”|போன்றவற்றை 
        அனுப்பலாம்.
    </p>

    
    <p>7. நடுவர்கள்/சமரசம் செய்பவர்கள் குழுவில் இருந்து சமரசம் செய்பவர் நியமிக்கப்படுவார், மேலும் அத்தகைய நியமனம் 
        மத்தியஸ்தரின்/சமரசம் செய்பவரின் திறமை, அறிவு மற்றும் இருதரப்பு பிரச்சினைகளுக்கு இடையேயான விவகாரம் 
        ஆகியவற்றின் அடிப்படையில் இருக்கும்.</p>

    <p>8. நடுவர்/ சமரசம் செய்பவர் நியமனத்தை ஏற்றுக்கொண்டால், தரப்பினருக்கு நியமனம் குறித்து அறிவிக்கப்படும்.</p>

    <p>9. ஏதேனும் ஒரு தரப்பினருக்கு செவித்திறன் குறைபாடு  இருக்கும் பட்சத்தில் அவருக்கு இந்திய சைகை மொழி (ISL) மொழிபெயர்ப்பாளரின் உதவி தேவைப்பட்டால், admin@presolv360.com க்கு "மொழிபெயர்ப்பாளருக்கான கோரிக்கை | (வழக்கு ஐடி)" 
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

    </body>

</html>