@extends('admin.layouts.app')
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
    'SEND_ADDI_DOC_USER'=>'secondary',
    'SEND_ADDI_DOC_MED'=>'secondary',
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
                    if(count($allcase) == 1) {
                        $id = 'M'.sprintf('%06d',$allcase[0]);
                    } else {
                        sort($allcase);
                        $id = '<span class="badge-info badge total_res" style="position:absolute; top: -0.5em; left: -0.5em">'.count($allcase).'</span>';
                        $id .= '<select class="form-control-sm alert-'.$alertarr[$item->event].' mr-2">';
                        foreach($allcase as $key => $singleid) {
                            if($key == 0) {
                                $id .= '<option>M'.sprintf('%06d',$singleid).'</option>';
                            } else {
                                $id .= '<option disabled>M'.sprintf('%06d',$singleid).'</option>';
                            }
                        }
                        $id .= '</select>';
                    }
                @endphp
                @if ($sdate != date('d-m-Y',strtotime($item->created_at)))
                <div class="col-md-12">
                    <p><b>{{date('d-m-Y',strtotime($item->created_at))}}</b></p>
                </div>
                @endif
                {{-- {{dd($data)}} --}}
                <div class="col-md-12">
                    <div class="alert alert-{{$alertarr[$item->event]}}" role="alert">
                    @if($item->case_id != null) <?=$id?> @else @if($item->event == 'USER_REGI') User Id - @else Mediator Id - @endif {{$item->reg_id}}  @endif : @if($item->idescription != null) {{$item->idescription}} @else New @if($item->event == 'USER_REGI') User @else Mediator @endif Registration @endif
                    <span class="float-right">{{date('d-m-Y h:m:s A',strtotime($item->created_at))}}</span>
                </div>
                </div>
                <?php $sdate = date('d-m-Y',strtotime($item->created_at)); ?>

                @endforeach

            </div>
        </div>
    </div>

@endsection

<!-- Table datatable css -->

























