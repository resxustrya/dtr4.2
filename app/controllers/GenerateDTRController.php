<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 3/16/2017
 * Time: 4:27 PM
 */


class GenerateDTRController extends BaseController
{
    function __construct()
    {
        $this->beforeFilter(function () {
            if(!Auth::check())
            {
                return Redirect::to('/');
            }
        });
    }

    public function list_jo_dtr()
    {

        if(Request::method() == 'GET') {
            $lists = PdfFiles::where('type','JO')
                ->where('is_filtered','!=','1')
                ->orderBy('date_created', 'ASC')
                ->paginate(20);
            return View::make('dtr.dtr_list_jo')->with('lists', $lists);
        }

        if(Request::method('POST')) {
            if(Input::has('filter_range1')) {
                $str = $_POST['filter_range'];
                $temp1 = explode('-',$str);
                $temp2 = array_slice($temp1, 0, 1);
                $tmp = implode(',', $temp2);
                $date_from = date('Y-m-d',strtotime($tmp));
                $temp3 = array_slice($temp1, 1, 1);
                $tmp = implode(',', $temp3);
                $date_to = date('Y-m-d',strtotime($tmp));


                $lists = DB::table('generated_pdf')
                    ->where('date_from','>=',$date_from)
                    ->Where('date_to' ,' <=', $date_to)
                    ->orderBy('date_created', 'ASC')
                    ->paginate(20);
                return View::make('dtr.personal_list')->with('lists', $lists);

            } else {
                return Redirect::to('dtr/list/jo');
            }
        } else {
            return Redirect::to('dtr/list/jo');
        }

    }

    public function list_regular_dtr()
    {
        $lists = PdfFiles::where('type','REG')
                ->orderBy('date_created','ASC')
                ->paginate(20);
        return View::make('dtr.dtr_list_regular')->with('lists',$lists);
    }


    public function personal_filter_dtrlist()
    {
        $lists = PdfFiles::where('is_filtered','1')
                            ->orderBy('date_created','ASC')
                            ->paginate(20);
        return View::make('dtr.personal_filter_list')->with('lists',$lists);
    }
}