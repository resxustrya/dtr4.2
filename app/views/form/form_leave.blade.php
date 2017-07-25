@extends('layouts.app')

@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <h3 class="page-header">Create Application for Leave
            </h3>
            <div class="panel panel-default">
                <div class="panel-heading text-center"><strong style="color: #f0ad4e;font-size:medium;">Fill up leave form</strong></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-11">
                            <form action="{{ asset('form/leave') }}" method="POST">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess1">(1.) Office/Agency</label>
                                            <input type="text" class="form-control" id="inputSuccess1" name="office_agency" value="DOH 7">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess1">(2.)  Last Name</label>
                                            <input type="text" class="form-control" id="inputSuccess1" name="lastname" value="{{ $user->lname }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess1">First Name</label>
                                            <input type="text" class="form-control" id="inputSuccess1" name="firstname" value="{{ $user->fname }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess1">Middle Name</label>
                                            <input type="text" class="form-control" id="inputSuccess1" name="middlename" value="{{ $user->mname }}">
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-success  input-daterange">
                                            <label class="control-label" for="inputSuccess1">(3.) Date of Filling</label>
                                            <input type="text" class="form-control" name="date_filling" value="{{ date("Y-m-d") }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess1">(4.)  Position</label>
                                            <input type="text" class="form-control" id="inputSuccess1" name="position">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-success">
                                            <label class="control-label" for="inputSuccess1">(5.)Salary (Monthly)</label>
                                            <input type="text" class="form-control" id="inputSuccess1" name="salary" onkeypress='validate(event)'>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <h3 class="text-center">DETAILS OF APPLICATION</h3>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <strong>(6a) TYPE OF LEAVE</strong>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" value="Vication" name="leave_type">
                                                                    Vacation
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" value="To_sake_employement" name="leave_type">
                                                                    To seek employement
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="radio_others" value="Others" name="leave_type" />
                                                                    Others(Specify)
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="form-group has-success">
                                                                <textarea type="text" class="form-control" maxlength="" id="inputSuccess1" name="leave_type_others_1"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" value="Sick" name="leave_type" />
                                                                    Sick
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" value="Maternity" name="leave_type" />
                                                                    Maternity
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" value="Others2" name="leave_type">
                                                                    Others(Specify)
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="form-group has-success">
                                                                <textarea type="text" class="form-control" maxlength="200" id="inputSuccess1" name="leave_type_others_2"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <strong>(6c.)NUMBER OF WORKING DAYS APPLIED <br />FOR :</strong>
                                            <input type="text" name="applied_num_days" />
                                            <div class="form-group">
                                                <label class="control-label" for="inputSuccess1">Inclusive Dates :</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="inc_date" name="inc_date" placeholder="Input date range here..." required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <strong>(6b) WHERE LEAVE WILL BE SPENT</strong>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>(1.)In case of vacation leave</label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" class="vic_dis vic_dis_radio" value="local" name="vication_loc">
                                                                    Within the Philippines
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" class="vic_dis vic_dis_radio" value="abroad" name="vication_loc">
                                                                    Abroad (specify)
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="form-group has-success">
                                                                <textarea type="text" class="form-control vic_dis vic_dis_radio" maxlength="200" id="inputSuccess1" name="abroad_others"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>(2.)In case of sick leave</label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess"  class="sic_dis sic_dis_radio" value="in_hostpital" name="sick_loc">
                                                                    In Hospital (sepecify)
                                                                    <input type="text"  name="in_hospital_specify" class="sic_dis" />
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="has-success">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="radio" id="checkboxSuccess" value="out_patient" name="sick_loc sic_dis_radio" class="sic_dis">
                                                                    Out-patient (sepecify)
                                                                    <input type="text" name="out_patient_specify" class="sic_dis" />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <strong>(6d) COMMUTATION</strong>
                                            <div class="has-success">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="radio" id="checkboxSuccess" value="yes" name="com_requested">
                                                        Requested
                                                    </label>
                                                    <label>
                                                        <input type="radio" id="checkboxSuccess" value="no" name="com_requested">
                                                        Not Requested
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <br />
                                <div class="row">
                                    <div class="col-md-12 center-block">
                                        <button type="submit" name="submit" class="btn btn-primary btn-lg col-md-5">Submit</button>
                                    </div>
                                </div>
                            </form>
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

        $('#inc_date').daterangepicker();
        $('input[name="leave_type"]').change(function(){

            var val = this.value;


            if(val != "Vication") {
                $('.vic_dis').prop('disabled', true);
            } else {
                $('.vic_dis').prop('disabled', false);
            }

            if(val != "Sick") {
                $('.sic_dis').prop('disabled', true);
            } else {
                $('.sic_dis').prop('disabled', false);
            }

           if(val == "To_sake_employement") {
                if(this.getAttribute("type") == "radio") {
                    $('.sic_dis_radio').prop("checked", true);
                    $('.vic_dis_radio').prop("checked", true);
                }
           }
        });

        function validate(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var regex = /[0-9]|\./;
            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>
@endsection