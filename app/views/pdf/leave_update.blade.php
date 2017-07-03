
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

    </style>
</head>

<body>



<table id="Table1" cellpadding="0" style="width: 100%;" border="1">
    <tr id="FIRST_ROW" style="border-style:Solid;">
        <td colspan="3" style="border-style:Solid;text-align: center; font-size:medium; ">APPLICATION FOR LEAVE</td>
    </tr>
    <tr>
        <td colspan="1" style="border-style:Solid;">(1.) OFFICE/AGENCY <br /><b> {{ $leave->office_agency }}</b></td>
        <td colspan="2" style="border-style:Solid;">2. NAME (Last) (First) ( M. .)
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
        <td colspan="2" style="border-style:Solid;">6a) TYPE OF LEAVE</td><td colspan="1" style="border-style:Solid;">6b) WHERE LEAVE WILL BE SPENT:</td>
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