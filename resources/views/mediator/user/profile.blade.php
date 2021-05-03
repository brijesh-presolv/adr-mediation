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
            <div class="card-header">
                <h3>Profile Update</h3>
            </div>
            <form action="{{ route('mediator.profile_save') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="first_name" value="{{ Auth::user()->first_name }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="last_name" value="{{ Auth::user()->last_name }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"  value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="number" class="form-control" id="phone" name="mobile_number" value="{{ Auth::user()->mobile_number }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="address_line_1">Address Line 1</label>
                            <input type="text" class="form-control" id="address_line_1" name="address" value="{{ Auth::user()->address }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="address_line_2">Address Line 2</label>
                            <input type="text" class="form-control" id="address_line_2" name="address1" value="{{ Auth::user()->address1 }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ Auth::user()->city }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pinCode">Pin code</label>
                            <input type="text" class="form-control" id="pinCode" name="pincode" value="{{ Auth::user()->pincode }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ Auth::user()->state }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ Auth::user()->country }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="area_of_specialization">Area of Specialization </label>
                            <input type="text" class="form-control" id="area_of_specialization" name="area_of_specialization" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="no_of_arbitrations">No. of Arbitrations</label>
                            <select type="text" class="form-control" id="no_of_arbitrations" name="no_of_arbitrations">
                                <option>0 – 5</option>
                                <option>6 – 10</option>
                                <option>11 – 25</option>
                                <option>25 – 50</option>
                                <option>Above 50</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="linked_in_profile_link">LinkedIn Profile Link</label>
                            <input type="url" class="form-control" id="linked_in_profile_link" name="linked_in_profile_link">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="experience">Experience</label>
                            <input type="text" class="form-control" id="experience" name="experience" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="field1">Field 1</label>
                            <input type="text" class="form-control" id="field1" name="field1" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="field1">Field 2</label>
                            <input type="text" class="form-control" id="field2" name="field2" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="field1">Field 3</label>
                            <input type="text" class="form-control" id="field3" name="field3" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_accept1" value="1"  class="form-check-input" id="is_accept1" required>
                            <label class="form-check-label" for="is_accept1">I confirm that the details provided above are true, accurate, current and complete and consent to verification by Presolv360.</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_accept2" value="1"  class="form-check-input" id="is_accept2" required>
                            <label class="form-check-label" for="is_accept2">I confirm that I am qualified, I possess the required competence, knowledge and expertise and I will devote sufficient time to conduct the mediation proceedings within the time limits prescribed in Presolv360's <a href="{{url('arbitrator/important_documents')}}" target="_blank">Dispute Resolution Rules</a></label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_accept3" value="1"  class="form-check-input" id="is_accept3" required>
                            <label class="form-check-label" for="is_accept3">I acknowledge that I have read and understood Presolv360’s <a href="{{ url('arbitrator/important_documents')}}">Dispute Resolution Rules</a>, the Arbitrators’ and Mediators’ <a href="{{ url('arbitrator/important_documents')}}">Code of Conduct and Disclosure Rules</a>, <a href="{{ url('terms_conditions')}}">Terms & Conditions</a> and <a href="{{ url('privacy_policy')}}">Privacy Policy</a>.</label>
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
