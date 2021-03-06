
<!DOCTYPE html>
<html>
<head>

    <title>
        Application fo Leaves
    </title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: x-small;
            padding: -25px;
        }
        table{
            border-spacing: 0;
            margin-top: 30px;
        }

        table td {
            padding-top: 10px;
            padding-right: 5px;
            padding-bottom: 3px;
            padding-left: 5px;
        }
        table#type_leave tr td {
            padding-top: 0px;
            padding-right: 0px;
            padding-bottom: 0px;
            padding-left: 0px;
        }

    </style>
</head>

<body>



<table id="Table1" cellpadding="0" style="width: 100%;" border="1">
    <tr id="FIRST_ROW" style="border-style:Solid;">
        <td colspan="3" style="border-style:Solid;text-align: center; font-size:medium; ">APPLICATION FOR LEAVE</td>
    </tr>
    <tr>
        <td colspan="1" style="border-style:Solid; vertical-align: top;">(1.) OFFICE/AGENCY <br /><b> {{ $leave->office_agency }}</b></td>
        <td colspan="2" style="border-style:Solid; vertical-align: top;">2. NAME (Last) (First) ( M. .)
            <div style="padding: 8px;">
                <span class="col-sm-3">&nbsp;</span>
                <span class="col-sm-3 tab1"><b>{{ $leave->lastname }}</b></span>
                <span class="col-sm-3"><b>{{ $leave->firstname }}</b></span>
                <span class="col-sm-3"><b>{{ $leave->middlename }}</b></span>
            </div>
        </td>
    </tr>
    <tr>
        <td style="border-style:Solid;">3. DATE OF FILING <br /><b>{{ $leave->date_filling }}</b></td>
        <td style="border-style:Solid;">4. POSITION <br /><b>{{ $leave->position }}</b></td>
        <td style="border-style:Solid;">5. SALARY (MONTHLY) <br /><b>{{ sprintf("%.2f",$leave->salary); }}</b></td>
    </tr>
    <tr>
        <td colspan="3" style="border-style:Solid;text-align: center; font-size: medium;"> DETAILS OF APPLICATION</td>
    </tr>
    <tr>
        <td colspan="2" style="border-style:solid;">
            <table border="1" style="width: 90%;margin-top:-27px;" id="type_leave">
                <tr>
                    <td colspan="2"><b>6a) TYPE OF LEAVE</b></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->leave_type == "Vication")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>VACATION</strong></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->leave_type == "To_sake_employement")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>TO SAKE EMPLOYEMENT</strong></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->leave_type == "Others")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>OTHERS (specify)</strong></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        @if(isset($leave->leave_type_others_1))
                            <span class="tab2"><em>{{  $leave->leave_type_others_1 }}</em></span>
                        @endif
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->leave_type == "Sick")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>SICK</strong></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->leave_type == "Maternity")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>MATERNITY</strong></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->leave_type == "Others2")
                            <b><span style="font-family: DejaVu Sans;">&#10004; </span></b>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>OTHERS (specify)</strong></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        @if(isset($leave->leave_type_others_2))
                            <span class="tab2"><em>{{  $leave->leave_type_others_2 }}</em></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><b>(6c) NUMBER OF WORKING DAYS APPLIED FOR :</b></td>
                </tr>
                </tbody>
            </table>
        </td>
        <td colspan="1" style="border-style:Solid;">
            <table border="1" style="width: 90%;margin-top:-10px;" id="type_leave">
                <tr>
                    <td colspan="2"><b>6b) WHERE LEAVE WILL BE SPENT:</b></td>
                </tr>
                <tr>
                    <td colspan="2"><b style="margin-left: 20px;">(1) In case of vacation leave</b></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->vication_loc == "local")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong> Within the Philippines</strong></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->vication_loc == "abroad")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>Abroad (specify)</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><b style="margin-left: 20px;">(2) In case of sick leave</b></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->sick_loc == "in_hostpital")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>In Hospital (Specify)</strong></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">&nbsp;</td>
                    <td>
                        <em>
                            @if(isset($leave->in_hospital_specify))
                                {{ $leave->in_hospital_specify }}
                            @else
                                <strong><hr /></strong>
                            @endif
                        </em>
                    </td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->sick_loc == "out_patient")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @endif
                    </td>
                    <td><strong>Out-patient (specify)</strong></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <em>
                            @if(isset($leave->out_patient_specify))
                                {{ $leave->out_patient_specify }}
                            @else
                                <strong><hr /></strong>
                            @endif
                        </em>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><b>6d) COMMUTATION</b></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->com_requested == "yes")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>

                        @endif
                    </td>
                    <td><strong>Requested</strong></td>
                </tr>
                <tr>
                    <td style="width: 25%;text-align: right;">
                        @if($leave->com_requested == "no")
                            <strong><span style="font-family: DejaVu Sans;">&#10004; </span></strong>
                        @else
                            <span style="text-decoration: underline;width: 10px;" aria-hidden="true">&nbsp;&nbsp;&nbsp;&nbsp;</span>

                        @endif
                    </td>
                    <td><strong>Not Requested</strong></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">

                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border-style:Solid;">DETAILS OF ACTION ON APPLICATION</td>
    </tr>
    <tr>
        <td colspan="2" style="border-style:Solid;">7a) CERTIFICATION OF LEAVE CREDITS</td><td colspan="1" style="border-style:Solid;"> 7b) RECOMMENDATION</td>
    </tr>
    <tr>
        <td colspan="2" style="border-style:Solid;">7c) APPROVED FOR:</td><td colspan="1" style="border-style:Solid;"> 7d) DISAPPROVED DUE TO:</td>
    </tr>
    <tr>
        <td colspan="3" style="border-style:Solid;">By Authority of the Secretary of Health</td>
    </tr>
</table>


<div style="padding: 4px; text-align: center;">
    <strong>{{ $leave->route_no }}</strong>
    <br />
    <img src="data:image/png;base64,{{  DNS1D::getBarcodePNG($leave->route_no, 'C39E' ,1,15)}}" alt="barcode" style="width:400px;" />
</div>
</body>
</html>
