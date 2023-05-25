<?php
use App\Models\InvoledUser;
?>
@extends('user.layouts.app')
@section('title', 'Closed')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">Closed </a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Closed')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">

                <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Case ID</th>
                            <th>Ref ID</th>
                            <th>Date</th>
                            <th>Case Details</th>
                            <th>Party Details</th>
                            <th>Mediator</th>
                            <!-- <th>Comment</th> -->
                            <th>Session</th>
                            <th>Settelment Agreement</th>

                            <th>Status</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Share</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="commentForm" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="case_id" class="form-control">
                        <input type="hidden" name="type" class="form-control">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Comment:</label>
                            <textarea class="form-control" name="comment" required></textarea>
                        </div>
                        <div class="row" id="commentView" style="height: 200px;overflow-x: auto">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="commentModal-close"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">save comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade h-75" id="viewSession-modal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title text-white">Session Records</h4>
                    <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table" id="sessRecId">
                        <thead>
                            <th scope="col">S.No. </th>
                            <th scope="col">Scheduling done on:</th>
                            <th scope="col">Session scheduled for:</th>
                            <th scope="col">Zoom Id :</th>
                            <th scope="col">Note :</th>
                            <th scope="col">Party</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>Close</span>
                    </button>
                    <div id="sessionShowBtn" class="text-center"></div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawModalLabel">Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="withdrawreason"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="settelmentModal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title text-white">Settelment Agreemnet</h4>
                    <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>file</th>
                                <th>Upload By</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>Close</span>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


@endsection

<!-- Table datatable css -->
@section('head')

    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

@endsection


