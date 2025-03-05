<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <form action="{{ route('create_wa_log') }}" method="post" enctype="multipart/form-data">
                    
                    <h4 class="header-title"><b>Upload Log CSV</b></h4>
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="sub_user">CSV File :</label>
                                <input type="file" name="log_file" />
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
            </form>

        </div>
    </div>
</div>