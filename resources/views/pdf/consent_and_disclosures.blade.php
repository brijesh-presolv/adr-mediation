<!DOCTYPE html>
<html>
    <head>
        <title> {{ config('app.name', 'Medtiator') }} | Consent And Disclosures</title>
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
        </style>
    </head>
    <body>
    <htmlpageheader name="page-header" >
        <h3 class="text-center"><u>Mediator’s Consent and Disclosures</u></h3>
    </htmlpageheader>
    <p class="text-center">
        See Rule 6 of Section 3 of Presolv360’s Dispute Resolution Rules (“Rules”) read with the Arbitrators’ and Mediators’ Code of Conduct and Disclosure Rules (“Code”)
    </p>
    <u>Details of the Dispute</u>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>Initiating Party</th>
                <th>Responding Party</th>
            <tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <p>{{$party[0]->name}}</p>
                    <p>{{$party[0]->address1}} {{$party[0]->address2}}</p>
                    @php
                    $address = array();
                    $address[]=$party[0]->city." - ".$party[0]->pincode;
                    $address[]=$party[0]->state;
                    $address[]=$party[0]->country;
                    @endphp
                    <p>{{ implode(", ",$address) }}</p>
                    <p>{{$party[0]->userEmail }}</p>
                    <p>{{$party[0]->userPhone }}</p>
                </td>
                <td>
                    @if(isset($party[1]))
                    <p>{{$party[1]->name}}</p>
                    <p>{{$party[1]->address1}} {{$party[1]->address2}}</p>
                    @php
                    $address = array();
                    $address[]=$party[1]->city." - ".$party[1]->pincode;
                    $address[]=$party[1]->state;
                    $address[]=$party[1]->country;
                    @endphp
                    <p>{{ implode(", ",$address) }}</p>
                    <p>{{$party[1]->userEmail }}</p>
                    <p>{{$party[1]->userPhone }}</p>
                    @endif
                </td>
            </tr>
            @foreach($party as $key=>$p)
            @if($key>1)
            <tr>
                <td></td>
                <td>
                    <p>{{$p->name}}</p>
                    <p>{{$p->address1}} {{$p->address2}}</p>
                    @php
                    $address = array();
                    $address[]=$p->city." - ".$p->pincode;
                    $address[]=$p->state;
                    $address[]=$p->country;
                    @endphp
                    <p>{{ implode(", ",$address) }}</p>
                    <p>{{$p->userEmail }}</p>
                    <p>{{$p->userPhone }}</p>
                </td>
            </tr>
            @endif
            @endforeach            
        </tbody>
    </table>
    <br/><br/>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th class="text-left">Consent</th>
                <th>Yes</th>
                <th>No</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>I accept and consent to act as a mediator in the captioned dispute </td>
                <td>{!! ($consent_disclosures->consent1==1)?"&#10004;":"" !!}</td>
                <td>{!! ($consent_disclosures->consent1==0)?"&#10004;":"" !!}</td>
            </tr>
            <tr>
                <td>I am qualified, possess the required competence, knowledge and expertise, and have sufficient time to be able to conduct the mediation proceedings within the time limits prescribed in the Rules</td>
                <td>{!! ($consent_disclosures->consent2==1)?"&#10004;":"" !!}</td>
                <td>{!! ($consent_disclosures->consent2==0)?"&#10004;":"" !!}</td>
            </tr>
            <tr>
                <td>I shall be, and remain, independent and neutral throughout the proceedings i.e. from beginning to end and ensure that my words, manner, attitude, body language and process management reflects an impartial and even-handed approach</td>
                <td>{!! ($consent_disclosures->consent3==1)?"&#10004;":"" !!}</td>
                <td>{!! ($consent_disclosures->consent3==0)?"&#10004;":"" !!}</td>
            </tr>
            <tr>
                <td>I shall conduct the mediation proceedings in a fair and impartial manner, and endeavour to provide a procedurally fair process in which each party is given an adequate opportunity to participate </td>
                <td>{!! ($consent_disclosures->consent4==1)?"&#10004;":"" !!}</td>
                <td>{!! ($consent_disclosures->consent4==0)?"&#10004;":"" !!}</td>
            </tr>
            <tr>
                <td>I shall maintain utmost confidentiality of all matters relating to mediation proceedings, including all documents, records, and communications, during as well as after its completion</td>
                <td>{!!  ($consent_disclosures->consent5==1)?"&#10004;":"" !!}</td>
                <td>{!! ($consent_disclosures->consent5==0)?"&#10004;":"" !!}</td>
            </tr>
        </tbody>
    </table>
    <br /><br /><br />
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th class="text-left">Particulars</th>
                <th>Disclosures</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Experience</td>
                <td>{{$consent_disclosures->particulars1}}</td>
            </tr>
            <tr>
                <td>Circumstances disclosing any past or present relationship with, or interest in, any of the parties or in relation to the subject-matter in dispute, whether financial, business, professional or other kind, which is likely to impair your independence or impartiality (list out)</td>
                <td>{{$consent_disclosures->particulars2}}</td>
            </tr>
            <tr>
                <td>Circumstances which are likely to affect your ability to devote sufficient time to the mediation and in particular your </td>
                <td>{{$consent_disclosures->particulars3}}</td>
            </tr>
            <tr>
                <td>ability to complete the entire mediation within the time limits prescribed under the Rules</td>
                <td>{{$consent_disclosures->particulars4}}</td>
            </tr>
        </tbody>
    </table>
    <br /><br /><br />
    <p>I confirm that the details provided above are true, accurate, current and complete and acknowledge that a copy of the consent and disclosures will be provided to the parties.</p>
    <p>By checking this box, I accept and agree to conduct the mediation in accordance with the Rules and confirm that I shall abide by the <a href="#">Code</a>, <a href="#">Terms</a> & <a href="#">Conditions and Privacy Policy</a>.</p>
    <br /><br /><br /><br />
    <h4 class="text-right">
        <small>{{$consent_disclosures->first_name}} {{$consent_disclosures->last_name}}</small><br />Mediator &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    </h4>
</body>
</html>