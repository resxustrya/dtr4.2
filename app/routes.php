<?php


//FOR ADMIN ROUTE GROUP



Route::get('logout', function(){
	Auth::logout();
	Session::flush();
	return Redirect::to('/');
});

Route::get('dtr/{id}', 'GenerateDTRController@download_dtr');

Route::match(array('GET','POST'),'/', 'AdminController@index');
Route::get('home',array('before' => 'admin','uses' => 'AdminController@home'));


Route::get('rpchallenge', 'PasswordController@change_password');

Route::match(array('GET','POST'), 'admin/upload', 'DtrController@upload');
Route::match(array('GET','POST'),'search', 'DtrController@search');
Route::match(array('GET','POST'), 'add/attendance', 'DtrController@create_attendance');
Route::get('employees','AdminController@list_all');
Route::match(array('GET','POST'),'beginning_balance','cdoController@beginning_balance');
Route::match(array('GET','POST'),'update_bbalance','cdoController@update_bbalance');

Route::get('list/regular', 'AdminController@list_regular');
Route::match(array('GET','POST'), 'change/work-schedule', 'AdminController@change_schedule');
Route::match(array('GET','POST'), 'print/individual', 'AdminController@print_individual');
Route::get('/search/user/j','AdminController@search_jo');
Route::get('/search/user/r','AdminController@search_regular');
Route::get('/search','AdminController@search');
Route::match(['GET','POST'],'add/user', 'AdminController@adduser');
Route::get('schedule/flixe', 'AdminController@flexi_group');
Route::get('datatables', 'AdminController@datatables');
Route::get('filter/flixe','AdminController@filter_flixe');
Route::get('work-schedule/group', 'AdminController@show_group');
Route::post('delete/attendance', 'AdminController@delete');
Route::match(['GET','POST'], 'reset/password', 'PasswordController@reset_password');
Route::post('user/delete', 'AdminController@delete_user');
Route::match(['GET','POST'],'user/edit', 'AdminController@user_edit');
Route::get('print/user/logs','AdminController@print_user_logs');
Route::post('print/mobile/logs','AdminController@print_mobile_logs');
Route::match(['GET','POST'],'leave/credits','AdminController@leave_credits');
Route::get('get/regular/employee','AdminController@get_regular_emp');
Route::get('add_leave_table','AdminController@add_leave_table');
Route::match(array('GET','POST'), 'print-monthly', 'PrintController@print_monthly');
Route::get('print-monthly/attendance', 'PrintController@print_pdf');
Route::match(array('GET','POST'), 'print/employee-attendance', 'PrintController@print_employee');

Route::get('work-schedule' ,'HoursController@create');
Route::match(array('GET','POST'), 'create/work-schedule', 'HoursController@work_schedule');
Route::match(array('GET','POST') , 'edit/work-schedule/{id}' ,'HoursController@edit_schedule');
Route::match(array('GET','POST') , 'edit/attendance/{id?}', 'DtrController@edit_attendance');
Route::get('delete/edited/logs/{userid}/{datein}/{time}/{event}','PersonalController@delete_logs');



Route::get('attendance','DtrController@attendance');
Route::get('attendance/q', 'DtrController@filter_attendance');
Route::match(array('GET','POST'),'resetpass', 'PasswordController@change_password');
//Route::post('/', 'PasswordController@save_changes');

//LEAVE PROCCES

Route::get('tracked/leave','AdminController@track_leave');
Route::post('leave/approval','AdminController@approve_leave');
Route::get('leave/cancel/{route_no}','AdminController@cancel_leave');
Route::get('search/leave','AdminController@search_leave');



//DTR
Route::get('dtr/list/jo', 'GenerateDTRController@list_jo_dtr');
Route::get('search/jo','GenerateDTRController@search_jo_dtr');
Route::get('dtr/list/regular', 'GenerateDTRController@list_regular_dtr');
Route::get('search/regular', 'GenerateDTRController@search_reg_dtr');
Route::get('dtr/download/{id}', 'GenerateDTRController@download_dtr');
Route::match(['GET','POST'],'/personal/dtr/list', 'PersonalController@personal_dtrlist');
Route::get('/ab','PersonalController@personal_filter_dtrlist');
//FOR PERSONAL ROUTE GROUP

