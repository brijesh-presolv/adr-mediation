@section('title','Update about your case')
@extends('email.layouts.app')
@section('content')
<p>Dear Customer,</p>

<p>{{$party_name}} has completed the onboarding process in respect of your case bearing ID {{$id}}. To view the status of parties onboarded, kindly login to your account <a href="{{route("login")}}">login</a>. </p>

<p>For any clarifications please contact our Customer Care at 022-23822446. You can also email your queries to {{config('email.info','info@appname.com')}}</p>

<p>Best regards,</p>
<p>Team {{ config('app.name', 'Laravel') }}</p>


<p>This Email is system generated. Please do not reply to this Email ID.</p>

<p>Notice of Confidentiality:</p>
<p>This transmission contains information that may be confidential and may also be privileged. Unless you are the intended recipient of the message (or authorised to receive it for the intended recipient), you may not copy, forward, or otherwise use it, or disclose it or its contents to anyone else. If you have received this transmission in error please notify us immediately and delete it from your system.</p>

<p>Edgecraft Solutions Private Limited.</p>

@endsection