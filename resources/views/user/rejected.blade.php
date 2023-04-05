<?php
use App\Models\InvoledUser;
?>
@extends('user.layouts.app')
@section('title', 'Rejected')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">Rejected</a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Rejected')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Case Id</th>
                            <th>Date</th>
                            <th>Case Details</th>
                            <th>Party Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                    $i=1;
                    $id='';

                    foreach ($closed as $key => $value) {

                     ?>
                        <tr>

                            <?php
                            
                            if ($value->caseid == $id) {
                                continue;
                            }
                            
                            $id = $value->caseid;
                            
                            ?>
                            <td>{{ $i++ }}</td>
                            <td><?= 'M' . sprintf('%06d', $value->caseid) ?></td>
                            <td><?= date('d-m-Y', strtotime($value->date)) ?></td>
                            <td><a class="btn   btn-sm btn-primary label label-success {{ count($value->party) > 0 ? '' : 'disabled' }}"
                                    target="_blank" href="{{ route('user.casedetails', $value->caseid) }}">View</a></td>


                            <td><?php
                            
                            foreach ($value->party as $key => $v) {
                                if ($v->isOnboarded == 1) {
                                    if ($v->name != '') {
                                        if ($v->organization != null && $v->isClaimant == 0) {
                                            echo '<span class="text-success">' . $v->organization . '</span></br>';
                                            /************ Added for IP Name **********************/
                                            if ($v->name != '') {
                                                echo '<span class="text-success">' . $v->name . '</span></br>';
                                            }
                                            /************ Added for IP Name **********************/
                                        } else {
                                            echo '<span class="text-success">' . $v->name . '</span></br>';
                                        }
                                    }
                                } else {
                                    if ($v->name != '') {
                                    //     echo '<span class="text-danger">' . $v->name . '</span></br>';                          
                                    if ($v->isClaimant == 0) {
                                        echo '<span class="text-success">' . $v->name . '</span></br>';
                                    } else {
                                        echo '<span class="text-danger">' . $v->name . '</span></br>';
                                    }
                                }
                                }
                            }
                            
                            ?></td>

                            <td>

                                <span class="badge badge-danger ">Rejected | At: {{ $value->casestatus->created }}</ </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
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
        $(document).ready(function() {

            $('#users').DataTable();









        });
    </script>

@endsection
