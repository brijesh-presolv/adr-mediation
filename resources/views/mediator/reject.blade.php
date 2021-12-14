<?php
use App\Models\InvoledUser;
?>

@extends('mediator.layouts.app')
@section('title', 'Rejected')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Rejected-Case </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="header-title"><b>Rejected Case</b></h4>
            <table  id="datatable" id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr.No</th>
                        <th>Case Id</th>
                        <th>Date</th>
                        <th>Party Details</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                   <!--  <tr>
                        <td>1</td>
                        <td>MD000200</td>
                        <td>12/04/2021</td>
                        <td>Party 1 <br>
                            Party 2 <br>
                            Party 3 <br>
                        </td>
                        <td>rejected<br>
                            DateOfReject
                        </td>
                    </tr> -->

                      <?php $sno = 1; ?>
                    @foreach($rejected_case as $data)  

                    <input type="hidden" name="" id="createdBy" value="{{ $data->mediator_id }}">

                    <tr>
                        <td>{{ $sno }}</td>
                        <td>{{"M" . sprintf("%06d", $data->mediation_case_id)}}</td>
                        <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
                        
                        <td>
                            <?php 
                            $invuser=InvoledUser::select('name','isOnboarded')->where(['userPlanid'=>$data->mediation_case_id])->get();
                            foreach ($invuser as $key => $value) {

                            if($value->isOnboarded==1){
                                if($value->name != null) {
                                echo '<span class="text-success">'.$value->name.'</span></br>';
                                }
                            } else{
                                if($value->name != null) {
                                echo '<span class="text-danger">'.$value->name.'</span></br>';
                                }
                            }   
                        }
                        ?>
                        </td>
                        <td><span class="text-danger">Rejected</span><br>{{ $data->updated_at }}</td>
                    </tr>
                     <?php $sno++ ?>  
                     @endforeach
                </tbody>
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

@endsection