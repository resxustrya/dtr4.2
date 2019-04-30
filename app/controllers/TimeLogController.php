<?php

class TimeLogController extends Controller
{
    public function timeLog(){
        $userid = Auth::user()->userid;
        if(Request::method() == 'POST'){
            Session::put("filter_dates",Input::get('filter_dates'));
            $filter_date = explode(' - ',Input::get("filter_dates"));
            $date_from = date("Y-m-d",strtotime($filter_date[0]));
            $date_to = date("Y-m-d",strtotime($filter_date[1]));
            //C# API
            /*$url = "http://192.168.100.81/dtr_api/logs/GetLogs";
            $data = [
                "userid" => Auth::user()->userid,
                "df" => $date_from,
                "dt" => $date_to
            ];
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

            $logs = json_decode($response);

            foreach($logs as $log)
            {
                $check = DtrDetails::where('userid',$log->userid)
                                ->where('datein',$log->date)
                                ->where('time',$log->time)
                                ->where('event',$log->event_type)
                                ->first();
                if(!$check){
                    $dtr_file = new DtrDetails();
                    $dtr_file->userid = $log->userid;
                    $dtr_file->datein = $log->date;
                    $dtr_file->time = $log->time;
                    $dtr_file->event = $log->event_type;
                    $dtr_file->remark = "#FP";
                    $dtr_file->edited = 0;
                    $dtr_file->save();
                }
            }*/
            //C# API END
            $timeLog = DB::connection('mysql')->select("call getLogs2('$userid','$date_from','$date_to')");
        } else {
            Session::put("filter_dates",date("m/01/Y - m/d/Y"));
            $date_from = date("Y-m-01");
            $date_to = date("Y-m-d");
            return $timeLog = DB::connection('mysql')->select("call getLogs2('$userid','$date_from','$date_to')");
        }
        return View::make("timelog.timelog",[
            "timeLog" => $timeLog
        ]);
    }

    public function append(){
        return View::make("timelog.append_timelog",[
            "elementId" => Input::get('elementId'),
            "am_in" => Input::get("am_in"),
            "am_out" => Input::get("am_out"),
            "pm_in" => Input::get("pm_in"),
            "pm_out" => Input::get("pm_out")
        ]);
    }

    public function edit(){
        $userid = Input::get("userid");
        $datein = Input::get("datein");
        $time = str_replace("-",":",Input::get("time"));
        $edited_display = Input::get("edited_display");
        $log_status = Input::get("log_status");
        $log_status_change = Input::get("log_status_change");
        $log_type = Input::get("log_type");

        switch ($log_status){ //REMOVE
            case "so":
                SoLogs::where("userid",$userid)
                    ->where("datein",$datein)
                    ->where("time",$time)
                    ->where("event",explode("_",$log_type)[1])
                    ->delete();
                break;
            case "cdo":
                CdoLogs::where("userid",$userid)
                    ->where("datein",$datein)
                    ->where("time",$time)
                    ->where("event",explode("_",$log_type)[1])
                    ->delete();
                break;
            case "leave":
                LeaveLogs::where("userid",$userid)
                    ->where("datein",$datein)
                    ->where("time",$time)
                    ->where("event",explode("_",$log_type)[1])
                    ->delete();
                break;
            case "edited":
                EditedLogs::where("userid",$userid)
                    ->where("datein",$datein)
                    ->where("time",$time)
                    ->where("event",explode("_",$log_type)[1])
                    ->delete();
                break;
        }

        $time_display = '';
        switch ($log_status_change){ //INSERT
            case "so":
                $so = new SoLogs();
                $so->userid = $userid;
                $so->datein = $datein;
                if($log_type == "AM_IN"){
                    $so->time = "08:00:00";
                    $time_display = "08:00:00";
                }
                elseif($log_type == "AM_OUT"){
                    $so->time = "12:00:00";
                    $time_display = "12:00:00";
                }
                elseif($log_type == "PM_IN"){
                    $so->time = "13:00:00";
                    $time_display = "13:00:00";
                }
                elseif($log_type == "PM_OUT"){
                    $so->time = "18:00:00";
                    $time_display = "18:00:00";
                }
                $so->event = explode("_",$log_type)[1];
                $so->remark = explode("#",$edited_display)[1];
                $so->edited = 1;
                $so->holiday = 003;
                $so->save();

                return "added in SO-".$time_display;
                break;
            case "cdo":
                $cdo = new CdoLogs();
                $cdo->userid = $userid;
                $cdo->datein = $datein;
                if($log_type == "AM_IN"){
                    $cdo->time = "08:00:00";
                    $time_display = "08:00:00";
                }
                elseif($log_type == "AM_OUT"){
                    $cdo->time = "12:00:00";
                    $time_display = "12:00:00";
                }
                elseif($log_type == "PM_IN"){
                    $cdo->time = "13:00:00";
                    $time_display = "13:00:00";
                }
                elseif($log_type == "PM_OUT"){
                    $cdo->time = "18:00:00";
                    $time_display = "18:00:00";
                }
                $cdo->event = explode("_",$log_type)[1];
                $cdo->remark = "CTO";
                $cdo->edited = 1;
                $cdo->holiday = 006;
                $cdo->save();

                return "added in CDO-".$time_display;
                break;
            case "leave":
                $leave = new LeaveLogs();
                $leave->userid = $userid;
                $leave->datein = $datein;
                if($log_type == "AM_IN"){
                    $leave->time = "08:00:00";
                    $time_display = "08:00:00";
                }
                elseif($log_type == "AM_OUT"){
                    $leave->time = "12:00:00";
                    $time_display = "12:00:00";
                }
                elseif($log_type == "PM_IN"){
                    $leave->time = "13:00:00";
                    $time_display = "13:00:00";
                }
                elseif($log_type == "PM_OUT"){
                    $leave->time = "18:00:00";
                    $time_display = "18:00:00";
                }
                $leave->event = explode("_",$log_type)[1];
                $leave->remark = $edited_display;
                $leave->edited = 1;
                $leave->holiday = 007;
                $leave->save();

                return "added in LEAVE-".$time_display;
                break;
            case "edited":
                $edited = new EditedLogs();
                $edited->userid = $userid;
                $edited->datein = $datein;
                $edited->time = $edited_display;
                $time_display = $edited_display;
                $edited->event = explode("_",$log_type)[1];
                $edited->remark = "WEB CREATED";
                $edited->edited = 1;
                $edited->holiday = 'A';
                $edited->save();

                return "added in EDITED-".$time_display;
                break;
        }

        return "wew";
    }

}