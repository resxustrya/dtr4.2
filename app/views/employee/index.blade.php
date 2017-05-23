
@extends('layouts.app')

@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading"><strong style="color: #f0ad4e;font-size:medium;">Print individual DTR</strong></div>
                                <div class="panel-body">
                                    <form action="{{ asset('FPDF/print_individual.php') }}" method="POST" id="print_pdf">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-sm-3"><strong>User ID </strong></td>
                                                    <td class="col-sm-1">: </td>
                                                    <td class="col-sm-9">
                                                        @if(Auth::user()->usertype == "1")
                                                            <input type="text" class="col-md-2 form-control" id="inputEmail3" name="userid" value="" required>
                                                        @else
                                                            <input type="text" readonly class="col-md-2 form-control" id="inputEmail3" name="userid" value="{{ Auth::user()->userid }}" required>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3"><strong>Dates</strong></td>
                                                    <td class="col-sm-1"> :</td>
                                                    <td class="col-sm-9">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input type="text" class="form-control" id="inclusive3" name="filter_range" placeholder="Input date range here..." required>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <button type="submit"  class="btn-lg btn-success center-block col-sm-12" id="upload" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Printing DTR">
                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong style="color: #f0ad4e;font-size:medium;">Your current time logs for this month</strong></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="form-inline" method="GET" action="{{ asset('/personal/search/filter') }}"  id="searchForm">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="filter_dates" name="filter_range" placeholder="Filter date range here..." required>
                                                </div>
                                                <button type="submit" name="filter" class="btn btn-success form-control" value="Filter">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Filters
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(isset($lists) and count($lists) >0)
                                            <div class="table-responsive">
                                                <table class="table table-list table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Dtr ID</th>
                                                        <th>Attendance Date</th>
                                                        <th>Attendance Time</th>
                                                        <th>Event Type</th>
                                                        <th>Option</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($lists as $list)
                                                        <tr>
                                                            <td>{{ $list->dtr_id }}</td>

                                                            <td>
                                                                {{ $list->datein }}
                                                            </td>
                                                            <td>{{ date("h:i A", strtotime($list->time)) }}</td>
                                                            <td>{{ $list->event }}</td>
                                                            <td>
                                                                <a class="btn btn-default" href="{{ asset('edit/personal/attendance/' .$list->dtr_id) }}">Edit</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{ $lists->links() }}
                                        @else
                                            <div class="alert alert-danger" role="alert">DTR records are empty.</div>
                                        @endif

                                    </div>
                                </div>
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
        $('.input-daterange input').each(function() {
            $(this).datepicker("clearDates");
        });
        $('#inclusive3').daterangepicker();
        $('#filter_dates').daterangepicker();
        $('#print_pdf').submit(function(){
            $('#upload').button('loading');
        });
    </script>
@endsection