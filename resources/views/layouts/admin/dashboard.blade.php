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


<!-- end end -->
@endsection

















































