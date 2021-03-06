<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Session;
use Input;
//use Illuminate\Http\RedirectResponse;
use Auth;
use Validator;
use Hash;
use DB;
use Redirect;
use App\notification;
//use Request;

use App\Http\Library\Image_lib;
use File;
use Mail;
use Response;

use App\Http\Models\ShippingMethod;
use App\Http\Models\PwpProduct;
use App\Http\Models\Admin\Orders;
use App\Http\Models\Admin\Product;
use App\Http\Models\Admin\RoomPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;

use LaravelAnalytics;
use Spatie\Analytics\Period;


class RoomsController extends Controller {
	private $data = array();
	private $ProductModel = null;
	private $roomPriceModel = null;
	private $CategoryModel = null;
	private $Brand = null;
	private $Color = null;
	private $OrderModel = null;

	private $ShippingModel = null;



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
	public function __construct()
	{
		$this->middleware('auth');
		$this->OrderModel  = new Orders();
		/*$this->ProductModel = new Product();
		$this->roomPriceModel = new RoomPrice();
		$this->CategoryModel = new Category();
		$this->BrandModel = new Brand();
		$this->ColorModel = new Color();
		$this->ShippingModel = new ShippingMethod(); */

		$this->Image = new Image_lib();
	}

	public function index()
	{ 
		return view('admin.booking.room_sales_list');
	}
	
