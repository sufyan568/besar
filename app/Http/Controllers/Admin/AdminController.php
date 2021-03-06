<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Paginator;
use App\Http\Controllers\Controller;
use App\PopUp;
use App\Loader;
use App\NewBooking;
use App\Models\Page;
use App\Http\Models\User;
use App\Http\Models\Admin\Orders;
use App\Http\Models\Admin\PageImage;
use App\Http\Models\Admin\Banner;
use App\Http\Models\Admin\RoomBookDate as RoomBookDate;
use Session;
use Input;
use Illuminate\Http\RedirectResponse;
use App\Http\Models\Admin\Category;
use Auth;
use Validator;
use Hash;
use DB;
use Redirect;
use Illuminate\Http\Request as Request1;
//use App\Http\Requests\Request;
use Request;
use View;
use Response;
use Carbon\Carbon;
use App\Http\Models\Admin\ActivityLogs;

class AdminController extends Controller {

    private $data = array();
    private $BannerModel = null;
    private $CategoryModel = null;
    private $RoomBookDateModel = null;

    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        $this->middleware('auth');

        $this->BannerModel = new Banner();

        $this->CategoryModel = new Category();
        //echo "3"; exit;
    }

    private function get_ip() {

        if ( function_exists( 'apache_request_headers' ) ) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists.
        if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {
            $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
        }
        return $the_ip;
    }
    function index() {
        // echo "2"; exit;
        return redirect('web88cms/dashboard');
    }

    function login() {

        //return "dfdsfgsdgfsdgfs";

        return redirect('/web88cms/dashboard');
    }

    function logout() {
        //return redirect('/auth/logout/');

        if(Auth::user()){
            $ip = $this->get_ip();
            $activity = new ActivityLogs();

            $activity->user_id = Auth::user()->id;
            $activity->ip = $ip;
            $activity->action = 'Logout';
            $activity->activity = '-';
            $activity->details = '-';
            $activity->save();
        }

        Auth::logout();
        return redirect('web88cms/login');
    }

    function booking(){
        $data['book'] = NewBooking::all();
        return view('admin.booking.index',$data);
    }

    function check_ota_rate(){
        $page = 0;
        $sort = 'ASC';
        $sort_by = 'createdate';
        $inputs = Input::get();
        $per_page = 10;
        $start_date = '';
        $end_date = '';
        $ota_search = '';

        $ota_list = DB::table('OTA_checklist');
        if( isset($inputs['per_page']) ){
            $per_page = $inputs['per_page'];
        }
        if( isset($inputs['start_date']) && $inputs['start_date'] != '' ){
            $start_date = date('Y-m-d',strtotime($inputs['start_date']));
            // $start_date = date('D, M d',strtotime($inputs['start_date']));
        }
        if( isset($inputs['end_date']) && $inputs['end_date'] != ''  ){
            $end_date = date('Y-m-d',strtotime($inputs['end_date']));
            // $end_date = date('D, M d',strtotime($inputs['end_date']));
        }
        if( isset($inputs['ota_search']) && $inputs['ota_search'] != '' ){
            $ota_search = $inputs['ota_search'];
        }

        // response variable is set when item is deleted
        $this->data['success'] = Session::get('success');
        $this->data['error'] = Session::get('error');
        Session::forget('success');
        Session::forget('error');


        if(Request::isMethod('post'))
        {
            $all_data = $_POST;
            if(isset($all_data['hotel_name']) && $all_data['hotel_name'] != ''){
                DB::table('hotelname')->insert(['hotelname' => $all_data['hotel_name']]);
                $this->updateAdminLastActivity('check_ota_rate');
                Session::put('success', 'The information has been saved/updated successfully.');
            }
            return Redirect::back();
            exit;
        }

        if($ota_search != ''){
            $ota_searchLower = strtolower($ota_search);
            $ota_list = $ota_list->whereRaw('lower(sitename) like (?)',["%{$ota_searchLower}%"]);//->where('hotelname', 'ilike', '%'.$ota_search.'%');
        }if($start_date != '' && $end_date != ''){
            // $ota_list = $ota_list->whereBetween('created_at', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
            $ota_list = $ota_list->whereRaw('STR_TO_DATE( checkin, "%a, " "%b " "%e" ) >= STR_TO_DATE( DATE_FORMAT( "'.$start_date.'", "%a, " "%b " "%e" ) , "%a, " "%b " "%e" ) AND STR_TO_DATE( checkin, "%a, " "%b " "%e" ) <= STR_TO_DATE( DATE_FORMAT( "'.$end_date.'", "%a, " "%b " "%e" ) , "%a, " "%b " "%e" )');
        }

        //$this->updateAdminLastActivity('check_ota_rate');
        $this->data['ota_check_list'] = $ota_list->paginate($per_page)->appends(Input::except('page'));
        $this->data['last_modified'] = DB::table('admin_last_activity')->where('section','check_ota_rate')->orderBy('updated_at','desc')->pluck('updated_at');//NewBooking::all();

        $this->data['per_page'] = $per_page;
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;
        $this->data['ota_search'] = $ota_search;
        // dd($this->data);
        return view('admin.check_ota_rate',$this->data);
    }
    function updateBooking(){
        NewBooking::where(['id'=>Input::get('booking_id')])->update([
                'description'=>Input::get('description'),
                'status'=>Input::get('status')?1:0,
            ]);
        Session::flash('flash_message', 'The data has been updated');

        return Redirect::back();

    }
    function destroyBooking(){
        NewBooking::where(['id'=>Input::get('booking_id')])->delete();
        Session::flash('flash_message', 'The data has been deleted');

        return Redirect::back();

    }
    function storeOnScreenMessage(){
        NewBooking::create([
                'description'=>Input::get('description'),
                'status'=>Input::get('status')?1:0,
            ]);
        Session::flash('flash_message', 'The data has been Added');

        return Redirect::back();

    }

    function popUp(){
        $data['pop'] = PopUp::all();
        return view('admin.popup.index',$data);
    }
    function storePopUp(){
        if(Input::hasFile('image')){
            $file=Input::file('image');
            $name = time() . '.'.$file->getClientOriginalName();
            $file->move(
                base_path() . '/public/images/', $name
            );
            PopUp::where(['id'=>1])->update([
                'title'=>Input::get('title'),
                'status'=>Input::get('status')?1:0,
                'image'=>'/public/images/'.$name
            ]);
        }else{
            PopUp::where(['id'=>1])->update([
                'title'=>Input::get('title'),
                'status'=>Input::get('status')?1:0,
            ]);
        }
        return Redirect::back()->with(['flash_message', 'The data has been updated']);

    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function dashboard() {
        //echo "sdasdfasfadf"; exit;
        //// Get orders for dashboard order graph
        $data['graphOrders'] = DB::select("SELECT sum(totalPrice) as totalPrice,MONTH(modifydate) as month FROM `orders` where YEAR(modifydate)=YEAR(CURDATE()) and payment_status='Paid' group by MONTH(modifydate) order by MONTH(modifydate)");

        //// Get today sale dashboard order graph
        $data['todaySale'] = DB::select("SELECT sum(totalPrice) as totalPrice, sum(shipping_charge) as shipping_charge FROM `orders` where YEAR(modifydate)=YEAR(CURDATE()) and MONTH(modifydate)=MONTH(CURDATE()) and DAY(modifydate)=DAY(CURDATE()) and payment_status='Paid' group by MONTH(modifydate) order by MONTH(modifydate)");

        //// Get customers for dashboard customer graph
        $data['newCustomers'] = DB::select("SELECT count(id) as countCustomers,MONTH(createdate) as month FROM `customers` where YEAR(createdate)=YEAR(CURDATE()) group by MONTH(createdate) order by MONTH(createdate)");

        //// Get returning customers for dashboard customer graph
        $data['returnCustomers'] = DB::select("SELECT MONTH( modifydate ) AS month, count(customer_id) as countCustomers FROM  `orders` WHERE YEAR( modifydate ) = YEAR( CURDATE( ) ) GROUP BY MONTH( modifydate ) ORDER BY MONTH( modifydate )");

        ///// GET last 5 orders
        //  $data['last5Orders'] = DB::select("select *, sum(order_to_product.quantity) as quantity from orders inner join order_to_product on orders.id = order_to_product.order_id group by order_to_product.order_id order by orders.id desc limit 5 offset 0");
        $last5orders = DB::select("select id from orders order by id desc limit 5");
        $orderModel = new Orders();
        foreach ($last5orders as $last5order) {
            $order = $orderModel->getOrderTax($last5order->id);
            $data['last5Orders'][] = $order;
        }

        ///// GET life time sales
        $data['lifetimesales'] = DB::select("SELECT sum(totalPrice)   as totalsale, sum(shipping_charge) as shipping_charge FROM orders WHERE (payment_status='Paid' and status='New Order') OR (payment_status='Paid' and status='Completed')");

        //////// Get average order
        $data['totalorder'] = DB::select("select AVG(`totalPrice`) as average FROM orders WHERE payment_status='Paid'");

        ////// GET Best Sellers
        /*$data['bestsellers'] = DB::select("SELECT p.id as product_id,p.type ,p.sale_price, dertable.quantityordered  FROM products p INNER JOIN (SELECT DISTINCT(product_id)  as pro_id ,SUM(quantity) as quantityordered FROM `order_to_product` WHERE `order_id` IN (SELECT id
            FROM orders WHERE payment_status = 'Paid') group by product_id ) as dertable ON p.id=dertable.pro_id order by quantityordered DESC limit 0,5");*/

        $data['bestsellers'] = DB::table('order_to_product')->join('products', 'products.id', '=', 'order_to_product.product_id')
                                ->join('orders', 'orders.id', '=', 'order_to_product.order_id')
                                ->select(DB::raw('SUM(order_to_product.quantity) as quantityordered'), 'order_to_product.product_id', 'products.type', 'order_to_product.amount as sale_price')
                                ->where('orders.payment_status', 'Paid')
                                ->groupBy('order_to_product.product_id')
                                ->orderBy('quantityordered', 'DESC')
                                ->take(5)
                                ->get();

        ///// Get New Customers
        $data['newcustomers'] = DB::select("SELECT `first_name` ,id, `email`, `createdate`FROM `customers` order by`createdate` desc limit 0,5");

        ///// GET most viwed product
        //echo "SELECT * FROM products inner join viewProduct on products.id = viewProduct.product_id order by viewProduct.views_count desc limit 5 offset 0";
        $data['mostViwedProducts'] = DB::select("SELECT * FROM products inner join viewproduct on products.id = viewproduct.product_id order by viewproduct.views_count desc limit 5 offset 0");

        /* $data['search_terms'] = DB::select("select DISTINCT keyword from search_terms   order by last_searched desc limit 5"); */

        $data['search_terms'] = DB::select("SELECT keyword, MAX(results) AS results, last_searched FROM search_terms GROUP BY keyword order by last_searched desc limit 5");
        $today_date = Carbon::today();
        $data['today_date'] = $today_date->toDateString();
        $data['checkins_count'] = RoomBookDate::has('order.customer')->where('date_checkin', '=', $data['today_date'])->orderBy('date_checkin', 'DESC')->count();
        $data['checkouts_count'] = RoomBookDate::with('order.customer')->where('date_checkout', '=', $data['today_date'])->orderBy('date_checkout', 'DESC')->count();

        return view('admin.dashboard')->with('data', $data);
    }

    function updatePassword($user_id) {
        if (Request::isMethod('post')) {
            //DB::table('password_resets')->insert(array('token' => Input::get('_token')));// exit;

            $password = Input::get('password');
            $passwordconf = Input::get('password_confirmation');

            $validator = Validator::make(Request::all(), [
                        'password' => 'required|confirmed|min:6',
                        'password_confirmation' => 'required_with:password|min:6',
                            ]
            );

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
                //return Redirect::back()->withErrors($validator);
                //echo Redirect::back()->withErrors($validator); exit;
            } else {
                //echo Hash::make($password); // hash password
                $user = User::find($user_id);
                $user->password = Hash::make($password);
                $user->save();

                echo json_encode(array('success' => 'success'));
                exit;
            }
        }
        return view('admin.updatePassword');
    }

    function updateAvtar($user_id) {
        if (Request::isMethod('post')) {
            //DB::table('password_resets')->insert(array('token' => Input::get('_token')));// exit;

            $messages = [
                //'required' => 'The :attribute field is required.',
                'max' => 'Max file size should be less than 2MB.',
            ];

            $validator = Validator::make(Request::all(), [
                        'avtarImage' => 'required|image|mimes:jpeg,png,gif|max:2000',
                            ], $messages
            );

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
                //return Redirect::back()->withErrors($validator);
                //echo Redirect::back()->withErrors($validator); exit;
            } else {
                //echo '<pre>'; print_r($_FILES); exit;
                $imageName = time() . '_' . $_FILES['avtarImage']['name'];
                Request::file('avtarImage')->move(
                        base_path() . '/public/admin/avtar/', $imageName
                );

                $user = User::find($user_id);
                $user->image = $imageName;
                $user->save();

                echo json_encode(array('success' => $imageName));
                exit;
            }
        }
        return view('admin.updatePassword');
    }

    /* function getUserDetails($id)
      {
      $user = new User();
      $data['userDetails'] = $user->getUser($id);
      return view('admin.profile', $data);
      }

      function getAlbums()
      {
      $user = new User();
      $data['albums'] = $user->getAlbums();
      return view('admin.albums', $data);
      }

      public function checkSession()
      {
      Session::put('session_key', 'sad adl lasdla');
      echo Session::get('session_key');
      exit;
      //return view('admin.dashboard');
      } */

    public function bannertop() {

        $currentdate = date('Y-m-d');
        $resultsdata = DB::update("UPDATE banner_top SET status= 0 WHERE end_date <'" . $currentdate . "' ");

        if ((isset($_GET['rec']) && $_GET['rec'] != '')) {
            $data['num_rec_per_page'] = $num_rec_per_page = $_GET['rec'];
        } else {
            $data['num_rec_per_page'] = $num_rec_per_page = 10;
        }
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        } else {
            $page = 1;
        };
        $start_from = ($page - 1) * $num_rec_per_page;
        $pagedata = DB::table('banner_top');
        $total_records = $pagedata->count();
        $data['total_pages'] = ceil($total_records / $num_rec_per_page);
        $data['start_from'] = $start_from = (($page - 1) * ($num_rec_per_page));
        $data['banner_alltopdata'] = DB::select("SELECT * FROM banner_top");
        $data['banner_topdata'] = DB::select("SELECT * FROM banner_top LIMIT $start_from, $num_rec_per_page");

        /* Showing 3 to 4 of 8 entries */

        $page_to = (($page * $num_rec_per_page) > $total_records) ? $total_records : ($page * $num_rec_per_page);
        if ($page_to == 0) {
            $data['msg'] = 'Showing ' . $page_to . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        } else {
            $data['msg'] = 'Showing ' . ((($page - 1) * $num_rec_per_page) + 1) . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        }
        //$data['msg'] = 'Showing '. ((($page-1) * $num_rec_per_page) + 1) .' to '. $page_to .' of '. $total_records .' entries';
        /* Showing 3 to 4 of 8 entries end */

        $data['page_title'] = 'Index Top Banners:: Listing';

        $data['lastUpdated'] = $this->BannerModel->getLastUpdatedtop();
        return View::make('admin.index_banner_top_list')->with('result', $data);
    }

    public function bannermiddletop() {
        $currentdate = date('Y-m-d');
        $resultsdata = DB::update("UPDATE banner_middle_top SET status= 0 WHERE end_date <'" . $currentdate . "' ");

        if ((isset($_GET['rec']) && $_GET['rec'] != '')) {
            $data['num_rec_per_page'] = $num_rec_per_page = $_GET['rec'];
        } else {
            $data['num_rec_per_page'] = $num_rec_per_page = 10;
        }
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        } else {
            $page = 1;
        };
        $start_from = ($page - 1) * $num_rec_per_page;
        $pagedata = DB::table('banner_middle_top');
        $total_records = $pagedata->count();
        $data['total_pages'] = ceil($total_records / $num_rec_per_page);
        $data['start_from'] = $start_from = (($page - 1) * ($num_rec_per_page));
        $data['banner_allmiddletopdata'] = DB::select("SELECT * FROM banner_middle_top");
        $data['banner_middletopdata'] = DB::select("SELECT * FROM banner_middle_top LIMIT $start_from, $num_rec_per_page");

        /* Showing 3 to 4 of 8 entries */

        $page_to = (($page * $num_rec_per_page) > $total_records) ? $total_records : ($page * $num_rec_per_page);
        if ($page_to == 0) {
            $data['msg'] = 'Showing ' . $page_to . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        } else {
            $data['msg'] = 'Showing ' . ((($page - 1) * $num_rec_per_page) + 1) . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        }
        //$data['msg'] = 'Showing '. ((($page-1) * $num_rec_per_page) + 1) .' to '. $page_to .' of '. $total_records .' entries';
        /* Showing 3 to 4 of 8 entries end */

        $data['page_title'] = 'Index Middle Top Banners:: Listing';

        //$data['banner_middletopdata']= DB::table('banner_middle_top')->select('*')->take($num_rec_per_page)->get();
        $data['lastUpdated'] = $this->BannerModel->getLastUpdatedmiddletop();
        return View::make('admin.index_middle_top_list')->with('result', $data);
    }

    public function bannermiddlebottom() {

        $currentdate = date('Y-m-d');
        $resultsdata = DB::update("UPDATE banner_middle_bottom SET status= 0 WHERE end_date <'" . $currentdate . "' ");
        //$data['banner_middlebottomdata']= DB::table('banner_middle_bottom')->select('*')->get();
        if ((isset($_GET['rec']) && $_GET['rec'] != '')) {
            $data['num_rec_per_page'] = $num_rec_per_page = $_GET['rec'];
        } else {
            $data['num_rec_per_page'] = $num_rec_per_page = 10;
        }
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        } else {
            $page = 1;
        };
        $start_from = ($page - 1) * $num_rec_per_page;
        $pagedata = DB::table('banner_middle_bottom');

        $data['banner_allmiddlebottomdata'] = DB::select("SELECT * FROM banner_middle_bottom");

        $total_records = $pagedata->count();
        $data['total_pages'] = ceil($total_records / $num_rec_per_page);
        $data['start_from'] = $start_from = (($page - 1) * ($num_rec_per_page));
        $data['banner_middlebottomdata'] = DB::select("SELECT * FROM banner_middle_bottom LIMIT $start_from, $num_rec_per_page");



        /* Showing 3 to 4 of 8 entries */

        $page_to = (($page * $num_rec_per_page) > $total_records) ? $total_records : ($page * $num_rec_per_page);

        if ($page_to == 0) {
            $data['msg'] = 'Showing ' . $page_to . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        } else {
            $data['msg'] = 'Showing ' . ((($page - 1) * $num_rec_per_page) + 1) . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        }
        //$data['msg'] = 'Showing '. ((($page-1) * $num_rec_per_page) + 1) .' to '. $page_to .' of '. $total_records .' entries';


        $data['page_title'] = 'Index Middle Bottom Banners:: Listing';

        $data['lastUpdated'] = $this->BannerModel->getLastUpdatedmiddlebottom();
        return View::make('admin.index_middle_bottom_list')->with('result', $data);
    }

    public function leftbanner() {
        $currentdate = date('Y-m-d');
        $resultsdata = DB::update("UPDATE banner_left SET status= 0 WHERE end_date <'" . $currentdate . "' ");
        //return view('admin.left_banner_list');
        //$data['banner_leftdata']= DB::table('banner_left')->select('*')->get();

        if ((isset($_GET['rec']) && $_GET['rec'] != '')) {
            $data['num_rec_per_page'] = $num_rec_per_page = $_GET['rec'];
        } else {
            $data['num_rec_per_page'] = $num_rec_per_page = 10;
        }
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        } else {
            $page = 1;
        };
        $start_from = ($page - 1) * $num_rec_per_page;
        $pagedata = DB::table('banner_left');
        $total_records = $pagedata->count();
        $data['total_pages'] = ceil($total_records / $num_rec_per_page);
        $data['start_from'] = $start_from = (($page - 1) * ($num_rec_per_page));
        $data['banner_allleftdata'] = DB::select("SELECT * FROM banner_left");
        $data['banner_leftdata'] = DB::select("
            SELECT banner_left.*, group_concat(blc.category_id) as categories FROM banner_left
            LEFT JOIN banner_left_categories as blc ON blc.banner_id = banner_left.id
            GROUP BY banner_left.id
            LIMIT $start_from, $num_rec_per_page");

        /* Showing 3 to 4 of 8 entries */

        $page_to = (($page * $num_rec_per_page) > $total_records) ? $total_records : ($page * $num_rec_per_page);
        if ($page_to == 0) {
            $data['msg'] = 'Showing ' . $page_to . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        } else {
            $data['msg'] = 'Showing ' . ((($page - 1) * $num_rec_per_page) + 1) . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        }
        //$data['msg'] = 'Showing '. ((($page-1) * $num_rec_per_page) + 1) .' to '. $page_to .' of '. $total_records .' entries';
        /* Showing 3 to 4 of 8 entries end */




        $data['page_title'] = 'Left Banners:: Listing';

        $data['lastUpdated'] = $this->BannerModel->getLastUpdatedleft();

        $data['categories'] = $this->CategoryModel->getSelectedCategoriesTree(array(Input::get('categories')));

        return View::make('admin.left_banner_list')->with('result', $data);
    }

    public function leftpromotionbanner() {
        $currentdate = date('Y-m-d');
        $resultsdata = DB::update("UPDATE banner_left_promotion SET status= 0 WHERE end_date <'" . $currentdate . "' ");
        //return view('admin.left_promotion_banner_list');
        //$data['banner_leftpromotiondata']= DB::table('banner_left_promotion')->select('*')->get();
        if ((isset($_GET['rec']) && $_GET['rec'] != '')) {
            $data['num_rec_per_page'] = $num_rec_per_page = $_GET['rec'];
        } else {
            $data['num_rec_per_page'] = $num_rec_per_page = 10;
        }
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        } else {
            $page = 1;
        };
        $start_from = ($page - 1) * $num_rec_per_page;
        $pagedata = DB::table('banner_left_promotion');
        $total_records = $pagedata->count();
        $data['total_pages'] = ceil($total_records / $num_rec_per_page);
        $data['start_from'] = $start_from = (($page - 1) * ($num_rec_per_page));
        $data['banner_allleftpromotiondata'] = DB::select("SELECT * FROM banner_left_promotion");
        $data['banner_leftpromotiondata'] = DB::select("SELECT * FROM banner_left_promotion LIMIT $start_from, $num_rec_per_page");

        /* Showing 3 to 4 of 8 entries */

        $page_to = (($page * $num_rec_per_page) > $total_records) ? $total_records : ($page * $num_rec_per_page);
        if ($page_to == 0) {
            $data['msg'] = 'Showing ' . $page_to . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        } else {
            $data['msg'] = 'Showing ' . ((($page - 1) * $num_rec_per_page) + 1) . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        }
        //$data['msg'] = 'Showing '. ((($page-1) * $num_rec_per_page) + 1) .' to '. $page_to .' of '. $total_records .' entries';
        /* Showing 3 to 4 of 8 entries end */




        $data['page_title'] = 'Left Promotion Banners:: Listing';

        $data['lastUpdated'] = $this->BannerModel->getLastUpdatedleftpromotion();
        return View::make('admin.left_promotion_banner_list')->with('result', $data);
    }

    public function productbanner() {
        //return view('admin.product_banner_list');
        //$data['banner_productdata']= DB::table('product_banner_list')->select('*')->get();

        $currentdate = date('Y-m-d');
        $resultsdata = DB::update("UPDATE product_banner_list SET status= 0 WHERE end_date <'" . $currentdate . "' ");

        if ((isset($_GET['rec']) && $_GET['rec'] != '')) {
            $data['num_rec_per_page'] = $num_rec_per_page = $_GET['rec'];
        } else {
            $data['num_rec_per_page'] = $num_rec_per_page = 10;
        }
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
        } else {
            $page = 1;
        };
        $start_from = ($page - 1) * $num_rec_per_page;
        $pagedata = DB::table('product_banner_list');
        $total_records = $pagedata->count();
        $data['total_pages'] = ceil($total_records / $num_rec_per_page);
        $data['start_from'] = $start_from = (($page - 1) * ($num_rec_per_page));
        $data['banner_productdata'] = DB::select("SELECT * FROM product_banner_list LIMIT $start_from, $num_rec_per_page");

        $page_to = (($page * $num_rec_per_page) > $total_records) ? $total_records : ($page * $num_rec_per_page);

        if ($page_to == 0) {
            $data['msg'] = 'Showing ' . $page_to . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        } else {
            $data['msg'] = 'Showing ' . ((($page - 1) * $num_rec_per_page) + 1) . ' to ' . $page_to . ' of ' . $total_records . ' entries';
        }
        //$data['msg'] = 'Showing '. ((($page-1) * $num_rec_per_page) + 1) .' to '. $page_to .' of '. $total_records .' entries';
        /* Showing 3 to 4 of 8 entries end */


        $data['page_title'] = 'Product Banners:: Listing';

        $this->data['page_title'] = 'tes';
        $data['lastUpdated'] = $this->BannerModel->getLastUpdatedproduct();
        $data['getcategories'] = $this->BannerModel->getcategorydata();
        $data['categories'] = $this->CategoryModel->getCategoriesTree();
        return View::make('admin.product_banner_list')->with('result', $data);
    }

    public function aboutusEdit() {
        $data['content'] = DB::select('select * from aboutus where id = 1');
        $data['obj'] = DB::select('select * from aboutusobjective');
        return View::make('admin.aboutusEdit')->with('result', $data);
    }

    public function aboutEdit() {
        $pages = Page::where('page','=','aboutedit')->first();
        $data = unserialize($pages->old_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.about_us_edit')->with(['data'=>$data,'updated'=>$updated]);
    }

    public function about_editor(){
        $pages = Page::where('page','=','aboutedit')->first();
        if (Request::isMethod('post')) {
            if (Input::get('about') && Input::get('wbx_preview')) {
                foreach(Input::get('about') as $about) {
                        $data[] = $about;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/about_us_edit');
            } else {
                foreach(Input::get('about') as $about) {
                    $data[] = $about;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    public function aboutusUpdate() {
        if (Request::isMethod('post')) {
            $content1 = Request::input('content1');
            $content2 = Request::input('content2');
            $content3 = Request::input('content3');
            $content4 = Request::input('content4');
            $content5 = Request::input('content5');
            $content6 = Request::input('content6');
            $content7 = Request::input('content7');
            $content8 = Request::input('content8');
            $content9 = Request::input('content9');
            $content10 = Request::input('content10');
            $content11 = Request::input('content11');
            $content12 = Request::input('content12');
            $content13 = Request::input('content13');
            $content14 = Request::input('content14');
            $content15 = Request::input('content15');
            $content16 = Request::input('content16');
            $content17 = Request::input('content17');
            $content18 = Request::input('content18');
            $content19 = Request::input('content19');
            $content20 = Request::input('content20');
            $icon1 = Request::input('icon1');
            $icon2 = Request::input('icon2');
            $affected = DB::update("update aboutus set content1 = ?,
                                                        content2 = ?,
                                                        content3 = ?,
                                                        content4 = ?,
                                                        content5 = ?,
                                                        content6 = ?,
                                                        content7 = ?,
                                                        content8 = ?,
                                                        content9 = ?,
                                                        content10 = ?,
                                                        content11 = ?,
                                                        content12 = ?,
                                                        content13 = ?,
                                                        content14 = ?,
                                                        content15 = ?,
                                                        content16 = ?,
                                                        content17 = ?,
                                                        content18 = ?,
                                                        content19 = ?,
                                                        content20 = ?,
                                                        icon1 = ?,
                                                        icon2 = ?
                                                         where id = 1", [$content1, $content2, $content3, $content4, $content5, $content6, $content7, $content8, $content9, $content10, $content11, $content12, $content13, $content14, $content15, $content16, $content17, $content18, $content19, $content20, $icon1, $icon2]);
        } else {
            return view('admin.aboutusEdit');
        }
    }

    public function aboutusObjective() {
        if (Request::isMethod('post')) {
            $validator = Validator::make(Request::all(), [
                        'objText' => 'required',
            ]);

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
            } else {
                $objText = Request::input('objText');
                $status = Request::input('status');
                if ($status == 1) {
                    $status = 1;
                } else {
                    $status = 0;
                }

                $affected = DB::update("insert into aboutusObjective set objText = ?,
                                                        status = ? ", [$objText, $status]);

                echo json_encode(array('success' => 'success'));
                exit;
            }
        }
    }

    public function aboutusUpdateObjective() {
        if (Request::isMethod('post')) {
            $validator = Validator::make(Request::all(), [
                        'objText' => 'required',
            ]);

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
            } else {
                $objId = Request::input('objId');
                $objText = Request::input('objText');
                $status = Request::input('status');
                if ($status == 1) {
                    $status = 1;
                } else {
                    $status = 0;
                }

                $affected = DB::update("Update aboutusObjective set objText = ?,
                                                        status = ? where id=?", [$objText, $status, $objId]);

                echo json_encode(array('success' => 'success'));
                exit;
            }
        }
    }

    public function aboutusDeleteObjective() {
        if (Request::isMethod('post')) {
            $objId = Request::input('objId');

            $deleted = DB::delete("delete from aboutusObjective where id='" . $objId . "'");

            echo json_encode(array('success' => 'success'));
            exit;
        }
    }


    // ******************************************* Page Images List ************************************************
    public function page_images_list(){
        $page_image = new PageImage;
        // dd($page_image);
        if (Request::isMethod('post')) {
            // dd(Request::all());
            $file = Input::file('file_name');

            if (isset($file) && !empty($file)) {

                $info = getimagesize($file);
                // $name = Input::file('file_name')->getClientOriginalName();
                $file_extension   = Input::file('file_name')->getClientOriginalExtension();
                $name = "page-image-".time().".".$file_extension;
                $size = Input::file('file_name')->getSize();

                if ($size > 1049576 || !preg_match('/.jpg|.jpeg|.gif|.png$/', $name) || intval($info[0]) > 3450 || intval($info[1]) > 1950) {
                    Session::put('fail', '1');
                    return Redirect::back();
                }
                // $ret = Input::file('file_name')->move(base_path() . '/public/images/about', $name);
                $ret = Input::file('file_name')->move(base_path() . '/public/images/'. Input::get('page') .'/', $name);
                $page_image->image = $name;
            }
            if (Input::get('status_chk')) {
                $page_image->status  = 1;
            } else {
                $page_image->status = 0;
            }
            $page_image->title=Input::get('title');
            $page_image->page=Input::get('page');
            $page_image->type=Input::get('type');
            $page_image->link= Input::get('link')? Input::get('link') : NULL;
            $page_image->order=Input::get('order');
            $page_image->save();
            Session::put('success', '1');
            return Redirect::back();

        }

    }

    public function edit_page_images_list(){
        try {
            if (Request::isMethod('post')) {
                // dd(Request::all());
                $p_key=Input::get('key');
                $page_image = PageImage::find($p_key);

                $file = Input::file('file_name');
                if (isset($file) && !empty($file)) {
                    // remove old image if.
                    if(Input::get('old_image')){
                        @unlink(base_path() . '/public/images/'. Input::get('page')  .'/'.Input::get('old_image'));
                    }
                    $info = getimagesize($file);
                    // $name = Input::file('file_name')->getClientOriginalName();
                    $file_extension   = Input::file('file_name')->getClientOriginalExtension();
                    $name = "page-image-".time().".".$file_extension;
                    $size = Input::file('file_name')->getSize();

                    if ($size > 1049576 || !preg_match('/.jpg|.jpeg|.gif|.png$/', $name) || intval($info[0]) > 3450 || intval($info[1]) > 1950) {
                        Session::put('fail', '1');
                        return Redirect::back();
                    }
                    $ret = Input::file('file_name')->move(base_path() . '/public/images/'. Input::get('page') .'/', $name);
                    $page_image->image = $name;
                }
                if (Input::get('status_chk')) {
                    $page_image->status  = 1;
                } else {
                    $page_image->status = 0;
                }
                $page_image->title=Input::get('title');
                $page_image->page=Input::get('page');
                $page_image->type=Input::get('type');
                $page_image->link= Input::get('link')? Input::get('link') : NULL;
                $page_image->order=Input::get('order');
                $page_image->save();
                Session::put('success', '1');
                return Redirect::back();

            }

        }

        catch (Exception $e) {
            Session::put('fail', '1');
            if(Input::get('page') == 'home'){
                return Redirect::to('/web88cms/index_edit');
            }else if(Input::get('page') == 'dining') {
                return Redirect::to('/web88cms/dining_edit');
            }else if(Input::get('page') == 'facilities') {
                return Redirect::to('/web88cms/facilities_edit');
            } else if(Input::get('page') == 'about') {
                return Redirect::to('/web88cms/about_us_edit');
            } else {
                return Redirect::to('/web88cms/index_edit_third');
            }
        }

    }
    // for all records.
    public function page_images_list_all_del(){
        try {

            DB::table('page_images')->where('page', Input::get('page'))->delete();
            // delete all file from particular directory.
            // print_r(glob(base_path() . '/public/images/'. Input::get('page')."/*")); die;
            $files = glob(base_path() . '/public/images/'. Input::get('page')  .'/*'); //get all file names
            foreach($files as $file){
                if(is_file($file))
                unlink($file); //delete file
            }
            // foreach($files as $file)
            // {
            //     if(is_dir($file)) {
            //         recursiveDeleteDirectory($file);
            //     } else {
            //         unlink($file);
            //     }
            // }
            // rmdir($directory);
            Session::put('success', '1');
            if(Input::get('page') == 'home'){
                return Redirect::to('/web88cms/index_edit');
            } else if(Input::get('page') == 'dining') {
                return Redirect::to('/web88cms/dining_edit');
            }else if(Input::get('page') == 'facilities') {
                return Redirect::to('/web88cms/facilities_edit');
            } else if(Input::get('page') == 'about') {
                return Redirect::to('/web88cms/about_us_edit');
            } else {
                return Redirect::to('/web88cms/index_edit_third');
            }
        }

        catch (Exception $e) {
            Session::put('fail', '1');
            if(Input::get('page') == 'home'){
                return Redirect::to('/web88cms/index_edit');
            }else if(Input::get('page') == 'dining') {
                return Redirect::to('/web88cms/dining_edit');
            }else if(Input::get('page') == 'facilities') {
                return Redirect::to('/web88cms/facilities_edit');
            } else if(Input::get('page') == 'about') {
                return Redirect::to('/web88cms/about_us_edit');
            } else {
                return Redirect::to('/web88cms/index_edit_third');
            }
        }

    }
    // for selected records.

    public function page_images_list_selected_all_del(){
        if (Request::isMethod('post')) {
            $ids = Request::input('index');
            $remove_last_comma = rtrim($ids,',');
            $clean_array = explode(',', $remove_last_comma); // make an array
            $data = DB::table('page_images')->whereIn('id', explode(",",$remove_last_comma))->get();
            foreach ($data as $value) {
                if($value->image){
                    unlink(base_path() . '/public/images/'. Input::get('page')  .'/'. $value->image);
                }
            }
            DB::table("page_images")->whereIn('id',explode(",",$ids))->delete();
            Session::put('success', '1');
            if(Input::get('page') == 'home'){
                return Redirect::to('/web88cms/index_edit');
            }else if(Input::get('page') == 'dining') {
                return Redirect::to('/web88cms/dining_edit');
            }else if(Input::get('page') == 'facilities') {
                return Redirect::to('/web88cms/facilities_edit');
            } else if(Input::get('page') == 'about') {
                return Redirect::to('/web88cms/about_us_edit');
            } else {
                return Redirect::to('/web88cms/index_edit_third');
            }
        }

    }

    // single item delete.
    public function page_images_list_del(){
        try {
            // die('here');
            $p_key=Input::get('keys');
             // remove old image if.
            @unlink(base_path() . '/public/images/'. Input::get('page')  .'/'. Input::get('image_path'));
            PageImage::destroy($p_key);
            Session::put('success', '1');
            if(Input::get('page') == 'home'){
                return Redirect::to('/web88cms/index_edit');
            }else if(Input::get('page') == 'dining') {
                return Redirect::to('/web88cms/dining_edit');
            }else if(Input::get('page') == 'facilities') {
                return Redirect::to('/web88cms/facilities_edit');
            } else if(Input::get('page') == 'about') {
                return Redirect::to('/web88cms/about_us_edit');
            } else {
                return Redirect::to('/web88cms/index_edit_third');
            }
        }

        catch (Exception $e) {
            Session::put('fail', '1');
            if(Input::get('page') == 'home'){
                return Redirect::to('/web88cms/index_edit'); }
            else if(Input::get('page') == 'dining') {
                return Redirect::to('/web88cms/dining_edit');
            }else if(Input::get('page') == 'facilities') {
                return Redirect::to('/web88cms/facilities_edit');
            }else if(Input::get('page') == 'about') {
                return Redirect::to('/web88cms/about_us_edit');
            } else {
                return Redirect::to('/web88cms/index_edit_third');
            }
        }
    }
    // single only image delete.
    public function page_images_del_img(){
          // dd(Request::all());
        if (Request::isMethod('post')) {
            // remove old image if.
            if(Input::get('old_image')){
                @unlink(base_path() . '/public/images/'. Input::get('page')  .'/'. Input::get('image_path'));
            }
            $del_path = Request::input('image_path');
            $fas=PageImage::where('image','=',$del_path)->first();
            $fas->image=null;
            $fas->save();

            Session::put('success', '1');
            if(Input::get('page') == 'home'){
                return Redirect::to('/web88cms/index_edit'); }
            else if(Input::get('page') == 'dining') {
                return Redirect::to('/web88cms/dining_edit');
            }else if(Input::get('page') == 'facilities') {
                return Redirect::to('/web88cms/facilities_edit');
            } else if(Input::get('page') == 'about') {
                return Redirect::to('/web88cms/about_us_edit');
            } else {
                return Redirect::to('/web88cms/index_edit_third');
            }
        }
    }




    public function postAddvacancy() {
        try {
            $data = Input::all();
            foreach ($data["vacancy"] as $key => $item) {

                if ($key == 'requirement')
                    $data['vacancy']['requirement'] = base64_decode($item);
                if ($key == 'preferred')
                    $data['vacancy']['preferred'] = base64_decode($item);

                if ($key === 'date')
                    continue;
                if (!isset($item) || empty($item)) {
                    Session::put('fail', '1');
                    return Redirect::to('admin/vacancy');
                }
            }
            // print_r($data);die();

            $page = Page::where('page', '=', $data['type'])->firstOrFail();
            $arr = [];
            if (!empty($page->slider_text)) {
                $arr = unserialize($page->slider_text);
            }
            $time = explode('/', $data['vacancy']['date']);
            $new_time = array_reverse($time);
            $data['vacancy']['date'] = implode('/', $new_time);
            $arr[] = $data['vacancy'];
            $page->slider_text = serialize($arr);
            if ($page->save())
                Session::put('success', '1');
            else
                Session::put('fail', '1');
            return Redirect::to('web88/admin/vacancy');
        } catch (Exception $e) {
            Session::put('fail', '1');
            return Redirect::to('web88/admin/vacancy');
        }
    }

    public function checkInOutListing(Request1 $request,$limit = 10) {
        $page = 0;

        if (Input::get('page')) {
            $page = Input::get('page');
        }
        if (Input::get('sort')) {
            $sort = Input::get('sort');
        } else {
            $sort = 'ASC';
        }
        if (Input::get('sort_by')) {
            $sort_by = Input::get('sort_by');
        } else {
            $sort_by = 'createdate';
        }

        $this->data['success'] = Session::get('checkinout.success');
        Session::forget('checkinout.success');
        $this->data['warning'] = Session::get('checkinout.warning');
        Session::forget('checkinout.warning');

        //Sorting URL Start
        $sortingUrl = url('web88cms/checkInOut/' . $limit) . '?';
        if (Input::get('name')) {
            $sortingUrl .= '&name=' . Input::get('name');
        }
        //Sorting URL End

        $this->data['limit'] = $limit;
        $this->data['page'] = $page;
        $this->data['sorting_url'] = $sortingUrl;
        $this->data['sort'] = $sort;
        $this->data['sort_by'] = $sort_by;

        //$this->data['checkins'] = $this->RoomBookDateModel->getGstrates($limit, $page, Input::get());
        //        $this->data['checkins'] =  RoomBookDate::with('order.customer')->where("room_booked_date.id","=","156")->paginate($limit);
        $input = Input::all();
        $checkin_date = Carbon::today();
        $checkout_date = Carbon::today();
        if (!empty($input['checkin_date'])) {
            $checkin_date = Carbon::createFromFormat('Y-m-d', $input['checkin_date']);
        }
        if (!empty($input['checkout_date'])) {
            $checkout_date = Carbon::createFromFormat('Y-m-d', $input['checkout_date']);
        }
        //echo "date = ".$mytime->toDateString();
        $this->data['current_date_chackin'] = $checkin_date->toDateString();
        $this->data['current_date_chackout'] = $checkout_date->toDateString();

        //$checkin_previous_date = Carbon::yesterday();
        $checkin_previous_date = $checkin_date->addDays(-1);
        $this->data['checkin_previous_date'] = $checkin_previous_date->toDateString();
        //        $checkin_next_date = Carbon::tomorrow();
        $checkin_next_date = $checkin_date->addDays(2);
        $this->data['checkin_next_date'] = $checkin_next_date->toDateString();

        //$checkout_previous_date = Carbon::yesterday();
        $checkout_previous_date = $checkout_date->addDays(-1);
        $this->data['checkout_previous_date'] = $checkout_previous_date->toDateString();
        //$checkout_previous_date = Carbon::tomorrow();
        $checkout_next_date = $checkout_date->addDays(2);
        $this->data['checkout_next_date'] = $checkout_next_date->toDateString();

        if (!empty($input['checkin_from']) && !empty($input['checkin_to'])) {
            $checkin_from = Carbon::createFromFormat('d-m-Y', $input['checkin_from'])->toDateString();
            $checkin_to = Carbon::createFromFormat('d-m-Y', $input['checkin_to'])->toDateString();
            $this->data['checkins'] = RoomBookDate::has('order.customer')->with('order.customer')->whereBetween('date_checkin', [$checkin_from, $checkin_to])->orderBy('date_checkin', 'DESC')->paginate($limit);
        } else {
            $this->data['checkins'] = RoomBookDate::with('order.customer')->has('order.customer')->where('date_checkin', '=', $this->data['current_date_chackin'])->orderBy('date_checkin', 'DESC')->paginate($limit);
        }
        if (!empty($input['checkout_from']) && !empty($input['checkout_to'])) {
            $checkout_from = Carbon::createFromFormat('d-m-Y', $input['checkout_from'])->toDateString();
            $checkout_to = Carbon::createFromFormat('d-m-Y', $input['checkout_to'])->toDateString();
            $this->data['checkouts'] = RoomBookDate::has('order.customer')->with('order.customer')->whereBetween('date_checkout', [$checkout_from, $checkout_to])->orderBy('date_checkout', 'DESC')->paginate($limit);
        } else {
            $this->data['checkouts'] = RoomBookDate::has('order.customer')->with('order.customer')->where('date_checkout', '=', $this->data['current_date_chackout'])->orderBy('date_checkout', 'DESC')->paginate($limit);
        }
        //        echo "<pre>";
        //        foreach ($this->data['checkins'] as $value){
        //            print_r($value->order->customer);
        //        }
        //        exit;
        $currentQueries = $request->query();
        $csv_url = $request->fullUrl();
        if(count($currentQueries) > 0){
            $csv_url .= "&csv=true";
        }  else {
            $csv_url .= "?csv=true";
        }
        //        $this->data['csv_url']=$request->fullUrl();
        $this->data['csv_url']=$csv_url;
        if (Input::has('csv')) {
            $filename = "orders.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Booking ID', 'Customer Name', 'Checking In', 'Checking Out'));

            foreach ($this->data['checkins'] as $row) {
                fputcsv($handle, array($row->order_id, $row->order->customer->first_name." ".$row->order->customer->last_name,$row->date_checkin,''));
            }
            foreach ($this->data['checkouts'] as $row) {
                fputcsv($handle, array($row->order_id, $row->order->customer->first_name." ".$row->order->customer->last_name,'',$row->date_checkout));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            );

            return Response::download($filename, $filename, $headers);
        } else {
            return view('admin.checkinout_listing', $this->data);
        }
    }

    public function loader()
    {
        $data['loader'] =Loader::all();
        return view('admin.loader.index',$data);
    }

    public function storeLoader(){
        Loader::where(['id'=>1])->update([
            'name'=>Input::get('name'),
            'status'=>Input::get('status')?1:0,
        ]);
        return Redirect::back()->with(['flash_message', 'The data has been updated']);

    }

    public function headerUpdate()
    {
        // dd(Request::all());
        if (Request::isMethod('post')) {
            $content = Request::input('content');
            $page = Request::input('page');
            $isExist = DB::table('page_header')->where('page',$page)->exists();
            if($isExist){
                $affected = DB::update("update page_header set content = ? where page = ?", [$content,$page]);
            }else{
                $affected = DB::table('page_header')
                                ->insert([
                                    'page' => $page,
                                    'content' =>$content
                                ]);
            }
            if($page == 'gallery'){
                $this->updateAdminLastActivity('gallery');
            }

        }
        // else {
        //     return view('admin.promotions.promotions_list');
        // }
    }


    public function diningEdit() {
        $pages = Page::where('page','=','dinning')->first();
        $dining_mid = Page::where('page','=','dining_two')->first();
        $dining_left = Page::where('page','=','dining_three')->first();
        // $page_heading = DB::table('page_header')->where('page', 'dining')->first();
        // $heading= unserialize($page_heading->content);
        $header = DB::table('page_header')->where('page','dining')->get();
        $mid= unserialize($dining_mid->new_content);
        $left = unserialize($dining_left->new_content);
        $data = unserialize($pages->new_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.dining.dining_edit')->with(['data'=>$data,'updated'=>$updated, 'header' => $header, 'mid'=> $mid,'left'=> $left]);
        // return View::make('admin.dining.dining_edit')->with(['data'=>$data,'updated'=>$updated, 'heading' => $heading, 'mid'=> $mid,'left'=> $left]);
    }

    public function dinning_editor(){
        $pages = Page::where('page','=','dinning')->first();
        if (Request::isMethod('post')) {
            // dd(Request::all());
            if (Input::get('dining') && Input::get('wbx_preview')) {
                foreach(Input::get('dining') as $dining) {
                        $data[] = $dining;
                }
                $content = serialize($data);
                // dd($content);
                // $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/dining_edit');
            } else {
                foreach(Input::get('dining') as $dining) {
                    $data[] = $dining;
                }
                $content = serialize($data);
                // dd($content);
                // $pages->edit_content = $content;
                $pages->new_content = $content;
                // $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }




    // for dining heading.
    public function dinning_header_editor(){
        if (Request::isMethod('post')) {
            // dd(Request::all());
            if (Input::get('heading') && Input::get('wbx_preview_heading')) {
                foreach(Input::get('heading') as $heading) {
                        $data[] = $heading;
                }
                $content = serialize($data);
                // dd($content);
                DB::update("update page_header set content = ? where page = ?", [$content,'dining']);
                return Redirect::to('web88cms/dining_editor');
            } else {
                foreach(Input::get('heading') as $heading) {
                    $data[] = $heading;
                }
                $content = serialize($data);
                DB::update("update page_header set content = ? where page = ?", [$content,'dining']);
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }
    // dinig mid.
    public function dinning_mid_editor(){
        $pages = Page::where('page','=','dining_two')->first();
        if (Request::isMethod('post')) {
            if (Input::get('mid') && Input::get('wbx_preview_mid')) {
                foreach(Input::get('mid') as $mid) {
                        $data[] = $mid;
                }
                $content = serialize($data);
                // $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/dining_mid_editor');
            } else {
                foreach(Input::get('mid') as $mid) {
                    $data[] = $mid;
                }
                $content = serialize($data);
                // $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }
    // dining left
    public function dinning_left_editor(){
        $pages = Page::where('page','=','dining_three')->first();
        if (Request::isMethod('post')) {
            if (Input::get('left') && Input::get('wbx_preview_left')) {
                foreach(Input::get('left') as $left) {
                        $data[] = $left;
                }
                $content = serialize($data);
                //$pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/dining_left_editor');
            } else {
                foreach(Input::get('left') as $left) {
                    $data[] = $left;
                }
                $content = serialize($data);
                //$pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }


    public function facilityEdit($item=10, $page=1) {


        $pages = Page::where('page','=','facilities')->first();
        $data = unserialize($pages->new_content);
        //echo '<pre>';print_r($data);exit;
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        // dd(DB::select('select * from facilities'));
        $videos = DB::select('select * from videos');
        // $facilities = DB::select('select * from facilities');
        // total Facilities
        $totalFacilities = DB::table('facilities')->orderBy('id','desc')->get();
        if($page>1){
            $startLimit = ($page-1)*$item;
        }else{
            $startLimit = 0;
        }
        $facilities = DB::table('facilities')->orderBy('id','desc')->offset($startLimit)->take($item)->get();

        $header = DB::table('page_header')->where('page','facilities')->get();

        if(count($totalFacilities) < $item and $page!=1){
            dd('ehre');
            return Redirect::to('web88cms/facilities_edit/'.$item.'/1');
        }

        return View::make('admin.facility.facility_edit')->with(['page' => $page,'item' => $item,'data'=>$data,'updated'=>$updated,'videos'=> $videos, 'facilities'=>$facilities, 'totalFacilities' => count($totalFacilities),'fpage' => $pages,'header' => $header]);
    }

    public function facility_editor(){
        $pages = Page::where('page','=','facilities')->first();
        if (Request::isMethod('post')) {
            // dd(Request::all());
            if (Input::get('facility') && Input::get('wbx_preview')) {
                foreach(Input::get('facility') as $facility) {
                        $data[] = $facility;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/facilities_edit');
            } else {
                foreach(Input::get('facility') as $facility) {
                    $data[] = $facility;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    // add/update facility
    public function facilityAdd() {
        if (Request::isMethod('post')) {
            // var_dump($_FILES);
            // dd(Request::all());
            // dd(Request::file('icon'));
            $validator = Validator::make(Request::all(), [
                'name' => 'required',
                'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
            ]);

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
            } else {
                $name = Request::input('name');
                $status = Request::input('status')? 1 : 0;
                $file = Request::file('icon');
                // file rename.
                $file_extension   = $file->getClientOriginalExtension();
                $new_name = "facility-".time().".".$file_extension;
                $file->move(
                    base_path() . '/public/images/', $new_name
                );
                $icon = '/public/images/'.$new_name;
                $affected = DB::update("insert into facilities set name = ?,icon = ?,status = ? ", [$name, $icon, $status]);
                echo json_encode(array('success' => 'success'));
                exit;
            }
        }
    }

    public function facilityUpdate() {
        if (Request::isMethod('post')) {
            // dd(Request::all());
            $validator = Validator::make(Request::all(), [
                'name' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:500',
            ]);

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
            } else {
                $id = Request::input('objId');
                $old_icon = Request::input('old_icon');
                $name = Request::input('name');
                $status = Request::input('status')? 1 : 0;
                $file = Request::file('icon');
                // file rename.
                if(!empty($file)){
                    // remove old one.
                    unlink(base_path().$old_icon);
                    // unlink(base_path()."/public/images/".$old_icon);
                    // new file.
                    $file_extension   = $file->getClientOriginalExtension();
                    $new_name = "facility-".time().".".$file_extension;
                    $file->move(
                        base_path() . '/public/images/', $new_name
                    );
                    $icon = '/public/images/'.$new_name;
                    $affected = DB::update("UPDATE facilities SET name = ?, icon = ?, status = ? WHERE id=?", [$name, $icon, $status, $id]);
                } else {
                    $affected = DB::update("UPDATE facilities SET name = ?, status = ? WHERE id=?", [$name, $status, $id]);
                }
                // echo json_encode(array('success' => 'success'));
                // exit;
                if($affected){
                    Session::put('success', '1');
                    return Redirect::back();
                } else {
                    Session::put('fail', '1');
                    return Redirect::back();
                }
            }
        }
    }

    public function facilityDelete() {
        if (Request::isMethod('post')) {
            $objId = Request::input('objId');

            $deleted = DB::delete("DELETE FROM facilities WHERE id='" . $objId . "'");

            // echo json_encode(array('success' => 'success'));
            // exit;
             if($deleted){
                Session::put('success', '1');
                return Redirect::back();
            } else {
                Session::put('fail', '1');
                return Redirect::back();
            }
        }
    }

    // add/update/delete video.
        // add/update video
    public function videoAdd() {
        if (Request::isMethod('post')) {
            $validator = Validator::make(Request::all(), [
                'title' => 'required',
                'video' => 'required',
                'background' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1536',
            ]);

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
            } else {
                $title = Request::input('title');
                $video = Request::input('video');
                $status = Request::input('status')? 1 : 0;
                $file = Request::file('background');
                // file rename.
                $file_extension   = $file->getClientOriginalExtension();
                $new_name = "video-".time().".".$file_extension;
                $file->move(
                    base_path() . '/public/images/', $new_name
                );
                $background = '/public/images/'.$new_name;
                $affected = DB::update("insert into videos set title = ?, video =?, background = ?, status = ? ", [$title, $video, $background, $status]);
                echo json_encode(array('success' => 'success'));
                exit;
            }
        }
    }

    public function videoUpdate() {
        if (Request::isMethod('post')) {
            $validator = Validator::make(Request::all(), [
                'title' => 'required',
                'video' => 'required',
                'background' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1536',
            ]);

            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
            } else {
                $id = Request::input('objId');
                $old_background = Request::input('old_background');
                $title = Request::input('title');
                $video = Request::input('video');
                $status = Request::input('status')? 1 : 0;
                $file = Request::file('background');
                // file rename.
                if(!empty($file)){
                    // remove old one.
                    unlink(base_path().$old_background);
                    // unlink(base_path()."/public/images/".$old_background);
                    // new file.
                    $file_extension   = $file->getClientOriginalExtension();
                    $new_background = "video-".time().".".$file_extension;
                    $file->move(
                        base_path() . '/public/images/', $new_background
                    );
                    $background = '/public/images/'.$new_background;
                    $affected = DB::update("UPDATE videos SET title = ?, background = ?, video = ?, status = ? WHERE id=?", [$title, $background, $video, $status, $id]);
                } else {
                    $affected = DB::update("UPDATE videos SET title = ?, video = ?, status = ? WHERE id=?", [$title, $video, $status, $id]);
                }
                // echo json_encode(array('success' => 'success'));
                // exit;
                if($affected){
                    Session::put('success', '1');
                    return Redirect::back();
                } else {
                    Session::put('fail', '1');
                    return Redirect::back();
                }
            }
        }
    }

    public function videoDelete() {
        if (Request::isMethod('post')) {
            $objId = Request::input('objId');
            $deleted = DB::delete("DELETE FROM videos WHERE id ='" . $objId . "'");
            // echo json_encode(array('success' => 'success'));
            // exit;
             if($deleted){
                Session::put('success', '1');
                return Redirect::back();
            } else {
                Session::put('fail', '1');
                return Redirect::back();
            }
        }
    }

    public function backgroundUpdate() {
        if (Request::isMethod('post')) {
            // dd(Request::all());
            $validator = Validator::make(Request::all(), [
                'background' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1536',
            ]);
            if ($validator->fails()) {
                $json['error'] = $validator->errors()->all();
                echo json_encode($json);
                exit;
            } else {
                $id = Request::input('objId');
                $title = Request::input('title');
                $old_background = Request::input('old_background');
                $status = Request::input('status')? 1 : 0;
                $file = Request::file('background');
                // file rename.
                if(!empty($file)){
                    // remove old one.
                    unlink(base_path().$old_background);
                    // new file.
                    $file_extension   = $file->getClientOriginalExtension();
                    $new_background = "background-".time().".".$file_extension;
                    $file->move(
                        base_path() . '/public/images/', $new_background
                    );
                    $background = '/public/images/'.$new_background;
                    $affected = DB::update("UPDATE pages SET title =?,  background = ? , status = ? WHERE page = ?", [$title, $background, $status , 'facilities']);
                } else {
                    $affected = DB::update("UPDATE pages SET title = ?, status = ? WHERE page = ?", [$title, $status , 'facilities']);
                }

                echo json_encode(array('success' => 'success'));
                exit;
            }
        }
    }



    // video_title_edit
    public function videoTitleEdit() {
        $pages = Page::where('page','=','video')->first();
        $data = unserialize($pages->new_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.index_video_title_edit')->with(['data'=>$data,'updated'=>$updated]);
    }

    public function video_title_editor(){
        $pages = Page::where('page','=','video')->first();
        if (Request::isMethod('post')) {
            // dd(Request::all());
            if (Input::get('video') && Input::get('wbx_preview')) {
                foreach(Input::get('facility') as $videodata) {
                        $data[] = $videodata;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/video_title_edit');
            } else {
                foreach(Input::get('video') as $videodata) {
                    $data[] = $videodata;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }


    public function weddingEdit() {
        $pages = Page::where('page','=','wedding')->first();

        // $page_heading = DB::table('page_header')->where('page', 'dining')->first();
        // $heading= unserialize($page_heading->content);
        $header = DB::table('page_header')->where('page','wedding')->get();
       $data = unserialize($pages->new_content);
        //echo '<pre>';
        //print_r(uns );
        //exit;
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.wedding.wedding_edit')->with(['data'=>$data,'header'=>$header,'updated'=>$updated]);
    }

    public function weddings_editor(){
        $pages = Page::where('page','=','wedding')->first();
        if (Request::isMethod('post')) {
            if (Input::get('data') && Input::get('wbx_preview')) {
                foreach(Input::get('data') as $wedding) {
                        $data[] = $wedding;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/weddings_editor');
            } else {
                foreach(Input::get('data') as $wedding) {
                    $data[] = $wedding;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }
    // for wedding heading.
    public function wedding_header_editor(){
        if (Request::isMethod('post')) {
            // dd(Request::all());
            if (Input::get('heading') && Input::get('wbx_preview_heading')) {
                foreach(Input::get('heading') as $heading) {
                        $data[] = $heading;
                }
                $content = serialize($data);
                // dd($content);
                DB::update("update page_header set content = ? where page = ?", [$content,'wedding']);
                return Redirect::to('web88cms/weddings_editor');
            } else {
                foreach(Input::get('heading') as $heading) {
                    $data[] = $heading;
                }
                $content = serialize($data);
                DB::update("update page_header set content = ? where page = ?", [$content,'wedding']);
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    public function eventsEdit() {
        $pages = Page::where('page','=','events')->first();
        $events_left = Page::where('page','=','events_three')->first();
        // $page_heading = DB::table('page_header')->where('page', 'dining')->first();
        // $heading= unserialize($page_heading->content);
        $header = DB::table('page_header')->where('page','events')->get();
        //$mid= unserialize(eve_mid->new_content);
        $left = unserialize($events_left->new_content);
        $data = unserialize($pages->new_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.events.events_edit')->with(['data'=>$data,'updated'=>$updated, 'header' => $header,'left'=> $left]);
    }

    public function events_editor(){
        $pages = Page::where('page','=','events')->first();
        if (Request::isMethod('post')) {
            // dd(Request::all());
            if (Input::get('events') && Input::get('wbx_preview')) {
                foreach(Input::get('events') as $events) {
                        $data[] = $events;
                }
                $content = serialize($data);
                // dd($content);
                // $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/events_edit');
            } else {
                foreach(Input::get('events') as $events) {
                    $data[] = $events;
                }
                $content = serialize($data);
                // dd($content);
                // $pages->edit_content = $content;
                $pages->new_content = $content;
                // $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
        /* $pages = Page::where('page','=','event')->first();
        if (Request::isMethod('post')) {
            if (Input::get('events') && Input::get('wbx_preview')) {
                foreach(Input::get('events') as $events) {
                        $data[] = $events;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/events_edit');
            } else {
                foreach(Input::get('events') as $events) {
                    $data[] = $events;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        } */
    }


    public function events_header_editor(){
        if (Request::isMethod('post')) {
            // dd(Request::all());
            if (Input::get('heading') && Input::get('wbx_preview_heading')) {
                foreach(Input::get('heading') as $heading) {
                        $data[] = $heading;
                }
                $content = serialize($data);
                // dd($content);
                DB::update("update page_header set content = ? where page = ?", [$content,'events']);
                return Redirect::to('web88cms/events_editor');
            } else {
                foreach(Input::get('heading') as $heading) {
                    $data[] = $heading;
                }
                $content = serialize($data);
                DB::update("update page_header set content = ? where page = ?", [$content,'events']);
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    public function events_left_editor(){
        $pages = Page::where('page','=','events_three')->first();
        if (Request::isMethod('post')) {
            if (Input::get('left') && Input::get('wbx_preview_left')) {
                foreach(Input::get('left') as $left) {
                        $data[] = $left;
                }
                $content = serialize($data);
                //$pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/events_left_editor');
            } else {
                foreach(Input::get('left') as $left) {
                    $data[] = $left;
                }
                $content = serialize($data);
                //$pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    //Dynamic Index.
    public function indexEdit() {
        $pages = Page::where('page','=','index')->first();
        $data = unserialize($pages->new_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.index_edit')->with(['data'=>$data,'updated'=>$updated]);
    }

    public function index_editor(){
        $pages = Page::where('page','=','index')->first();
        if (Request::isMethod('post')) {
            if (Input::get('index') && Input::get('wbx_preview')) {
                foreach(Input::get('index') as $index) {
                        $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/index_edit');
            } else {
                foreach(Input::get('index') as $index) {
                    $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    // LATER ADDED.
    public function indexEditSecond() {
        $pages = Page::where('page','=','index_second')->first();
        $data = unserialize($pages->new_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.index_edit_second')->with(['data'=>$data,'updated'=>$updated]);
    }
    public function index_editor_second(){
        $pages = Page::where('page','=','index_second')->first();
        if (Request::isMethod('post')) {
            if (Input::get('index') && Input::get('wbx_preview')) {
                foreach(Input::get('index') as $index) {
                        $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/index_edit_second');
            } else {
                foreach(Input::get('index') as $index) {
                    $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    public function indexEditThird() {
        $pages = Page::where('page','=','index_third')->first();
        $data = unserialize($pages->new_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        return View::make('admin.index_edit_third')->with(['data'=>$data,'updated'=>$updated]);
    }
    public function index_editor_third(){
        $pages = Page::where('page','=','index_third')->first();
        if (Request::isMethod('post')) {
            if (Input::get('index') && Input::get('wbx_preview')) {
                foreach(Input::get('index') as $index) {
                        $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/index_edit_third');
            } else {
                foreach(Input::get('index') as $index) {
                    $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    public function indexEditFourth() {
        $pages = Page::where('page','=','index_fourth')->first();
        $data = unserialize($pages->new_content);
        $updated=date('d M, Y @ g.iA', strtotime($pages->updated_at));
        $header = DB::table('page_header')->where('page','home_promotions')->get();
        return View::make('admin.index_edit_fourth')->with(['data'=>$data,'updated'=>$updated,'header' => $header]);
    }
    public function index_editor_fourth(){
        $pages = Page::where('page','=','index_fourth')->first();
        if (Request::isMethod('post')) {
            if (Input::get('index') && Input::get('wbx_preview')) {
                foreach(Input::get('index') as $index) {
                        $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->save();
                return Redirect::to('web88cms/index_edit_fourth');
            } else {
                foreach(Input::get('index') as $index) {
                    $data[] = $index;
                }
                $content = serialize($data);
                $pages->edit_content = $content;
                $pages->new_content = $content;
                $pages->old_content = $content;
                $pages->save();
            }
            Session::put('success', '1');
            return Redirect::back();//->with('updated',date('d M, Y @ g.iA', strtotime($pages->updated_at)));
        }
        else{
            Session::put('fail', '1');
            return Redirect::back();
        }
    }

    public function updateAdminLastActivity($section='')
    {
        if(Auth::check() && $section != ''){
            $admin_id = Auth::user()->id;
        }else{
            return 0;
        }
        $last = DB::table('admin_last_activity')->where('section',$section)->orderBy('updated_at','desc')->first();
        $update = ['admin_id' => $admin_id, 'section' => $section, 'updated_at' => date("Y-m-d H:i:s")];
        if($last){
            DB::table('admin_last_activity')->where('id', $last->id)->update($update);
        }else{
            $update['created_at'] = date("Y-m-d H:i:s");
            DB::table('admin_last_activity')->insert($update);
        }
    }


}