Route::get('personal/home', function() {
	Session::forget('f_from');
	Session::forget('f_to');
	Session::forget('lists');
	return Redirect::to('personal/index');
});
Route::get('personal/monthly',function() {
	Session::forget('filter_list');
	return Redirect::to('personal/print/monthly');
});

Route::match(['GET','POST'],'personal/index',array('before' => 'standard-user','uses' => 'PersonalController@index'));

Route::get('personal/search', 'PersonalController@search');
Route::get('/personal/search/filter', 'PersonalController@search_filter');
Route::get('personal/print/monthly', 'PersonalController@print_monthly');
Route::post('personal/print/filter' ,'PersonalController@filter');
Route::post('personal/filter', 'PersonalController@emp_filtered');
Route::post('personal/filter/save', 'PersonalController@save_filtered');
Route::match(['get','post'], 'edit/personal/attendance/{id?}', 'PersonalController@edit_attendance');
Route::match(array('GET','POST'),'/personal/add/logs', 'PersonalController@add_logs');
Route::match(['GET','POST'],'create/absent/description', 'PersonalController@absent_description');
Route::post('delete/user/created/logs','PersonalController@delete_created_logs');


//DOCUMENTS
Route::match(array('GET','POST'),'form/leave','DocumentController@leave');
Route::get('form/leave/all', 'DocumentController@all_leave');
Route::get('leave/get/{id}','DocumentController@get_leave');
Route::get('leave/print/{id}', 'DocumentController@print_leave');
Route::get('leave/print/a/{id}', 'DocumentController@print_a');
Route::get('leave/update/{id}', 'DocumentController@edit_leave');
Route::post('leave/update/save', 'DocumentController@save_edit_leave');
Route::get('leave/delete/{id}', 'DocumentController@delete_leave');


//ADMIN TRACKED DOCUMENTS
Route::get('tracked/so', 'DocumentController@so_tracking');

Route::get('list/pdf', 'DocumentController@list_print');

Route::get('clear', function(){
	Session::flush();
	return Redirect::to('/');
});

Route::get('modal',function(){
	return view('users.modal');
});

Route::get('errorupload', function(){
	return view('errorupload');
});

Route::get('test/form', function(){
	return view('test.form');
});
Route::post('test/form',function(\Illuminate\Http\Request $request){
	return $request->all();
});

Route::get('pdf/leave',function() {

	$display = view("pdf.leave");
	$pdf = App::make('dompdf.wrapper');
	$pdf->loadHTML($display);
	return $pdf->stream();
});

/////////RUSEL
////OFFICE ORDER
Route::match(array('GET','POST'), 'form/so', 'DocumentController@so');
Route::match(array('GET','POST'), 'form/so_view', 'DocumentController@so_view');
Route::match(array('GET','POST'), 'form/so_list', 'DocumentController@so_list');
Route::match(array('GET','POST'), 'form/sov1', 'DocumentController@sov1');
Route::get('inclusive_name_page', 'DocumentController@inclusive_name_page');
Route::get('inclusive_name_view', 'DocumentController@inclusive_name_view');
Route::post('so_add','DocumentController@so_add');
Route::post('so_delete','DocumentController@so_delete');
Route::post('so_updatev1','DocumentController@so_updatev1');
Route::post('so_update','DocumentController@so_update');
Route::get('so_pdf','DocumentController@so_pdf');

Route::match(['get','post'], 'form/track/{route_no}', 'DocumentController@track');
Route::get('form/so_pdf','DocumentController@so_pdf');
Route::get('inclusive_name', 'DocumentController@inclusive_name');
Route::get('so_append','DocumentController@so_append');
Route::get('form/info/{route}/{doc_type}', 'DocumentController@show');

