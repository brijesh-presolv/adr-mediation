@section('title', 'Case M'.sprintf('%06d',$case->id))
@extends('admin.layouts.app')


@section('breadcrumb')

<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.casedetails')</a></li>
<!-- end page title -->
@endsection
@section('page_title', 'Details for Case ID: M'.sprintf('%06d',$case->id))

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
                                        @if($value->joinCode != "")
                                        JoinCode:  {{ $value->joinCode}}<br>   
                                        @endif 
                                        @if($value->onboardedDate != "")
                                        Date of Onboarded: {{date('d-m-Y', strtotime($value->onboardedDate))}}<br>   
                                        @endif
                                        <br>
                                        @endif
                                        
                                        <?php
                                    }
                                }?>
                                {{-- {{dd($case)}} --}}
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
                                        @if($value->joinCode != "")
                                        JoinCode:  {{ $value->joinCode}}<br>   
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
                        @if($case->natureOfAgreement != NULL) 
                        <tr>
                            <td>Nature of Agreement</td>
                            <td>{{$case->natureOfAgreement}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Disputed Amount</td>
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
                            <td>Request Letter</td>
                            <td>
                                <?php
                                if ($case->request_letter) {

                                    $doc = 'storage/app/public/mediation/' . $case->id . '/' . $case->request_letter;
                                    ?>
                                    <a href="{{url($doc)}}" target="_blank">@lang('case.view')</a>
                                <?php } else { ?>
                                    @lang('case.na')
                                <?php } ?>
                            </td>
                        </tr>
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
                        <tr>
                            <td>Mediator Appointment Letter</td>
                            <td>

                                <?php
                                if ($case->invitation) {
                                    if($case->invitation->file_name_mediator_appointment != null) {
                                    $doc = 'storage/app/public/mediation/' . $case->id . '/' . $case->invitation->file_name_mediator_appointment;
                                    ?>
                                    <a href="{{url($doc)}}" target="_blank">@lang('case.view')</a>
                                    <?php } else { ?>
                                        @lang('case.na')
                                <?php } } else { ?>
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
                                <th>@lang('case.supporting_documents')</th>
                                <th>Share With</th>
                                <th>Share With Mediator?</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th></th>
                            </tr>

                                <?php foreach ($case->supporting_document as $k => $v) { ?>
                                    {{-- {{dd($v->username)}} --}}
                                    <tr>

                                    <td><?= basename($v->file_name) ?>
                                    </td>
                                    <?php $userAccess = App\Models\InvoledUser::where('userPlanId', $case->id)->get();?>
                                        
                                    <td>
                                        <?php $accessParty = explode(',', $v->access); ?>
                                        @foreach ($userAccess as $key => $item)
                                        @if ($item->name != null)
                                        @if(in_array($item->id, $accessParty))
                                            <input type="checkbox" data-manageid={{$v->id}} name="party[]"  checked class="partyShare" id="party{{$v->id}}" value="{{$item->id}}" data-filepath="{{$v->file_name}}">
                                            <label class="form-check-label"  for="party{{$v->id}}"> {{$item->name}} </label><br>
                                        @else
                                            <input type="checkbox" data-manageid={{$v->id}} name="party[]"   class="partyShare" id="party{{$v->id}}" value="{{$item->id}}" data-filepath="{{$v->file_name}}">
                                            <label class="form-check-label"  for="party{{$v->id}}"> {{$item->name}} </label><br>
                                        @endif
                                        @endif
                                        
                                        @endforeach
                                    </td>
                                    <td><div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" id="status_change_approvel{{$v->id}}" name=="user_status" value="{{$v->id}}" class="custom-control-input status_change_approvel" {{$v->mediator_access == 1 ? "checked" : ""}} data-filepath="{{$v->file_name}}" data-caseid="{{$case->id}}">
                                        <label class="custom-control-label" for="status_change_approvel{{$v->id}}"> </label>
                                    </div></div>
                                    </td>
                                    <td>{{$v->username}}</td>
                                    <td>{{date('d-m-Y', strtotime($v->created_at))}}</td>
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

@section('footer')
<script>
    
    $(document).ready(function () {
        $(document).on('change', ".partyShare", function () {
        // var id = $(this).val();
        var manageid = $(this).data('manageid');
        var filename_path = $(this).data('filepath');
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        var checkedId = "";
        var uncheckedId = "";
        if ($(this).is(':checked')) {
            // var status = 1;
            checkedId = $(this).val();
        } else {
            uncheckedId = $(this).val();
        }
        console.log(filename_path);
        console.log("checkedId : ", checkedId);
        console.log("uncheckedId : ", uncheckedId);

        $.ajax({
            url: '{{ route("admin.users.docs_access_change") }}',
            method: "post",
            data: {manageid: manageid, checkedId: checkedId, uncheckedId: uncheckedId, filename_path: filename_path, '_token': csrf},
        }).done(function (data) {
            // .reload()
            // console.log(data);
            location.reload();
        });
        });

        $(document).on('change', ".status_change_approvel", function () {
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var manageid = $(this).val();
            var filename_path = $(this).data('filepath');
            var caseid = $(this).data('caseid');
            var mediatorAccess;
            if ($(this).is(':checked')) {
                mediatorAccess = 1;
            } else {
                mediatorAccess = 0;
            }
            $.ajax({
                url: '{{ route("admin.users.mediator_access_change") }}',
                method: "post",
                data: {'manageid': manageid, 'mediatorAccess': mediatorAccess, 'filename_path': filename_path, 'caseid': caseid, '_token': csrf},
            }).done(function (data) {
                // .reload()
                // console.log(data);
                location.reload();
            });

            // console.log(mediatorAccess);

        });
    });
</script>
@endsection
