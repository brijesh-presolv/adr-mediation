@extends('user.layouts.app')


    @section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Invoke </a></li>
    <!-- end page title -->
    @endsection
@section('content')

<div class="card">
	<div class="card-body">
		


<section>
	
	<div class="row">
		<div class="col-md-12">
			
		</div>
	</div>
</section>

<form  method="post" enctype="multipart/form-data" action="">

	@csrf
<section class="claimant">
		<div class="row">
			<div class="col-md-12 ">
				<h6>Initiating Party</h6>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Name<span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Email <span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Phone <span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Address line 1 <span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Address line 2</label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>City <span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Pincode <span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>State <span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Country <span style="color:red; ">*</span></label>
					<input type="text" name="" class="form-control" readonly>
				</div>
			</div>
		</div>
</section>

<section>
	<div class="row">
	<div class="col-md-12 ">
				<h6>Reponding Party</h6>
			</div>
	</div>
</section>
<section class="respondent">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Name<span style="color:red; ">*</span></label>
					<input type="text" name="name[]" class="form-control" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Email <span style="color:red; ">*</span></label>
					<input type="email" name="email[]" class="form-control" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Phone <span style="color:red; ">*</span></label>
					<input type="text" name="phone[]" class="form-control" required>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Address line 1 <span style="color:red; ">*</span></label>
					<input name="add1[]" type="text" class="form-control" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Address line 2</label>
					<input name="add2[]" type="text" class="form-control" >
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>City <span style="color:red; ">*</span></label>
					<input name="city[]" type="text" class="form-control" required>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Pincode <span style="color:red; ">*</span></label>
					<input name="pincode[]" type="text" class="form-control" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>State <span style="color:red; ">*</span></label>
					<input name="state[]" type="text" class="form-control" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label>Country <span style="color:red; ">*</span></label>
					<input name="country[]" type="text" class="form-control" required>
				</div>
			</div>
		</div>
</section>
<section class="respondents">
	
</section>
<section>
	<div class="row">
		<div class="col-md-12">
			<button class="btn btn-sm btn-warning" id="addmore">Add</button>
			<button class="btn btn-sm btn-danger" id="removeresp">Remove</button>

		</div>
	</div>
</section>
<section>
	<div class="row">
		<div class="col-md-12">
			<hr>
			<div class="form-group">
				<textarea class="form-control" rows="4" name="issue"></textarea>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<input class="form-control" type="file" name="document"></input>
			</div>
		</div>
		<div class="col-md-12">
			<button class="btn btn-success">Invoke</button>
		</div>
	</div>
</section>
</form>
	</div>
</div>
@endsection