	public function room_sales_report_graph(Request $request){

		//retrieve visitors and pageview data for the current day and the last seven days
		$totalsForAllResults = LaravelAnalytics::getWebsiteConversion();
/*
		$totalDataForAllResults = LaravelAnalytics::getWebsiteAllData();

		$userTypesResults = LaravelAnalytics::fetchUserTypes();
		dd($userTypesResults);*/

		$requestArr = $request->all();
		$newCustomers = $topSoldRoomArr = array();
		// Sales Report Graph Monthly
		$monthsArr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		if(!empty($requestArr['start']) && !empty($requestArr['end'])){
			
			$start = date('Y-m-d', strtotime($requestArr['start']));
			$end = date('Y-m-d', strtotime($requestArr['end']));
			
			$orders = DB::table('orders')->whereBetween('modifydate', [$start, $end])->groupBy('orderedDate')->select(DB::raw('DATE_FORMAT(modifydate, "%Y-%m-%d") AS orderedDate, SUM(`totalPrice`) AS totalPrice'))->lists('totalPrice', 'orderedDate');
			$ordersArr = array();
			foreach($orders as $date => $sale){
				$date = str_replace('-', '-<br>', $date);
				$ordersArr[] = [$date, $sale];
			}
			
			// Sales By Room Type
			$dateRangeOrdersIds = DB::table('orders')->whereBetween('modifydate', [$start, $end])->lists('id','id');
			$salesByRoomType = DB::table('order_to_product')->join('products', 'products.id', '=', 'order_to_product.product_id')->whereIn('order_id', $dateRangeOrdersIds)->groupBy('product_id')->select(DB::raw('SUM(`amount`) AS monthlyTotalPrice, products.type as product_name'))->lists('monthlyTotalPrice','product_name');
			$salesByRoomTypeArr = array();
			foreach($salesByRoomType as $roomType => $amount){
				$roomType = str_replace(' ', '<br>', $roomType);
				$salesByRoomTypeArr[] = [$roomType, $amount];
			}
			// Sales By Room Type - END
			
			// newCustomers
			$newCustomers = DB::select("SELECT count(id) as countCustomers, DATE_FORMAT(createdate, \"%Y-%m-%d\") as month FROM `customers` WHERE DATE_FORMAT(createdate, \"%Y-%m-%d\") between '$start' AND '$end' group by DATE_FORMAT(createdate, \"%Y-%m-%d\") order by DATE_FORMAT(createdate, \"%Y-%m-%d\")");
			
			$newCustomersArr = array();
			foreach($newCustomers as $valueArr){
				$date = str_replace('-', '-<br>', $valueArr->month);
				$newCustomersArr[] = [$date, $valueArr->countCustomers];
			}
			$data['newCustomersArr'] = json_encode($newCustomersArr);
			
			//// Get returning customers for dashboard customer graph
			$returnCustomers  = DB::select("SELECT DATE_FORMAT(createdate, \"%Y-%m-%d\") as month, count(customer_id) as countCustomers FROM  `orders` WHERE DATE_FORMAT(modifydate, \"%Y-%m-%d\") between '$start' AND '$end' GROUP BY MONTH( modifydate ) ORDER BY MONTH( modifydate )");
			
			$returnCustomersArr = array();
			foreach($returnCustomers as $valueArr){
				$date = str_replace('-', '-<br>', $valueArr->month);
				$returnCustomersArr[] = [$date, $valueArr->countCustomers];
			}
			$data['returnCustomersArr'] = json_encode($returnCustomersArr);
			
			// order_to_product
			$topSoldRoomArr = DB::table('order_to_product')->join('products', 'products.id', '=', 'order_to_product.product_id')->whereIn('order_id', $dateRangeOrdersIds)->groupBy('product_id')->select(DB::raw('SUM(`amount`) AS monthlyTotalPrice, products.type as product_name'))->orderBy('monthlyTotalPrice', 'DESC')->limit(5)->lists('monthlyTotalPrice','product_name');
			
			//dd($newCustomers);
			$data['datesArr']['start'] = $requestArr['start'];
			$data['datesArr']['end'] = $requestArr['end'];
			
		}else{
			
			$orders = DB::table('orders')->whereRaw('YEAR(modifydate) = YEAR(CURDATE())')->groupBy(DB::raw('DATE_FORMAT(modifydate, "%m")'))->select(DB::raw('LEFT(MONTHNAME(modifydate),3) AS Month, SUM(`totalPrice`) AS monthlyTotalPrice'))->lists('monthlyTotalPrice', 'Month');
			$ordersArr = array();
			
			foreach($monthsArr as $month){
				if(array_key_exists($month,$orders)){
					$ordersArr[] = [$month, $orders[$month]];
				}else{ 
					$ordersArr[] = [$month, 0];
				}
			}
			
			// Sales By Room Type
			$currentYearsOrdersIds = DB::table('orders')->whereRaw('YEAR(modifydate) = YEAR(CURDATE())')->lists('id','id');
			$salesByRoomType = DB::table('order_to_product')->join('products', 'products.id', '=', 'order_to_product.product_id')->whereIn('order_id', $currentYearsOrdersIds)->groupBy('product_id')->select(DB::raw('SUM(`amount`) AS monthlyTotalPrice, products.type as product_name'))->lists('monthlyTotalPrice','product_name');
			
			$app = new App();

			$salesByRoomTypeArr = array();
			foreach($salesByRoomType as $roomType => $amount){
				//$roomType = str_replace(' ', '<br>', $roomType);
				$salesByRoomTypeArr[] = [$roomType, $amount];
				$app->series[] = '{ name: "'.$roomType.'", data: ['.$amount.'] }';
				// $app->series[] = '{ data: [["'.$roomType.'",'.$amount.']], label: "'.$roomType.'" }';
			}

			$aa = '';
			foreach($app->series as $v){
				$aa .= $v.',';
			}
			
			$data['charts'] = $aa;
			//dd($data['charts']);
			// Sales By Room Type - END
			
			// newCustomers
			$newCustomers = DB::select("SELECT count(id) as countCustomers,LEFT(MONTHNAME(modifydate),3) AS month FROM `customers` where YEAR(createdate)=YEAR(CURDATE()) group by LEFT(MONTHNAME(modifydate),3) order by LEFT(MONTHNAME(modifydate),3)");

			$newCustomersArr = array();
			foreach($newCustomers as $newCustomer){
				$newCustomersArr[$newCustomer->month] = $newCustomer->countCustomers;
			}
			$tempNewCustomersArr = array();
			foreach($monthsArr as $month){
				if(array_key_exists($month,$newCustomersArr)){
					$tempNewCustomersArr[] = [$month, $newCustomersArr[$month]];
				}else{ 
					$tempNewCustomersArr[] = [$month, 0];
				}
			}
			
			$data['newCustomersArr'] = json_encode($tempNewCustomersArr);
			
			//// Get returning customers for dashboard customer graph
			$returnCustomers = DB::select("SELECT LEFT(MONTHNAME(modifydate),3) AS month, count(customer_id) as countCustomers FROM  `orders` WHERE YEAR( modifydate ) = YEAR( CURDATE( ) ) GROUP BY LEFT(MONTHNAME(modifydate),3) ORDER BY LEFT(MONTHNAME(modifydate),3)");
			
			$returnCustomersArr = array();
			foreach($returnCustomers as $returnCustomer){
				$returnCustomersArr[$returnCustomer->month] = $returnCustomer->countCustomers;
			}
			
			$tempReturnCustomersArr = array();
			foreach($monthsArr as $month){
				if(array_key_exists($month,$returnCustomersArr)){
					$tempReturnCustomersArr[] = [$month, $returnCustomersArr[$month]];
				}else{ 
					$tempReturnCustomersArr[] = [$month, 0];
				}
			}
			
			// order_to_product
			$topSoldRoomArr = DB::table('order_to_product')->join('products', 'products.id', '=', 'order_to_product.product_id')->whereIn('order_id', $currentYearsOrdersIds)->groupBy('product_id')->select(DB::raw('SUM(`amount`) AS monthlyTotalPrice, products.type as product_name'))->orderBy('monthlyTotalPrice', 'DESC')->limit(5)->lists('monthlyTotalPrice','product_name');


			
			$data['returnCustomersArr'] = json_encode($tempReturnCustomersArr);
		}
		
		
		////////////////////////// Qty Stock Sum  ////////////////////////////////
		$qtyStockSumArr = DB::table('product_room_prices')->whereRaw('YEAR(date) = YEAR(CURDATE())')->groupBy(DB::raw('DATE_FORMAT(date, "%m")'))->select(DB::raw('LEFT(MONTHNAME(date),3) AS Month, SUM(`qty_stock`) AS qty_stock_sum'))->lists('qty_stock_sum', 'Month');
		
		$current_month = (int) date('m', strtotime(date('Y-m-d')));
		$tempQtyStockSumArr = array();
		$i= (int) ($current_month>6)?($current_month-5):1;
		$j=0;
		foreach($monthsArr as $month_key => $month){
			/*if($i>6){
				break;
			}*/
			if($i!=($month_key+1)){
				continue;
			}
			if(($i<=$current_month)){
				if(array_key_exists($month,$qtyStockSumArr)){
					$j++;
					$tempQtyStockSumArr[] = [$month, $qtyStockSumArr[$month]];
				}else{
					$tempQtyStockSumArr[] = [$month, 0];
				}
			}
			$i++;
		}
		$data['qtyStockSumArr'] = json_encode($tempQtyStockSumArr);
		 //////////////////////////////// Qty Stock Sum - END  ////////////////////////////////
		
		$data['totalsForAllResults'] = $totalsForAllResults;
		 //////////////////////////////// Qty Stock Sum on Promotion  ////////////////////////////////
		$qtyPromoArr = DB::table('promocodes')->where('status',1)->lists('id', 'id');
		$qtyProductOnPromoArr = DB::table('promocodes_to_product')->whereIn('promocode_id',$qtyPromoArr)->lists('product_id', 'id');
		
		$qtyGlobalDiscountArr = DB::table('global_discounts')->where('status',1)->lists('id', 'id');
		$qtyProductOnGlobalDiscountArr = DB::table('global_discounts_to_products')->whereIn('global_discount_id',$qtyGlobalDiscountArr)->lists('product_id', 'id');

		$qtyProductOnPromoStockSumArr = DB::table('product_room_prices')->whereIn('product_id',$qtyProductOnPromoArr)->orWhereIn('product_id',$qtyProductOnGlobalDiscountArr)->whereRaw('YEAR(date) = YEAR(CURDATE())')->groupBy(DB::raw('DATE_FORMAT(date, "%m")'))->select(DB::raw('LEFT(MONTHNAME(date),3) AS Month, SUM(`qty_stock`) AS qty_stock_sum'))->lists('qty_stock_sum', 'Month');
		
		$current_month = (int) date('m', strtotime(date('Y-m-d')));
		$tempProductOnPromoQtyStockSumArr = array();
		$i= (int) ($current_month>6)?($current_month-5):1;
		$j=0;
		foreach($monthsArr as $month_key => $month){
			/*if($i>6){
				break;
			}*/
			if($i!=($month_key+1)){
				continue;
			}
			if(($i<=$current_month)){
				if(array_key_exists($month,$qtyProductOnPromoStockSumArr)){
					$j++;
					$tempProductOnPromoQtyStockSumArr[] = [$month, $qtyProductOnPromoStockSumArr[$month]];
				}else{
					$tempProductOnPromoQtyStockSumArr[] = [$month, 0];
				}
			}
			$i++;
		} 
				
		$data['productOnPromoQtyStockSumArr'] = json_encode($tempProductOnPromoQtyStockSumArr);
		
		 //////////////////////////////// Qty Stock Sum on Promotion - END  ////////////////////////////////
				
		$newTopSoldRoomArr = array();
		if(!empty($topSoldRoomArr)){
			$total = 0;
			foreach($topSoldRoomArr as $topSoldRoom){
				$total += $topSoldRoom;
			}
			
			$i=0;
			foreach($topSoldRoomArr as $room => $topSoldRoom){
				$newTopSoldRoomArr[$i]['data'] = (($topSoldRoom*$total)/100);
				$newTopSoldRoomArr[$i]['label'] = (strlen($room)>12) ? substr($room,0,12).'...' : $room; //str_replace(' ', '<br>', $room);
				if($i==0){
					$newTopSoldRoomArr[$i]['color'] = '#3DB9D3';
				}elseif ($i==1){
					$newTopSoldRoomArr[$i]['color'] = '#ffce54';
				} elseif ($i==2){
					$newTopSoldRoomArr[$i]['color'] = '#fc6e51';
				}
				$i++;
			}
		}
			
		$data['topSoldRoomArr'] = !empty($newTopSoldRoomArr)?json_encode($newTopSoldRoomArr):'';
		
		//dd($data['topSoldRoomArr']);
		
		$data['ordersArr'] = json_encode($ordersArr);	
		// Sales Report Graph Monthly - END
		$data['salesByRoomTypeArr'] = json_encode($salesByRoomTypeArr);
		
		//// Get customers for dashboard customer graph
        $data['newCustomers'] = $newCustomers;
		
		
		////////////////
		$tempRoomTypeSoldArr = array();
		$current_month = (int) date('m', strtotime(date('Y-m-d')));
		$i=(int) ($current_month>6)?($current_month-5):1;
		//echo '<pre>';
		foreach($monthsArr as $month_key => $month){
			/*if($i>6){
				break;
			}  */
			if($i!=($month_key+1)){
				continue;
			}
			if(($i<=$current_month)){
				 $orderIdsArr = DB::table('room_booked_date')->whereRaw('(MONTH(date_checkin) = '.$i.' AND YEAR(date_checkin) = YEAR(CURDATE())) OR (MONTH(date_checkout) = '.$i.' AND YEAR(date_checkout) = YEAR(CURDATE()))')->distinct()->lists('order_id');
				 //print_r($orderIdsArr);
				 $tempRoomTypeSold = DB::table('order_to_product')
				 ->join('products', 'products.id', '=', 'order_to_product.product_id')
				 ->whereIn('order_to_product.order_id', $orderIdsArr)->groupBy('order_to_product.product_id')->select(DB::raw('products.type AS product_type, SUM(quantity) AS quantity_sum'))->lists('quantity_sum', 'product_type');
				 //print_r($tempRoomTypeSold);
				  if(!empty($tempRoomTypeSold)){
					  $tempRoomTypeSoldAmt = current(array_keys($tempRoomTypeSold, max($tempRoomTypeSold)));
					  //$tempRoomTypeSoldAmt = (strlen($tempRoomTypeSoldAmt)>12) ? substr($tempRoomTypeSoldAmt,0,12).'...' : $tempRoomTypeSoldAmt;
					  //$maxs = str_replace(' ', '<br>', );
					 // dd($maxs);
					  $tempRoomTypeSoldArr[] = [$month, max($tempRoomTypeSold), ['roomType'=>$tempRoomTypeSoldAmt]];
				  }else{
					  $tempRoomTypeSoldArr[] = [$month, 0, ['roomType'=>'']];
				  }
				 
			}
			$i++;
		}
		//die;
		//dd($tempRoomTypeSoldArr);
		$data['roomTypeSoldArr'] = json_encode($tempRoomTypeSoldArr);
		////////////////
		
		$idsArr = [3,4,5];
		$settings = DB::table('global_setting')->whereIn('id', $idsArr)->lists('value','key');
		
		$current_month = (int) date('m', strtotime(date('Y-m-d')));
		$current_year = (int) date('Y', strtotime(date('Y-m-d')));
		$third_party_sale = $settings['third_party_sale'];
		$number_of_rooms = $settings['number_of_rooms'];
		
		if($settings['third_party_sale_period']=='Monthly'){
			$orders = DB::table('orders')->whereRaw('MONTH(createdate) = '.$current_month.' AND YEAR(createdate) = '.$current_year)->sum('totalPrice');
		}
		if($settings['third_party_sale_period']=='Quarterly'){
			$month_condition ='';
			if($current_month<=3){
				$month_condition = "month(createdate) between '01' and '03'";
			}
			if(($current_month>3) && ($current_month<=6)){
				$month_condition = "month(createdate) between '04' and '06'";
			}
			if(($current_month>6) && ($current_month<=9)){
				$month_condition = "month(createdate) between '07' and '09'"; //'(MONTH(createdate) > 6 OR MONTH(createdate) <= 9)';
			}
			if($current_month>9){
				$month_condition = "month(createdate) between '09' and '12'"; //'MONTH(createdate) > 9';
			}

			$orders = DB::table('orders')->whereRaw($month_condition.' AND YEAR(createdate) = '.$current_year)->sum('totalPrice');
		}
		if($settings['third_party_sale_period']=='Yearly'){
			$orders = DB::table('orders')->whereRaw('YEAR(createdate) = '.$current_year)->sum('totalPrice');
		}
		//dd($orders);
		$drr = $orders-$third_party_sale;

		$drr_percentage = !empty($orders) ? (($drr/$orders)*100) : 0;
		$orders_for_room_sales_per = DB::table('orders')->whereRaw('YEAR(createdate) = '.$current_year)->sum('totalPrice');
		$room_sales_report_graph = ($orders_for_room_sales_per/$number_of_rooms)/100; //$orders/$number_of_rooms;
		//dd($room_sales_report_graph);
		$data['room_sales_report_graph'] = $room_sales_report_graph;
		$data['orders'] = $orders;
		$data['settings'] = $settings;
		$data['drr'] = $drr;
		$data['drr_percentage'] = $drr_percentage;
		//dd($data);
		return view('admin.booking.room_sales_report_graph')->with('data', $data); // with(compact('ordersArr', 'salesByRoomTypeArr'));
	}
	
