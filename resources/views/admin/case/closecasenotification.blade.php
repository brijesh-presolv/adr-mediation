@extends('admin.layouts.app')
@section('title', "Close Case Notifications")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Invitation To Mediate Notifications</a></li>
<!-- end page title -->
@endsection
@section('page_title', "Close Case Notifications")

@section('content')
    <style>
        .my_switch {
            position: relative;
            left: 7%;
            width: 20px;
            height: 20px;
        }

        .mySwitchLabel {
            position: relative;
            left: 10%;
            bottom: 5px;
        }
    </style>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <form id="batchSubmitClose">
                    <div class="form-group">
                        <label>Select the Batch in which Close case notification will be disabled</label>
                        <select name="batch" id="batchSelect" class="form-control">
                            <option value="" selected>Select Batch...</option>
                            @foreach ($batchName as $value)
                                <option value={{ $value->id }}>{{ $value->batch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <br>

                    <div class="form-group">
                        <label for="">Disable notification when case is closed</label>
                        <br>
                        <input class="form-check-input my_switch" type="checkbox" id="mySwitchIPClose" name="mySwitchIPClose">
                        <label for="" class="mySwitchLabel">Initiating Party(s)</label>

                        <br>
                        <input class="form-check-input my_switch" type="checkbox" id="mySwitchRPClose" name="mySwitchRPClose">
                        <label for="" class="mySwitchLabel">Responding Party(s)</label>

                        <br>
                        <input class="form-check-input my_switch" type="checkbox" id="mySwitchMedClose" name="mySwitchMedClose">
                        <label for="" class="mySwitchLabel">Mediator</label>
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
        $('#batchSubmitClose').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.case.batchNotificationStopWhenClose') }}',
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























