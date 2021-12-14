@section('title', 'Case M'.sprintf('%06d',$case->id))
@extends('mediator.layouts.app')


@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.casedetails')</a></li>
<!-- end page title -->
@endsection
@section('content')

<div class="card">
    <div class="card-body">
        <section>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <td>@lang('case.initiating_party')</td>
                            <td>
                                @lang('case.name'): {{ $case->party[0]->name}}<br>
                                @lang('case.emai'): {{ $case->party[0]->userEmail}}<br>   
                                @lang('case.phone'): {{ $case->party[0]->userPhone}}<br>
                                @lang('case.address'): <?= $case->party[0]->address1 . ' ' . $case->party[0]->address2 . ' ' . $case->party[0]->city . ', ' . $case->party[0]->pincode . ', ' . $case->party[0]->state . ' ' . $case->party[0]->country ?><br>  
                            </td>
                        </tr>
                        <tr>
                            <td>@lang('case.responding_party')</td>
                            <td>

                                <?php
                                foreach ($case->party as $key => $value) {
                                    if ($key > 0) {
                                        ?>
                                        @if($value->name != "")
                                        @if($value->name != "")
                                        @lang('case.name'): {{ $value->name}}<br>
                                        @endif
                                        @if($value->userEmail != "")
                                        @lang('case.emai'): {{ $value->userEmail}}<br>   
                                        @endif 
                                        @if($value->userPhone != "")
                                        @lang('case.phone'): {{ $value->userPhone}}<br>
                                        @endif
                                        @if($value->address1 != "")
                                        @lang('case.address'): <?= $value->address1 . ' ' . $value->address2 . ' ' . $value->city . ', ' . $value->pincode . ', ' . $value->state . ' ' . $value->country ?><br>
                                        @elseif($value->fulladdress != "")
                                        @lang('case.address'): <?= $value->fulladdress ?><br>
                                        @endif
                                        <br>
                                        @endif
                                        
                                        <?php
                                    }
                                }?>
                                @if($case->otherRespondentDetails != "")
                                    {{$case->otherRespondentDetails}}<br>
                                @endif
                                <?php
                                foreach ($case->party as $key => $value) {
                                    if ($key > 0) {
                                        ?>
                                        @if($value->name == "")
                                        
                                        @if($value->userEmail != "")
                                        @lang('case.emai'): {{ $value->userEmail}}<br>   
                                        @endif 
                                        @if($value->userPhone != "")
                                        @lang('case.phone'): {{ $value->userPhone}}<br>
                                        @endif
                                        
                                        @endif
                                        <br>
                                        <?php
                                    }
                                }?>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Dispute Category</td>
                            <td>{{$case->disputeCategory}}</td>
                        </tr>
                        <tr>
                            <td>Disptued Amount</td>
                            <td>{{$case->amount}}</td>
                        </tr>
                        <tr>
                            <td>@lang('case.issue')</td>
                            <td>{{$case->issue}}</td>
                        </tr>
                        @if($case->proposedSolution != NULL) 
                        <tr>
                            <td>Proposed Solution</td>
                            <td>{{$case->proposedSolution}}</td>
                        </tr>
                        @endif
                        
                        <tr>
                            <td>Agreement Date</td>
                            <td>{{$case->created_at}}</td>
                        </tr>
                        
                        <?php if ($case->mfirstname) { ?>
                            <tr>
                                <td>@lang('case.mediator')</td>
                                <td>{{$case->mfirstname}}  {{$case->mlastname}}</td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td>@lang('case.invitation_to_mediate')</td>
                            <td>

                                <?php
                                if ($case->invitation) {

                                    $doc = 'storage/app/public/mediation/' . $case->id . '/' . $case->invitation->file_name;
                                    ?>
                                    <a href="{{url($doc)}}" target="_blank">@lang('case.view')</a>
                                <?php } else { ?>
                                    @lang('case.na')
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                    <?php if($case->documentPath != "NULL"){ $doc='storage/app/public/mediation/'.$case->id.'/'.$case->documentPath;
                                    ?>

                                     <table class="table table-bordered">

                         <tr >
                            <th colspan="2">Supporting document (User)</th>
                        </tr>



                    <tr>
                        <td><?= basename($case->documentPath)?></td>
                            
                            <td>

                               <a href="{{url($doc)}}" target="_blank">Download</a>
                                
                            </td>
                        </tr>
                        </table>
                    <?php } ?>
                    <?php if (count($case->supporting_document) > 0) { ?>
                        <table class="table table-bordered">
                            <tr >
                                <th colspan="2">@lang('case.supporting_documents')</th>
                            </tr>

                            <tr>
                                <?php foreach ($case->supporting_document as $k => $v) { ?>

                                    <td><?= basename($v->file_name) ?></td>

                                    <td><a class="btn btn-sm btn-success" target="_blank" href="{{url('storage/app/'.$v->file_name)}}">@lang('case.view')</a></td>
                                </tr>
                            <?php } ?>


                        </table>
                    <?php } ?>

                </div>
            </div>
        </section>

    </div>
</div>
@endsection