	public function room_sales_report(Request $request){
		$requestArr = $request->all();
		$offset = 0;
		$data = array();
		$defaultTab = 'rooms_suits';
		if(!empty($requestArr['active_tab'])){
			$defaultTab = $requestArr['active_tab'];
		}
		
		$currentYear = date('Y');
		$currentMonth = date('m');
		if(!empty($requestArr['page'])){
			//$firstValue = $requestArr['page']*10-10;			
			$offset = ($requestArr['page']*10)-10;
			$data['page'] = $requestArr['page'];
		}
		if(!empty($requestArr['month'])){
			$currentMonth = ($request->month);
		}
		if(!empty($requestArr['year'])){
			$currentYear = ($request->year);
		}
		
		$projectObj = new Product;
		if($defaultTab== 'rooms_suits'){
			$categoryList = $projectObj->getSubCategories(array(8));
			array_push($categoryList,8);
		}else{
			$categoryList = $projectObj->getSubCategories(array(9));
			array_push($categoryList,9);
		}
		
		$products = DB::table('products')->join('product_to_category as c','products.id','=','c.product_id')->whereIN('c.category_id',$categoryList)->offset($offset)->limit(10)->select('products.*')->orderBy('status', 'asc')->get();
		//dd($products);
		$product_count = DB::table('products')->join('product_to_category as c','products.id','=','c.product_id')->whereIN('c.category_id',$categoryList)->count(); //Product::count();
		//dd($product_count);
		//echo "<pre>";
		foreach($products as $product){

			$product_id = $product->id;
			//echo $product_id.'================================================================<br>';
			$roomBookedDateOrderIdsArr = DB::table('room_booked_date')->whereRaw('((MONTH(date_checkin) = '.$currentMonth.' AND YEAR(date_checkin) = '.$currentYear.') OR (MONTH(date_checkout) = '.$currentMonth.' AND YEAR(date_checkout) = '.$currentYear.')) AND product_id = '.$product_id)->get();

			$tempOrderToProductArr = array();
			//dd($roomBookedDateOrderIdsArr);
			foreach($roomBookedDateOrderIdsArr as $roomBookedDateOrderIdArr){

				$orderToProductArr = DB::table('order_to_product')->where('product_id',$roomBookedDateOrderIdArr->product_id)->where('order_id',$roomBookedDateOrderIdArr->order_id)->first();
				

				//print_r($orderToProductArr);
				
				$date_checkin = $roomBookedDateOrderIdArr->date_checkin;
				
				$date_checkout = $roomBookedDateOrderIdArr->date_checkout;
				
				while (strtotime($date_checkin) < strtotime($date_checkout)) {
					if(!empty($tempOrderToProductArr[$date_checkin])){
						$tempOrderToProductArr[$date_checkin] += !empty($orderToProductArr->quantity)?$orderToProductArr->quantity:0;
					}else{
						$tempOrderToProductArr[$date_checkin] = !empty($orderToProductArr->quantity)?$orderToProductArr->quantity:0;
					}
					$date_checkin = date ("Y-m-d", strtotime("+1 days", strtotime($date_checkin)));
				}

			}
			
			$productRoomPricesArr = DB::table('product_room_prices')->where('product_id', $product->id)->whereRaw('MONTH(date) = '.$currentMonth.' AND YEAR(date) = '.$currentYear)->orderBy('date', 'ASC')->get();
			$productRoomPricesArr2 = DB::table('product_room_prices')->where('product_id', $product->id)->orderBy('date', 'ASC')->get();
			$tempRoomPricesArr = array();
			$qty_stock = $low_level = 0;

			foreach($productRoomPricesArr as $productRoomPrice){
				$tempRoomPricesArr[$productRoomPrice->date] = $productRoomPrice;
			}
			foreach($productRoomPricesArr2 as $productRoomPrice2){
				try {
					$dt = Carbon::createFromFormat('Y-m-d', $productRoomPrice2->date);
					$dt_now = Carbon::now();
					if($dt_now->format('d-m-Y') == $dt->format('d-m-Y'))
					{
						$qty_stock += $productRoomPrice2->qty_stock;
						$low_level += $productRoomPrice2->low_level;
					}
				}catch(\InvalidArgumentException $x) {
					//echo $x->getMessage();
				}
			}
			
			$data['products'][$product_id]['quantity_in_stock'] = $qty_stock;
			$data['products'][$product_id]['low_level_in_stock'] = $low_level;

			$data['products'][$product_id]['roomPricesArr'] = $tempRoomPricesArr;
			$data['products'][$product_id]['productDetails'] = $product;
			$data['products'][$product_id]['orderToProductArr'] = $tempOrderToProductArr;

			//die;

		}
		
		$downArr = $upArr = $productsArr = array();
		foreach ($data['products'] as $key => $value) {
			if(!empty($value['productDetails']->quantity_in_stock)){
				$upArr[$key] = $value;
			} else {
				$downArr[$key] = $value;
			}
		}

		$productsArr = array_merge($upArr,$downArr);
		$data['products'] = $productsArr;
		$data['currentMonth'] = $currentMonth;
		$data['currentYear'] = $currentYear;
		$data['product_count'] = $product_count;
		$data['defaultTab'] = $defaultTab;
		$data['years'] = DB::select('SELECT DISTINCT DATE_FORMAT(date_checkin, "%Y") as years FROM room_booked_date order by years desc');
		
		$data['last_updated'] = $this->OrderModel->getLastUpdated();

		//dd($data);
		return view('admin.booking.room_sales_report')->with('data', $data);
	}
	
	public function changeProductStatus(Request $request){
        $requestArr = $request->all();
       // dd($requestArr);
        if(!empty($requestArr['product_checked']) && ($requestArr['product_checked']=='true')){
            //echo 'kapil';
            $status = 1;
        }else{
            //echo 'kumar';
            $status = 0;
        }
        $result = DB::table('products')->where('id', $request->product_id)->update(['status' => $status]);
        return $status;
	}

	public function analytics_reports(){
		$data = LaravelAnalytics::fetchUserTypes(7);
		$page_view = LaravelAnalytics::getMostVisitedPages(7);
		return view('admin.booking.analytics_reports')->with('data', $data)->with('page_view', $page_view);
	}
	
}

class App {
  
}
