@extends('layouts.app')

@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <h3 class="page-header">Upload DTR File
            </h3>
            <div class="container">
                <div class="row">
                    <div class="col-md-11">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                <strong class="text-center" style="font-size: medium;font-weight: bold;">Please wait. The system is extracting data from the file.</strong>
                            </div>
                        </div>

                        <div class="alert alert-warning alert-dismissible col-lg-12" role="alert">
                            <strong>Warning!</strong>You selected an invalid file. Select a file that ends with .txt file extension.
                        </div>
                        <div class="row upload-section">
                            <div class="alert-success alert col-md-6 col-lg-offset-3">
                                <h3 style="font-weight: bold;" class="text-center">Upload a file</h3>
                                <form id="form_upload" data-link="{{ asset('admin/upload') }}" action="{{ asset('admin/upload') }}" method="POST" enctype="multipart/form-data">
                                    <input id="file" type="file" class="hidden" value="" name="dtr_file" onchange="readFile(this);"/>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <p class="text-center" id="file_select" style="border: dashed;padding:20px;">
                                        Click here to select a file
                                    </p>
                                    <button type="submit"  class="btn-lg btn-success center-block" id="upload">Upload File</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @@parent
    <script>

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('input[type="file"]').attr('value', e.target.result);
                    $('#file_select').html('<strong>'+ $('input[type="file"]').val() + '</strong>');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#file_select").click(function() {
            $('input[type="file"]').trigger("click");
        });
        (function($){
            $('.progress').hide();
            $('.alert-warning').hide();

            $('#form_upload').on('submit', function(e){
                var x = $('input[type="file"]').val();
                var arr = x.split('.');
                if(arr[1] === "txt"){
                    $('.upload-section').fadeOut(1000);
                    $('.progress').show();
                    $('.alert-warning').hide();
                    $('a').prop('disabled',true);

                } else {
                    e.preventDefault();
                    $('.alert-warning').show();
                }
            });
        })($);

        function check_file() {
            $('#file').change(function(event){
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function(progress){
                    var lines = this.result.split('\n');

                    for (var line = 0; line < 1;line++) {
                        if(line == 0 ){
                            console.log(lines[line]);
                            var data = lines[line].split(',');
                            if(data[0].length < 9){
                                $("#upload").prop("disabled",true);
                            }
                        }
                    }

                };
                reader.readAsText(file);
            });
        }
    </script>

@endsection
