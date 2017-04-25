

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
    function form($name,$userid,$date_from,$date_to)
    {

        $day1 = explode('-',$date_from);
        $day2 = explode('-',$date_to);

        $startday = floor($day1[2]);
        $endday = $day2[2];

        //echo date("M",strtotime($date_from)).' '. $day1[2].'-'.$day2[2].'  '.$day2[0];
        $late_total = 0;
        $ut_total = 0;

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

        $w = array(10,15,15,15,15);
        $index = 0;
        $log_date = "";
        $log = "";
        $late = '';
        $ut = '';
        $pdo = conn();
        $query = "SELECT * FROM work_sched WHERE id = '1'";
        $st = $pdo->prepare($query);
        $st->execute();
        $sched = $st->fetchAll(PDO::FETCH_ASSOC);

        $s_am_in = $sched[0]["am_in"];
        $s_am_out =  $sched[0]["am_out"];
        $s_pm_in = $sched[0]["pm_in"];
        $s_pm_out = $sched[0]["pm_out"];

        $logs = get_logs($userid,$date_from,$date_to);

        $temp1 = -0;
        $temp2 = -0;
        $condition = -0;
        $title = '';
        $am_in = '';
        $am_out = '';
        $pm_in = '';
        $pm_out = '';
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

                    $late = late($s_am_in,$s_pm_in,$am_in,$pm_in,$log['datein']);
                    if($late != '' or $late != null)
                    {
                        $late_total = $late_total + $late;
                    }
                    $ut = undertime($s_am_out,$s_pm_out,$am_out,$pm_out,$log['datein']);
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
                }

                $this->Cell(5,5,$r1,'');
                $this->Cell(7,5,$day_name,'');

                $am_in == '' ? ($am_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '') $am_out = 'DAY OFF';
                $this->Cell($w[1],5,$am_in,'');
                $this->SetTextColor(0,0,0);

                $am_out == '' ? ($am_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '' AND $am_out == '') $am_out = 'DAY OFF';
                $this->Cell($w[1],5,$am_out,'');
                $this->SetTextColor(0,0,0);

                $pm_in == '' ? ($pm_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '' AND $am_out == '' AND $pm_in == '') $am_out = 'DAY OFF';
                $this->Cell($w[2],5,$pm_in,'',0,'R');
                $this->SetTextColor(0,0,0);

                $pm_out == '' ? ($pm_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                if($day_name == 'Sat' || $day_name == 'Sun' AND $am_in == '' AND $am_out == '' AND $pm_in == '' AND $pm_out == '') $am_out = 'DAY OFF';
                $this->Cell($w[3],5,$pm_out,'',0,'R');
                $this->SetTextColor(0,0,0);



                //LATE/UNDERTIME
                //$this->Cell($w[3],5,"$late       $ut",'',0,'R');
                $this->Cell(8,5,$late,'',0,'R');
                $this->Cell(8,5,$ut,'',0,'R');

                $this->Cell(14.5);
                $this->Cell(5,5,$r1,'');
                $this->Cell(7,5,$day_name,'');

                $am_in == 'sono.1234' ? ($am_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                $this->Cell($w[1],5,$am_in,'');
                $this->SetTextColor(0,0,0);

                $am_out == 'sono.1234' ? ($am_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                $this->Cell($w[1],5,$am_out,'');
                $this->SetTextColor(0,0,0);

                $pm_in == 'sono.1234' ? ($pm_in = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                $this->Cell($w[2],5,$pm_in,'',0,'R');
                $this->SetTextColor(0,0,0);

                $pm_out == 'sono.1234' ? ($pm_out = look_calendar($datein,$userid,$temp1,$temp2) AND $this->SetTextColor(255,0,0)) : $this->SetTextColor(0,0,0);
                $this->Cell($w[3],5,$pm_out,'',0,'R');
                $this->SetTextColor(0,0,0);


                //LATE/UNDERTIME
                //$this->Cell($w[3],5,"$late       $ut",'',0,'R');
                $this->Cell(8,5,$late,'',0,'R');
                $this->Cell(8,5,$ut,'',0,'R');

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
if(isset($_POST['filter_range'])) {
    $str = $_POST['filter_range'];
    $temp1 = explode('-',$str);
    $temp2 = array_slice($temp1, 0, 1);
    $tmp = implode(',', $temp2);
    $date_from = date('Y-m-d',strtotime($tmp));
    $temp3 = array_slice($temp1, 1, 1);
    $tmp = implode(',', $temp3);
    $date_to = date('Y-m-d',strtotime($tmp));
}
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->SetTitle('DTR report From : ' . date('l', strtotime($date_from)) .'---'.date('l', strtotime($date_to)));
$row = userlist();
if(isset($row) and count($row) > 0)
{
    for($i = 0; $i < count($row); $i++) {
        $pdf->form($row[$i]['fname'] . ' ' . $row[$i]['lname'] . ' ' . $row[$i]['mname'], $row[$i]['userid'], $date_from, $date_to);
        $pdf->SetEmpname($row[$i]['fname'] . ' ' . $row[$i]['lname'] . ' ' . $row[$i]['mname']);
    }
}
$time = rand(1,1000);
$filename = __DIR__.'/pdf-files/'.$time.'-dtr-'.$date_from .'-'.$date_to.'_.pdf';
$file =  $time.'-dtr-'.$date_from .'-'.$date_to.'_.pdf';
save_file_name($file,$date_from,$date_to);
$pdf->Output($filename,'F');
$host = $_SERVER['HTTP_HOST'];
$uri = explode('/',$_SERVER['REQUEST_URI']);
$protocol = 'http://';
$address = $protocol.$host.'/'.$uri[1].'/dtr/list/jo';
header('Location:'.$address);
exit();


function get_logs($id,$date_from,$date_to)
{
    $pdo = conn();
    $query = "SELECT * FROM work_sched WHERE id = '1'";
    $st = $pdo->prepare($query);
    $st->execute();
    $sched = $st->fetchAll(PDO::FETCH_ASSOC);
    $am_in = explode(':',$sched[0]['am_in']);
    $am_out =  explode(':',$sched[0]['am_out']);
    $pm_in =  explode(':',$sched[0]['pm_in']);
    $pm_out = explode(':',$sched[0]['pm_out']);
    $query = "SELECT DISTINCT e.userid, datein,
                    (SELECT MIN(t1.time) FROM dtr_file t1 WHERE t1.userid = '". $id."' and datein = d.datein and t1.time_h < ". $am_out[0] .") as am_in,
                    (SELECT MAX(t2.time) FROM dtr_file t2 WHERE t2.userid = '". $id."' and datein = d.datein and t2.time_h < ". $pm_in[0]." AND t2.event = 'OUT') as am_out,
                    (SELECT MIN(t3.time) FROM dtr_file t3 WHERE t3.userid = '". $id."' AND datein = d.datein and t3.time_h >= ". $am_out[0]." and t3.time_h < ". $pm_out[0]." AND t3.event = 'IN' ) as pm_in,
                    (SELECT MAX(t4.time) FROM dtr_file t4 WHERE t4.userid = '". $id."' AND datein = d.datein and t4.time_h > ". $pm_in[0] ." and t4. time_h < 24) as pm_out
                    FROM dtr_file d LEFT JOIN users e
                        ON d.userid = e.userid
                    WHERE d.datein BETWEEN '". $date_from. "' AND '" . $date_to . "'
                          AND e.userid = '". $id."'
                    ORDER BY datein ASC";
    try
    {
        $st = $pdo->prepare($query);
        $st->execute();
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
function userlist()
{
    $pdo = conn();
    try {
        $st = $pdo->prepare("SELECT DISTINCT e.userid,e.fname,e.lname,e.mname FROM users e LEFT JOIN dtr_file d ON e.userid = d.userid WHERE e.usertype != '1' and e.emptype = 'JO' ORDER BY e.lname ASC");
        //$st = $pdo->prepare("SELECT DISTINCT userid,fname,lname,mname FROM users WHERE usertype != '1' and userid !='Unknown User' ORDER BY lname ASC");
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
function undertime($s_am_out,$s_pm_out,$am_out,$pm_out,$datein)
{

    $hour = '';
    $min = '';
    $total = '';

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
            $a = new DateTime($datein.' '.$pm_out);
            $b = new DateTime($datein.' '.$s_pm_out);

            $interval = $b->diff($a);
            $hour2 = $interval->h;
            $min2 = $interval->i;


            if($hour2 > 0) {
                $hour2 = $hour2 * 60;
            }

            /*echo "<br />";
            echo "DATE IN : " .$datein;
            echo "<br />";
            echo "S_PM_OUT : " . $s_pm_out;
            echo "<br />";
            echo "PM OUT : " . $pm_out;
            echo "<br />";
            echo "Hour1 : " .$hour2;
            echo "<br />";
            echo "Min1 : " . $min2;
            echo "<br />";

            echo "PM OUT TOTAL : " . $total;
            echo "<br /><br />";*/
            $total += ($hour2 + $min2);
        }
    }
    if($total == 0 ) $total = '';
    return $total;
}


?>
