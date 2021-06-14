@section('title','Invitation by '.$initiating_party.' to participate in resolution of your case via '.config('app.name', 'Laravel'))
@extends('email.layouts.app')
@section('content')
<p>Dear Sir / Ma’am,</p>

<p>Desirous of arriving at an amicable resolution, ||Initiating party|| has approached {{ config('app.name', 'Laravel') }} to conduct a neutral and non-adversarial resolution for your dispute with the abovenamed.</p>
<p>‘{{ config('app.name', 'Laravel') }}’ (recognised by the Department of Justice, Union Ministry of Law and Justice, Government of India) specializes in facilitating a quick and economical resolution of disputes. Equipped with a state-of-the-art dispute management platform, {{ config('app.name', 'Laravel') }}’s panel of experts assist parties to resolve their disputes effectively, thereby, optimizing resource outflow and more importantly, enhancing relations through superior practices.</p>
<p> We request you to complete the onboarding process as follows:</p>
<ol>
    <li>
        <p>
            Login / sign up and verify your details by clicking the following link: <a href="{{route("login")}}">login</a> / <a href="{{route("register")}}">signup</a>.
        </p>
    </li>
    <li>
        <p>
            Your 'Join Code' is: <b style="padding:5px;background-color:#e2e2e2;border:1px solid #000;font-size:20px">{{$code}}</b> Kindly use this code to successfully participate in the resolution process, under the PresolvDirect tab.
        </p>
    </li>
    <li>
        <p>
            Here is a link to download the Presolv Dispute Resolution Rules (‘PDRR’), which is a complete guide to everything you’ll ever need to know. Click here to download the rules - <a href="{{url("/pdrr.pdf")}}">{{url("/pdrr.pdf")}}</a>
        </p>
    </li>
</ol>



<p>We have enclosed a copy of the ‘Invitation Letter’ alongwith the mail. We request you to submit your acceptance by completing the aforesaid process or via a reply to this email within 15 days from the receipt of this communication, failing which this invitation shall be deemed to have lapsed.</p>

<p>In case you are not the intended recipient of this mail, please notify us immediately at 022-23822446 or mail us at {{config('email.info','info@appname.com')}}</p>

<p>Assuring you the best of our services at all times!</p>

<p>Best regards,</p>
<p>Team {{ config('app.name', 'Laravel') }}</p>


<p>Notice of Confidentiality:</p>
<p>This transmission contains information that may be confidential and may also be privileged. Unless you are the intended recipient of the message (or authorised to receive it for the intended recipient), you may not copy, forward, or otherwise use it, or disclose it or its contents to anyone else. If you have received this transmission in error please notify us immediately and delete it from your system.</p>

<p>Edgecraft Solutions Private Limited.</p>

@endsection