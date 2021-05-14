@extends('admin.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard </a></li>
<!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <form action="{{route('admin.users.update')}}" method="post">
                <input type="hidden" name="id" value="{{$user->id}}">
                <h4 class="header-title"><b>Edit</b></h4>
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"  value="{{$user->first_name}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"  value="{{$user->last_name}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"  value="{{$user->email}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="username">username</label>
                            <input type="text" class="form-control" id="username" name="username"  value="{{$user->username}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="mobile_number">mobile number</label>
                            <input type="number" class="form-control" id="mobile_number" name="mobile_number"  value="{{$user->mobile_number}}">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="organization">organization</label>
                            <input type="text" class="form-control" id="organization" name="organization"  value="{{$user->organization}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country_code">country code</label>
                            <input type="text" class="form-control" id="country_code" name="country_code"  value="{{$user->country_code}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country">country</label>
                            <input type="text" class="form-control" id="country" name="country"  value="{{$user->country}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">address Line1</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{$user->address}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">address Line2</label>
                            <input type="text" class="form-control" id="address1" name="address1" value="{{$user->address1}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pincode">pincode</label>
                            <input type="number" class="form-control" id="pincode" name="pincode" value="{{$user->pincode}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="city">city</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{$user->city}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">state</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{$user->state}}">
                        </div>
                        @if($user->role==1)
                        <div class="form-group col-md-6">
                            <label for="area_of_specialization">Area of Specialization</label>
                            <select  class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="" id="area_of_specialization" name="area_of_specialization[]" required>
                                <option value="">--- Select Area of Specialization ---</option>
                                @foreach($areaOfSpecialization as $specialization)
                                <option value="{{$specialization->name}}" {{(isset($medi->area_of_specialization) && array_search($specialization->name, json_decode($medi->area_of_specialization)) !==false )?"selected":"" }}>{{$specialization->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="no_of_arbitrations">No. of Arbitrations</label>
                            <select  class="form-control" id="no_of_arbitrations" name="no_of_arbitrations">
                                <option value="">--- Select No. of Arbitrations ---</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='0 – 5')?"selected":"" }}>0 – 5</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='6 – 10')?"selected":"" }}>6 – 10</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='11 – 25')?"selected":"" }}>11 – 25</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='25 – 50')?"selected":"" }}>25 – 50</option>
                                <option {{(isset($medi->no_of_arbitrations) && $medi->no_of_arbitrations=='Above 50')?"selected":"" }}>Above 50</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="linked_in_profile_link">LinkedIn Profile Link</label>
                            <input type="url" class="form-control" id="linked_in_profile_link" value="{{isset($medi->linked_in_profile_link)?$medi->no_of_arbitrations:"" }}" name="linked_in_profile_link">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="experience">Experience</label>
                            <textarea class="form-control" id="experience" name="experience" required>{{ isset($medi->experience)?$medi->experience:"" }}</textarea>
                        </div>
                        <div class="form-group col-md-4  d-none">
                            <label for="field1">Field 1</label>
                            <input type="text" class="form-control" id="field1" name="field1">
                        </div>
                        <div class="form-group col-md-4  d-none">
                            <label for="field1">Field 2</label>
                            <input type="text" class="form-control" id="field2" name="field2">
                        </div>
                        <div class="form-group col-md-4 d-none">
                            <label for="field1">Field 3</label>
                            <input type="text" class="form-control" id="field3" name="field3">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">Approve</label>
                            <select name="status" class="form-control">
                                <option value="1">Approve</option>
                                <option value="0">Unapprove</option>

                            </select>
                        </div>
                        @endif

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

@endsection























