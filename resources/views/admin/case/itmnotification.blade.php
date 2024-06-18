@extends('admin.layouts.app')
@section('title', "ITM Notifications")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Invitation To Mediate Notifications</a></li>
<!-- end page title -->
@endsection
@section('page_title', "ITM Notifications")

@section('content')
    <style>
        #mySwitch {
            position: relative;
            left: 7%;
            width: 20px;
            height: 20px;
        }

        #mySwitchLabel {
            position: relative;
            left: 10%;
            bottom: 5px;
        }
    </style>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="batchSubmit">
                    <div class="form-group">
                        <label>Select the Batch for which ITM notification will </label>
                        <select name="batch" id="batchSelect" class="form-control">
                            <option value="" selected>Select Batch...</option>
                            @foreach ($batchName as $value)
                                <option value={{ $value->id }}>{{ $value->batch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <input class="form-check-input" type="checkbox" id="mySwitch" name="mySwitch" checked>
                        <label for="" id="mySwitchLabel">Stop Invitation To Mediate Notifications</label>
                    </div>

                    <input type="submit" id="submit" name="batchNotification"
                            class="btn btn-sm btn-primary mt-3">
                </form>
            </div>
        </div>
    </div>

@endsection

<!-- Table datatable css -->

@section('footer')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $('#batchSubmit').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.case.batchNotificationStop') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    swal({
                        title: 'Loading...',
                        showConfirmButton: false,
                        buttons: false,
                        allowOutsideClick: false,
                    });
                },
                success: (data) => {
                    //this.reset();
                    swal(data.message, {
                        icon: "success",
                    });
                    // $("#uploadSupportingDocsModal").modal("hide");
                },
                error: function(data) {
                    //alert(data.responseJSON.errors.files[0]);
                    console.log(data);
                }
            });
        });
    });
</script>

@endsection























