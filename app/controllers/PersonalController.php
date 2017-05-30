<?php



/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 1/12/2017
 * Time: 10:03 AM
 */

ini_set('max_execution_time', 0);
ini_set('memory_limit','1000M');
ini_set('max_input_time','300000');
class PersonalController extends Controller
{
    private $userid;
    public function __construct()
    {
        $this->beforeFilter('personal');
    }

    public function index()
    {
        if(Request::method() == 'POST'){
            if(Input::has('filter_range1')) {
                $str = $_POST['filter_range1'];
                $temp1 = explode('-',$str);
                $temp2 = array_slice($temp1, 0, 1);
                $tmp = implode(',', $temp2);
                $date_from = date('Y-m-d',strtotime($tmp));
                $temp3 = array_slice($temp1, 1, 1);
                $tmp = implode(',', $temp3);
                $date_to = date('Y-m-d',strtotime($tmp));

                $lists = DB::table('dtr_file')
                    ->leftJoin('users', function($join){
                        $join->on('dtr_file.userid', '=', 'users.userid')
                            ->where('users.userid', '<>', '1')
                            ->where('users.userid', '<>', '--');
                    })
                    ->where('dtr_file.userid', '=', Auth::user()->userid)
                    ->whereBetween('datein', array($date_from,$date_to))
                    ->orderBy('dtr_file.datein', 'ASC')
                    ->paginate(20);

                return View::make('employee.index')->with('lists',$lists);
            } else {
                return Redirect::to('personal/index');
            }
        }
        if(Request::method() == 'GET') {
            $date = explode("-",date("Y-m-d"));
            $firt_day = "$date[0]-$date[1]-01";
            $last_day = "$date[0]-$date[1]-31";

            $lists = DB::table('dtr_file')
                ->leftJoin('users', function($join){
                    $join->on('dtr_file.userid', '=', 'users.userid')
                        ->where('users.userid', '<>', '1')
                        ->where('users.userid', '<>', '--');
                })
                ->where('dtr_file.userid', '=', Auth::user()->userid)
                ->whereBetween('datein', array($firt_day,$last_day))
                ->orderBy('dtr_file.datein', 'ASC')
                ->paginate(20);

            return View::make('employee.index')->with('lists',$lists);
        }

    }
    public function edit_attendance($id = null)
    {

        if(Request::method() == 'GET') {
            if(isset($id)) {
                Session::put('dtr_id',$id);
            }
            $dtr = DtrDetails::where('dtr_id', $id)->first();
            return View::make('employee.edit_attendance')->with('dtr',$dtr);
        }
        if(Request::method() == 'POST') {
            if(Session::has('dtr_id')) {
                $dtr_id = Session::get('dtr_id');
                $dtr = DtrDetails::where('dtr_id', $dtr_id)->first();
                $dtr->time = Input::get('time');
                $time = explode(':', Input::get('time'));
                $dtr->time_h = array_key_exists(0, $time) == true ?trim($time[0], "\" ") : null;
                $dtr->time_m = array_key_exists(1, $time) == true ?trim($time[1], "\" ") : null;
                $dtr->time_s = array_key_exists(2, $time) == true ? trim($time[2], "\" ") : null;
                $dtr->event = Input::get('event');
                $dtr->terminal = Input::get('terminal');
                $dtr->remark = Input::get('remarks');
                $dtr->edited = "1";
                $dtr->save();
                Session::forget('dtr_id');
                return Redirect::to('personal/index');
            }
        }
    }

