
@extends('layouts.app')

@section('content')
    <style>
        u{
            color: #307bff;
        }
    </style>
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-inline" autocomplete="off" method="POST" action="{{ asset('logs/timelog') }}" id="submit_logs">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="text" class="form-control filter_dates" value="{{ Session::get('filter_dates') }}" id="inclusive3" name="filter_dates" placeholder="Filter Date" required>
                        <button type="submit" class="btn btn-success" id="print">
                            Go
                        </button>
                    </form>
                    <br>
                    @if(empty($timeLog))
                        <div class="alert alert-info">
                            <p style="color: #1387ff">
                                No logs record
                            </p>
                        </div>
                    @else
                        <div class="panel panel-default">
                            <div class="panel-heading"><strong style="color: #f0ad4e;font-size:medium;">Logs</strong></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-list table-hover table-striped">
                                        <tr>
                                            <th>Date In</th>
                                            <th>AM IN</th>
                                            <th>AM OUT</th>
                                            <th>PM IN</th>
                                            <th>PM OUT</th>
                                        </tr>
                                        <tbody class="timelog">
                                        <?php $count = 0; ?>
                                        @foreach($timeLog as $row)
                                        <?php $count++; ?>
                                        <tr>
                                            <td width="10%">{{ $row->datein }}</td>
                                            <td width="10%">
                                                @if(explode("_",explode('|',$row->time)[0])[1] == "''")
                                                    <b><u><span id="{{ $count."am_in" }}">{{ explode("_",explode('|',$row->time)[0])[0] }}</span></u></b>
                                                @elseif(explode("_",explode('|',$row->time)[0])[1] == "empty")
                                                    <span style="cursor: pointer;" class="editable" id="{{ $count."am_in" }}"></span>
                                                @else
                                                    <span style="cursor: pointer;" class="editable" id="{{ $count."am_in" }}">{{ explode("_",explode('|',$row->time)[0])[0] }}</span>
                                                @endif
                                            </td>
                                            <td width="10%">
                                                @if(explode("_",explode('|',$row->time)[0])[1] == "''")
                                                    <b><u><span id="{{ $count."am_in" }}">{{ explode("_",explode('|',$row->time)[1])[0] }}</span></u></b>
                                                @else
                                                    <span style="cursor: pointer;" class="editable" id="{{ $count."am_out" }}">{{ explode("_",explode('|',$row->time)[1])[0] }}</span>
                                                @endif
                                            </td>
                                            <td width="10%">
                                                @if(explode("_",explode('|',$row->time)[0])[1] == "''")
                                                    <b><u><span id="{{ $count."am_in" }}">{{ explode("_",explode('|',$row->time)[2])[0] }}</span></u></b>
                                                @else
                                                    <span style="cursor: pointer;" class="editable" id="{{ $count."pm_in" }}">{{ explode("_",explode('|',$row->time)[2])[0] }}</span>
                                                @endif
                                            </td>
                                            <td width="10%">
                                                @if(explode("_",explode('|',$row->time)[0])[1] == "''")
                                                    <b><u><span id="{{ $count."am_in" }}">{{ explode("_",explode('|',$row->time)[3])[0] }}</span></u></b>
                                                @else
                                                    <span style="cursor: pointer;" class="editable" id="{{ $count."pm_out" }}">{{ explode("_",explode('|',$row->time)[3])[0] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    <a href="#" id="username" style="color:black" data-type="radiolist"></a>
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

        $.fn.editable.defaults.mode = 'inline';

        $(function(){
            $(".editable").each(function(){
                $('#'+this.id).editable({
                    type: 'radiolist',
                    value: '',
                    source: [
                        {value: 1, text: ''},
                    ],
                    validate: function(value) {
                        var timelog = $("#"+this.id+"time_log").val();
                        var office_order = $("#"+this.id+"office_order").val();
                        var cdo = $("#"+this.id+"cdo").val();
                        var leave = $("#"+this.id+"leave").val();
                        $("#"+this.id).html(office_order);
                    }
                });
            })
        });

        ( function($) {
            var Radiolist = function(options) {
                this.init('radiolist', options, Radiolist.defaults);
            };
            $.fn.editableutils.inherit(Radiolist, $.fn.editabletypes.checklist);

            $.extend(Radiolist.prototype, {
                renderList : function() {
                    var $label;
                    this.$tpl.empty();
                    if (!$.isArray(this.sourceData)) {
                        return;
                    }

                    for (var i = 0; i < this.sourceData.length; i++) {
                        var ID = this.options.scope.id;
                        $label = $('<label>', {'class':this.options.inputclass}).append($('<input>', {
                            type : 'radio',
                            name : this.options.name,
                            value : this.sourceData[i].value
                        })).append($('<span>').text(this.sourceData[i].text));

                    }

                    this.$input = this.$tpl.find('input[type="radio"]');
                    var timelogToAppend = this.$tpl;
                    console.log(ID);
                    $.get("<?php echo asset('logs/append').'/' ?>"+ID,function(result){
                        timelogToAppend.append(result);
                    });
                },
                input2value : function() {
                    return this.$input.filter(':checked').val();
                },
                str2value: function(str) {
                    return str || null;
                },

                value2input: function(value) {
                    this.$input.val([value]);
                },
                value2str: function(value) {
                    return value || '';
                },
            });

            Radiolist.defaults = $.extend({}, $.fn.editabletypes.list.defaults, {
                /**
                 @property tpl
                 @default <div></div>
                 **/
                tpl : '<div class="editable-radiolist"></div>',

                /**
                 @property inputclass, attached to the <label> wrapper instead of the input element
                 @type string
                 @default null
                 **/
                inputclass : '',

                name : 'defaultname'
            });

            $.fn.editabletypes.radiolist = Radiolist;
        }(window.jQuery));

    </script>


@endsection