@extends('layouts.app')
@section('content')
<div class="container">
    <div class="alert alert-jim">
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron">
                    <h2>User's Manual</h2>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2>DTR printing</h2>
                <hr />
                <h4>You can print directly your DTR by just specifying the date range. You can only print a semi monthly or one month date range per page of your dtr.</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <img src="{{ asset('public/manual/printdtr.png') }}" class="img-fluid" style="width: auto;" height="400" />
                    </div>
                </div>
                <hr />
                <p><b>Click the Dates textbox to display the datepicker window and enter your date range after that click <span class="btn btn-success">Apply</span> button inside the datepicker to set the date range of the DTR. After you click <span class="btn btn-success">Apply</span> button, click the <button class="btn btn-success"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button> button to generate the DTR</b></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2>Time Logs filtering</h2>
                <hr />
                <h4>This right side panel will display all your time logs that was uploaded from the biometric device. If logs were empty it is possibly not available or you did not punch to the biometric device.</h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <img src="{{ asset('public/manual/rightpanel.png') }}" class="img-fluid" style="width: auto;" height="400" />
                    </div>
                </div>
                <hr />
                <p><b>To filter your logs click the textbox to display the datepicker window and enter your date range. After you set, click the <span class="btn btn-success">Apply</span> button and then <span class="btn btn-success">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Filters
                </span> button to display results. </b>
                </p>
            </div>
        </div>
    </div>
</div>
@stop