    public function personal_dtrlist()
    {

        if(Request::method() == "GET"){
            $lists = PdfFiles::where('is_filtered','<>', '1')
                ->where('type', '=', Auth::user()->emptype)
                ->orderBy('date_created', 'ASC')
                ->paginate(20);
            return View::make('dtr.personal_list')->with('lists', $lists);
        }
        if(Request::method('POST')) {

            if(Input::has('filter_range')) {
                $str = $_POST['filter_range'];
                $temp1 = explode('-',$str);
                $temp2 = array_slice($temp1, 0, 1);
                $tmp = implode(',', $temp2);
                $date_from = date('Y-m-d',strtotime($tmp));
                $temp3 = array_slice($temp1, 1, 1);
                $tmp = implode(',', $temp3);
                $date_to = date('Y-m-d',strtotime($tmp));

                $lists = DB::table('generated_pdf')
                    ->whereBetween('date_from',array($date_from,$date_to))
                    ->whereBetween('date_to' ,array($date_from,$date_to))
                    ->where('type','=','JO')
                    ->orderBy('date_created', 'ASC')
                    ->paginate(20);
                return View::make('dtr.personal_list')->with('lists', $lists);

            } else {
                return Redirect::to('personal/dtr/list');
            }
        } else {
            return Redirect::to('personal/dtr/list');
        }
    }

