@section('title', 'Case M' . sprintf('%06d', $case->id))
@extends('admin.layouts.app')


@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.casedetails')</a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Details for Case ID: M' . sprintf('%06d', $case->id))

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
                                    @foreach ($case->party as $key => $data)
                                        @if ($data->isClaimant == 0)
                                            @lang('case.name'): {{ $data->name }}<br>
                                            @lang('case.emai'): {{ $data->userEmail }}<br>
                                            @lang('case.phone'): {{ $data->userPhone }}<br>
                                            @lang('case.address'):
                                            @if ($data->address1 != null)
                                                <?= $data->address1 . ' ' . $data->address2 . ' ' . $data->city . ', ' . $data->pincode . ', ' . $data->state . ' ' . $data->country ?>
                                            @elseif($data->fulladdress)
                                                <?= $data->fulladdress ?>
                                            @else
                                                <?= $data->useraddress . ' ' . $data->useraddress1 . ' ' . $data->usercity . ', ' . $data->userpincode . ', ' . $data->userstate . ' ' . $data->usercountry ?>
                                            @endif
                                            <br> <br>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>@lang('case.responding_party')</td>
                                <td>

                                    <?php
                                foreach ($case->party as $key => $value) {
                                    if ($value->isClaimant != 0) {
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
                                        @if ($value->joinCode != '')
                                            JoinCode: {{ $value->joinCode }}<br>
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
                                    if ($value->isClaimant != 0) {
                                        ?>
                                    @if ($value->name == '')

                                        @if ($value->userEmail != '')
                                            @lang('case.emai'): {{ $value->userEmail }}<br>
                                        @endif
                                        @if ($value->userPhone != '')
                                            @lang('case.phone'): {{ $value->userPhone }}<br>
                                        @endif
                                        @if ($value->joinCode != '')
                                            JoinCode: {{ $value->joinCode }}<br>
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

                            @if ($case->discussion != null)
                                <tr>
                                    <td>Contact for Discussion</td>
                                    <td>{{ $case->discussion }}</td>
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
                                <td>Request Letter</td>
                                <td>
                                    <?php
                                if ($case->request_letter) {

                                    $doc = 'storage/app/public/mediation/' . $case->id . '/' . $case->request_letter;
                                    if(file_exists($doc)) {
                                    ?>
                                    <a href="{{ url($doc) }}" target="_blank">@lang('case.view')</a>
                                    <?php } else { ?>
                                    <a href="javascript:void(0);" data-url="{{ $case->request_letter }}"
                                        data-id="{{ $case->id }}" class="btn btn-success secureDownload"
                                        data-userid="{{ Auth::user()->id }}">Download</a>
                                    <?php } } else { ?>
                                    @lang('case.na')
                                    <?php } ?>
                                </td>
                            </tr>
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
                                                {{-- @if ($key == 0) --}}
                                                @if (file_exists($doc))
                                                    <td><a href="{{ url($doc) }}"
                                                            target="_blank"><b>{{ $value->file_name }}</b></a></td>
                                                @else
                                                    <td><a href="javascript:void(0);" data-url="{{ $value->file_name }}"
                                                            data-id="{{ $case->id }}" class="secureDownload"
                                                            data-userid="{{ Auth::user()->id }}"><b>{{ $value->file_name }}</b></a>
                                                    </td>
                                                @endif
                                                {{-- @else
                                                    <td><a href="{{ url($doc) }}"
                                                            target="_blank"><b>{{ $value->file_name }}</b></a></td> --}}
                                                {{-- @endif --}}
                                                {{-- <td><a href="{{url($doc)}}" target="_blank">@lang('case.view')</a></td> --}}
                                                <td style="width: 15%;"><b>{{ $cdate }}</b></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <?php  } else { ?>
                                    NA
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
                                    if(file_exists($doc)) {
                                    ?>
                                    <a href="{{ url($doc) }}" target="_blank">@lang('case.view')</a>
                                    <?php }
                                    else { ?>
                                    <a href="javascript:void(0);"
                                        data-url="{{ $case->appointment->file_name_mediator_appointment }}"
                                        data-id="{{ $case->id }}" class="btn btn-success secureDownload"
                                        data-userid="{{ Auth::user()->id }}">Download</a>
                                    <?php } } else { ?>
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

                                    @if (file_exists($doc))
                                        <a href="{{ url($doc) }}" class="btn btn-sm btn-success"
                                            target="_blank">View</a>
                                    @else
                                        <a href="javascript:void(0);" data-folder="user/supportingDocument"
                                            data-url="{{ $case->documentPath }}" data-id="{{ $case->id }}"
                                            class="secureDownload" data-userid="{{ Auth::user()->id }}">Download</a>
                                    @endif
                                    {{-- <a href="{{ url($doc) }}" class="btn btn-sm btn-success" target="_blank">View</a> --}}

                                </td>
                            </tr>
                        </table>
                        <?php } ?>
                        <?php if (count($case->supporting_document) > 0) { ?>
                        <table class="table table-bordered">
                            <tr>
                                <th>@lang('case.supporting_documents')</th>
                                <th>Share With</th>
                                <th>Share With Mediator?</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th></th>
                            </tr>

                            <tr>
                                <?php foreach ($case->supporting_document as $k => $v) { ?>

                                <td><?= basename($v->file_name) ?></td>
                                <?php $userAccess = App\Models\InvoledUser::where('userPlanId', $case->id)->get(); ?>

                                <td>
                                    <?php $accessParty = explode(',', $v->access); ?>
                                    @foreach ($userAccess as $key => $item)
                                        @if ($item->name != null)
                                            @if (in_array($item->id, $accessParty))
                                                <input type="checkbox" data-manageid={{ $v->id }} name="party[]"
                                                    checked class="partyShare" id="party{{ $v->id }}"
                                                    value="{{ $item->id }}" data-filepath="{{ $v->file_name }}">
                                                <label class="form-check-label" for="party{{ $v->id }}">
                                                    {{ $item->name }} </label><br>
                                            @else
                                                <input type="checkbox" data-manageid={{ $v->id }} name="party[]"
                                                    class="partyShare" id="party{{ $v->id }}"
                                                    value="{{ $item->id }}" data-filepath="{{ $v->file_name }}">
                                                <label class="form-check-label" for="party{{ $v->id }}">
                                                    {{ $item->name }} </label><br>
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div
                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" id="status_change_approvel{{ $v->id }}"
                                                name=="user_status" value="{{ $v->id }}"
                                                class="custom-control-input status_change_approvel"
                                                {{ $v->mediator_access == 1 ? 'checked' : '' }}
                                                data-filepath="{{ $v->file_name }}" data-caseid="{{ $case->id }}">
                                            <label class="custom-control-label"
                                                for="status_change_approvel{{ $v->id }}"> </label>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $v->username }}</td>
                                <td>{{ date('d-m-Y', strtotime($v->created_at)) }}</td>
                                <td>
                                    @if (file_exists('storage/app/' . $v->file_name))
                                        <a class="btn btn-sm btn-success" target="_blank"
                                            href="{{ url('storage/app/' . $v->file_name) }}">@lang('case.view')</a>
                                    @else
                                        <a href="javascript:void(0);" data-folder="supportingDocument"
                                            data-url="{{ $v->file_name }}" data-id="{{ $case->id }}"
                                            class="secureDownload" data-userid="{{ Auth::user()->id }}">Download</a>
                                    @endif

                                </td>
                            </tr>
                            <?php } ?>


                        </table>
                        <?php } ?>


                        <?php
                        if(!empty($case->mom) && count($case->mom)>0){
                         
                        
                        ?>
                            <table class="table table-bordered">

                                <tr>
                                    <th colspan="2">Minutes of the Meeting Documents</th>
                                </tr>
                                <?php
                                foreach($case->mom as $mom) {
                                    $mom_doc = 'storage/app/public/mediation/' . $case->id . '/' . $mom->file_name;
                                ?>

                                <tr>
                                    <td><?= basename($mom->file_name) ?></td>

                                    <td>

                                        @if (file_exists($mom_doc))
                                        <a href="javascript:void(0);" data-folder=""
                                                data-url="{{ $mom->file_name }}" data-id="{{ $case->id }}"
                                                class="secureDownload btn btn-sm btn-success" data-userid="{{ Auth::user()->id }}">Download</a>
                                        
                                        <a href="{{ url($mom_doc) }}" class="btn btn-sm btn-success" target="_blank" style="margin-left: 10px;">View</a>
                                        
                                        @endif
                                        
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        
                        <?php } ?>




                        @if ($case->PayLink != null)
                        <table class="table table-bordered">
                            <tr>
                                <td>Payment Link</td>
                                <td>
                                {{ $case->PayLink }}
                                </td>
                            </tr>
                            @if ($case->restructure_offer_1 != null)
                            <tr>
                                <td>Restructure Offer(s)</td>
                                <td>
                                 1 : {{ $case->restructure_offer_1 }} <br>
                                 @if ($case->restructure_offer_2 != null)
                                 2 : {{ $case->restructure_offer_2 }} <br>
                                 @endif
                                 @if ($case->restructure_offer_3 != null)
                                 3 : {{ $case->restructure_offer_3 }} 
                                 @endif
                                </td>
                            </tr>
                            @endif
                            @if (!empty($case->restructureFile))
                            <tr>
                                <td></td>
                                <td>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Restructure Date</th>
                                            <th>Restructure Letter</th>
                                        </tr>
                                        <tr>
                                            <td> <?php $restructureDate = new DateTime($case->restructureFile->created_at);
                                            $restructureDate = $restructureDate->format('d-m-Y'); ?>
                                            {{ $restructureDate}}</td>
                                            <td>  
                                                <a href="javascript:void(0);" data-folder=""
                                                        data-url="mediation_documents/medrestructure/{{$case->id}}/{{ $case->restructureFile->restructureFile }}" data-id="{{ $case->id }}"
                                                        class="btn btn-success secureDownloadDirect" data-userid="{{ Auth::user()->id }}">Download</a>
                                            </td>
                                        </tr>
                                    <table>
                                </td>
                            </tr>
                            @endif
                        </table>
                        @endif

                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(document).ready(function() {
            $(document).on('change', ".partyShare", function() {
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
                // console.log(manageid);
                // console.log("checkedId : ", checkedId);
                // console.log("uncheckedId : ", uncheckedId);

                $.ajax({
                    url: '{{ route('admin.users.docs_access_change') }}',
                    method: "post",
                    data: {
                        manageid: manageid,
                        checkedId: checkedId,
                        uncheckedId: uncheckedId,
                        filename_path: filename_path,
                        '_token': csrf
                    },
                }).done(function(data) {
                    // .reload()
                    // console.log(data);
                    location.reload();
                });
            });

            $(document).on('change', ".status_change_approvel", function() {
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
                    url: '{{ route('admin.users.mediator_access_change') }}',
                    method: "post",
                    data: {
                        'manageid': manageid,
                        'mediatorAccess': mediatorAccess,
                        'filename_path': filename_path,
                        'caseid': caseid,
                        '_token': csrf
                    },
                }).done(function(data) {
                    // .reload()
                    // console.log(data);
                    location.reload();
                });

                // console.log(mediatorAccess);

            });

            $(document).on("click", ".secureDownload", function() {
                var id = $(this).data("id");
                var filename = $(this).data("url");
                var userid = $(this).data("userid");
                var parentFolder = $(this).data("folder");
                var csrf = document.querySelector('meta[name="csrf-token"]').content;

                $.ajax({
                    url: '{{ route('downloadSecure') }}',
                    method: "POST",
                    data: {
                        id: id,
                        urlpath: filename,
                        parentFolder: parentFolder,
                        user_id: userid,
                        _token: csrf
                    },
                    xhrFields: {
                        responseType: "blob", // to avoid binary data being mangled on charset conversion
                    },
                    success: function(blob, status, xhr) {
                        // check for a filename
                        var filename = "";
                        var disposition = xhr.getResponseHeader("Content-Disposition");
                        if (disposition && disposition.indexOf("attachment") !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1])
                                filename = matches[1].replace(/['"]/g, "");
                        }

                        if (typeof window.navigator.msSaveBlob !== "undefined") {
                            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);

                            if (filename) {
                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");
                                // safari doesn't support this yet
                                if (typeof a.download === "undefined") {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }

                            setTimeout(function() {
                                URL.revokeObjectURL(downloadUrl);
                                swal({
                                    text: "Downloaded successfully!",
                                    title: "Thanks!",
                                    icon: "success",
                                }).then(function() {
                                    location.reload();
                                });
                            }, 100); // cleanup
                        }
                    },

                    error: function(err) {
                        console.log(err);
                    },
                });
            });

            $(document).on("click", ".secureDownloadDirect", function() {
                var id = $(this).data("id");
                var filename = $(this).data("url");
                var userid = $(this).data("userid");
                var parentFolder = $(this).data("folder");
                var csrf = document.querySelector('meta[name="csrf-token"]').content;

                $.ajax({
                    url: '{{ route('downloadSecure') }}',
                    method: "POST",
                    data: {
                        id: id,
                        urlpath: filename,
                        parentFolder: parentFolder,
                        user_id: userid,
                        _token: csrf
                    },
                    xhrFields: {
                        responseType: "blob", // to avoid binary data being mangled on charset conversion
                    },
                    success: function(blob, status, xhr) {
                        // check for a filename
                        var filename = "";
                        var disposition = xhr.getResponseHeader("Content-Disposition");
                        if (disposition && disposition.indexOf("attachment") !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1])
                                filename = matches[1].replace(/['"]/g, "");
                        }

                        if (typeof window.navigator.msSaveBlob !== "undefined") {
                            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);

                            if (filename) {
                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");
                                // safari doesn't support this yet
                                if (typeof a.download === "undefined") {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }

                            setTimeout(function() {
                                URL.revokeObjectURL(downloadUrl);
                                swal({
                                    text: "Downloaded successfully!",
                                    title: "Thanks!",
                                    icon: "success",
                                }).then(function() {
                                    location.reload();
                                });
                            }, 100); // cleanup
                        }
                    },

                    error: function(err) {
                        console.log(err);
                    },
                });
            });
        });
    </script>
@endsection
