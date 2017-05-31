@extends('layouts.app')


@section('content')
<div class="col-md-12 wrapper">
    <div class="alert alert-jim">
        <h3 class="page-header">Office Order
        </h3>
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong style="color: #f0ad4e;font-size:medium;">Option</strong></div>
                            <div class="panel-body">
                                <form class="form-inline" method="POST" action="{{ asset('search') }}" onsubmit="return searchDocument();" id="searchForm">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td class="col-sm-3" style="font-size: 12px;"><strong>Keyword</strong></td>
                                                <td class="col-sm-1">: </td>
                                                <td class="col-sm-9">
                                                    <input type="text" class="col-md-2 form-control" id="inputEmail3" name="keyword" placeholder="Route no, Subject">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-sm-3" style="font-size: 12px;"><strong>Dates</strong></td>
                                                <td class="col-sm-1"> :</td>
                                                <td class="col-sm-9">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control" id="inclusive3" name="filter_range" placeholder="Input date range here...">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <button type="submit"  class="btn-lg btn-success center-block col-sm-12" id="print" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Printing DTR">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong style="color: #f0ad4e;font-size:medium;">List</strong></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a class="btn btn-success" data-dismiss="modal" data-backdrop="static" data-toggle="modal" data-target="#form_type"><i class="fa fa-plus"></i> Create new</a>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(isset($office_order) and count($office_order) >0)
                                            <div class="table-responsive">
                                                <table class="table table-list table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center"></th>
                                                        <th class="text-center">Route #</th>
                                                        <th class="text-center">Prepared Date</th>
                                                        <th class="text-center">Document Type</th>
                                                        <th class="text-center">Subject</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($office_order as $so)
                                                        <tr>
                                                            <td class="text-center"><a href="#track" data-link="{{ asset('form/track/'.$so->route_no) }}" data-route="{{ $so->route_no }}" data-toggle="modal" class="btn btn-sm btn-success col-sm-12" ><i class="fa fa-line-chart"></i> Track</a></td>
                                                            <td class="text-center"><a class="title-info" data-route="{{ $so->route_no }}" data-backdrop="static" data-link="{{ asset('/form/info/'.$so->route_no.'/office_order') }}" href="#document_info" data-toggle="modal">{{ $so->route_no }}</a></td>
                                                            <td class="text-center">{{ date('M d, Y',strtotime($so->prepared_date)) }}<br>{{ date('h:i:s A',strtotime($so->prepared_date)) }}</td>
                                                            <td class="text-center">Office Order</td>
                                                            <td class="text-center">{{ $so->subject }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{ $office_order->links() }}
                                        @else
                                            <div class="alert alert-danger" role="alert">Documents records are empty.</div>
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
</div>

@endsection
@section('js')
    @if(Session::get('added'))
        {{--<div class="alert alert-success">
            <i class="fa fa-check"></i> Successfully Added!
        </div>--}}
        <script>
            Lobibox.notify('success',{
                msg:'Successfully Added!'
            });
        </script>
        <?php Session::forget('added'); ?>
    @endif
    @if(Session::get('deleted'))
        {{--<div class="alert alert-warning">
            <i class="fa fa-check"></i> Successfully Deleted!
        </div>--}}
        <script>
            Lobibox.notify('error',{
                msg:'Successfully Deleted!'
            });
        </script>
        <?php Session::forget('deleted'); ?>
    @endif
    @if(Session::get('updated'))
        <script>
            Lobibox.notify('info',{
                msg:'Successfully Updated!'
            });
        </script>
        <?php Session::forget('updated'); ?>
    @endif

    @parent
    <script>
        $('.input-daterange input').each(function() {
            $(this).datepicker("clearDates");
        });
        //document information
        $("a[href='#document_info']").on('click',function(){
            var route_no = $(this).data('route');
            $('.modal_content').html(loadingState);
            $('.modal-title').html('Route #: '+route_no);
            var url = $(this).data('link');

            setTimeout(function(){
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('.modal_content').html(data);
                        $('#reservation').daterangepicker();
                        var datePicker = $('body').find('.datepicker');
                        $('input').attr('autocomplete', 'off');
                    }
                });
            },1000);
        });

        $("a[href='#document_form']").on('click',function(){
            $('.modal-title').html('Office Order');
            $('.modal_content').html(loadingState);
            var url = $(this).data('link');
            setTimeout(function(){
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('.modal_content').html(data);
                        $('#reservation').daterangepicker();
                        var datePicker = $('body').find('.datepicker');
                        $('input').attr('autocomplete', 'off');
                    }
                });
            },1000);
        });

        $("a[href='#form_type']").on("click",function(){
            <?php
                $asset = asset('form/sov1');
                $delete = asset('so_delete');
                $doc_type = "OFFICE ORDER";
            ?>
        });

        $('#inclusive3').daterangepicker();
    </script>

@endsection
