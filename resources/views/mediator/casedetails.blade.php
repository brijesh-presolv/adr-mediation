@section('title', 'Case M' . sprintf('%06d', $case->id))
@extends('mediator.layouts.app')


@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.casedetails')</a></li>
    <!-- end page title -->
@endsection
@section('pageTitleOnDashboard', 'Details for Case ID: M' . sprintf('%06d', $case->id))

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
                                    @lang('case.name'): {{ $case->party[0]->name }}<br>
                                    @lang('case.emai'): {{ $case->party[0]->userEmail }}<br>
                                    @lang('case.phone'): {{ $case->party[0]->userPhone }}<br>
                                    @lang('case.address'):
                                    <?= $case->party[0]->address1 . ' ' . $case->party[0]->address2 . ' ' . $case->party[0]->city . ', ' . $case->party[0]->pincode . ', ' . $case->party[0]->state . ' ' . $case->party[0]->country ?><br>
                                </td>
                            </tr>
                            <tr>
                                <td>@lang('case.responding_party')</td>
                                <td>



                                    <?php
                                foreach ($case->party as $key => $value) {
                                    if ($key > 0) {
                                        ?>
                                    @if ($value->name != '')
                                        @if ($value->name != '')
                                            @lang('case.name'): {{ $value->name }}<br>
                                        @endif
                                        @if ($value->userEmail != '')
                                            @lang('case.emai'): {{ $value->userEmail }}<br>
                                        @endif
                                        @if ($value->userPhone != '')
                                            @lang('case.phone'): {{ $value->userPhone }}<br>
                                        @endif
                                        @if ($value->address1 != '')
                                            @lang('case.address'):
                                            <?= $value->address1 . ' ' . $value->address2 . ' ' . $value->city . ', ' . $value->pincode . ', ' . $value->state . ' ' . $value->country ?><br>
                                        @elseif($value->fulladdress != '')
                                            @lang('case.address'): <?= $value->fulladdress ?><br>
                                        @endif
                                        @if ($value->onboardedDate != '')
                                            Date of Onboarded: {{ date('d-m-Y', strtotime($value->onboardedDate)) }}<br>
                                        @endif
                                        <br>
                                    @endif

                                    <?php
                                    }
                                }?>
                                    @if ($case->otherRespondentDetails != '')
                                        @lang('case.otherRespondentDetails'): {{ $case->otherRespondentDetails }}<br>
                                    @endif
                                    <?php
                                foreach ($case->party as $key => $value) {
                                    if ($key > 0) {
                                        ?>
                                    @if ($value->name == '')

                                        @if ($value->userEmail != '')
                                            @lang('case.emai'): {{ $value->userEmail }}<br>
                                        @endif
                                        @if ($value->userPhone != '')
                                            @lang('case.phone'): {{ $value->userPhone }}<br>
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
                                <td>{{ $case->disputeCategory }}</td>
                            </tr>
                            @if ($case->natureOfAgreement != null)
                                <tr>
                                    <td>Nature of Agreement</td>
                                    <td>{{ $case->natureOfAgreement }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td>Disputed Amount</td>
                                <td>{{ $case->amount }}</td>
                            </tr>
                            <tr>
                                <td>@lang('case.issue')</td>
                                <td>{{ $case->issue }}</td>
                            </tr>
                            @if ($case->proposedSolution != null)
                                <tr>
                                    <td>Proposed Solution</td>
                                    <td>{{ $case->proposedSolution }}</td>
                                </tr>
                            @endif

                            @if ($case->agreementDate != null)
                                <tr>
                                    <td>Agreement Date</td>
                                    <td>{{ $case->agreementDate }}</td>
                                </tr>
                            @endif

                            <?php if ($case->mfirstname) { ?>
                            <tr>
                                <td>@lang('case.mediator')</td>
                                <td>{{ $case->mfirstname }} {{ $case->mlastname }}</td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td>@lang('case.invitation_to_mediate')</td>
                                <td>
                                    <?php
                                if ($case->invitation) { ?>
                                    <table style="width: 100%">
                                        @foreach ($case->invitation as $key => $value)
                                            <?php $doc = 'storage/app/public/mediation/' . $case->id . '/' . $value->file_name; ?>
                                            <?php $cdate = new DateTime($value->created_at);
                                            $cdate = $cdate->format('d-m-Y'); ?>
                                            <tr>
                                                <td style="width: 5%;"><b>{{ $key + 1 }}</b></td>
                                                @if ($key == 0)
                                                    <td><a href="{{ url($doc) }}"
                                                            target="_blank">{{ $value->file_name }}</b></a></td>
                                                @else
                                                    <td><a href="{{ url($doc) }}"
                                                            target="_blank">{{ $value->file_name }}</b></a></td>
                                                @endif
                                                {{-- <td><a href="{{url($doc)}}" target="_blank">@lang('case.view')</a></td> --}}
                                                <td style="width: 15%;"><b>{{ $cdate }}</b></td>

                                            </tr>
                                        @endforeach
                                    </table>
                                    <?php  } else { ?>
                                    @lang('case.na')
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Mediator Appointment Letter</td>
                                <td>

                                    <?php
                                if ($case->appointment) {
                                    if($case->appointment->file_name_mediator_appointment != null) {
                                    $doc = 'storage/app/public/mediation/' . $case->id . '/' . $case->appointment->file_name_mediator_appointment;
                                    ?>
                                    <a href="{{ url($doc) }}" target="_blank">@lang('case.view')</a>
                                    <?php } else { ?>
                                    @lang('case.na')
                                    <?php } } else { ?>
                                    @lang('case.na')
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                        <?php if($case->documentPath != "NULL" && $case->documentPath != NULL){ $doc='storage/app/public/mediation/'.$case->id.'/'.$case->documentPath;
                                    ?>

                        <table class="table table-bordered">

                            <tr>
                                <th colspan="2">Supporting document (User)</th>
                            </tr>



                            <tr>
                                <td><?= basename($case->documentPath) ?></td>

                                <td>

                                    <a href="{{ url($doc) }}" class="btn btn-sm btn-success" target="_blank">View</a>

                                </td>
                            </tr>
                        </table>
                        <?php } ?>
                        <?php if (count($case->supporting_document) > 0) { ?>
                        <table class="table table-bordered">
                            <tr>
                                <th>@lang('case.supporting_documents')</th>
                                <th>Share With</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th></th>
                            </tr>

                            <tr>
                                <?php foreach ($case->supporting_document as $k => $v) { ?>
                                @if ($v->mediator_access == 1)
                                    <?php $userAccess = App\Models\InvoledUser::where('userPlanId', $case->id)->get(); ?>
                                    <td><?= basename($v->file_name) ?></td>
                                    <td>
                                        <?php $accessParty = explode(',', $v->access); ?>
                                        @foreach ($userAccess as $key => $item)
                                            @if ($item->name != null)
                                                @if (in_array($item->id, $accessParty))
                                                    <input type="checkbox" data-manageid={{ $v->id }} name="party[]"
                                                        disabled checked class="partyShare"
                                                        id="party{{ $v->id }}" value="{{ $item->id }}"
                                                        data-filepath="{{ $v->file_name }}">
                                                    <label class="form-check-label" for="party{{ $v->id }}">
                                                        {{ $item->name }} </label><br>
                                                @else
                                                    <input type="checkbox" data-manageid={{ $v->id }} name="party[]"
                                                        disabled class="partyShare" id="party{{ $v->id }}"
                                                        value="{{ $item->id }}" data-filepath="{{ $v->file_name }}">
                                                    <label class="form-check-label" for="party{{ $v->id }}">
                                                        {{ $item->name }} </label><br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $v->username }}</td>
                                    <td>{{ date('d-m-Y', strtotime($v->created_at)) }}</td>
                                    <td><a class="btn btn-sm btn-success" target="_blank"
                                            href="{{ url('storage/app/' . $v->file_name) }}">@lang('case.view')</a></td>
                                @endif
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
