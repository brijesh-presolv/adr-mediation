@extends('admin.layouts.app')
@section('title', 'Track M' . sprintf('%06d', $id))


@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">Track</a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Track for Case ID: M' . sprintf('%06d', $id))

@section('content')

    <?php
    
    // function mapEmail($d, $e)
    // {
    //     if ($d['user1email'] == $e) {
    //         return ['type' => 'Claimant', 'name' => $d['user1name'], 'id' => 1];
    //     } elseif ($d['user2email'] == $e) {
    //         return ['type' => 'Respondent', 'name' => $d['user2name'], 'id' => 2];
    //     } elseif ($d['arbemail'] == $e) {
    //         return ['type' => 'Arbitrator', 'name' => $d['arbname'], 'id' => 3];
    //     } else {
    //         if ($d['OtherEmail'] != '') {
    //             if (in_array($e, explode(',', $d['OtherEmail']))) {
    //                 return ['type' => 'Other Respondent', 'name' => $d['Other Respondent'], 'id' => 2];
    //             }
    
    //             return ['type' => 'Claimant', 'name' => $d['user1name'], 'id' => 1];
    //         }
    //     }
    // }
    ?>
    <div class="card bg-info text-light">
        <p class="m-2">The details pertaining to the delivery and service of all digital communications throughout the
            proceedings is tracked and obtained through Email and WhatsApp APIs (Application Programming Interface) integrated with Presolv360’s ODR platform.</p>
    </div>
    
    <!--- Added for disabled ITM notifications : START ------------->
    <div class="card bg-info text-light">
        <p class="m-2">Notfications {{$parties}}</p>
    </div>
    <!--- Added for disabled ITM notifications : END ------------->
    
    <div class="card">

        <div class="card-body">
            <h5>WhatsApp Track </h5>
            <section>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table id="whatsappTrack" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Event Title</th>
                                    <th>Event Description</th>
                                    <th>Event Date</th>
                                    <th>Message</th>
                                    <th>Media</th>

                                    <th>Name</th>
                                    <th>Mobile No.</th>
                                    <th>Status</th>
                                    <th>Date</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($whatsapp as $key => $subvalue)
                                    @foreach ($subvalue as $key => $value)
                                        <tr>
                                            <td></td>
                                            <td>
                                                @if ($key == 0)
                                                    {{ $value->title }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key == 0)
                                                    {{ $value->whdescription }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key == 0)
                                                    <?php $date = new DateTime($value->created_at); ?> {{ $date->format('d-m-Y H:i:s') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key == 0)
                                                    @if ($value->content != '')
                                                        <button class="btn btn-primary viewmsg" data-toggle="modal"
                                                            data-target="#myModal230"
                                                            data-msg="{{ str_replace(['::', ';;'], ['‘', '’'], $value->content) }}">View</button><br><br>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key == 0)
                                                    @if ($value->media != '')
                                                        @if (stripos(get_headers($value->media)[0], '200 OK'))
                                                            <a class="btn btn-primary" href="{{ $value->media }}">View</a>
                                                        @else
                                                            <a href="javascript:void(0);" data-fullurl="{{ $value->media }}"
                                                                data-id="{{ $value->caseid }}"
                                                                class="btn btn-primary secureDownload"
                                                                data-userid="{{ Auth::user()->id }}">View</a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td></td>

                                            <td>{{ $value->contact }}</td>
                                            <td>{{ ucfirst($value->wlstatus) }}</td>
                                            <td><?php
                                            // date_default_timezone_set('Asia/Kolkata');
                                            if ($value->updated_time != null) {
                                                $time = new DateTime($value->updated_time, new DateTimeZone('UTC'));
                                                $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                            } else {
                                                $time = new DateTime($value->sent_time, new DateTimeZone('UTC'));
                                                $time->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                            }
                                            ?> {{ $time->format('d-m-Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach



                            </tbody>
                        </table>

                    </div>
                </div>
            </section>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Email Track </h5>

            <section>
                <div class="row">
                    <div class="col-md-12 table-responsive">

                        <table id="emailTrack" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Event Title</th>
                                    <th>Event Description</th>
                                    <th>Event Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Date</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($email as $key => $subvalue)
                                    {{-- {{dd($value)}} --}}
                                    {{-- <?php $date = new DateTime($value->created_at); ?> --}}
                                    @foreach ($subvalue as $key => $value)
                                        <tr>
                                            <td></td>
                                            <td>
                                                @if ($key == 0)
                                                    {{ $value->title }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key == 0)
                                                    {{ $value->description }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($key == 0)
                                                    <?php $date = new DateTime($value->created_at); ?> {{ $date->format('d-m-Y H:i:s') }}
                                                @endif
                                            </td>
                                            <td></td>
                                            <td>{{ $value->edemail }}</td>
                                            <td>
                                                {{ $value->edevent }}
                                                @if ($value->edevent == 'click')
                                                    <br><span title='{{ $value->url }}'
                                                        style='cursor: pointer;color: green;'><b>Link</b></span>
                                                    <br />
                                                @endif
                                            </td>

                                            <td>{{ date('d-m-Y H:i:s', $value->timestamp) }}</td>


                                        </tr>
                                    @endforeach
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </section>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <h5>Courier Track</h5>

            </div>

            <div class="row">
                <div class="col-md-12 table-responsive">

                    <table id="courierTrack" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                                <th>Notice Id</th>
                                <th>Status</th>
                                <th>Status As On Date</th>
                                <th>Status At</th>
                                <th>Last Activity</th>
                                <th>Reason</th>
                                <th>Final Status</th>
                                <th>Type</th>
                                <th>Courier PDF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courierCsv as $key => $value)
                                <tr>
                                    <td>{{ $value->noticeId }}</td>
                                    <td>{{ $value->status }}</td>
                                    <td>{{ $value->status_as_on_date }}</td>
                                    <td>{{ $value->status_at }}</td>
                                    <td>{{ $value->last_activity }}</td>
                                    <td>{{ $value->reason }}</td>
                                    <td>{{ $value->final_status }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td>
                                        @if ($value->file_name != null)
                                            <a href="javascript:void(0);" data-folder="courier_pdf"
                                                data-url="{{ $value->file_name }}" data-id="{{ $value->case_id }}"
                                                class="secureDownload"
                                                data-userid="{{ Auth::user()->id }}"><b>Download</b></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>


    <!--------------- SMS Track -------------------------------------->
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <h5>SMS Track</h5>

            </div>

            <div class="row">
                <div class="col-md-12 table-responsive">

                    <table id="smsTrack" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                            <th>Event Title</th>
                                <th>Event Description</th>
                                <th>Event Date and Time</th>
                                <th>Message</th>
                                <!-- <th>Name</th> -->
                                <th>Mobile No.</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                                           $smsevent='';
                                           $smstitle='';
                                           $smsdesc='';
                                           $dpmsg='';
                                            foreach($sms as $key => $value){
                                                
                                              //  $smsdata=mapEmail($value->contact, 0);
            
                                               // $smdata=getEventDetails($render_array['eventCodes'],$value->event,$smsdata['id']);
                                         ?>
                           
                                <tr>
                                <td>
                                    <?php
                                    // if (!empty($smdata)) {
                                    //     if (isset($smdata->title) && $smdata->title != $smstitle) {
                                    //         echo $smdata->title;
                                    //     }
                                    //     $smstitle = $smdata->title;
                                    // } else {
                                    //     $smstitle = '';
                                    // }
                                    echo $value->event;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // if (!empty($smdata)) {
                                    //     if (isset($smdata->smsdescription)) {
                                    //         if ($smdata->smsdescription != $smsdesc) {
                                    //             echo $smdata->smsdescription;
                                    //         }
                                    
                                    //         $smsdesc = $smdata->smsdescription;
                                    //     } else {
                                    //         $smsdesc = '';
                                    //     }
                                    // } else {
                                    //     $smsdesc = '';
                                    // }
                                    echo $value->content;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($smsevent != $value->event) {
                                        $date = new DateTime($value->created_at);
                                        echo $date->format('d-m-Y H:i:s');
                                        $smsevent = $value->event;
                                    }
                                    ?>
                                </td>

                                <td>

                                    <?php
                                                if($value->content!='' and $dpmsg!=$value->request_id){
                                              $dpmsg=$value->request_id;
                                                 ?>
                                    <button class="btn btn-primary viewmsg" data-toggle="modal"
                                        data-target="#myModal230"
                                        data-msg="<?= str_replace(['::', ';;'], ['‘', '’'], $value->content) ?>">View</button>
                                    <?php }?>
                                </td>


                                <!-- <td>
                                    <?php
                                    //echo "test";
                                    ?>
                                </td> -->
                                <td>
                                    <?= $value->contact ?>
                                </td>
                                <td>

                                    <?php if (ucfirst($value->smstatus) === '1') {
                                        echo 'Sent';
                                    } else {
                                        echo 'Failed';
                                    } ?>


                                </td>
                                <td>
                                    <?php $date = new DateTime($value->smsdate);
                                    echo $date->format('d-m-Y H:i:s');
                                    ?>
                                </td>
                                </tr>
                                <?php  } ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
    <!--------------- SMS Track -------------------------------------->



    <div class="card">
        <div class="card-body">

            <div class="d-flex">
                <h5>IVR Log</h5>

            </div>



            <!-- <h3>IVR Log</h3> -->
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table id="ivrcase" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>Sr. no.</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Date</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($ivr as $key => $value)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $value['phone'] }}</td>
                                    <td>{{ $value['status'] }}</td>
                                    <td><?php $date = new DateTime($value['created_at']);
                                    $i++; ?> {{ $date->format('d-m-Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <h5> Bot MIS Track</h5>

            </div>

            <div class="row">
                <div class="col-md-12 table-responsive">

                    <table id="BotMISTrack" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                        <thead>
                            <tr>
                                <th>Why did i get this? (Button click)</th>
                                <th>Pay now (Button click) </th>
                                <th>Explore alternatives (Button click)</th>
                                <th>Restructure (Link click)</th>
                                <th>Restructure (Offer selected)</th>
                                <th>Reply (Link click)</th>
                                <th>Reply submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($botMisReport)>0)
                                <tr>
                                    <td>{{ $botMisReport[0]->Whatsapp_Why_Count }}</td>
                                    <td>{{ $botMisReport[0]->Whatsapp_PayNow_Count }}</td>
                                    <td>{{ $botMisReport[0]->Whatsapp_Explore_Alternatives_Count }}</td>
                                    <td>{{ $botMisReport[0]->Whatsapp_Restructure_Link_Count }}</td>
                                    <td>{{ $botMisReport[0]->Restructure_Option }}</td>
                                    <td>{{ $botMisReport[0]->Web_Submit_Reply_Count }}</td>
                                    <td>{{ $botMisReport[0]->Reply }}</td>
                                </tr>
                                @endif
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

    <div id="myModal230" class="logmodal modal fade " role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Message</h4>

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body msgBody">
                    <span>

                    </span>
                </div>
            </div>

        </div>
    </div>

<?php 

function mapEmail($e, $r)
{
    if (array_key_exists($e, $r)) {
        if ($r[$e]['role'] == 'Claimant') {
            return ['type' => 'Claimant', 'name' => $r[$e]['name'], 'id' => 1];
        }

        if ($r[$e]['role'] == 'Respondent') {
            return ['type' => 'Respondent', 'name' => $r[$e]['name'], 'id' => 2];
        }

        if ($r[$e]['role'] == 'Arbitrator') {
            return ['type' => 'Arbitrator', 'name' => $r[$e]['name'], 'id' => 3];
        }

        if ($r[$e]['role'] == 'Lawyer') {
            return ['type' => 'Respondent', 'name' => 'Authorized Representative', 'id' => 2];
        }

        if ($r[$e]['role'] == 'Other Respondent') {
            return ['type' => 'Respondent', 'name' => $r[$e]['name'], 'id' => 2];
        }

        if ($r[$e]['role'] == 'Other Claimant') {
            return ['type' => 'Claimant', 'name' => $r[$e]['name'], 'id' => 1];
        }
    }

        return ['type' => 'Respondent', 'name' => 'Other Respondent', 'id' => 2];
}

function getEventDetails($e, $ev, $t)
{
    foreach ($e as $v) {
        if ($v->type == "$t" and $v->code == "$ev") {
            return $v;
        }
    }
}
?>

    {{-- <script type="text/javascript">
function xyz($) {
    $(document).ready( function() {



$('.viewmsg').on('click',function(){
    console.log($(this).data('msg'));


$('#myModal230 .modal-body span').empty();

$('#myModal230 .modal-body span').append( $(this).data('msg'));

});
})
}
</script> --}}


@endsection

@section('footer')
    <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>

    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="{{ url('/') }}/assets/js/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('.viewmsg').on('click', function() {
                // console.log($(this).data('msg'));


                $('#myModal230 .modal-body span').empty();

                $('#myModal230 .modal-body span').append($(this).data('msg'));



            });

            $(document).on("click", ".secureDownload", function() {
                var id = $(this).data("id");
                var filename = $(this).data("url");
                var userid = $(this).data("userid");
                var parentFolder = $(this).data("folder");
                var csrf = document.querySelector('meta[name="csrf-token"]').content;
                var fullurl = $(this).data("fullurl");

                console.log("filename", filename);
                console.log("parentFolder", parentFolder);
                console.log("fullurl", fullurl);


                $.ajax({
                    url: '{{ route('downloadSecure') }}',
                    method: "POST",
                    data: {
                        id: id,
                        urlpath: filename,
                        parentFolder: parentFolder,
                        user_id: userid,
                        fullurl: fullurl,
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

            $('#whatsappTrack').DataTable({
                "responsive": true,
                "aaSorting": []
            });

            $('#emailTrack').DataTable({
                "responsive": true,
                "aaSorting": []
            });

            $('#ivrcase').DataTable({
                "responsive": true,
                "aaSorting": []
            });

            $('#courierTrack').DataTable({
                "responsive": true,
                "aaSorting": []
            });

            $('#smsTrack').DataTable({
                "responsive": true,
                "aaSorting": []
            });

            $('#BotMISTrack').DataTable({
                "responsive": true,
                "aaSorting": []
            });

        });
    </script>

@endsection
