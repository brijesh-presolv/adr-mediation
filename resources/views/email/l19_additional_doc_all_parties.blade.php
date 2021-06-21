@section('title','URGENT: ‘Additional Document’')
@extends('email.layouts.app')
@section('content')
<p>Dear Party,</p>
<p>This mail is regarding the mediation having case ID {{$id}}. An ‘Additional Document’ has
    been uploaded in the matter. The same is enclosed alongwith this mail and can also be viewed on the
    platform by clicking here <a href="{{route("login")}}">{{route("login")}}</a>.</p>
<p>For any clarifications, email your queries to {{ config('app.name', 'Laravel') }}</p>
<p>Best regards,</p>
<p>Team {{ config('app.name', 'Laravel') }}</p>

<p>This Email is system generated. Please do not reply to this Email ID.</p>
<p>Notice of Confidentiality:</p>
<p>This transmission contains information that may be confidential and may also be privileged. Unless you
    are the intended recipient of the message (or authorised to receive it for the intended recipient), you may
    not copy, forward, or otherwise use it, or disclose it or its contents to anyone else. If you have received
    this transmission in error please notify us immediately and delete it from your system.</p>
<p>Edgecraft Solutions Private Limited.</p>

@endsection