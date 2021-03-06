<html lang="en">
<title>CTO</title>
<head>
    <link href="{{ realpath(__DIR__ . '/../../..').'/public/assets/css/print.css' }}" rel="stylesheet">
    <style>
        html {
            margin-top: 20px;
            margin-right: 20px;
            margin-left: 20px;
            margin-bottom: 0px;
            font-size:x-small;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        #border{
            border-collapse: collapse;
            border: none;
        }
        #border-top{
            border-collapse: collapse;
            border-top: none;
        }
        #border-right{
            border-collapse: collapse;
            border:1px solid #000;
        }
        #border-bottom{
            border-collapse: collapse;
            border-bottom: none;
        }
        #border-bottom-t{
            border-collapse: collapse;
            border-top:1px solid red;
            border-bottom:1px solid red;
        }
        #border-left{
            border-collapse: collapse;
            border:1px solid #000;
        }
        .align{
            text-align: center;
        }
        .align-top{
            vertical-align : top;
        }
        .align-right{
            text-align : right;
        }
        .table1 {
            width: 100%;
        }
        .table1 td {
            border:1px solid #000;
        }
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }
        .footer {
            bottom: 15px;
        }
        .pagenum:before {
            content: counter(page);
        }
        .pagenum:before {
            content: counter(page);
        }
        .new-times-roman{
            font-family: "Times New Roman", Times, serif;
            font-size: 9.5pt;
        }
        #no-border{
            border-collapse: collapse;
            border: none;
        }
    </style>
