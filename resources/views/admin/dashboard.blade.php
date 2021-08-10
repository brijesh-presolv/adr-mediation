@extends('admin.layouts.app')


@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard </a></li>
<!-- end page title -->
@endsection
@section('content')

<div class="row">
    <!-- end col -->
    <div class="col-lg-12">
        <h4 class="page-title">@lang('case.cases')</h4>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card widget-box-three">
            <div class="card-body">
                <div class="float-right mt-2">
                    <i class="mdi mdi-account-convert display-3 m-0"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-uppercase font-weight-medium text-truncate mb-2">@lang('case.users')</p>
                    <h2 class="mb-0"><span data-plugin="counterup">{{$usersCount}}</span></h2>
                    <p class="text-muted mt-2 m-0">@lang('case.usersDiscription')</p>
                </div>

            </div>
        </div>
    </div>
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

<div class="row">
    <div class="col-lg-12">
        <h4 class="page-title">@lang('case.cases')</h4>
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
<!-- end end -->
@endsection

















































