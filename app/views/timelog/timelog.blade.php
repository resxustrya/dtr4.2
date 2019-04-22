
@extends('layouts.app')

@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline" autocomplete="off" method="POST" action="{{ asset('filterTimeLog') }}" id="submit_logs">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="text" class="form-control filter_dates" value="{{ Session::get('filter_dates') }}" id="inclusive3" name="filter_dates" placeholder="Filter Date" required>
                        <button type="submit" class="btn btn-success" id="print">
                            Go
                        </button>
                    </form>
                    <br>
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong style="color: #f0ad4e;font-size:medium;">Logs</strong></div>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ asset('personal/add/logs') }}" method="POST">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <table class="table table-list table-hover table-striped">
                                        <tr>
                                            <th>Date In</th>
                                            <th>AM IN</th>
                                            <th>AM OUT</th>
                                            <th>PM IN</th>
                                            <th>PM OUT</th>
                                        </tr>
                                        <tbody class="timelog">
                                        <tr>
                                            <td><input type="text" value="{{ date('m/d/Y') }}" class="form-control" name="date_in"></td>
                                            <td><input type="time" class="form-control" name="am_in"></td>
                                            <td><input type="time" class="form-control" name="am_out"></td>
                                            <td><input type="time" class="form-control" name="pm_in"></td>
                                            <td><input type="time" class="form-control" name="pm_out"></td>
                                        </tr>
                                        </tbody>
                                    </table>

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
    @parent

    <script>
        $('#inclusive3').daterangepicker();
        $('#submit_logs').submit(function(){
            $('#print_individual').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });
    </script>
@endsection