@section('title','Withdrawal of your case :'.$id)
@extends('email.layouts.app')
@section('content')

<p>Dear Customer,</p>

<p>This is to inform you that your case bearing ID {{$id}} has been successfully withdrawn. </p>

<p>For any clarifications please contact our Customer Care at 022-23822446. You can also email your queries to {{config('email.info','info@appname.com')}}</p>

<p>Best regards,</p>
<p>Team {{ config('app.name', 'Laravel') }}</p>


<p>This Email is system generated. Please do not reply to this Email ID.</p>

<p>Notice of Confidentiality:</p>
<p>This transmission contains information that may be confidential and may also be privileged. Unless you are the intended recipient of the message (or authorised to receive it for the intended recipient), you may not copy, forward, or otherwise use it, or disclose it or its contents to anyone else. If you have received this transmission in error please notify us immediately and delete it from your system.</p>

<p>Edgecraft Solutions Private Limited.</p>

@endsection