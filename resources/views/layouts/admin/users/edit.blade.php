@extends('admin.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="{{url()->previous()}}">User </a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('user.edit_title')</a></li>
<!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <form action="{{route('admin.users.update')}}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{$user->id}}">
                <h4 class="header-title"><b>@lang('user.edit_title')</b></h4>
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="first_name">@lang('user.first_name')</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"  value="{{$user->first_name}}"  data-validation="required">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">@lang('user.last_name')</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"  value="{{$user->last_name}}"  data-validation="required">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">@lang('user.email')</label>
                            <input type="text" class="form-control" id="email" name="email"  value="{{$user->email}}"  data-validation="required email">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="username">@lang('user.username')</label>
                            <input type="text" class="form-control" id="username" name="username"  value="{{$user->username}}"  data-validation="required">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mobile_number">@lang('user.phone')</label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number"  value="{{$user->mobile_number}}"  data-validation="required custom length" data-validation-length="8-15" data-validation-regexp="^([0-9\s+-]+)$">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="organization">@lang('user.organization')</label>
                            <input type="text" class="form-control" id="organization" name="organization"  value="{{$user->organization}}"  data-validation=" @if($user->role==1) required @endif">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country_code">@lang('user.country_code')</label>
                            <select class="form-control" id="country_code" name="country_code" >
                                <option>48</option>
                            </select>
                        </div>                        
                        <div class="form-group col-md-6">
                            <label for="address">@lang('user.address1')</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{$user->address}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">@lang('user.address2')</label>
                            <input type="text" class="form-control" id="address1" name="address1" value="{{$user->address1}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country">country</label>
                            <input list="countryData" class="form-control" id="country" name="country" value="{{$user->country}}" data-validation="required length" data-validation-length="4-10">
                            {{-- <datalist id="countryData">
                                <option>Polska</option>
                            </datalist> --}}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">@lang('user.state')</label>
                            <input list="stateData"  class="form-control" id="state" value="{{$user->state}}" name="state">
                            {{-- <datalist id="stateData">
                                <option>
                                    dolnośląskie
                                </option>
                                <option>
                                    kujawsko-pomorskie
                                </option>
                                <option>
                                    lubelskie
                                </option>
                                <option>
                                    lubuskie
                                </option>
                                <option>
                                    łódzkie
                                </option>
                                <option>
                                    małopolskie
                                </option>
                                <option>
                                    mazowieckie
                                </option>
                                <option>
                                    opolskie
                                </option>
                                <option>
                                    podkarpackie
                                </option>
                                <option>
                                    podlaskie
                                </option>
                                <option>
                                    pomorskie
                                </option>
                                <option>
                                    śląskie
                                </option>
                                <option>
                                    świętokrzyskie
                                </option>
                                <option>
                                    warmińsko-mazurskie
                                </option>
                                <option>
                                    wielkopolskie
                                </option>
                                <option>
                                    zachodniopomorskie
                                </option>
                            </datalist> --}}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="city">@lang('user.city')</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{$user->city}}"  data-validation="required">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pincode">@lang('user.pincode')</label>
                            <input type="number" class="form-control" id="pincode" name="pincode" value="{{$user->pincode}}"  data-validation="required">
                        </div>


                        @if($user->role==1)
                        <div class="form-group col-md-6">
                            <label for="area_of_specialization">@lang('user.area_of_specialization')</label>
                            <select  class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="" id="area_of_specialization" name="area_of_specialization[]" required>
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
                        
                        <div class="form-group col-md-6">
                            <label for="signature">Upload Profile Pic</label>
                            @if($user->profile_pic != null) 
                                <br><img  width="12%" src="{{Config::get('constants.mediator_path')}}/{{$user->id}}/profile/{{$user->profile_pic}}" /> 
                            @endif
                            <input type="file" class="form-control" id="profilePic"  name="profilePic">
                        </div>
                        @endif
                        <div class="form-group col-md-6">
                            <label for="signature">Upload Signature</label>
                            @if($user->signature_photo != null) 
                                <br><img  width="12%" src="{{($user->role==1) ? Config::get('constants.mediator_path') : Config::get('constants.user_path')}}/{{$user->id}}/signature/{{$user->signature_photo}}" />  
                            @endif
                            <input type="file" class="form-control" id="signature"  name="signature">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">Status</label>
                            <select name="status" class="form-control">
                                <option {{(isset($user->isDone) && $user->isDone=='1')?"selected":"" }} value="1">@lang('user.approve')</option>
                                <option {{(isset($user->isDone) && $user->isDone=='0')?"selected":"" }} value="0">@lang('user.unapprove')</option>

                            </select>
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

<!-- Table datatable css -->
@section('head')
<link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{url('assets/')}}/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{url('assets/')}}/libs/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
@endsection


@section('footer')
<!-- Datatable plugin js -->
<script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Datatables init -->
<script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
<script src="{{url('assets/')}}/libs/select2/select2.min.js"></script>
<script src = "{{url('assets/')}}/libs/bootstrap-select/bootstrap-select.min.js" ></script>
<script>
$(document).ready(function () {
    $('.select2-multiple').select2();
});
</script>
<script>
    $.validate();
</script>
@endsection
