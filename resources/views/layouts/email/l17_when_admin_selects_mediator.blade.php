@section('title','Urgent: Appointment as Mediator')
@extends('email.layouts.app')
@section('content')

<p>Dear Mediator,</p>
<p>We seek your acceptance and consent to act as mediator for an e-mediation process on the {{ config('app.name', 'Laravel') }}</p>
<p>platform having case id {{$id}}. For accepting the mediation, kindly complete the following</p>
steps:
1. Accept the mediation by clicking here <a href="{{route("login")}}">{{route("login")}}</a>.
2. Upon acceptance, submit your &#39;Consent and Disclosures&#39;.
Kindly note that if your consent to act as mediator is not received within 5 working days, it shall be
deemed to have been rejected and a new mediator shall be appointed.
<p>For any clarifications please contact our Customer Care at 022-23822446. You can also email your queries to {{config('email.info','info@appname.com')}}</p>

<p>Best regards,</p>
<p>Team {{ config('app.name', 'Laravel') }}</p>


<p>This Email is system generated. Please do not reply to this Email ID.</p>

<p>Notice of Confidentiality:</p>
<p>This transmission contains information that may be confidential and may also be privileged. Unless you are the intended recipient of the message (or authorised to receive it for the intended recipient), you may not copy, forward, or otherwise use it, or disclose it or its contents to anyone else. If you have received this transmission in error please notify us immediately and delete it from your system.</p>

<p>Edgecraft Solutions Private Limited.</p>


@endsection