@section('footer')
    <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">
        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
        var userTable = $('#users').DataTable({
            "serverMethod": "POST",
            "sAjaxSource": '{{ route('user.case.json', $confirm_status) }}',
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000],
            ],
            "iDisplayLength": 10,
            "responsive": true,
            serverData: function(sSource, aoData, fnCallback, oSettings) {
                // aoData.append('token',token)

                oSettings = $.ajax({
                    dataType: "json",
                    type: "post",
                    // async: false,
                    crossDomain: true,
                    url: sSource,
                    data: aoData,
                    success: fnCallback

                });

            },
            "columns": [{
                    "data": "key",
                },
                {
                    "data": "case.caseid",
                    render: function(data) {
                        var button = "M" + pad(data, 6);
                        return button;
                    }
                },
                {
                    "data": "case.ref_id",
                    render: function(data, type, row, meta) {
                        if (data == null) {
                            var button = "";
                            button = button + `<p style="font-size: 16px;"> -- </p>`;
                            return button;
                        } else {
                            var button = "";
                            button = button + `<p style="font-size: 16px;">` + data + `</p>`;
                            return button;
                        }

                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "case.caseid",
                    render: function(data) {
                        var button = ` <a href="{{ url('user/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        return button;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";
                        for (i in data) {
                            if (data[i].isOnboarded == 1) {
                                if (data[i].name != null) {
                                    if (data[i].organization != null && data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .organization + `</span><br>`;
                                            
                                        /************ Added for IP Name **********************/
                                        var ip_name = data[i].name;
                                        if(ip_name != ""){
                                            d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + ip_name + `</span><br>`;
                                        }
                                        /************ Added for IP Name **********************/
                                    } else {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    }
                                }
                            } else {
                                if (data[i].name != null) {
                                    // d = d + `<span class="text-danger party_name" data-inid="` + data[i]
                                    //     .id + `" data-id="` + data[i].userId + `">` + data[i].name +
                                    //     `</span><br>`;
                                    if(data[i].isClaimant == 0){
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i].name + `</span><br>`;
                                    } else {
                                        d = d + `<span class="text-danger party_name" data-inid="` + data[i]
                                        .id + `" data-id="` + data[i].userId + `">` + data[i].name +
                                        `</span><br>`;
                                    }
                                }
                            }
                        }
                        return d;
                    }
                },
                {
                    "data": "case",
                    render: function(data, type, row) {
                        var button = "";
                        // return  date.toLocaleDateString('en-GB');
                        button = button + `<button  class="btn btn-info btn-sm">` +
                            data.mediator + ` </button>`;
                        if (data.mstatus == 0) {
                            button = button +
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                data.mstatus + `">@lang('case.status_pending')</span>`;
                        } else if (data.mstatus == 1) {
                            button = button +
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                data.mstatus + `">@lang('case.status_accepted')</span>`;
                            button = button +
                                `<br><a href="{{ url('user/consent-and-disclosures/') }}/` + data.caseid +
                                `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a> `;
                            button = button + `<br><span class="">` +
                                row.mediator_create_action_date + `</span>`;
                        } else {
                            button = button +
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                data.mstatus + `">@lang('case.status_rejected')</span>`;
                            // button = button + `<br><span class="badge badge-danger">Date of Rejection: `+row.mediator_action_date+`</span>`;
                        }

                        return button;
                    }
                },
                // {
                //     "data": "case",
                //     render: function(data, type, row) {
                //         var button = "";
                //         button = button +
                //             `<div class="position-relative"> <button type="button" data-type="0" data-typename="Share" data-id="` +
                //             data.caseid +
                //             `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">@lang('case.btn_share')</button>`;
                //         if (row.share_view_count !== 0) {
                //             button += ` <span class="badge  badge-danger share_unseen">` + row
                //                 .share_view_count + ` </span>`;
                //         }
                //         button += ` <span class="badge badge-success share_total">` + row.share_count +
                //             `</span></div>`;
                //         return button;
                //     }
                // },
                {
                    "data": "case.caseid",
                    render: function(data, type, row) {
                        var button = "";
                        button = button + ` <button id="Sessview` + data + `" value="` + data +
                            `"  data-id="` + data +
                            `"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal" title="@lang('case.btn_session_view')" ><span class="mdi mdi-file-eye-outline"></span></button>`;
                        return button;
                    }
                },
                {
                    "data": "casestatus",
                    render: function(data, type, row) {
                        var button = "";
                        if (data.status === 6) {
                            button = button + `<button value="` + row.case.caseid + `"  data-id="` + row
                                .case.caseid +
                                `" class="btn btn-success waves-effect btn-sm" data-toggle="modal" data-target="#settelmentModal">View</button>`;
                        } else if (data.status === 5) {
                            button = button + `<button value="Comment" data-withdraw="` + row.case
                                .withdraw +
                                `" data-toggle="modal" data-target="#withdrawModal" class="btn btn-success waves-effect btn-sm">Withdrawn</button>`;
                        } else if (data.status === 7) {
                            button = button + `<button value="Comment" data-withdraw="` + row.case
                                .withdraw +
                                `" data-toggle="modal" data-target="#withdrawModal" class="btn btn-danger waves-effect btn-sm">Unresolved</button>`;
                        }
                        return button;
                    }
                },
                {
                    "data": "casestatus",
                    render: function(data, type, row) {
                        var button = "";
                        if (data.status === 6) {
                            button = button + `<span class="">` + data.description +
                                `| @lang('case.At'): ` + data.created + `</span>`;
                        } else {
                            button = button + `<span class="">` + data.description +
                                `| @lang('case.At'): ` + data.created + `</span>`;
                        }
                        return button;
                    }
                }
            ],
        });

        $(document).ready(function() {




            $('#viewSession-modal').on('show.bs.modal', function(event) {



                var button = $(event.relatedTarget);


                var caseid = button.data('id');


                var csrf = document.querySelector('meta[name="csrf-token"]').content;


                $.ajax({
                    type: 'post',
                    url: '{{ route('user.sessions') }}',
                    data: {
                        caseid: caseid,
                        '_token': csrf
                    },
                    success: function(data) {
                        var pdfButton = "";
                        if (data != "") {
                            // console.log(caseid);
                            var link = '{{ route('user.case.sessionPdf', '') }}' + '/' + caseid;
                            // console.log(link);
                            pdfButton = "<a target='_blank' href='" + link +
                                "' class='btn btn-success'><span>Download PDF</span></button>"
                            $('#sessRecId tbody').html(data);
                            $('#sessionShowBtn').html(pdfButton);
                        } else {
                            $('#sessRecId tbody').html("No Session");
                            $('#sessionShowBtn').html(pdfButton);

                        }
                    }

                });

            });



            $('#withdrawModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var withdraw = button.data('withdraw');
                var modal = $(this)
                modal.find('#withdrawreason').text(withdraw);
            });

            $('#settelmentModal').on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var recipient = button.data('id');

                var csrf = document.querySelector('meta[name="csrf-token"]').content;



                $.ajax({
                    type: 'post',
                    url: '{{ route('user.viewSettelment') }}',
                    data: {
                        id: recipient,
                        '_token': csrf
                    },
                    success: function(data) {


                        $("#settelmentModal tbody").html('');
                        $("#settelmentModal tbody").append(data);
                        //$("#supportingDocumnet").datatable();
                    }
                });
                $('#caseIdF2').val(recipient);
            });






        });

        $('#commentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var typename = button.data('typename');
            var type = button.data('type');
            var modal = $(this);
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var urlpdf = '{{ route('user.case.commentPDF', '', '') }}' + '/' + id + '/' + type;

            $("#commentView").html("");
            $('#commentForm .modal-footer #DownLoadPdf').remove();

            $.ajax({
                type: 'post',
                url: '{{ route('user.case.comment_view') }}',
                data: {
                    type: type,
                    case_id: id,
                    _token: csrf
                },
                success: function(data) {
                    for (i in data) {
                        //console.log(data[0]);
                        if (data[i].username == '{{ Auth::user()->username }}') {
                            var msg = `<div class="col-md-12 text-right border-top">
                                <div class="row">
                                                <div class="col-md-4 text-left"><small class="text-muted">` + data[i]
                                .created + `</small></div>
                                                <div class="col-md-8"><small class="text-muted">` + data[i].username + `</small></div>
                                    </div>           
                                    <p>` + data[i].comment + `</p>
                            </div>`;
                            $("#commentView").append(msg);
                        } else {
                            var msg = `<div class="col-md-12 border-top">
                                <div class="row">
                                                <div class="col-md-8"><small class="text-muted">` + data[i].username + `</small></div>
                                                <div class="col-md-4 text-right"><small class="text-muted">` + data[i]
                                .created + `</small></div>
                                    </div>           
                                    <p>` + data[i].comment + `</p>
                            </div>`;
                            $("#commentView").append(msg);
                        }
                    }
                    if (data[i] != null) {
                        $('#commentForm .modal-footer').append("<a href=" + urlpdf +
                            "><button type='button' class='btn btn-success' id='DownLoadPdf'>Download Comment</button></a>"
                        );
                    }
                }
            });

            modal.find('#commentModalLabel').text(typename);
            modal.find('.modal-body input[name="type"]').val(type);
            modal.find('.modal-body input[name="case_id"]').val(id);
        });

        $('#commentModal-close').on('click', function() {
            location.reload();
        });

        $('#commentForm').on('submit', function(e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "add this comment!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('user.case.comment') }}',
                        data: $('#commentForm').serialize(),
                        success: function() {
                            swal("comment save successfully!", {
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                            $('#commentForm')[0].reset();
                            $('#commentModal').modal("hide");
                        },
                        error: function(data) {
                            swal(data.responseJSON.errors.comment[0], {
                                icon: "error",
                            });
                        }
                    });
                } else {
                    swal("comment not added!");
                }
            });
            return false;
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
    </script>

@endsection
