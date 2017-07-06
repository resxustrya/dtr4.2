<style>
    .table-info tr td:first-child {
        color: #2b542c;
    }
</style>
<span id="so_append" data-link="{{ asset('so_append') }}"></span>
<form action="{{ asset('so_addv1') }}" method="POST" class="form-submit">
    <input type="hidden" name="_token" value="{{ csrf_token(); }}">
    <div class="table-responsive">
        <table>
            <tr>
                <td class="col-md-1"><img height="130" width="130" src="{{ asset('public/img/ro7.png') }}" /></td>
                <td class="col-lg-10" style="text-align: center;">
                    Repulic of the Philippines <br />
                    <strong>DEPARTMENT OF HEALTH REGIONAL OFFICE NO. VII</strong><br />
                    Osmeña Boulevard, Cebu City, 6000 Philippines <br />
                    Regional Director's Office Tel. No. (032) 253-635-6355 Fax No. (032) 254-0109 <br />
                    Official Website:<a target="_blank" href="http://www.ro7.doh.gov.ph"><u>http://www.ro7.doh.gov.ph</u></a> Email Address: dohro7{{ '@' }}gmail.com
                </td>
                <td class="col-md-10"><img height="130" width="130" src="{{ asset('public/img/ro7.png') }}" /> </td>
            </tr>
        </table>
        <table class="table table-hover table-form table-striped table-info">
            <tr>
                <td class="col-sm-3"><label>Document Type</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-bookmark"></i>
                        </div>
                        <input type="text" class="form-control" value="Office Order" readonly>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Prepared date</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar-plus-o"></i>
                        </div>
                        <input class="form-control datepickercalendar" value="{{ date('m/d/Y') }}" name="prepared_date" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Subject</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-book"></i>
                        </div>
                        <textarea class="form-control" name="subject" rows="3" style="resize:none;" required></textarea>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Check to select all employee</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <input type="checkbox" class="form-control" name="all_employee">
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Inclusive Name</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="name_loading"></div>
                    <div class="inclusive_name">
                        <select class='form-control select2' name='inclusive_name[]' multiple='multiple' data-placeholder='Select a name' required>
                            @foreach($users as $row)
                                <option value='{{ $row['id'] }}'>{{ $row['fname'].' '.$row['mname'].' '.$row['lname'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
            </tr>
            <tbody class="p_inclusive_date">
            <tr>
                <td class="col-sm-3"><label>Inclusive Date and Area</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" id="inclusive1" name="inclusive[]" placeholder="Input date range here..." style="width: 40%;" required>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-location-arrow"></i>
                            </div>
                            <textarea name="area[]" id="area1" class="form-control" rows="1" cols="30" placeholder="Input your area here..." style="resize: none;width: 100%;" required></textarea>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
            <tr>
                <td class="col-sm-3"></td>
                <td class="col-sm-1"></td>
                <td class="col-sm-8" id="border-top">
                    <button class="btn-xs btn-primary pull-right" type="button" style="color: white" onclick="add_inclusive();"><i class="fa fa-plus"></i> Add inclusive date</button>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-success btn-submit" style="color:white;"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>

<script>
    $('.datepickercalendar').datepicker({
        autoclose: true
    });
    $(".select2").select2();
    $(function(){
        $("body").delegate("#inclusive1","focusin",function(){
            $(this).daterangepicker();
        });
    });
    var count = 1;
    function add_inclusive(){
        event.preventDefault();
        count++;
        var url = $("#so_append").data('link')+"?count="+count;
        $.get(url,function(result){
            $(".p_inclusive_date").append(result);
        });
        $(function() {
            $("body").delegate("#inclusive"+count, "focusin", function(){
                $(this).daterangepicker();
            });
        });
    }

    function remove_row(id){
        //make multiple call to remove
        for(var i=0; i<100; i++){
            $("#"+id.val()).remove();
        }
    }

    $('.form-submit').on('submit',function(){
        $('.btn-submit').attr("disabled", true);
    });

    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    $('select').val(<?php echo $prepared_by;?>).trigger('change');
    $('input[name=all_employee]').on('ifChecked', function(event){
        $(".name_loading").html(loadingState);
        $(".inclusive_name").hide();
        setTimeout(function(){
            $('select').val(<?php echo $all_user?>).trigger('change');
            $(".name_loading").html('');
            $(".inclusive_name").show();
        },700);
    });

    $('input[name=all_employee]').on('ifUnchecked', function(event){
        $(".name_loading").html(loadingState);
        $(".inclusive_name").hide();
        setTimeout(function(){
            $('select').val(<?php echo $prepared_by?>).trigger('change');
            $(".name_loading").html('');
            $(".inclusive_name").show()
        },700);
    });
</script>
