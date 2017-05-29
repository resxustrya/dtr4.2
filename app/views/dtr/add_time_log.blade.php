
@extends('layouts.app')

@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <h2 class="page-header">Create time log
            </h2>
            <div class="container">
                <div class="row">
                    <div class="col-md-11">
                        <form action="{{ asset('personal/add/logs') }}" method="POST">

                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group  input-daterange">
                                        <label class="control-label col-md-2" for="inputSuccess1">Date In</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="datein" value="2012-04-05" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="control-label col-md-2" for="inputSuccess1">Time</label>
                                        <div class="col-sm-5">
                                            <div class="table-responsive">
                                                <table class="table table-list">
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th class="text-center">Arrival</th>
                                                        <th class="text-center">Departure</th>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>AM</strong></td>
                                                        <td>
                                                            <input id="input-a" value="" data-default="20:48" name="am_in" class="form-control clock" >
                                                        </td>
                                                        <td>
                                                            <input id="input-b" value="" data-default="20:48" name="am_out" class="form-control clock" >

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>PM</strong></td>
                                                        <td>
                                                            <input id="input-c" value="" data-default="20:48" name="pm_in" class="form-control clock" >
                                                        </td>
                                                        <td>
                                                            <input id="input-d" value="" data-default="20:48" name="pm_out" class="form-control clock" >

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-md-2 control-label">Terminal</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="col-md-2 form-control" value="WEB" readonly name="terminal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-md-2 control-label">Remarks</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="col-md-2 form-control" value="WEB CREATED" readonly name="remarks">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-5 col-md-offset-2">
                                            <input type="submit" name="submit" class="btn btn-success" value="Submit">
                                            <a href="{{ asset('/') }}" class="btn btn-default">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script>

        var input = $('#input-a');
        input.clockpicker({
            autoclose: true,
            placement : 'top',
            align : 'left',
            donetext : 'Ok',
            'default' : '8:00'
        });

        var input = $('#input-b');
        input.clockpicker({
            autoclose: true,
            placement : 'top',
            align : 'left',
            donetext : 'Ok',
            'default' : '12:00'
        });

        var input = $('#input-c');
        input.clockpicker({
            autoclose: true,
            placement : 'top',
            align : 'left',
            donetext : 'Ok',
            'default' : '13:00'
        });

        var input = $('#input-d');
        input.clockpicker({
            autoclose: true,
            placement : 'top',
            align : 'left',
            donetext : 'Ok',
            'default' : '17:00'
        });

        $('.input-daterange input').each(function() {
            $(this).datepicker("clearDates");
        });

    </script>

@endsection
