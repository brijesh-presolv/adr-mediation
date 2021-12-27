@extends('mediator.layouts.app')
@section('title', 'Profile')

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Profile </a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">update </a></li>
<!-- end page title -->
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            @if(Session::has('key'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('key') }}</p>
                @endif
            <div class="card-header">
                <h3>Profile Update</h3>
            </div>
            <form action="{{ route('mediator.profile_save') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            @if(Auth::user()->isDone==1 && Auth::user()->status==0)
                            <div class="alert alert-warning" role="alert">
                                @lang('user.account_under_review')
                            </div>
                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="firstname">@lang('user.first_name')</label>
                            <input type="text" class="form-control" id="firstname" name="first_name" value="{{ Auth::user()->first_name }}" data-validation="required" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">@lang('user.last_name')</label>
                            <input type="text" class="form-control" id="lastname" name="last_name" value="{{ Auth::user()->last_name }}" data-validation="required" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">@lang('user.email')</label>
                            <input type="email" class="form-control" id="email" name="email"  value="{{ Auth::user()->email }}" data-validation="required" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">@lang('user.phone')</label>
                            <input type="text" class="form-control" id="phone" name="mobile_number" value="{{ Auth::user()->mobile_number }}" data-validation="required custom length" data-validation-length="8-15" data-validation-regexp="^([0-9\s+-]+)$"  readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="address_line_1">@lang('user.address1')</label>
                            <input type="text" class="form-control" id="address_line_1" name="address" value="{{ Auth::user()->address }}" data-validation="required" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="address_line_2">@lang('user.address2')</label>
                            <input type="text" class="form-control" id="address_line_2" name="address1" value="{{ Auth::user()->address1 }}" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="city">@lang('user.city')</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ Auth::user()->city }}" data-validation="required" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pinCode">@lang('user.pincode')</label>
                            <input type="text" class="form-control" id="pinCode" name="pincode" value="{{ Auth::user()->pincode }}" data-validation="required length" data-validation-length="4-10" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">@lang('user.state')</label>
                            <input type="text" list="stateData" value="{{Auth::user()->state}}"  class="form-control" id="state" name="state">
                            
                        </div>
                        <div class="form-group col-md-4">
                            <label for="country">country</label>
                            <input list="countryData" class="form-control" value="{{Auth::user()->country}}" id="country" name="country">
                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-3" class="control-label">Change Your Password : </label>
                                <a href="{{ route('mediator.change.password',['id'=> Auth::user()->id]) }}" class="btn-sm btn-warning px-2" >Change Password </a>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="area_of_specialization">@lang('user.area_of_specialization')</label>
                            <select  class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="" id="area_of_specialization" name="area_of_specialization[]" data-validation="required">
                                <option value="">@lang('user.area_of_specialization_option')</option>
                                @foreach($areaOfSpecialization as $specialization)
                                <option value="{{$specialization->name}}" {{(isset($medi->area_of_specialization) && array_search($specialization->name, json_decode($medi->area_of_specialization)) !==false )?"selected":"" }}>{{$specialization->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="no_of_arbitrations">@lang('user.no_of_arbitrations')</label>
                            <select  class="form-control" id="no_of_arbitrations" name="no_of_arbitrations">
                                <option value="">@lang('user.no_of_arbitrations_option')</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='0 – 5')?"selected":"" }}>0 – 5</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='6 – 10')?"selected":"" }}>6 – 10</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='11 – 25')?"selected":"" }}>11 – 25</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='25 – 50')?"selected":"" }}>25 – 50</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='Above 50')?"selected":"" }}>Above 50</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="linked_in_profile_link">@lang('user.linkedin_profile_link')</label>
                            <input type="url" class="form-control" id="linked_in_profile_link" value="{{isset($medi->linked_in_profile_link)?$medi->linked_in_profile_link:"" }}" name="linked_in_profile_link">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="experience">@lang('user.experience')</label>
                            <textarea class="form-control" id="experience" name="experience" required>{{ isset($medi->experience)?$medi->experience:"" }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="signature">Upload Signature</label>
                            @if(Auth::user()->signature_photo != null) 
                                <br><img  width="12%" src="{{Config::get('constants.mediator_path')}}/{{Auth::user()->id}}/signature/{{Auth::user()->signature_photo}}" /> 
                            @endif
                            <input type="file" class="form-control" id="signature"  name="signature">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="signature">Upload Profile Pic</label>
                            @if(Auth::user()->profile_pic != null) 
                                <br><img  width="12%" src="{{Config::get('constants.mediator_path')}}/{{Auth::user()->id}}/profile/{{Auth::user()->profile_pic}}" /> 
                            @endif
                            <input type="file" class="form-control" id="profilePic"  name="profilePic">
                        </div>
                        <div class="form-group col-md-4  d-none">
                            <label for="field1">@lang('user.field_1')</label>
                            <input type="text" class="form-control" id="field1" name="field1">
                        </div>
                        <div class="form-group col-md-4  d-none">
                            <label for="field1">@lang('user.field_2')</label>
                            <input type="text" class="form-control" id="field2" name="field2">
                        </div>
                        <div class="form-group col-md-4 d-none">
                            <label for="field1">@lang('user.field_3')</label>
                            <input type="text" class="form-control" id="field3" name="field3">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_accept1" value="1"  class="form-check-input" id="is_accept1"  {{ (isset($medi->is_accept1) && $medi->is_accept1==1)?"checked":"" }}  data-validation="required">
                            <label class="form-check-label" for="is_accept1">@lang('user.confirm1')</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_accept2" value="1"  class="form-check-input" id="is_accept2" data-validation="required" {{ (isset($medi->is_accept2) && $medi->is_accept2==1)?"checked":"" }}>
                            <label class="form-check-label" for="is_accept2">@lang('user.confirm2')</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_accept3" value="1"  class="form-check-input" id="is_accept3" data-validation="required" {{ (isset($medi->is_accept3) && $medi->is_accept3==1)?"checked":"" }}>
                            <label class="form-check-label" for="is_accept3">@lang('user.confirm3')</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('head')
<link href="{{url('assets/')}}/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{url('assets/')}}/libs/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('footer')
<script src="{{url('assets/')}}/libs/select2/select2.min.js"></script>
<script src="{{url('assets/')}}/libs/bootstrap-select/bootstrap-select.min.js"></script>
<script>
$(document).ready(function () {
    $('.select2-multiple').select2();
});
</script>
<script>
    $.validate();
</script>
@endsection
