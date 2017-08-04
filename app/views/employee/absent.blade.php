<center>
    <form action="{{ asset('create/absent/description') }}" method="POST" class="add_absent" >
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="alert alert-warning error-alert" role="alert"><strong id="error"></strong></div>
        <table class="table table-hover table-form table-striped">
            <tr>
                <td class="col-sm-3"><label>Absent Type</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="absent_type" class="form-control" id="absent_type_select" required onchange="select_absent(this);">
                        <option value="" selected >Select absent type</option>
                        @foreach(AbsentTypes::all() as $absent)
                            <option value="{{ $absent->code }}">{{ $absent->desc }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="description_tr_desc">
                <td class="col-sm-3"><label>Enter SO #</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <input type="text" id="desc" disabled="true" name="description" class="form-control" placeholder="OFFICE ORDER NO. ONLY"  onkeypress="return isNumber(event)">
                </td>
            </tr>
            <tr class="description_tr_leave">
                <td class="col-sm-3"><label>Leave Type</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                    <select name="leave_type" id="leave_type_select"  class="form-control" disabled="true" >
                        <option value="0" selected>Select leave type</option>
                        @foreach(DB::table('leave_type')->get() as $leave)
                            <option value="{{ $leave->code }}">{{ $leave->desc }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="col-sm-3"><label>Dates</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8"><input type="text" class="form-control form-group" id="absent" name="date_range" placeholder="Input date range here..." required></td>
            </tr>
        </table>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-success" id="save"><i class="ace-icon fa fa-floppy-o bigger-160"></i> Save</button>
        </div>

    </form>
</center>
<script>

    $(function(){
        $('.error-alert').hide();

        $("body").delegate("#absent","focusin",function(){
            $(this).daterangepicker();
        });
    });



    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }


</script>