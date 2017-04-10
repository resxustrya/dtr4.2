<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 12/2/2016
 * Time: 11:37 AM
 */

class DtrController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter(function () {
            if(!Auth::check())
            {
                return Redirect::to('/');
            }
        });
    }
    public function upload()
    {

        //GET Request
        if(Request::method() == 'GET'){
            return View::make('dtr.upload');
        }
        //POST Request
        if(Request::method() == 'POST'){

            if(Input::hasFile('dtr_file')){

                $file = Input::file('dtr_file');
                ini_set('max_execution_time', 0);
                $dtr = file_get_contents($file);
                $data = explode(PHP_EOL,$dtr);
                for($i = 1; $i < count($data); $i++) {
                    try
                    {
                        $employee = explode(',', $data[$i]);
                        $details = new DtrDetails();

                        $id = trim($employee[0], "\" ");
                        $id = ltrim($id, "\" ");


                        if($id != 'Unknown User'){
                            $details->userid = array_key_exists(0, $employee) == true ? trim($employee[0], "\" ") : null;
                            $details->firstname = array_key_exists(1, $employee) == true ? trim($employee[1], "\" ") : null;
                            $details->lastname = array_key_exists(2, $employee) == true ? trim($employee[2], "\" ") : null;
                            $details->department = array_key_exists(3, $employee) == true ? trim($employee[3], "\" ") : null;
                            $details->datein = array_key_exists(4, $employee) == true ? trim($employee[4], "\" ") : null;
                            try{
                                if(array_key_exists(4, $employee) == true){
                                    $date = explode('/', $employee[4]);
                                    $details->date_y = array_key_exists(0, $date) == true ? trim($date[0], "\" ") : null;
                                    $details->date_m = array_key_exists(1, $date) == true ?trim($date[1], "\" ") : null;
                                    $details->date_d = array_key_exists(2, $date) == true ?trim($date[2], "\" ") : null;
                                }
                            } catch(Exception $ex){

                            }
                            $details->time = array_key_exists(5, $employee) == true ? trim($employee[5], "\" ") : null;
                            try{
                                if(array_key_exists(5,$employee) == true){
                                    $time = explode(':', $employee[5]);
                                    $details->time_h = array_key_exists(0, $time) == true ?trim($time[0], "\" ") : null;
                                    $details->time_m = array_key_exists(1, $time) == true ?trim($time[1], "\" ") : null;
                                    $details->time_s = array_key_exists(2, $time) == true ? trim($time[2], "\" ") : null;
                                }
                            } catch(Exception $ex){
                                Log::info("Exception at time array in line 68 : " .$ex->getMessage());
                            }
                            $details->event = array_key_exists(6, $employee) == true ? trim($employee[6], "\" ") : null;
                            $details->terminal = array_key_exists(7, $employee) == true ? trim($employee[7], "\" ") : null;
                            $details->remark = array_key_exists(8, $employee) == true ? trim($employee[8], "\" ") : null;
                            try{
                                $details->save();
                            } catch(QueryException $ex){

                            }
                            //FOR INSERTING DATA TO THE USERS TABLE ONLY. IF THE USERS TABLE HAS NO DATA, JUST UNCOMMENT THIS COMMENT.
                            $user = User::where('userid',$details->userid)->first();
                            //checking for duplicate userid
                            if( !isset($user) and !count($user) > 0){
                                $user = new User();
                                $user->fname = $details->firstname;
                                $user->lname = $details->lastname;
                                $user->userid = $details->userid;
                                $user->username = $details->userid;
                                $user->password = Hash::make($details->userid);

                                $user->usertype = 0;

                                if(strlen($id)> 5) {
                                    $user->emptype = 'REG';
                                } else {
                                    $user->emptype = 'JO';
                                }
                                $user->save();

                            }
                        }
                    } catch (Exception $ex) {

                    }
                }
               return Redirect::to('index');
            }
        }
    }
    public function dtr_list()
    {
        $lists = DB::table('dtr_file')
            ->where('userid','<>', NULL)
            ->orderBy('lastname', 'ASC')
            ->groupBy('userid')
            ->paginate(30);
        return view('dashboard.dtrlist')->with('lists',$lists);
    }
    public function search()
    {
        $lists = null;
        if (Input::has('keyword')) {
            $keyword = Input::get('keyword');
            Session::put('keyword', $keyword);

        }
        if (Input::has('from') and Input::has('to')) {
            Session::forget('keyword');
            $_from = explode('/', Input::get('from'));
            $_to = explode('/', Input::get('to'));

            $f_from = $_from[2] . '-' . $_from[0] . '-' . $_from[1];
            $f_to = $_to[2] . '-' . $_to[0] . '-' . $_to[1];
            Session::put('f_from', $f_from);
            Session::put('f_to', $f_to);

        }

        if (Session::has('f_from') and Session::has('f_to') and Session::has('keyword')) {
            $f_from = Session::get('f_from');
            $f_to = Session::get('f_to');
            $keyword = Session::get('keyword');
            $lists = DtrDetails::where('department','<>','- -')
                ->where('datein', '>=', $f_from)
                ->where('datein', '<=', $f_to)
                ->orWhere('userid', 'LIKE', '%'.$keyword.'%')
                ->orWhere('firstname', 'LIKE', '%'.$keyword.'%')
                ->orWhere('lastname', 'LIKE', '%'.$keyword.'%')
                ->orderBy('datein', 'ASC')
                ->paginate(20);

        }
        if(Session::has('keyword')) {
            $keyword = Session::get('keyword');
            $lists = DB::table('dtr_file')->where('userid','!=', "")
                                ->orWhere('userid', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('firstname', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('lastname', 'LIKE', '%'.$keyword.'%')
                                ->orderBy('userid','DESC')
                                ->paginate(20);

        }
        return View::make('home')->with('lists', $lists);
    }
    public function create_attendance()
    {
        if(Request::method() == 'GET') {
            return View::make('dtr.new_attendance');
        }
        if(Request::method() == 'POST') {
            $dtr = new DtrDetails();
            $dtr->userid = Input::get('userid');
            $dtr->firstname = Input::get('firstname');
            $dtr->lastname = Input::get('lastname');
            $dtr->department = Input::get('department');
            $date = explode('/', Input::get('datein'));
            $date = $date[2] . '-' . $date[0] . '-' . $date[1];
            $dtr->datein = $date;
            $date = explode('-', $date);
            $dtr->date_y = array_key_exists(0, $date) == true ? trim($date[0], "\" ") : null;
            $dtr->date_m = array_key_exists(1, $date) == true ?trim($date[1], "\" ") : null;
            $dtr->date_d = array_key_exists(2, $date) == true ?trim($date[2], "\" ") : null;

            $dtr->time = Input::get('time');
            $time = explode(':', Input::get('time'));
            $dtr->time_h = array_key_exists(0, $time) == true ?trim($time[0], "\" ") : null;
            $dtr->time_m = array_key_exists(1, $time) == true ?trim($time[1], "\" ") : null;
            $dtr->time_s = array_key_exists(2, $time) == true ? trim($time[2], "\" ") : null;

            $dtr->event = Input::get('event');
            $dtr->terminal = Input::get('terminal');
            $dtr->remark = Input::get('remarks');
            $dtr->save();


            $user = User::where('userid',$dtr->userid)->first();
            //checking for duplicate userid
            if( !isset($user) and !count($user) > 0){
                $user = new User();
                $user->fname = $dtr->firstname;
                $user->lname = $dtr->lastname;
                $user->userid = $dtr->userid;
                $user->username = $dtr->userid;
                $user->password = Hash::make($dtr->userid);
                $user->usertype = 0;
                $user->save();
            }
            return Redirect::to('home');
        }
    }
    public function edit_attendance($id = null)
    {
        if(Request::method() == 'GET') {
            if(isset($id)) {
                Session::put('dtr_id',$id);
            }
            $dtr = DtrDetails::where('dtr_id', $id)->first();
            return View::make('dtr.edit_attendance')->with('dtr',$dtr);
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
                $dtr->save();
                Session::forget('dtr_id');
                return Redirect::to('home');
            }
        }
    }
    public function delete()
    {
        $dtr = DtrDetails::where('dtr_id',Input::get('dtr_id'))->first();
        if(isset($dtr) and $dtr != null)
        {
            $dtr->delete();
            return Redirect::to('index')->with('message','Attendance succesfully deleted.');
        }
    }
}