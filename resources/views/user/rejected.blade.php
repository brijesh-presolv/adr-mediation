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
<style>
    table tbody .btn,  table tbody td{
        font-size: 14px;
    }
    table tbody button {
        margin-top: 7px;
    }
    table tbody input[type='checkbox'] {
        margin: 15px;
        height: 12px;
    }
</style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Case ID</th>
                            <!-- <th>Ref ID</th> -->
                            <th>Date <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            <th>Case Details <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Case Details'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
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
                            <td><?= 'M' . sprintf('%06d', $value->caseid) ?>
                            <br><a href="{{ url('user/track/') }}/<?php echo $value->caseid; ?>" target="_blank" class="btn btn-secondary waves-effect  waves-light btn-sm" title="Track">Track</a>
                       
                            </td>
                            <?php /*
                            <td>
                                <?php
                                    if($value->ref_id == null){
                                        $ref_id = "--";
                                    } else {
                                        $ref_id = $value->ref_id; 
                                    }   
                                ?>
                            <?php echo $ref_id; ?></td>
                            */ ?>
                            <td><?= date('d-m-Y', strtotime($value->date)) ?></td>
                            <td><a class="btn   btn-sm btn-primary label label-success {{ count($value->party) > 0 ? '' : 'disabled' }}"
                                    target="_blank" href="{{ route('user.casedetails', $value->caseid) }}"><i class="mdi mdi-file-eye-outline"></i></a></td>


                            <td><?php
                            $d = "";
                            $d_ip = "<strong>Initiating Party(s) :</strong><br/>";
                            $d_rp = "<br/><strong>Responding Party(s) :</strong><br/>";
                            //dd($value->party);
                            foreach ($value->party as $key => $v) {
                                if($v->isOnboarded == 1){
                                    $class_name = "text-success";
                                } else {
                                    $class_name = "text-danger";
                                }
                                if($v->isClaimant == 0) {
                                    if($v->name != null) {
                                        $d_ip = $d_ip.'<span class="'.$class_name.'">'.$v->name.'</span></br>';
                                        }
                                } else {
                                    if($v->name != null) {
                                       $d_rp = $d_rp. '<span class="'.$class_name.'">'.$v->name.'</span></br>';
                                        }
                                }
                               
                            }
                            $d = $d . $d_ip . $d_rp;
                            echo $d;
                            ?></td>

                            <td>

                                <span class="">Rejected <br> At: {{ $value->casestatus->created }}</ </td>
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
