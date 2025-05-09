@extends('admin.layouts.app')
@section('title', "Select Platform")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Send Whatsapp Plarform Selection</a></li>
<!-- end page title -->
@endsection
@section('page_title', "Select Platform")

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
                <form id="waPlatformSelection">
                    <div class="form-group">
                        <label>Select the platfrom from whatsapp notification will go.</label>
                        <select name="platform" id="platformSelect" class="form-control">
                            <option value="" selected>Select Platform...</option>
                            <option value="i">Interekt</option>
                            <option value="m">Mtalkz</option>
                        </select>
                    </div>

                    <br>

                    <div class="form-group">
                        <div id="interektinfo">
                            <label for="">Whatsapp information for Interekt is as below,</label>
                            <br>
                            <span>Mobile - 8291329943 (10k)</span>
                            <br>
                            <span>Mobile - 8108768497  (1k)</span>
                        </div>

                        <br>

                        <div id="mtalkzinfo">
                            <label for="">Whatsapp information for Mtalkz is as below,</label>
                            <br>
                            <span>Mobile - 8879651360</span>
                        </div>
                        
                    </div>

                    <input type="submit" id="submit" name="platformSelection"
                            class="btn btn-sm btn-primary mt-3">
                </form>
            </div>
        </div>
    </div>

@endsection

<!-- Table datatable css -->

@section('footer')
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="{{ url('/') }}/assets/js/sweetalert.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $('#interektinfo').hide();
        $('#mtalkzinfo').hide();



        $('#waPlatformSelection').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.case.platformWiseWhtsapp') }}',
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


        $('#platformSelect').change(function(){
            $optval = $(this).val();
            if($optval == "i") {
                $('#interektinfo').show();
                $('#mtalkzinfo').hide();
            } else if($optval == "m") {
                $('#interektinfo').hide();
                $('#mtalkzinfo').show();
            } else {
                $('#interektinfo').hide();
                $('#mtalkzinfo').hide();
            }
        });
    });
</script>

@endsection