</head>
<body>
@for($i=0;$i<2;$i++)
    <div class="new-times-roman" style="margin-top:<?php if($i == 1) echo '3.5%'; else echo '0%'; ?>">
        @if($i == 1)
            <hr>
        @endif
        <table class="letter-head" cellpadding="0" cellspacing="0">
            <tr>
                <td id="no-border" class="align"><img src="{{ realpath(__DIR__ . '/../../..').'/public/img/doh.png' }}" width="100"></td>
                <td width="100%" id="no-border">
                    <div class="align" style="font-size: 10.5pt">
                        Republic of the Philippines<br>
                        DEPARTMENT OF HEALTH<br>
                        <strong>CENTRAL VISAYAS CENTER for HEALTH DEVELOPMENT</strong><br>
                        Osmeña Boulevard,Sambag II,Cebu City, 6000 Philippines<br>
                        Regional Director’s Office Tel. No. (032) 253-6355 Fax No. (032) 254-0109<br>
                        Official Website: http://www.ro7.doh.gov.ph Email Address: dohro7@gmail.com<br>
                    </div>
                </td>
                <td id="no-border" class="align"><img src="{{ realpath(__DIR__ . '/../../..').'/public/img/f1.jpg' }}" width="100"></td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td id="border" width="7%">Section:</td>
                <td id="border"><u>{{ $data['section'] }}</u></td>
                <td id="border" width="7%">Cluster:</td>
                <td id="border"><u>{{ $data['division'] }}</u></td>
                <td id="border" width="10%">Prepared Date:</td>
                <td id="border"><u>{{ date('M d, Y',strtotime($data['cdo']['prepared_date'])) }}<br>{{ date('h:i:s A',strtotime($data['cdo']['prepared_date'])) }}</u></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td id="border" width="60%">
                    <table class="letter-head" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="30%"><b>Name</b></td>
                            <td width="3%"><b>Signature</b></td>
                            <td width="30%"><b>Position</b></td>
                        </tr>
                        <tr>
                            <td>{{ $data['name'] }}</td>
                            <td></td>
                            <td>{{ $data['position'] }}</td>
                        </tr>
                    </table>
                </td>
                <td id="border" class="align-top">
                    <div style="margin-left: 2%">
                        <b >NUMBER OF WORKING DAY/S APPLIED FOR:</b><br>
                        <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;
                            <?php
                                if($data['cdo']['less_applied_for'] == 4){
                                    echo 'half day ('.strtoupper(explode('_',$data['cdo']['cdo_hours'])[1]).')';
                                }
                                else {
                                    if($data['cdo']['less_applied_for'] % 8 == 0){
                                        $halfWord = '';
                                        $minusDay = 0;
                                    } else{
                                        $halfWord = ' and half ('.strtoupper(explode('_',$data['cdo']['cdo_hours'])[1]).')';
                                        $minusDay = 1;
                                    }

                                    if($data['cdo']['working_days']-$minusDay > 1)
                                        echo $data['cdo']['working_days']-$minusDay.' days'.$halfWord;
                                    else
                                        echo $data['cdo']['working_days']-$minusDay.' day'.$halfWord;
                                }

                            ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </u>
                        <br><br>
                        <b>
                            INCLUSIVE DATES: <u>{{ date('M d, Y',strtotime($data['cdo']['start'])).' to '.date('M d, Y',strtotime('-1 day',strtotime($data['cdo']['end']))) }} </u>
                        </b>
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td id="border" width="60%">
                    <table class="letter-head" cellpadding="0" cellspacing="0">
                        <tr>
                            <td id="border" colspan="3"><b>CERTIFICATION OF COMPENSATORY OVERTIME CREDITS</b></td>
                        </tr>
                        <tr>
                            <td>
                                <b>Beginning Balance</b>
                                <br>
                                (In Hours)
                            </td>
                            <td>
                                <b>Less Applied for</b>
                                <br>
                                (In Hours)
                            </td>
                            <td>
                                <b>Remaining Balance</b>
                                <br>
                                (In Hours)
                            </td>
                        </tr>
                        <tr>
                            <td>@if($data['cdo']['beginning_balance'] != null) {{ $data['cdo']['beginning_balance'] }} @else.@endif</td>
                            <td>@if($data['cdo']['less_applied_for'] != null) {{ $data['cdo']['less_applied_for'] }} @else.@endif</td>
                            <td>@if($data['cdo']['remaining_balance'] != null) {{ $data['cdo']['remaining_balance'] }} @else.@endif</td>
                        </tr>
                    </table>
                </td>
                <td id="border" class="align-top">
                    <table class="letter-head" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="2" id="border"><b>RECOMENDATION:</b></td>
                        </tr>
                        <tr>
                            <td class="align-right" id="border">@if($data['cdo']['approved_status'] == 1)<div style="font-family: DejaVu Sans, sans-serif;font-size: 14pt">[✔]</div>@else[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]@endif</td>
                            <td id="border">Approval</td>
                        </tr>
                        <tr>
                            <td class="align-right" id="border">[&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</td>
                            <td id="border">Disapproval</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="60%">
                    <table width="100%">
                        <tr><td class="align"><strong>THERESA Q. TRAGICO</strong></td></tr>
                        <tr><td class="align">Administrative Officer V</td></tr>
                        <tr><td class="align">Personel Section</td></tr>
                    </table>
                </td>
                <td>
                    <table width="100%">
                        <tr><td class="align"><u>&nbsp;&nbsp;{{ $data['section_head']['fname'].' '.$data['section_head']['mname'].' '.$data['section_head']['lname'] }}&nbsp;&nbsp;</u></td></tr>
                        <tr><td class="align">Immediate Supervisor</td></tr>
                        <tr><td class="align"><br><u>&nbsp;&nbsp;{{ $data['division_head']['fname'].' '.$data['division_head']['mname'].' '.$data['division_head']['lname'] }}&nbsp;&nbsp;</u></td></tr>
                        <tr><td class="align">Division/Cluster Chief</td></tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <div style="position:absolute; left: 30%;" class="align">
            <?php echo DNS1D::getBarcodeHTML(Session::get('route_no'),"C39E",1,28) ?>
            <font class="route_no">{{ Session::get('route_no') }}</font>
        </div>
    </div>
@endfor
</body>
</html>