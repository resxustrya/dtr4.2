<div class="form-group" id="{{ $_GET['count'] }}">
    <label class="col-sm-2 control-label">Inclusive Date and Area</label>
    <div class="col-sm-4">
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control" id="{{ 'inclusive'.$_GET['count'] }}" name="inclusive[]" placeholder="Input date range here..." required>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-location-arrow"></i>
            </div>
            <textarea name="area[]" class="form-control" rows="1" placeholder="Input your area here..." required></textarea>
        </div>
    </div>
    <div class="col-sm-1">
        <button type="button" value="{{ $_GET['count'] }}" onclick="remove_row($(this));" id="rusel" class="btn btn-danger" style="color: white" ><span class="fa fa-close"></span></button>
    </div>
</div>





