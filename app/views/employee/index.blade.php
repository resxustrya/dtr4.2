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
                                    <form action="{{ asset('FPDF/timelog/print_individual1.php') }}" target="_blank" autocomplete="off" method="POST" id="print_pdf">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-sm-3"><strong>User ID </strong></td>
                                                    <td class="col-sm-1">: </td>
                                                    <td class="col-sm-9">
                                                        <input type="text" readonly class="col-md-2 form-control" id="inputEmail3" name="userid" value="{{ Auth::user()->userid }}" required>
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
                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Generate PDF
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
                            <!-- Box Comment -->
                            <div class="box box-widget">
                                <div class="box-header with-border">
                                    <div class="user-block">
                                        <img class="img-circle" src="{{ asset('public/img/doh.png') }}" alt="User Image">
                                        <span class="username"><strong class="text-blue">IT</strong></span>
                                        <span class="description">FAQs - 08:00 AM 05/16/2019</span>
                                    </div>
                                    <!-- /.user-block -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="alert alert-info text-blue">
                                        New features of DTR VERSION 4.3<br> Just click here <i class="ace-icon fa fa-hand-o-right"></i> <a href="{{ asset('logs/timelog') }}" target="_blank"><strong class="text-blue">Manage Timelog</strong></a>
                                    </div>
                                    <!--
                                    <div class="alert alert-success text-green">
                                        If you encountered an error, please send us feedback and suggestion. Just comment below <i class="ace-icon fa fa-hand-o-down"></i>
                                    </div>
                                    -->
                                </div>


                            </div>

                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div> <!-- END ROW -->
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

        var count = parseInt("<?php echo count($comments) ?>")+1;
        console.log(count);
        $(".submit_comment").submit(function(e){
            var url = "<?php echo asset('faq/comment_append'); ?>";
            var json = {
                "userid" : "<?php echo Auth::user()->userid; ?>",
                "post_id" : 1,
                "description" : $("#text_comment").val(),
                "status" : 1,
                "count" : count
            };
            $.post(url,json,function(result){
                $("#text_comment").val('');
                $("#text_comment").focus();
                $(".comment_append").append(result);
                $("#"+count).hide().fadeIn();
                count++;
            });
            e.preventDefault();
        });

        var replyCount = parseInt("<?php echo count($replies); ?>")+1;
        $(".form_reply").each(function(e){
            $("#"+this.id).submit(function(form){
                var inputElement = $("#"+this.id).find('input');
                var ID = this.id;
                var url = "<?php echo asset('faq/reply_append'); ?>";
                var json = {
                    "userid" : "<?php echo Auth::user()->userid; ?>",
                    "post_id" : 1,
                    "comment_id" : this.id.split('submit_reply')[1],
                    "description" : inputElement.val(),
                    "status" : 1,
                    "count" : replyCount
                };
                $.post(url,json,function(result){
                    inputElement.val('');
                    $(".reply_append"+ID.split('submit_reply')[1]).append(result);
                    $("#reply"+replyCount).hide().fadeIn();
                    replyCount++;
                });
                form.preventDefault();
            });
        });

    </script>
@endsection