    public function emp_filtered()
    {
        
        $row = null;
        $pdo = null;
        if(Input::has('filter_range')){
            $str = Input::get('filter_range');
            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $tmp = implode(',', $temp2);
            $date_from = date('Y-m-d',strtotime($tmp));

            $temp3 = array_slice($temp1, 1, 1);
            $tmp = implode(',', $temp3);
            $date_to = date('Y-m-d',strtotime($tmp));
           
            
            $id = Auth::user()->userid;
           
            $pdo = $this->conn();
            $sched_id = Auth::user()->sched;
            $query = "SELECT * FROM work_sched WHERE id = '".$sched_id ."'";
            $st = $pdo->prepare($query);
            $st->execute();
            $sched = $st->fetchAll(PDO::FETCH_ASSOC);



            if(isset($sched) and count($sched) > 0) {

                $am_in = $sched[0]["am_in"];
                $am_out =  $sched[0]["am_out"];
                $pm_in = $sched[0]["pm_in"];
                $pm_out = $sched[0]["pm_out"];


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
                    if(isset($row) and count($row) > 0)
                    {
                        return View::make('dtr.filtered')->with('lists',$row)->with('date_from',$date_from)->with('date_to',$date_to)->with('userid', $id);
                    } else {
                        return "<p><strong>No result logs from $date_from to $date_to</strong></p>";
                    }
                }catch(PDOException $ex){
                    echo $ex->getMessage();
                    exit();
                }
            } else {
                return "<p><strong>You daily time logs cannot be generated because your work shift schedule is not yet set.</strong></p>";
            }

        }

    }
    public  function search_filter()
    {

        if(Input::has('filter_range')){


            $str = Input::get('filter_range');
            $temp1 = explode('-',$str);
            $temp2 = array_slice($temp1, 0, 1);
            $tmp = implode(',', $temp2);
            $f_from = date('Y-m-d',strtotime($tmp));
            $temp3 = array_slice($temp1, 1, 1);
            $tmp = implode(',', $temp3);
            $f_to = date('Y-m-d',strtotime($tmp));


            Session::put('from',$f_from);
            Session::put('to', $f_to);
        }

        if(Session::has('from') and Session::has('to')) {

            $f_from = Session::get('from');
            $f_to = Session::get('to');
            $lists = DtrDetails::where('userid', Auth::user()->userid)
                ->where('datein', '>=', $f_from)
                ->where('datein', '<=', $f_to)
                ->orderBy('datein', 'ASC')
                ->paginate(10);

            return View::make('employee.index')->with('lists',$lists);
        }
    }
    public function print_monthly()
    {
        return View::make('print.personal');
    }
    public function filter()
    {
        $lists = null;

        $str = Input::get('date_range');
        $temp1 = explode('-',$str);
        $temp2 = array_slice($temp1, 0, 1);
        $tmp = implode(',', $temp2);
        $start_date = date('Y-m-d',strtotime($tmp));

        $temp3 = array_slice($temp1, 1, 1);
        $tmp = implode(',', $temp3);
        $end_date = date('Y-m-d',strtotime($tmp));

        

        $id = Auth::user()->userid;
        $pdo = conn();

        $query = "SELECT DISTINCT e.userid, datein,

                    (SELECT MIN(t1.time) FROM dtr_file t1 WHERE t1.userid = '". $id."' and datein = d.datein and t1.time_h < 12) as am_in,
                    (SELECT MAX(t2.time) FROM dtr_file t2 WHERE t2.userid = '". $id."' and datein = d.datein and t2.time_h < 13 AND t2.event = 'OUT') as am_out,
                    (SELECT MIN(t3.time) FROM dtr_file t3 WHERE t3.userid = '". $id."' AND datein = d.datein and t3.time_h >= 12 and t3.time_h < 17 AND t3.event = 'IN' ) as pm_in,
                    (SELECT MAX(t4.time) FROM dtr_file t4 WHERE t4.userid = '". $id."' AND datein = d.datein and t4.time_h > 13 and t4. time_h < 24) as pm_out

                    FROM dtr_file d LEFT JOIN users e
                        ON d.userid = e.userid
                    WHERE d.datein BETWEEN '". $start_date. "' AND '" . $end_date . "'
                          AND e.userid = '". $id."'
                    ORDER BY datein ASC";


        $st = $pdo->prepare($query);
        $st->execute();
        $lists = $st->fetchAll(PDO::FETCH_ASSOC);

        if(isset($lists) and count($lists) > 0) {
            return View::make('print.personal')->with('lists',$lists)->with('start_date',$start_date)->with('end_date',$end_date);
        } else {
            return Redirect::to('personal/print/monthly');
        }
    }

    public static function day_name($datein)
    {

        return date('D', strtotime($datein));
    }

    public function personal_filter_dtrlist()
    {
        return View::make('dtr.print_individual');
    }
    public function save_filtered()
    {
        
        $pdf = new PdfFiles();
        $pdf->date_from = Input::get('date_from');
        $pdf->date_to = Input::get('date_to');
        $pdf->type = Auth::user()->emptype;
        $pdf->empid = Auth::user()->userid;
        $pdf->is_filtered = "1";
        $pdf->date_created =   date("Y-m-d");
        $pdf->time_created = date("h:i:sa");

        $pdf->save();

        return Redirect::to('personal/dtr/filter/list');
    }

    public function add_logs()
    {
        if(Request::method() == "GET") {
            return View::make('dtr.add_time_log');
        }
        if(Request::method() == "POST") {

            $date = explode('/', Input::get('datein'));
            $date = $date[2] . '-' . $date[0] . '-' . $date[1];

            if(Input::has('am_in')) {
                $dtr = new DtrDetails();
                $dtr->userid = Auth::user()->userid;
                $dtr->datein = $date;
                $dtr->time = Input::get('am_in');
                $dtr->event = "IN";
                $dtr->edited = "1";
                $dtr->remark = "WEB CREATED";
                $dtr->save();
            }

            if(Input::has('am_out')) {
                $dtr = new DtrDetails();
                $dtr->userid = Auth::user()->userid;
                $dtr->datein = $date;
                $dtr->time = Input::get('am_out');
                $dtr->event = "OUT";
                $dtr->edited = "1";
                $dtr->remark = "WEB CREATED";
                $dtr->save();
            }

            if(Input::has('pm_in')) {
                $dtr = new DtrDetails();
                $dtr->userid = Auth::user()->userid;
                $dtr->datein = $date;
                $dtr->time = Input::get('pm_in');
                $dtr->event = "IN";
                $dtr->edited = "1";
                $dtr->remark = "WEB CREATED";
                $dtr->save();
            }

            if(Input::has('pm_out')) {
                $dtr = new DtrDetails();
                $dtr->userid = Auth::user()->userid;
                $dtr->datein = $date;
                $dtr->time = Input::get('pm_out');
                $dtr->event = "OUT";
                $dtr->edited = "1";
                $dtr->remark = "WEB CREATED";
                $dtr->save();
            }

            return Redirect::to('/');
        }
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
            exit();
        }
        return $pdo;
    }


}