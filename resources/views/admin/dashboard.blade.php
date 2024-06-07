@extends('admin.layouts.app')


@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard </a></li>
<!-- end page title -->
@endsection
@section('content')

<style type="text/css">
    .widget-box-three{
      height: 186.50px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <h4 class="page-title"></h4>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-chart-areaspline display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">@lang('case.casesRegistered')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$allCasesCount}}</span> </h2>
                    <p class="text-muted mt-2 m-0">@lang('case.casesRegisteredDiscription')</p>
                </div>
            </div>
        </div>
    </div>

    <!-- end col -->

    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-layers display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">@lang('case.newCases')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$newCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">@lang('case.newDiscription')</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-av-timer display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">@lang('case.ongoingCases')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$ongoingCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">@lang('case.ongoingDiscription')</p>
                </div>

            </div>
        </div>
    </div>
    <!-- end col -->
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-av-timer display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">@lang('case.resolvedCases')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$resolvedCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">@lang('case.resolvedDiscription')</p>
                </div>

            </div>
        </div>
    </div>
    <!-- end col -->

</div>

<div class="row">
    <div class="col-lg-12">
        <h4 class="page-title"></h4>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-av-timer display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">Unresolved Cases</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$unresolvedCount}}</span> </h2>
                    <p class="text-muted mt-2 m-0">Number of unresolved case</p>
                </div>
            </div>
        </div>
    </div>

    <!-- end col -->

    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-av-timer display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">Withdrawn Cases</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$WithdrawnCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">Number of withdrawn case</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-av-timer display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">Rejected Cases</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$rejectedCount}}</span> </h2>
                    <p class="text-muted mt-2 m-0">Number of rejected case</p>
                </div>

            </div>
        </div>
    </div>
    <!-- end col -->
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-av-timer display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">@lang('case.respondingPartiesCases')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$respondingPartiesCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">@lang('case.respondingPartiesDiscription')</p>
                </div>

            </div>
        </div>
    </div>
    <!-- end col -->

</div>

<div class="row admindash">
    <!-- end col -->
    <div class="col-lg-12">
        <h4 class="page-title"></h4>
    </div>
    {{-- <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-av-timer display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">@lang('case.respondingPartiesCases')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$respondingPartiesCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">@lang('case.respondingPartiesDiscription')</p>
                </div>

            </div>
        </div>
    </div> --}}
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-account-convert display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">Approve @lang('case.users')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$approveUsersCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">Total number of approve users</p>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-account-convert display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">Unapprove @lang('case.users')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$unapproveUsersCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">Total number of unapprove users</p>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-account-convert display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">Approve @lang('case.mediator')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$approveMediatorCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">Total number of approve mediator</p>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-account-convert display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">Unapprove @lang('case.mediator')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$unapproveMediatorCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">Total number of unapprove mediator</p>
                </div>

            </div>
        </div>
    </div>
    
    <!-- end col -->
</div>



<!-------- Upcoming Session List ----------->
<div class="row">
    <div class="col-lg-12">
        <h4>Upcoming Sessions</h4>
            <div class="card-box table-responsive">
                <table id="upcoming" class="table table-striped table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <th>@lang('case.case_id') </th>
                                <th>Initiating Party(s)</th>
                                <th>Responding Party(s)</th>
                                <th>Mediator</th>
                                <th>Session Date & Time</th>
                                <th>Zoom Link</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                </table>
            </div>
    </div>
</div>
<!-------- Upcoming Session List ----------->


<!-- end end -->
@endsection




@section('head')
    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    
@endsection


@section('footer')



    <!-- <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script> -->

    <!-- Datatables init -->
    <!-- <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/custombox/custombox.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

    <script type="text/javascript">
        $(document).ready(function(){
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
           // alert(csrf);
            getUpcoming(csrf);
            
        });

        function getUpcoming(csrf){
            $.ajax({
                type: 'post',
                url: '{{ route('admin.case.getUpcomingSession') }}',
                data: {
                   '_token': csrf
                },
                success: function(data) {

                   
                    //var pdfButton = "";
                    if (data != "") {
                        // console.log(caseid);
                        //var link = '{{ route('admin.case.sessionPdf', '') }}' + '/' + caseid;
                        // console.log(link);
                        //pdfButton = "<a target='_blank' href='" + link +
                            //"' class='btn btn-success'><span>Download PDF</span></button>"
                        $('#upcoming tbody').html(data);
                        //$('#sessionShowBtn').html(pdfButton);
                    } else {
                        $('#upcoming tbody').html("No Session");
                        //$('#sessionShowBtn').html(pdfButton);

                    }
                }

            });
        }
    </script>
@endsection

















































