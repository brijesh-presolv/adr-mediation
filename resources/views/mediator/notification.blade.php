@extends('mediator.layouts.app')
@section('title', "Notification")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Notification</a></li>
<!-- end page title -->
@endsection
@section('page_title', "Notification")

@section('content')
<?php 
use App\Models\Mediators_mediation_cases_status;

$sevid='';

$sdate='';


$alertarr=[
    'USER_REGI'=>'success',
    'MED_REGI'=>'success',
    'MEDI_ADD_ADM'=>'success',
    'ACPTARB_ADM'=>'primary',
    'SEND_APPO_MED'=>'success',
    'REJECTED_ADM'=>'danger',
    'REJECTED_MED'=>'danger',
    'SEND_SETT_AGRE_ADMIN'=>'secondary',
    'SEND_SETT_AGRE_MED'=>'secondary',
    'SEND_ADDI_DOC_ADMIN'=>'secondary',
    'SEND_ADDI_DOC_MED'=>'secondary',
    'SEND_ADDI_DOC_USER' => 'secondary',
    'WDRN_BY_ADMIN'=>'danger',
    'ONBOAR_USER'=>'info',
    'SUBMIT_FORM'=>'info',
    'RES_BY_MED'=>'info',
    'RES_BY_ADMIN'=>'info',
    'UNRES_BY_MED'=>'info',
    'UNRES_BY_ADMIN'=>'info',    
    'SESS_SCHE_ADMIN'=>'primary',
    'SESS_SCHE_MED'=>'primary',
    'COMM_ADM_SHARE'=>'success',
    'COMM_USER_SHARE'=>'success',
    'COMM_MED_SHARE'=>'success',
    'COMM_MED_PRIVATE'=>'success',
    'COMM_ADM_PRIVATE'=>'success',

];

?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                @foreach ($data as $item)
                @php
                    $allcase = explode(',', $item->case_id);
                    sort($allcase);
                    $adddata = array();
                    $id = "";
                    foreach($allcase as $key => $singleid) {
                        $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $singleid)
                                // ->where("mediators_mediation_cases_status.status", "=", 1)
                                ->first();
                        if(isset($mediator)) {
                            if($mediator->id == Auth::user()->id) {
                                $adddata[] = $singleid;
                            }
                        }
                    }
                    if(!empty($adddata)) {
                        if(count($adddata) == 1) {
                            $id = 'M'.sprintf('%06d',$adddata[0]);
                            
                        } else {
                            sort($adddata);
                            $id = '<select class="form-control-sm alert-'.$alertarr[$item->event].' mr-2">';
                            foreach($adddata as $key => $singleid) {
                                // $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                                //     ->where("mediators_mediation_cases_status.mediation_case_id", "=", $singleid)
                                //     // ->where("mediators_mediation_cases_status.status", "=", 1)
                                //     ->first();
                                // dd(Auth::user()->id);
                                // if(isset($mediator)) {
                                    // if($mediator->id == Auth::user()->id) {
                                if($key == 0) {
                                    $id .= '<option>M'.sprintf('%06d',$singleid).'</option>';
                                } else {
                                    $id .= '<option disabled>M'.sprintf('%06d',$singleid).'</option>';
                                }
                                    // }
                                // } 
                            }
                            $id .= '</select>';
                        }
                    } 
                @endphp
                @if($id != "")
                @if ($sdate != date('d-m-Y',strtotime($item->created_at)))
                <div class="col-md-12">
                    <p><b>{{date('d-m-Y',strtotime($item->created_at))}}</b></p>
                </div>
                @endif
                {{-- {{dd($data)}} --}}
                
                <div class="col-md-12">
                    <div class="alert alert-{{$alertarr[$item->event]}}" role="alert">
                        <?=$id?> : {{$item->idescription}}
                        <span class="float-right">{{date('d-m-Y h:m:s A',strtotime($item->created_at))}}</span>
            
                    </div>
                    
                </div>
                <?php $sdate = date('d-m-Y',strtotime($item->created_at)); ?>
                @endif
                @endforeach

            </div>
        </div>
    </div>

@endsection

<!-- Table datatable css -->

























