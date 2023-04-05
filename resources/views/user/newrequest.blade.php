<?php
use App\Models\InvoledUser;
?>
@extends('user.layouts.app')
@section('title', 'Pending')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('site.Home')</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('site.Pending') </a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Pending')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <!-- <h4 class="header-title"><b>New Request</b></h4> -->
            <div class="row">
                <div class="col-md-12">
                        <button class="btn btn-primary btn-sm" data-target="#myModalbupld" data-toggle="modal"> Bulk Upload</button>
                  <br>
                  <br>
                </div>
           </div>
           <div id="myModalbupld" class="mdladcm modal fade " role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">

                        <div class="blkfrmdiv">
                            <h3>Upload .csv file</h3>
                           <form enctype="multipart/form-data" method="post" action="{{route('user.bulkUpload')}}">
                            {{ csrf_field() }}
                                    <input type="hidden" name="token" id="token_input">

                                    <input type="hidden" name="claimant" value="{{auth()->user()->id}}">

                                     <input type="hidden" name="uploaded_by" value="{{auth()->user()->id}}" />

                                <div class="form-group">
                                    <input type="file" name="csv" id="fileInput" onchange="" class="col-md-12 dropify" data-allowed-file-extensions="csv" required="" data-max-file-size="20M" />
                                </div>

    <input type="Submit"  value="Submit" class="btn btn-primary blkupdbtnsb">
    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
        <span>@lang('case.btn_close')</span>
   </button>
                            </form>

                        </div>
                          {{-- <div class="loading_form" style="display: none;">
                        <center>

                            </center>
                        <center><p>Please Wait. Do Not Close Until Close Button Appear.</p></center>

                        <div style="height: 200px;
        overflow-y: scroll;" id="mess">

                        </div>
                        <!-- <a>Close</a> -->
                    </div> --}}

                    </div>
                </div>

            </div>
        </div>
            <table  id="users" id="" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('case.Sr. No')</th>
                        <th>@lang('case.case_id')</th>
                        <th>@lang('case.date')</th>
                        <th>@lang('case.case_details')</th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('case.status_logs')</th>
                        <th>@lang('Supporting Document')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

 <!-- Table datatable css -->
@section('head')

    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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
            "sAjaxSource": '{{ route('user.case.jsonnew') }}',
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
                    "data": "case.userPlanId",
                    render: function(data) {
                        var button = "M" + pad(data, 6);
                        return button;
                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "case",
                    render: function(data, type, row) {
                        if(row.party.length>0) {
                            var button = ` <a href="{{ url('user/casedetails/') }}/` + data.userPlanId +
                            `" target="_blank" class="btn btn-sm btn-primary label label-success" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        } else {
                            var button = `<a class="btn btn-sm btn-primary label label-success disabled"><i class="mdi mdi-file-eye-outline"></i></a>`;
                        }
                        
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
                        var button = `<span class="badge badge-danger">@lang('site.Pending')</span>`;
                        return button;
                    }
                },
                {
                    "data": "case",
                    render: function(data, type, row) {
                        var button = "";
                        if (data.documentPath !== 'NULL' && data.documentPath !== "") {
                            button = button + `<p class="btn btn-success btn-sm">`+data.documentPath+`</p>`;
                        } else {
                            button = button + `<form action="{{ url('user/uploaddocument/') }}/` + data.userPlanId + `"  method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input class="form-control" type="file" id="document" name="document" data-allowed-file-extensions="pdf zip rar"  data-max-file-size="20M"></input>
                                <p>*Only Pdf zip and rar file allowed</p>
                                <input type="submit" class="btn btn-primary btn-sm" id="upload" value="Upload">
                                </form>`;
                        }
                        return button;
                    }
                },
            ],
        });

     $(document).ready(function(){
        $('.dropify').dropify();

        <?php if(session()->has('success')) {?>
        swal({
            title: '{{session()->get("success")}}',
            // text: "Withdraw case!",
            icon: "success",
            buttons: true,
        }).then(function() {
    window.location ="{{route('user.newrequest')}}"});

    <?php } if(session()->has('error')) {?>
        swal({
            title: "Error",
            text: '{{session()->get("error")}}',
            icon: "error",
            buttons: true,
            dangerMode: true,
        }).then(function() {
    window.location ="{{route('user.newrequest')}}"});
    <?php } ?>

        <?php if($response=='success'){ ?>

             swal("Success", "Form has been submitted", "success").then(function() {
    window.location ="{{route('user.newrequest')}}"

});

<?php } ?>



    //withdraw the case

            $('#withdrawForm').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Withdraw case!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{ route("user.case.withdraw") }}',
                    data: $('#withdrawForm').serialize(),
                    success: function (data) {


                        console.log(data);
                        // alert('form was submitted');
                        //userTable.ajax.reload();


                        swal("withdraw successfully!", {
                            icon: "success",
                        });
                        $('#withdrawModal').modal("hide");
                    },
                    error:function(err){

                        console.log(err);
                    }
                });
            } else {
                swal("Cancle Withdraw Request!");
            }
        });
        return false;
    });

            $('#withdrawModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var modal = $(this)
        modal.find('.modal-body input[name="case_id"]').val(recipient);
    });

            });
    </script>



@endsection('footer')

