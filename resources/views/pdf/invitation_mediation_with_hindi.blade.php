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
    <title> {{ config('app.name', 'Medtiator') }} | Invitation of Mediation / Conciliation HINDI</title>
    <style type="text/css">
         body {
                font-family: 'Open Sans', 'Arial', 'sans-serif', 'Hind', 'DejaVu Sans', 'freeserif', 'lohitkannada', 'pothana2000';
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

        @font-face {

font-family: NotoSerifTelugu;

src: url('{{ url('fonts/NotoSerifTelugu-VariableFont_wght.ttf') }}');
}
    </style>


<link rel="stylesheet" href="{{ url('fonts/DroidSansFallback.ttf') }}">
</head>

<body>

    <center>
        <div class="text-center">
            <img src='{{ URL("assert/img/plogo.png") }}' style='width: 120px;'>
            <br><br><br>
        </div>
    </center>


    <h2 class="text-center">Invitation to Mediate / Conciliate</h2>

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
                <p>Date : {{ date('d-m-Y') }}</p>
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
    {{-- <p><b>Date of Request for Mediation / Conciliation: {{date('d-m-Y')}}</b></p>
    <p><b>Last date to Respond to Invitation to Mediate / Conciliate: {{$ldate}} 23:59:59</b></p> --}}
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

    

    <!-------------- HINDI ------------------------------------------------------------------------------------->
    @php
        $langfamilyfont = 'freeserif, Open Sans, Arial, Arial, sans-serif, Verdana';
    @endphp
    <center>
        <div class="text-center">
            <img src='{{ URL("assert/img/plogo.png") }}' style='width: 120px;'>
            <br><br><br>
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
    
    <p style="font-family:{{ $langfamilyfont }};">5. विपक्षी/प्रतिसाद देने वाला पक्ष मध्यस्थता/समाधान के लिए आमंत्रण प्राप्त होने के सात (7) कार्य दिवसों के भीतर Presolv360 को admin@presolv360.com पर ईमेल के माध्यम से उक्त आमंत्रण को स्वीकार या अस्वीकार करेगा, अन्यथा मध्यस्थता/समाधान (Mediation/Conciliation) को नॉन-स्टार्टर माना जाएगा।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">6. दावेदार और प्रतिवादी एक अधिकृत प्रतिनिधि द्वारा प्रतिनिधित्व या सहायता के लिए चुन सकते हैं, इस मामले में नियुक्ति करने वाली पार्टी प्राधिकरण का एक पत्र प्रस्तुत करेगी, जिसका प्रारूप यहां उपलब्ध है। नियुक्त करने वाली पार्टी Presolv360 को संबोधित एक ईमेल के माध्यम से admin@presolv360.com पर “Letter of Authority | (Case ID) | (Name of the Appointing Party)”|</p>
    
    <p style="font-family:{{ $langfamilyfont }};">7. मध्यस्थों/समाधानकर्ताओं  (mediator / conciliator) के पैनल से एक मध्यस्थ/समाधानकर्ता की नियुक्ति की जाएगी, और ऐसी नियुक्ति मध्यस्थ/समाधानकर्ता (mediator / conciliator)  की योग्यता, ज्ञान और पक्षों के बीच विवाद के विषय-वस्तु से निपटने की क्षमता पर आधारित होगी।</p>
    
    <p style="font-family:{{ $langfamilyfont }};">8. मध्यस्थ/समाधानकर्ता द्वारा नियुक्ति स्वीकार किए जाने पर, पक्षों को नियुक्ति की सूचना दी जाएगी।</p>

    <p style="font-family:{{ $langfamilyfont }};">9. यदि किसी पक्ष को श्रवण दोष के मामले में भारतीय सांकेतिक भाषा (आईएसएल) दुभाषिया की सहायता की आवश्यकता है, तो admin@presolv360.com पर "Request for Interpreter | (Case ID)"   विषय के साथ एक ईमेल लिखें, और यह सुविधा Presolv360 द्वारा प्रदान की जाएगी।</p>
    
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

</body>

</html>





