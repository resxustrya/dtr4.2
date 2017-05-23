

<?php
/*$host = $_SERVER['HTTP_HOST'];
$uri = explode('/',$_SERVER['REQUEST_URI']);
$protocol = 'http://';
$address = $protocol.$host.'/'.$uri[1].'/index';*/
//require('dbconn.php');
require('fpdf.php');
ini_set('max_execution_time', 0);
ini_set('memory_limit','1000M');
ini_set('max_input_time','300000');


class PDF extends FPDF
{
    private $empname = "";
// Page header

    function form($name,$userid,$date_from,$date_to,$sched)
    {

        $day1 = explode('-',$date_from);
        $day2 = explode('-',$date_to);

        $startday = floor($day1[2]);
        $endday = $day2[2];

        //echo date("M",strtotime($date_from)).' '. $day1[2].'-'.$day2[2].'  '.$day2[0];
        $late_total = 0;
        $ut_total = 0;
        $late = '';
        $ut = '';

        $w = array(10,15,15,15,15);
        $index = 0;
        $log_date = "";
        $log = "";

        $pdo = conn();
        $query = "SELECT * FROM work_sched WHERE id = '".$sched ."'";
        $st = $pdo->prepare($query);
        $st->execute();
        $sched = $st->fetchAll(PDO::FETCH_ASSOC);
        if(isset($sched) and count($sched) > 0) {
            $s_am_in = $sched[0]["am_in"];
            $s_am_out =  $sched[0]["am_out"];
            $s_pm_in = $sched[0]["pm_in"];
            $s_pm_out = $sched[0]["pm_out"];

            $logs = get_logs($s_am_in,$s_am_out,$s_pm_in,$s_pm_out,$userid,$date_from,$date_to);

            if(count($logs) <= 0) {

                $this->SetFont('Arial','B',8);
                $this->SetX(100);
                $this->Cell(10,0,"NO AVAILABLE TIME LOGS BETWEEN $date_from AND $date_to FOR USERID $userid . TRY UPLOADING NEW TIME LOGS FROM BIOMETRIC",0,0,'C');

            } else {

                $this->SetFont('Arial','',8);
                $this->SetX(10);
                $this->Cell(40,10,'Civil Service Form No. 43',0);
                $this->SetX(70);
                $this->Cell(40,10,'Printed : '. date('Y-m-d'),0);

                $this->SetX(112);
                $this->Cell(40,10,'Civil Service Form No. 43',0);
                $this->SetX(-40);
                $this->Cell(40,10,'Printed : '.date('Y-m-d') ,0);

                $this->Ln(5);
                $this->SetFont('Arial','',10);
                $this->SetXY(35,15);
                $this->Cell(40,10,'DAILY TIME RECORD',0);

                $this->SetFont('Arial','BU',10);
                $this->SetXY(25,22);
                $this->Cell(60,10,'                  '.$name.'                  ',0,1,'C');

                $this->SetFont('Arial','',8);
                $this->SetXY(10,28);
                $this->Cell(40,10,'For the month of : '.date("M",strtotime($date_from)).' '. $day1[2].'-'.$day2[2].'  '.$day2[0],0);

                $this->SetFont('Arial','',8);
                $this->SetXY(60,28);
                $this->Cell(40,10,'ID No.  '.$userid,0);

                $this->SetFont('Arial','',8);
                $this->SetXY(10,33);
                $this->Cell(40,10,'Official hours for (days A.M. P.M. arrival and departure)',0);



                $this->SetFont('Arial','',10);
                $this->SetXY(135,15);
                $this->Cell(40,10,'DAILY TIME RECORD',0);

                $this->SetFont('Arial','BU',10);
                $this->SetXY(135,22);
                $this->Cell(40,10,'                  '.$name.'                  ',0,1,'C');

                $this->SetFont('Arial','',8);
                $this->SetXY(112,28);
                $this->Cell(40,10,'For the month of : '.date("M",strtotime($date_from)).' '. $day1[2].'-'.$day2[2].'  '.$day2[0],0);

                $this->SetFont('Arial','',8);
                $this->SetXY(170,28);
                $this->Cell(40,10,'ID No.  '.$userid,0);

                $this->SetFont('Arial','',8);
                $this->SetXY(112,33);
                $this->Cell(40,10,'Official hours for (days A.M. P.M. arrival and departure)',0);


                $this->SetFont('Arial','',9);
                $this->SetXY(10,42);
                $this->Cell(89,5,'                     AM                             PM              UNDERTIME',1);


                $this->SetFont('Arial','',7.5);
                $this->SetXY(10,47);
                $this->Cell(89,5,'  DAY     ARRIVAL | DEPARTURE   ARRIVAL | DEPARTURE   LATE | UT',1);

                $this->SetFont('Arial', '', 7.5);
                $this->SetXY(10,54);

                $temp1 = -0;
                $temp2 = -0;
                $condition = -0;
                $title = '';
                $am_in = '';
                $am_out = '';
                $pm_in = '';
                $pm_out = '';

                $e1 = '';
                $e2 = '';
                $e3 = '';
                $e4 = '';
                if(isset($logs) and count($logs))
                {
                    for($r1 = $startday; $r1 <= $endday; $r1++)
                    {
                        $r1 >= 1 && $r1 < 10 ? $zero='0' : $zero = '';
                        $datein = $day1[0]."-".$day1[1]."-".$zero.$r1;

                        if($index != count($logs)) {
                            if($datein == $logs[$index]['datein']){
                                $log_date = $logs[$index]['datein'];
                                $log = $logs[$index];
                                $index = $index + 1;
                            }
                        }
                        $day_name = date('D', strtotime($datein));
                        if($datein == $log_date)
                        {
                            $am_in = $log['am_in'];
                            $am_out = $log['am_out'];
                            $pm_in = $log['pm_in'];
                            $pm_out = $log['pm_out'];
                            $e1 = $log['e1'];
                            $e2 = $log['e2'];
                            $e3 = $log['e3'];
                            $e4 = $log['e4'];

                            $late = late($s_am_in,$s_pm_in,$am_in,$pm_in,$log['datein']);
                            if($late != '' or $late != null)
                            {
                                $late_total = $late_total + $late;
                            }
                            $ut = undertime($s_am_in,$s_pm_in,$am_in,$pm_in,$s_am_out,$s_pm_out,$am_out,$pm_out,$log['datein']);
                            if($ut != '' or $ut != null)
                            {
                                $ut_total = $ut_total + $ut;
                            }
                        } else {
                            $am_in = '';
                            $am_out = '';
                            $pm_in = '';
                            $pm_out = '';
                            $late = '';
                            $e1 = '';
                            $e2 = '';
                            $e3 = '';
                            $e4 = '';
                            if($day_name != 'Sat' and $day_name != 'Sun') {
                                $ut = undertime($s_am_in,$s_pm_in,$am_in,$pm_in,$s_am_out,$s_pm_out,$am_out,$pm_out,$log['datein']);
                                if($ut != '' or $ut != null)
                                {
                                    $ut_total = $ut_total + $ut;
                                }
                            }
                        }

                        $this->SetFont('Arial','',8);
                        $this->Cell(5,5,$r1,'');
                        $this->Cell(7,5,$day_name,'');

                        $am_in == '' ? ($am_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '') $am_out = 'DAY OFF';
                        if(isset($e1) and $e1 == "1"){
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }

                        $this->Cell($w[1],5,$am_in,'');
                        $this->SetTextColor(0,0,0);


                        $am_out == '' ? ($am_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        $this->SetFont('Arial','',8);
                        if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '' AND $am_out == '') $am_out = 'DAY OFF';
                        if(isset($e2) and $e2 == "1"){
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }

                        $this->Cell($w[1],5,$am_out,'');
                        $this->SetTextColor(0,0,0);

                        $pm_in == '' ? ($pm_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '' AND $am_out == '' AND $pm_in == '') $am_out = 'DAY OFF';
                        if(isset($e3) and $e3 == "1"){
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }

                        $this->Cell($w[2],5,$pm_in,'',0,'R');
                        $this->SetTextColor(0,0,0);

                        $pm_out == '' ? ($pm_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '' AND $am_out == '' AND $pm_in == '' AND $pm_out == '') $am_out = 'DAY OFF';
                        if(isset($e4) and $e4 == "1") {
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }

                        $this->Cell($w[3],5,$pm_out,'',0,'R');
                        $this->SetTextColor(0,0,0);



                        //LATE/UNDERTIME
                        //$this->Cell($w[3],5,"$late       $ut",'',0,'R');
                        $this->SetFont('Arial','',8);
                        $this->Cell(8,5,$late,'',0,'R');
                        $this->Cell(8,5,$ut,'',0,'R');


                        $this->Cell(14.5);
                        $this->Cell(5,5,$r1,'');
                        $this->Cell(7,5,$day_name,'');

                        $am_in == 'sono.1234' ? ($am_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        if(isset($e1) and $e1 == "1"){
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }
                        $this->Cell($w[1],5,$am_in,'');
                        $this->SetTextColor(0,0,0);

                        $am_out == 'sono.1234' ? ($am_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        if(isset($e2) and $e2 == "1"){
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }
                        $this->Cell($w[1],5,$am_out,'');
                        $this->SetTextColor(0,0,0);

                        $pm_in == 'sono.1234' ? ($pm_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        if(isset($e3) and $e3 == "1"){
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }
                        $this->Cell($w[2],5,$pm_in,'',0,'R');
                        $this->SetTextColor(0,0,0);

                        $pm_out == 'sono.1234' ? ($pm_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                        if(isset($e4) and $e4 == "1"){
                            $this->SetFont('Arial','U',8);
                        } else {
                            $this->SetFont('Arial','',8);
                        }
                        $this->Cell($w[3],5,$pm_out,'',0,'R');
                        $this->SetTextColor(0,0,0);


                        //LATE/UNDERTIME
                        $this->SetFont('Arial','',8);
                        //$this->Cell($w[3],5,"$late       $ut",'',0,'R');
                        $this->Cell(8,5,$late,'',0,'R');
                        $this->Cell(8,5,$ut,'',0,'R');

                        $late = '';
                        $ut = '';

                        $this->Ln();
                        if($r1 == $endday)
                        {
                            $this->SetFont('Arial','BU',8);
                            $this->SetX(52);
                            $this->Cell(5,0,'                                                                                                                   ',0,0,'C');

                            $this->SetX(154);
                            $this->Cell(5,0,'                                                                                                                   ',0,0,'C');
                            $this->Ln();

                            $this->SetFont('Arial','',9);
                            $this->Cell(10,7,'TOTAL',0,0,'C');
                            $this->SetFont('Arial','',8);

                            $this->SetX(85);
                            $this->Cell(5,7,$late_total,0,0,'C');

                            $this->SetX(93);
                            $this->Cell(5,7,$ut_total,0,0,'C');


                            $this->SetX(113);
                            $this->Cell(10,7,'TOTAL',0,0,'C');

                            $this->SetX(188);
                            $this->Cell(5,7,$late_total,0,0,'C');

                            $this->SetX(195);
                            $this->Cell(5,7,$ut_total,0,0,'C');


                            $this->Ln();

                            $this->SetFont('Arial','',7);
                            $this->SetX(45);
                            $this->Cell(10,3,'      I CERTIFY on my honor that the above entry is true and correct report',0,0,'C');
                            $this->SetX(148);
                            $this->Cell(10,3,'      I CERTIFY on my honor that the above entry is true and correct report',0,0,'C');
                            $this->Ln();
                            $this->SetX(40);
                            $this->Cell(10,3,'              of the hours work performed, record of which was made daily at the time',0,0,'C');
                            $this->SetX(144);
                            $this->Cell(10,3,'              of the hours work performed, record of which was made daily at the time',0,0,'C');
                            $this->Ln();
                            $this->SetX(25);
                            $this->Cell(10,2,'     of arrival and departure from the office.',0,0,'C');
                            $this->SetX(129);
                            $this->Cell(10,2,'     of arrival and departure from the office.',0,0,'C');
                            $this->Ln();

                            $this->SetFont('Arial','BU',8);
                            $this->SetX(50);
                            $this->Cell(5,10,'                                                                                                              ',0,0,'C');
                            $this->SetX(153);
                            $this->Cell(5,10,'                                                                                                              ',0,0,'C');
                            $this->Ln();

                            $this->SetFont('Arial','',8);
                            $this->SetX(49);
                            $this->Cell(10,0,'Verified as to the prescribed office hours',0,0,'C');
                            $this->SetX(153);
                            $this->Cell(10,0,'Verified as to the prescribed office hours',0,0,'C');
                            $this->Ln();

                            $this->SetFont('Arial','BU',8);
                            $this->SetX(50);
                            $this->Cell(5,10,'                                                                                                             ',0,0,'C');
                            $this->SetX(153);
                            $this->Cell(5,10,'                                                                                                             ',0,0,'C');
                            $this->Ln();

                            $this->SetFont('Arial','',8);
                            $this->SetX(40);
                            $this->Cell(10,0,'IN-CHARGE',0,0,'C');
                            $this->SetX(150);
                            $this->Cell(10,0,'IN-CHARGE',0,0,'C');
                        }
                    }
                }

                $this->SetFont('Arial','',9);
                $this->SetXY(112,42);
                $this->Cell(89,5,'                     AM                              PM              UNDERTIME',1);

                $this->SetFont('Arial','',7.5);
                $this->SetXY(112,47);
                $this->Cell(89,5,'  DAY     ARRIVAL | DEPARTURE   ARRIVAL | DEPARTURE   LATE | UT',1);
                $this->Ln(500);

            }
        } else {
            $this->SetFont('Arial','B',8);
            $this->SetX(100);
            $this->Cell(10,0,"ATTENDANCE FOR USERID $userid CANNOT BE GENERATED. NO WORK SCHEDULE IS SET.",0,0,'C');
        }
    }
    function SetEmpname($empname)
    {
        $this->empname = $empname;
    }
    function GetName()
    {
        return $this->empname;
    }
// Page footer

}

$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$date_from = '';
$date_to = '';
$userid = '';
if(isset($_POST['filter_range']) and isset($_POST['userid'])) {
    $str = $_POST['filter_range'];
    $temp1 = explode('-',$str);
    $temp2 = array_slice($temp1, 0, 1);
    $tmp = implode(',', $temp2);
    $date_from = date('Y-m-d',strtotime($tmp));
    $temp3 = array_slice($temp1, 1, 1);
    $tmp = implode(',', $temp3);
    $date_to = date('Y-m-d',strtotime($tmp));
    $userid = $_POST['userid'];
} else {
    $pdf->SetFont('Arial','B',10);
    $pdf->SetX(40);
    $pdf->Cell(10,0,"Something went wrong. Go back to webpage",0,0,'C');
    $pdf->Output();
    exit();
}


$pdf->SetTitle('DTR report From : ' . date('l', strtotime($date_from)) .'---'.date('l', strtotime($date_to)));
$emp = userlist($userid);

if(isset($emp) and count($emp) > 0)
{
    $pdf->form($emp[0]['fname'] . ' ' . $emp[0]['lname'] . ' ' . $emp[0]['mname'], $emp[0]['userid'], $date_from, $date_to,$emp[0]['sched']);
    $pdf->SetEmpname($emp[0]['fname'] . ' ' . $emp[0]['lname'] . ' ' . $emp[0]['mname']);
    $pdf->SetTitle($emp[0]['fname'] . ' ' . $emp[0]['lname'] . ' ' . $emp[0]['mname']);
} else {
    $pdf->SetFont('Arial','B',10);
    $pdf->SetX(40);
    $pdf->Cell(10,0,"NO RECORDS FOUND FOR $userid ",0,0,'C');
}


$pdf->Output();
exit();

function get_logs($am_in,$am_out,$pm_in,$pm_out,$id,$date_from,$date_to)
{
    $pdo = conn();


    $query = "SELECT DISTINCT e.userid, datein,

                    (SELECT DISTINCT MIN(t1.time) FROM dtr_file t1 WHERE t1.userid = '". $id."' and datein = d.datein and t1.time < '". $am_out ."') as am_in,
                    (SELECT DISTINCT MAX(t2.time) FROM dtr_file t2 WHERE t2.userid = '". $id."' and datein = d.datein and t2.time < '". $pm_in."' AND t2.event = 'OUT') as am_out,
                    (SELECT DISTINCT MIN(t3.time) FROM dtr_file t3 WHERE t3.userid = '". $id."' AND datein = d.datein and t3.time >= '". $am_out."' and t3.time < '". $pm_out."' AND t3.event = 'IN' ) as pm_in,
                    (SELECT DISTINCT MAX(t4.time) FROM dtr_file t4 WHERE t4.userid = '". $id."' AND datein = d.datein and t4.time > '". $pm_in ."' and t4. time < '24:00:00') as pm_out,

                    (SELECT t1.edited FROM dtr_file t1 WHERE t1.userid = '". $id."' and datein = d.datein and t1.time < '". $am_out ."' AND t1.edited = '1' LIMIT 1) as e1,
                    (SELECT t2.edited  FROM dtr_file t2 WHERE t2.userid = '". $id."' and datein = d.datein and t2.time < '". $pm_in."' AND t2.event = 'OUT' AND t2.edited = '1' LIMIT 1) as e2,
                    (SELECT t3.edited FROM dtr_file t3 WHERE t3.userid = '". $id."' AND datein = d.datein and t3.time >='". $am_out."' and t3.time < '". $pm_out."' AND t3.event = 'IN' AND t3.edited = '1' LIMIT 1) as e3,
                    (SELECT t4.edited FROM dtr_file t4 WHERE t4.userid = '". $id."' AND datein = d.datein and t4.time > '". $pm_in ."'  and t4. time < '24:00:00' AND t4.edited = '1' LIMIT 1) as e4

                    FROM dtr_file d LEFT JOIN users e
                        ON d.userid = e.userid
                    WHERE d.datein BETWEEN :date_from AND :date_to
                          AND e.userid = :id
                    ORDER BY datein ASC";
    try
    {
        $st = $pdo->prepare($query);
        $st->execute(array('date_from' => $date_from, 'date_to' => $date_to, 'id' => $id));
        $row = $st->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $ex){
        echo $ex->getMessage();
        exit();
    }
    return $row;
}
function conn()
{
    $pdo = null;
    try{
        $pdo = new PDO('mysql:host=localhost; dbname=dohdtr','root','');
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch (PDOException $err) {
        $err->getMessage() . "<br/>";
        die();
    }
    return $pdo;
}
function userlist($id)
{
    $pdo = conn();
    try {
        $st = $pdo->prepare("SELECT DISTINCT userid,fname,lname,mname,sched FROM users WHERE usertype != '1' and userid !='Unknown User' and userid = :id" );
        $st->bindParam(":id", $id);
        $st->execute();
        $row = $st->fetchAll(PDO::FETCH_ASSOC);
        if(isset($row) and count($row) > 0)
        {
            $pdo = null;
            return $row;
        }
    }catch(PDOException $ex)
    {
        echo $ex->getMessage();
        exit();
    }
}
function save_file_name($filename,$date_from,$date_to)
{
    $pdo = conn();
    $time = date("h:i:sa");
    $date = date("Y-m-d");
    $userid = "0001";
    $query = "INSERT INTO generated_pdf(filename,date_created,time_created,date_from,date_to,created_at,updated_at,type,is_filtered)";
    $query .= " VALUES('".$filename . "','" . $date . "','" . $time . "','". $date_from. "','".$date_to ."',NOW(),NOW(),'JO','0')";
    $st = $pdo->prepare($query);
    $st->execute();
    $pdo = null;
}

//RUSEL
function check_inclusive_name($id)
{
    $db = conn();
    $sql = 'SELECT * FROM inclusive_name where user_id = ?';
    $pdo = $db->prepare($sql);
    $pdo->execute(array($id));
    $row = $pdo->fetchAll();
    $db = null;

    return $row;
}

function check_calendar($route_no)
{
    $db = conn();
    $sql = 'SELECT * FROM calendar where route_no = ?';
    $pdo = $db->prepare($sql);
    $pdo->execute(array($route_no));
    $row = $pdo->fetch();
    $db = null;

    return $row;
}

function look_calendar($datein,$userid,$temp1,$temp2){
    $condition = floor(strtotime($datein) / (60 * 60 * 24));
    foreach(check_inclusive_name($userid) as $check)
    {
        if(check_calendar($check['route_no'])) {
            $title = check_calendar($check['route_no']);

            $temp1 = floor(strtotime($title['start']) / (60 * 60 * 24));
            $temp2 = floor(strtotime($title['end']) / (60 * 60 * 24));
        }
        if($condition < $temp2 and $condition > $temp1 and $title != ''){
            return 'sono.1234';
        }
    }
}

function late($s_am_in,$s_pm_in,$am_in,$pm_in,$datein)
{
    $hour = 0;
    $min = 0;
    $total = 0;

    if(isset($am_in) and $am_in != '' || $am_in != null) {
        if(strtotime($am_in) > strtotime($s_am_in)) {
            $a = new DateTime($datein.' '. $am_in);
            $b = new DateTime($datein.' '. $s_am_in);

            $interval = $a->diff($b);
            $hour1 = $interval->h;
            $min1 = $interval->i;


            if($hour1 > 0) {
                $hour1 = $hour1 * 60;
            }
            $total += ($hour1 + $min1);
        }

    }

    if(isset($pm_in) and $pm_in != '' || $pm_in != null) {
        if(strtotime($pm_in) > strtotime($s_pm_in)) {
            $a = new DateTime($datein.' '.$pm_in);
            $b = new DateTime($datein.' '.$s_pm_in);

            $interval = $a->diff($b);
            $hour2 = $interval->h;
            $min2 = $interval->i;


            if($hour2 > 0) {
                $hour2 = $hour2 * 60;
            }

            $total += ($hour2 + $min2);
        }
    }

    if($total == 0) $total = '';
    return $total;

}

function undertime($s_am_in,$s_pm_in,$am_in,$pm_in,$s_am_out,$s_pm_out,$am_out,$pm_out,$datein)
{

    $hour = '';
    $min = '';
    $total = '';

    if($am_in == '' and $am_out == '') {
        $a = new DateTime($datein.' '. $s_am_in);
        $b = new DateTime($datein.' '. $s_am_out);

        $interval = $b->diff($a);
        $hour1 = $interval->h;
        $min1 = $interval->i;

        if($hour1 > 0) {
            $hour1 = $hour1 * 60;
        }
        $total += ($hour1 + $min1);

    }

    if($pm_in == '' and $pm_out == '') {

        $a = new DateTime($datein.' '. $s_pm_in);
        $b = new DateTime($datein.' '. $s_pm_out);

        $interval = $b->diff($a);
        $hour2 = $interval->h;
        $min1 = $interval->i;

        if($hour2 > 0) {
            $hour2 = $hour2 * 60;
        }
        $total += ($hour2 + $min1);

    }

    if(isset($am_out) and $am_out != '' || $am_out != null) {
        if(strtotime($am_out) < strtotime($s_am_out)) {
            $a = new DateTime($datein.' '. $am_out);
            $b = new DateTime($datein.' '. $s_am_out);

            $interval = $b->diff($a);
            $hour1 = $interval->h;
            $min1 = $interval->i;

            if($hour1 > 0) {
                $hour1 = $hour1 * 60;
            }
            $total += ($hour1 + $min1);
        }
    }
    if(isset($pm_out) and $pm_out != '' || $pm_out != null) {

        if(strtotime($pm_out) < strtotime($s_pm_out)) {
            $hour2 = 0;
            $a = new DateTime($datein.' '.$pm_out);
            $b = new DateTime($datein.' '.$s_pm_out);

            $interval = $b->diff($a);
            $hour2 = $interval->h;
            $min2 = $interval->i;

            if($hour2 > 0) {
                $hour2 = $hour2 * 60;
            }
            $total += ($hour2 + $min2);
        }
    }
    if($total == 0 ) $total = '';
    return $total;
}

?>