//////CDO
Route::match(array('GET','POST'), 'form/cdo_list', 'cdoController@cdo_list');
Route::match(array("GET","POST"), "form/cdov1/{pdf}","cdoController@cdov1");
Route::post('cdo_addv1','cdoController@cdo_addv1');
Route::post('cdo_updatev1','cdoController@cdo_updatev1');
Route::post('cdo_updatev1/{id}/{type}','cdoController@cdo_updatev1');
Route::match(array('GET','POST'),'click_all/{type}','cdoController@click_all');
Route::post('cdo_delete','cdoController@cdo_delete');

/////////CALENDAR
Route::get('calendar','CalendarController@calendar');
Route::get('calendar_holiday','CalendarController@calendar_holiday');
Route::get('calendarEvent/{userid}','CalendarController@calendarEvent');
Route::post('calendar_save','CalendarController@calendar_save');
Route::get('calendar_delete/{event_id}','CalendarController@calendar_delete');
Route::post('calendar_update','CalendarController@calendar_update');

Route::get('manual','PersonalController@manual');
Route::get('print/employee', 'AdminController@print_employees');
/////////PDF
Route::get('pdf/v1/{size}', function($size){
	$display = View::make("pdf.pdf",['size'=>$size]);
	$pdf = App::make('dompdf');
	$pdf->loadHTML($display);
	return $pdf->setPaper($size, 'portrait')->stream();
});
Route::get('pdf/track', function(){
	$display = View::make("pdf.track");
	$pdf = App::make('dompdf');
	$pdf->loadHTML($display);
	return $pdf->stream();
});
////// ABSENT
Route::match(array('GET','POST'), 'form/absent', 'DocumentController@absent');
Route::get('open/reset','RessetPasswordController@reset');

//TEST ROUTES
Route::get('phpinfo', function() {
	return json_encode(phpinfo());
});

Route::get('fpdf', 'PersonalController@rdr_home');

Route::get('emptype', function() {
	Schema::create('generated_pdf', function (Blueprint $table) {
		$table->increments('id');
		$table->string('filename')->nullable();
		$table->date('date_from')->nullable();
		$table->date('date_to')->nullable();
		$table->date('date_created');
		$table->time('time_created');
		$table->string('generated',10)->nullable();
		$table->rememberToken();
		$table->timestamps();
	});
});

Route::get('ajax',function(){
	return View::make('ajax');
});

Route::get('ajax1',function(){
	if(Request::ajax()){
		return "Ajax request";
	} else {
		return "Not ajax";
	}
});

Route::get('sologs', function(){
	return SoLogs::all();
});

Route::get('search/id',function(){
	if(!Auth::check()){
		$keyword = Input::get('q');
		$users = DB::table('users')
			->leftJoin('work_sched', function($join){
				$join->on('users.sched','=','work_sched.id');
			})
			->where(function($q) use ($keyword){
				$q->where('fname','like',"%$keyword%")
					->orwhere('lname','like',"%$keyword%")
					->orwhere('userid','like',"%$keyword%");
			})
			->where('usertype','=', '0')
			->orderBy('fname', 'ASC')
			->paginate(20);
		return View::make('auth.login',['users' => $users]);
	} else {
		return Redirect::to('/');
	}
});


//MOBILE URL
Route::post('mobile/login','MobileController@login');
Route::post('mobile/loginPis','MobileController@loginPis');
Route::post('mobile/add-logs','MobileController@add_logs');
Route::post('mobile/add-cto','MobileController@add_cto');
Route::post('mobile/add-so','MobileController@add_so');
Route::post('mobile/add-leave','MobileController@add_leave');

//SUB ADMIN - NEGROS AND BOHOL
Route::get('subHome',array('before' => 'sub','uses' => 'SubController@subHome'));
Route::post('sub/upload', 'SubController@upload');

//GIT
Route::get('git_pull','GitController@git_pull');
Route::get('git_add','GitController@git_pull');
Route::get('git_commit','GitController@git_pull');

?>