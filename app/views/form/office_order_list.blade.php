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
                                                            <td class="text-center"><a class="title-info" data-route="{{ $so->route_no }}" data-link="{{ asset('/form/info/'.$so->route_no.'/office_order') }}" href="#document_info" data-toggle="modal">{{ $so->route_no }}</a></td>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="form_type" style="z-index:999991;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: darkmagenta">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><i class="fa fa-file-pdf-o"></i> Select Form Type</h4>
                </div>
                <div class="modal-body text-center">
                    <div class="col-xs-4" style="left: 10%">
                        <a href="#document_form" data-dismiss="modal" data-link="{{ asset('form/sov1') }}"  data-toggle="modal" data-target="#document_form" class="text-success">
                            <i class="fa fa-file-pdf-o fa-5x"></i><br>
                            <i>Form V1</i>
                        </a>
                    </div>
                    <div class="col-xs-4" style="left: 25%;">
                        <a href="so" class="text-info">
                            <i class="fa fa-file-pdf-o fa-5x"></i><br>
                            <i>Form V2</i>
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br />
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="document_form">
        <div id="my_modal" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: darkmagenta">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="so_modal-title"><i class="fa fa-plus"></i> Create Document</h4>
                </div>
                <div class="so_modal_content"><center><img src="{{ asset('public/img/spin.gif') }}" width="150" style="padding:20px;"></center></div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
            alert(url);
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
            $('.so_modal-title').html('Office Order');
            var url = $(this).data('link');
            $('.so_modal_content').html(loadingState);
            setTimeout(function(){
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('.so_modal_content').html(data);
                        $('#reservation').daterangepicker();
                        var datePicker = $('body').find('.datepicker');
                        $('input').attr('autocomplete', 'off');
                    }
                });
            },1000);
        });

        $('#inclusive3').daterangepicker();
    </script>

@endsection
