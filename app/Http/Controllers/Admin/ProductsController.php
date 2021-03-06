<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Models\Admin\Product;
use App\Http\Models\Admin\RoomPrice;
use App\Http\Models\Admin\Category;
use App\Http\Models\Admin\Brand;
use App\Http\Models\Admin\Color;
use App\Http\Models\GlobalSettings;
use App\Http\Models\Admin\Property;


use App\Models\DropOffList;
use Session;
use Input;
use Illuminate\Http\RedirectResponse;
use Auth;
use Validator;
use Hash;
use DB;
use Redirect;
use Request;


use App\Http\Library\Image_lib;
use File;
use Mail;

use App\Http\Models\ShippingMethod;
use App\Http\Models\PwpProduct;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Null_;


class ProductsController extends Controller {
	private $data = array();
	private $ProductModel = null;
	private $roomPriceModel = null;
	private $CategoryModel = null;
	private $Brand = null;
	private $Color = null;

	private $ShippingModel = null;



	/*
	|--------------------------------------------------------------------------
	| Product Controller
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
		$this->ProductModel = new Product();
		$this->roomPriceModel = new RoomPrice();
		$this->CategoryModel = new Category();
		$this->BrandModel = new Brand();
		$this->ColorModel = new Color();
		$this->ShippingModel = new ShippingMethod();

		$this->Image = new Image_lib();
	}

	function index()
	{
		return redirect('web88cms/dashboard');
	}

	function importcsv(){
		if(Request::hasFile('datafile') || Request::get('change_status')){

			$st = (Request::get('status'))? 1 : 0;
			$settings = GlobalSettings::saveSettings('product_global',json_encode(array('status' => $st)));

			if(Request::get('change_status')){
				// change status
				$status = (Request::get('status'))? 1 : 0;
				$product = new Product();
				$product->updateAllProductsStatus($status);
			}

			if(Request::hasFile('datafile')){
				// do import
				if(Request::file('datafile')->getClientOriginalExtension() != "csv"){
					Session::set('product_global_setup.warning', 'Only csv files are allowed');
					return redirect('web88cms/prdouctglobalsetup');
				}
				Request::file('datafile')->move('public/uploads', 'products.csv');
				$row = 1;
				if (($handle = fopen("public/uploads/products.csv", "r")) !== FALSE) {
					$products = array();
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						if ($row == 1){
							$row++;
							continue;
						}
						$row++;
						$products[] = array(
							'status'						=> $data[0], // products table
							'type'                          => $data[1], // products table
							'room_code'                     => $data[2], // products table
							'category'                      => $data[3], //categories table
							'sub_category'                  => $data[4], //categories table
							'sub_sub_category'              => $data[5], //categories table
							'sub_sub_sub_category'          => $data[6], //categories table
							'sub_sub_sub_sub_category'      => $data[7], //categories table
							'product_brand'                 => $data[8], // brands table
							'sale_price'                    => $data[9], // products table
							'list_price'                    => $data[10], // products table
							'quantity_in_stock'             => $data[11], // products table
							'low_level_in_stock'            => $data[12], // products table
							'manufacturer_part_number'      => $data[13], // products table
							'tax'                           => $data[14], // products table
							'weight'                        => $data[15] // products table
						);


					}
					fclose($handle);
				}
				$product = new Product();
				$result = $product->importBulkProducts($products);

			}

			Session::set('product_global_setup.success', 'Settings saved successfully');

		} else{
			Session::set('product_global_setup.warning', 'Please upload a file or change the status');
		}
		return redirect('web88cms/prdouctglobalsetup');
	}


	function addProduct()
	{
		if(Request::isMethod('post'))
		{
			$messages = [
				//'required' => 'The :attribute field is required.',
				'large_image.required' => 'Max file size should be less than 2MB.',
				'roomPrices.required' => 'Please add the room price.'
			];

			$validator = Validator::make(Request::all(),[
				'type' => 'required',
				'room_code' => 'required',
				'categories' => 'required',
				'roomPrices' => 'required',
			], $messages);

		  	if ($validator->fails()) {
				$json['error'] = $validator->errors()->all();
				return Redirect::back()->withInput()->withErrors($validator);
				exit;
			}
			else
			{

				$roomPrices = json_decode(Request::get('roomPrices'));

				$imageName = null;
				$custom_data = array();

				if(isset($_FILES['thumbnail_image_1']['name']) && $_FILES['thumbnail_image_1']['name']!='')
				{
					$thumbnail_image_1 = time().'_'.$_FILES['thumbnail_image_1']['name'];
					Request::file('thumbnail_image_1')->move(
						base_path() . '/public/admin/products/medium/', $thumbnail_image_1
					);
					$custom_data['thumbnail_image_1'] = $thumbnail_image_1;
				}

				// add/update custom values to request input array
				$custom_data['status'] = (Request::input('status') == 'on') ? '1' : '0';
				$custom_data['is_tax'] = (Request::input('is_tax') == 'on') ? '1' : '0';
				$custom_data['starting_from'] = (Request::input('starting_from') == 'on') ? '1' : '0';
				$custom_data['gross_price_per_night'] = (Request::input('gross_price_per_night') == 'on') ? '1' : '0';
				$custom_data['net_price_per_night'] = (Request::input('net_price_per_night') == 'on') ? '1' : '0';
				$custom_data['reverse_tax_calculation'] = (Request::input('reverse_tax_calculation') == 'on') ? '1' : '0';
				// $custom_data['is_available'] = (Request::input('is_available') == 'on') ? '1' : '0';
				// $custom_data['in_physical_store_only'] = (Request::input('in_physical_store_only') == 'on') ? '1' : '0';
				$custom_data['display_order'] = (Request::input('display_order') != 0) ? Request::input('display_order') : '0';


				$custom_data['promo_behaviour'] = (Request::input('promo_behaviour')) ? implode(',',Request::input('promo_behaviour')) : '';

				if(sizeof($custom_data) > 0)
					Request::merge($custom_data);


				$product_id = $this->ProductModel->addProduct(Request::input());

				$checkPackages = $this->ProductModel->getPackages(0);
				if(count($checkPackages) > 0){
					$data = [
						'product_id' => $product_id
					];
					$this->ProductModel->updateQuantityDiscount($data);
				}

				if ($product_id && $roomPrices) {
					$this->roomPriceModel->addRoomPrices($product_id, $roomPrices);
				}

				$this->data['success'] = 'Product added successfully.';

				return redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);

				//Redirect::back()->with('data', $this->data);
			}
		}

		// get categories
		if(Request::old('categories'))
			$this->data['categories'] = $this->CategoryModel->getSelectedCategoriesTree(Request::old('categories'));
		else
			$this->data['categories'] = $this->CategoryModel->getCategoriesTree();

		// get active brands
		$this->data['brands'] = $this->BrandModel->getActiveBrands();

		// get packages
		$this->data['quantity_discounts'] = $this->ProductModel->getQuantityDiscounts(0);

		// get pagination record status
		$this->data['pagination_report'] = $this->ProductModel->getTotalQuantityDiscounts(Input::get('page'),0);

		// get active colors
		$this->data['colors'] = $this->ColorModel->getActiveColors();

		$this->data['property'] = Property::get();
		$this->data['drop_off_list'] = DropOffList::get();

		// set page title
		$this->data['page_title'] = 'Add Product';

		return view('admin.products.add_products',$this->data);
	}

	function editProduct($product_id)
	{
		$this->data['success_response'] = Session::get('response');
		Session::forget('response');
		if(Request::ajax()) {
			$prices = DB::table('product_room_prices')
                ->where('product_id', '=', $product_id)
                ->groupBy('date')
				->get();
			$priceArray = [];
			foreach($prices as $price) {
                if($price->sale_price!=0){
                    $priceArray[] = (object)[
                        'id' => date('Ymd', strtotime($price->date)),
                        'title' => 'RM: ' . number_format((float)$price->sale_price, 2, '.', '') . "\r\n" .
                            'Qty: ' . number_format((float)$price->qty_stock, 2, '.', ''),
                        'status' => $price->status == '1',
                        'salePrice' => $price->sale_price,
                        'listPrice' => $price->list_price,
                        'qtyStock' => $price->qty_stock,
                        'lowLevel' => $price->low_level,
                        'restrictionText' => $price->restriction_text,
                        'allDay' => true,
                        'start' => $price->date,
                        'textColor' => $price->status == '1' ? '#3c763d' : '#a94442',
                        'borderColor' => 'transparent',
                        'backgroundColor' => 'transparent',
                    ];
                }
			}

			return response()->json($priceArray);
		}

		if(Request::isMethod('post'))
		{
			$roomPrices = json_decode(Request::get('roomPrices'));
			$messages = [
				'thumbnail_image_1.required' => 'Max file size should be less than 2MB.',
				'max' => 'Max file size should be less than 2MB.'
			];

			$validation_rules = array(
				'type' => 'required',
				'room_code' => 'required',
				'categories' => 'required',
			);

			if(Request::file('thumbnail_image_1'))
			{
				$validation_rules['thumbnail_image_1'] = 'required|image|max:2000000';
			}

			$validator = Validator::make(Request::all(),$validation_rules,
											$messages
									);

		  if ($validator->fails()) {
				return Redirect::back()->withErrors($validator);
				exit;
			}
			else
			{

				$imageName = null;
				$custom_data = array();

				if(isset($_FILES['thumbnail_image_1']['name']) && $_FILES['thumbnail_image_1']['name']!='')
				{

					$thumbnail_image_1 = time().'_'.$_FILES['thumbnail_image_1']['name'];
					Request::file('thumbnail_image_1')->move(
						base_path() . '/public/admin/products/medium/', $thumbnail_image_1
					);

					$custom_data['thumbnail_image_1'] = $thumbnail_image_1;
				}

				// add/update custom values to request input array
				$custom_data['status'] = (Request::input('status') == 'on') ? '1' : '0';
				$custom_data['is_tax'] = (Request::input('is_tax') == 'on') ? '1' : '0';
				$custom_data['starting_from'] = (Request::input('starting_from') == 'on') ? '1' : '0';
				$custom_data['gross_price_per_night'] = (Request::input('gross_price_per_night') == 'on') ? '1' : '0';
				$custom_data['net_price_per_night'] = (Request::input('net_price_per_night') == 'on') ? '1' : '0';
				$custom_data['reverse_tax_calculation'] = (Request::input('reverse_tax_calculation') == 'on') ? '1' : '0';
				// $custom_data['is_available'] = (Request::input('is_available') == 'on') ? '1' : '0';
				// $custom_data['in_physical_store_only'] = (Request::input('in_physical_store_only') == 'on') ? '1' : '0';
				$custom_data['display_order'] = (Request::input('display_order') != 0) ? Request::input('display_order') : '0';

				$custom_data['promo_behaviour'] = (Request::input('promo_behaviour')) ? implode(',',Request::input('promo_behaviour')) : '';

				if(sizeof($custom_data) > 0)
					Request::merge($custom_data);

				$this->ProductModel->updateProduct(Request::input(),$product_id);

				if ($product_id && $roomPrices) {
					$this->roomPriceModel->addRoomPrices($product_id, $roomPrices);
				}

				//Notify user start
				if(Input::get('quantity_in_stock') > 0){
					$users = $this->ProductModel->getNotifyUsers($product_id);

					if($users){
						$ids = array();
						$messageBody = 'Hello, We\'ll like to let you know that product ' . Input::get('type') . ' ' . Input::get('room_code') . ' is now available in our site.';

						foreach($users as $users){
							$messageData = [
								'fromEmail' 			=> 'registration@ritzgardenhotel.com',
								'fromName' 				=> 'Ritz Garden Hotel Online booking',
								'toEmail' 				=> $users->email,
								'toName' 				=> $users->name,
								'subject'				=> Input::get('type') . ' ' . Input::get('room_code') . ' is now available!!!'
							];
							$ids[] = $users->id;
						}

						$this->ProductModel->updateNotifyUsers($ids);
					}
				}
				//Notify user end

				$this->data['success'] = 'Changes saved successfully.';

				Redirect::back()->with('data', $this->data);
			} // end else
		} // end if(Request::isMethod('post'))

		// get product details
		$this->data['details'] = $this->ProductModel->getProductDetails($product_id);

		// get categories
		//$this->data['categories'] = $this->CategoryModel->getCategoriesTree();

		$productCategoryList = array();
		if(sizeof($this->data['details']['productCategories']) > 0)
		{
			foreach($this->data['details']['productCategories'] as $productCategories)
			{
				array_push($productCategoryList,$productCategories->category_id);
			}
		}

		$this->data['categories'] = $this->CategoryModel->getSelectedCategoriesTree($productCategoryList);

		// get active brands
		$this->data['brands'] = $this->BrandModel->getActiveBrands();

		// get active colors
		$this->data['colors'] = $this->ColorModel->getActiveColors();

		// get product images
		$this->data['additional_images'] = $this->ProductModel->getProductImages($product_id);

		// get quantity discounts
		$this->data['quantity_discounts'] = $this->ProductModel->getQuantityDiscounts($product_id);

		// get pagination record status
		$this->data['pagination_report'] = $this->ProductModel->getTotalQuantityDiscounts(Input::get('page'),$product_id);

		// set page title
		$this->data['page_title'] = 'Edit Product';

		//Get Available Csv shipping method
		$this->data['csv_ships'] = $this->ShippingModel->getCsvShippingByWeight((float)$this->data['details']['productDetails']->weight);

		//Get pwp products
		$this->data['pwp_products'] = PwpProduct::where('product_id', $product_id)->with('product')->get();

		$this->data['tab'] = Input::get('activetab');

		//product ID
		$this->data['pid'] = $product_id;

		$this->data['property'] = Property::get();

        $this->data['drop_off_list'] = DropOffList::get();

		//room amenities
		$this->data['amenities'] = json_decode($this->ProductModel->getAmenities($product_id)->amenities);
//		dd($this->data);
        return view('admin.products.edit_products',$this->data);

	}

	function deleteImage($type,$product_id)
	{
		DB::table('products')->where('id',$product_id)->update(array($type => ''));

		$this->data['success'] = 'Image removed successfully.';

		//redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
		//Redirect::back('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
		return redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
	}


	/*update display order*/
	function update_display_order_all()
	{
		// dd($_POST);
		$postdata= $_POST;
		$data= array();

		if(Request::isMethod('post')){
			$flag = 'success';

			foreach($postdata['mydisplayorder'] as $key=>$value){
				//// Check display order already exist in db
				$results = DB::table('products')
                     ->select('id', 'display_order')
                     ->where('display_order', '=', $value)
                     ->where('id', '!=', $key)
                     ->get();
                 // dd($results);

				if(count($results)>0)
				{
					//// Check founded duplicate display order also change in current action yes/no
					if($value == $postdata['mydisplayorder'][$results[0]->id]){
						$flag = 'error';
						break;
					}
				}
			}
		}


	  	if($flag == 'error')
		{
		    return Redirect::to('web88cms/products/list')->withInput()->with('error', 'Please fill unique display order Field..');
		}else{
			$data= array();
			foreach($postdata['mydisplayorder'] as $key=>$value){
				$data['display_order'] = $value;

				DB::table('products')
				  ->where('id', $key)
				  ->update($data);
			}
			$detaildata['success'] = 'Product has been updated successfully.';
			$detaildata['data'] = $data;

			return Redirect::to('web88cms/products/list')->withFlashMessage('Product display order has been changed successfully..');
		}
	}


	function updateShippingInfo($product_id)
	{
		$this->data	= '';
		if(Request::ismethod('post'))
		{
			$formData = Request::input();

			unset($formData['_token']);

			// $formData['shipping_cost'] = str_replace(',','',$formData['shipping_cost']);
			$formData['last_modified'] = date('Y-m-d H:i:s');

			DB::table('products')->where('id',$product_id)->update($formData);

			$this->data['success'] = 'Changes saved successfully.';
		}

		return redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
	}

	function listProducts()
	{
	    $req = Request::input();
	    if(count($req) > 0) {
            $anyone = false;
            if ($req['type'] != ''){
                $anyone = true;
            } else if($req['room_code'] != '') {
                $anyone = true;
            } else if($req['price_to'] != '') {
                $anyone = true;
            } else if($req['price_from'] != '') {
                $anyone = true;
            } else if($req['category_id'] != 'all') {
                $anyone = true;
            } else {
                $anyone = false;
            }
            if($anyone != true) {
                return redirect('/web88cms/products/list')->with('error_message', 'Please Select At Least One Field');
            }
	    }
		$page = 0;
		$sort = 'ASC';
		$sort_by = 'createdate';

		// response variable is set when item is deleted
		$this->data['success'] = Session::get('response');
		Session::forget('response');

		/*if(Request::get('type'))
		{
			//Session::put('product.per_page',1);
			$this->data['products'] = $this->ProductModel->searchProducts(Input::get());

			// get pagination record status
			$this->data['pagination_report'] = $this->ProductModel->getTotalSearchResults(Input::get());
		}
		else
		{
			//Session::put('product.per_page',2);
			// get products
			$this->data['products'] = $this->ProductModel->getProducts(Input::get());

			// get pagination record status
			$this->data['pagination_report'] = $this->ProductModel->getTotalProducts(Input::get('page'));
		}*/

		$this->data['products'] = $this->ProductModel->searchProducts(Input::get());
        // echo '<pre>';print_r($this->data['products']->toArray());exit;
		// get pagination record status
		$this->data['pagination_report'] = $this->ProductModel->getTotalSearchResults(Input::get());

		// get categories
		//$this->data['categories'] = $this->CategoryModel->getCategoriesTree();
		//$productCategoryList = (Input::get('category_id') != 'all') ? array(Input::get('category_id')) : '';
		$this->data['categories'] = $this->CategoryModel->getSelectedCategoriesTree(array(Input::get('category_id')));

		// get active brands
		$this->data['brands'] = $this->BrandModel->getActiveBrands();

		// get last updated
		$this->data['last_modified'] = DB::table('products')->orderBy('last_modified','desc')->pluck('last_modified');

		// set page title
		$this->data['page_title'] = 'List Products';

		$inputs = Input::get();

		if(Input::get('sort')){
			$sort = Input::get('sort');
			unset($inputs['sort']);
		}

		if(Input::get('sort_by')){
			$sort_by = Input::get('sort_by');
			unset($inputs['sort_by']);
		}

		$this->data['sort'] = $sort;
		$this->data['sort_by'] = $sort_by;
		$url = url('web88cms/products/list') . '?';

		if($inputs){
			foreach($inputs as $key => $val){
				$url .= $key .'='. $val .'&';
			}
		}

		$this->data['sorting_url'] = $url;
		// added for order.
        $this->data['product_all_data'] = DB::select("SELECT * FROM products");
       /*  echo '<pre>';
        print_r($this->data);
        exit; */

		return view('admin.products.list_products', $this->data);
	}


	function advancedRoomSetup()
	{

        /* ini_set('post_max_size','8M');
        phpinfo();
        exit; */
		$page = 0;
		$sort = 'ASC';
		$sort_by = 'createdate';
		$inputs = Input::get();
		// response variable is set when item is deleted
		$this->data['success'] = Session::get('success');
		$this->data['error'] = Session::get('error');
		Session::forget('success');
		Session::forget('error');


		if(Request::isMethod('post'))
		{

			$all_data = $_POST;
			//phpinfo();exit();

			//nol
			/*
			$tot = 0;
			array_walk_recursive($all_data, function($x) use(&$tot) {
			    $tot++;
			});
			print("<pre>count of vars: ".$tot);
			print("<pre>post: ".print_r($all_data,true)."</pre>");exit();*/


			// echo "<pre>";
			// print_r($all_data); exit();
			if(isset($all_data['update'])){
				$todayDate = date("Y-m-d H:i:s");
				$table = RoomPrice::getModel()->getTable();
			    $total_updated = 0;
			    $total_created = 0;
				 // echo '<pre>'; print_r($all_data['update']); exit('exit');
				foreach ($all_data['update'] as $key => $value) {
					# code...
					//echo '<pre>'; print_r($key); die;
					if(isset($value['is_new_entry']) && $value['is_new_entry'] == 1 && isset($value['product_id'])){
						unset($value['is_new_entry']);
						unset($value['is_updated_entry']);
						$value['created_at'] = $todayDate;
						$value['updated_at'] = $todayDate;
						$value['restriction_text'] = '';
						$total_created++;

						//nol
						//echo '<pre>new: '; print_r($value); continue;

						DB::table('product_room_prices')->where('qty_stock',"0")
														->where('list_price',"0")
														->where('sale_price',"0")
														->where('product_id',$value['product_id'])
                                                        ->where('date',$value['date'])->delete();



						$que = DB::table('product_room_prices')->insert($value);




					} else if( $value['is_updated_entry'] == 1 ) {
						$total_updated++;
						$value['updated_at'] = $todayDate;
						unset($value['is_updated_entry']);

						//nol
						//echo '<pre>update: '; print_r($value); continue;

						DB::table('product_room_prices')->where('id', $key)->update($value);
          }

        }

				//nol
				//exit();

				//echo 'total_updated: '.$total_updated.'<hr>';
				//echo 'total_created: '.$total_created.'<hr>';
				//exit;
//                dd('stop');
				Session::put('success', 'Item(s) updated successfully.');
				return Redirect::back();
				exit;
			}
		}

		//get data to form
		if( isset($inputs['filter_start_date']) ){
			$start_date = Carbon::createFromFormat('d/m/Y', $inputs['filter_start_date']);
		}else{
			$start_date = Carbon::today();
		}
		$start_dateSTR = $start_date->toDateString();
		$add_num_days = 30;
		$add_num_days_dArray = 14;
		if( isset($inputs['filter_days']) ){
			$add_num_days = $inputs['filter_days']-1;
			$add_num_days_dArray = $inputs['filter_days']-1;
		}
		$end_dateSTR = $start_date->addDays($add_num_days)->toDateString();
		//$end_dateSTR_dArray = $start_date->addDays($add_num_days_dArray)->toDateString();
		// DB::enableQueryLog();

		//nol
		//print $end_dateSTR." ".$start_dateSTR."<br>";

		$advance_data = DB::table('product_room_prices as prp')
						//nol
						//->where('prp.sale_price','>','0')
						//->where('prp.list_price','>','0')
						//////
						->join('products', 'products.id', '=', 'prp.product_id')
						->whereBetween('prp.date', [$start_dateSTR, $end_dateSTR]);
						//nol
						//->orderBy('product_id')
						//->get();
						//////
		//nol
		//print $advance_data->toSql();exit();
		//print("<pre>get adv1 data: ".print_r($advance_data,true)."</pre>");exit();

		if(isset($inputs['filter_room_type']) && $inputs['filter_room_type'] > 0){
			$type_name = DB::table('products')->where('id',$inputs['filter_room_type'])->pluck('type');
			$advance_data = $advance_data->where('products.type', $type_name);
		}
		$advance_data = $advance_data
						->select('prp.id', 'prp.sale_price', 'prp.qty_stock', 'prp.date', 'prp.status', 'products.type', 'products.id as product_id', DB::raw('DATE_FORMAT(prp.date, "%d") AS date_day, DATE_FORMAT(prp.date, "%M") AS month_name, DATE_FORMAT(prp.date, "%a") AS date_day_name'))
						->orderBy('prp.date')
						->get();
						//nol
						//->orderBy('prp.id')


		//nol
		//print $advance_data->toSql();exit();
		//print("<pre>get adv data: ".print_r($advance_data,true)."</pre>");exit();

		$tableData = [];
		foreach ($advance_data as $key => $value) {
			$rcount=DB::table('room_booked_date')
			 	 ->where('product_id',$value->product_id)
				 ->where('date_checkout','>', $value->date)
	       ->where('date_checkin','<=', $value->date)
				 ->count();

      if($rcount==0) {
        $value->total_orders =$rcount;
      } elseif ($rcount>=1) {
        $roomsOrderd=DB::table('room_booked_date')
				  ->where('product_id',$value->product_id)
          ->where('date_checkout','>', $value->date)
					->where('date_checkin','<=', $value->date)->lists('room_orderd');

					if ($roomsOrderd==NULL||$roomsOrderd==0) {
              $value->total_orders =$rcount;
          } elseif(is_array($roomsOrderd)){
              $value->total_orders=array_sum($roomsOrderd);
          } else {
              $value->total_orders=$roomsOrderd;
          }
                                  }
          if(isset($tableData[$value->product_id])) {
						array_push($tableData[$value->product_id], $value);
					} else {
						$tableData[$value->product_id] = [$value];
					}
		}

		//nol
		//print("<pre>get tabledata: ".print_r($tableData,true)."</pre>");exit();

		// echo '<pre>';
		// print_r($tableData[$value->product_id]);
        //  echo '<pre>';
        // print_r($tableData);
        // exit;

		//echo '<pre>';print_r($end_dateSTR); echo '<hr>';print_r($add_num_days); echo '<hr>';exit;
		$allProducts = DB::table('products')->select('id', 'type')->get();
		$selectedProduct = reset($tableData)[0];
		//$tableData2 = $tableData;
		//echo '<pre>';print_r($tableData[1332]); echo '<hr>';exit;
		foreach ($allProducts as $eachProdKey => $eachProd) {
			$prod_id = $eachProd->id;
			if( !isset($tableData[$prod_id]) ){

				$newObj = new \stdClass();
				$newObj->id = 0;
				$newObj->date = $selectedProduct->date;
				$newObj->is_empty = 1;
				$newObj->status = 0;
				$newObj->product_id = $prod_id;
				$newObj->type = $eachProd->type;
				$tableData[$prod_id] = [$newObj];

			}

		}
		//$period = CarbonPeriod::create($start_dateSTR, $end_dateSTR);
		//echo '<pre>';print_r($tableData2);
		// exit;

		$dates = $this->createDateRange($start_dateSTR, $end_dateSTR);
		$dates_array = [];
		foreach ($dates as $key => $value) {
			$dFormat = Carbon::createFromFormat('Y-m-d', $value);
			$dates_array[] = (object)['date' => $value, 'date_day' => $dFormat->format('d'), 'month_name' => $dFormat->format('F'), 'date_day_name' => $dFormat->format('D')];
		}
		if(Input::has('printr') && Input::get('printr') == 1){

			echo '<pre>';
			echo '<hr>';print_r($tableData); exit;
		}
		$this->data['room_types'] = DB::table('products')->select('type as type_name', 'id')
			->groupBy('type')
			->orderBy('type')->get();

		$this->data['dates_array'] = $dates_array;
		$this->data['table_data'] = $tableData;

		//$this->data['products'] = $this->ProductModel->searchProducts(Input::get());
        // echo '<pre>';print_r($this->data['products']->toArray());exit;
		// get pagination record status
		//$this->data['pagination_report'] = $this->ProductModel->getTotalSearchResults(Input::get());

		// get categories
		//$this->data['categories'] = $this->CategoryModel->getCategoriesTree();
		//$productCategoryList = (Input::get('category_id') != 'all') ? array(Input::get('category_id')) : '';
		$this->data['categories'] = $this->CategoryModel->getSelectedCategoriesTree(array(Input::get('category_id')));

		// get active brands
		$this->data['brands'] = $this->BrandModel->getActiveBrands();

		// get last updated
		$this->data['last_modified'] = DB::table('product_room_prices')->orderBy('updated_at','desc')->pluck('updated_at');

		// set page title
		$this->data['page_title'] = 'List Products';



		if(Input::get('sort')){
			$sort = Input::get('sort');
			unset($inputs['sort']);
		}

		if(Input::get('sort_by')){
			$sort_by = Input::get('sort_by');
			unset($inputs['sort_by']);
		}

		$this->data['sort'] = $sort;
		$this->data['sort_by'] = $sort_by;
		/*$url = url('web88cms/products/list') . '?';

		if($inputs){
			foreach($inputs as $key => $val){
				$url .= $key .'='. $val .'&';
			}
		}

		$this->data['sorting_url'] = $url;*/
		// added for order.
		//$this->data['product_all_data'] = DB::select("SELECT * FROM products");

		// echo "<pre>";

		// print_r($this->data); exit();

		//nol
		//print("<pre>get: ".print_r($this->data,true)."</pre>");exit();


		return view('admin.products.advanced_room_setup', $this->data);
	}

	function setPerPage($per_page,$session_key,$redirect_to,$query_string=null)
	{
		Session::put($session_key.'.per_page',$per_page);
		if($query_string && $query_string !='no_qs')
		{
			$redirect_to .= '?'.$query_string;
		}
		//echo str_replace('~','/',$redirect_to); exit;
		return redirect(str_replace('~','/',$redirect_to));
	}


	function updateDescription($product_id)
	{
        $desc = Request::input('content');
		DB::table('products')->where('id',$product_id)->update(array('description' => $desc, 'last_modified' => date('Y-m-d H:i:s') ));
	}

	function updateTermsConditions($product_id)
	{
		$terms_and_conditions = Request::input('content');
		DB::table('products')->where('id',$product_id)->update(array('terms_and_conditions' => $terms_and_conditions, 'last_modified' => date('Y-m-d H:i:s') ));
	}

	function updateCancellationPolicy($product_id)
	{
        $cancellation_policy = Request::input('content');
		DB::table('products')->where('id',$product_id)->update(array('cancellation_policy' => $cancellation_policy, 'last_modified' => date('Y-m-d H:i:s') ));
	}

	function updateFeaturedVideo($product_id)
	{
		DB::table('products')->where('id',$product_id)->update(array('features_and_video' => Request::input('content'), 'last_modified' => date('Y-m-d H:i:s') ));
		//echo Request::input('content');
	}

	function updateWarrantyAndSupport($product_id)
	{
		DB::table('products')->where('id',$product_id)->update(array('warranty_and_support' => Request::input('content'), 'last_modified' => date('Y-m-d H:i:s') ));
	}

	function updateReturnPolicy($product_id)
	{
		DB::table('products')->where('id',$product_id)->update(array('return_policy' => Request::input('content'), 'last_modified' => date('Y-m-d H:i:s') ));
	}

	function addImages1($product_id)
	{
		//dd($_FILES);

		$files = Input::file('large_image');

		$file_uploaded = array();
		if(sizeof($_FILES['large_image']['name']) > 0)
		{
			for($i = 0; $i< sizeof($_FILES['large_image']['name']); $i++)
			{
				if($_FILES['large_image']['name'][$i] != '' && $_FILES['large_image']['error'][$i] == 0)
				{
					$imageName = time().'_'.$_FILES['large_image']['name'][$i];



					$files->move(
						base_path() . '/public/admin/products/large/', $imageName
					);

					/*Request::file('large_image')->move(
						base_path() . '/public/admin/products/large/', $imageName
					);*/

					array_push($file_uploaded,$imageName);
				}
			}
		}

		if(sizeof($file_uploaded) == 0)
		{
			$this->data['success'] = 'Please select valid image.';

			return redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
		}


	}

	// reference link : http://tutsnare.com/upload-multiple-files-in-laravel/
	public function addImages($product_id) {
		// getting all of the post data
		$files = Input::file('large_image');
		// Making counting of uploaded images
		$file_count = count($files);
		// start count how many uploaded
		$uploadcount = 0;
		$sizeError = '';

		foreach($files as $file) {

			$destinationPath = base_path() . '/public/admin/products/large/';
			if($file)
			{
				if($file->getClientSize() > 2000000 || $file->getClientSize() == 0)
				{
					$sizeError = 'Max file size should be less than 2MB.';
				}
				else
				{
					$filename = time().'_'.$file->getClientOriginalName();
					$upload_success = $file->move($destinationPath, $filename);

					// resize image
					$this->resizeImage($filename);

					$uploadcount ++;

					DB::table('product_to_images')->insert(array('product_id' => $product_id, 'file_name' => $filename));
				}
			}
		}

		if($sizeError != '')
		{
			$this->data['error'] = $sizeError;
		}
		else if($uploadcount == 0)
		{
			$this->data['error'] = 'Please select valid image.';
		}
		else
		{
		 $this->data['success'] = 'Image(s) saved successfully.';
		}

		return redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);

	}

	function resizeImage($file_name)
	{
		//$this->Image = new Image_lib();
		// resize image
		//$this->load->library('image_lib');
		$path = base_path() . '/public/admin/products/';
		$source_path = $path.'large/'.$file_name;
		$medium_image_path = $path.'medium/'.$file_name;
		//$thumb_path = $path.'small/'.$file_name;

		$config['image_library'] = 'gd2';
		$config['source_image'] = $source_path;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['thumb_marker'] = '';

		// generate thumbnail
		/*$config['new_image'] = $thumb_path;
		$config['width'] = 65;
		$config['height'] = 90;
		$this->Image->initialize_img($config);
		if ( ! $this->Image->resize())
		{
			//echo $this->Image->display_errors();
		}*/

		// medium size image
		$config['new_image'] = $medium_image_path;
		$config['width'] = 125;
		$config['height'] = 75;
		$this->Image->initialize_img($config);
		if ( ! $this->Image->resize())
		{
			//echo $this->Image->display_errors();
		}
		// end resize

		$this->Image->initialize_img($config);
		$this->Image->resize();

		if($this->Image->display_errors())
		{
			//echo $this->Image->display_errors();
		}

		//exit;
	}

	function deleteAdditionalImage($image_id,$product_id)
	{
		DB::table('product_to_images')->where('id',$image_id)->delete();

		$this->data['success'] = 'Image removed successfully.';

		//redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
		//Redirect::back('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
		return redirect('/web88cms/products/editProduct/'.$product_id)->with('data', $this->data);
	}

	function deleteProducts()
	{
		$this->ProductModel->deleteProducts($_POST['item_id']);
		Session::put('response', 'Item(s) deleted successfully.');
	}

	function categoryProducts()
	{
		$category_id = Request::Input('category_id');
		DB::enableQueryLog();
//		$result = DB::table('products as p')->select('p.*','c.display_order')->leftJoin('product_to_category as c','p.id','=','c.product_id')->where('p.status','1')->where('c.category_id',$category_id)->groupBY('p.id')->get();
        $result = DB::table('products as p')
            ->select(
                'p.*','c.display_order',
                DB::raw('COALESCE(prp.sale_price, 0) as s_price'),
                DB::raw('COALESCE(prp.list_price, 0) as list_price'),
                'prp.date as date'
            )->leftJoin(DB::raw('(SELECT * FROM `product_room_prices`) prp'), function ($join) {
                $join->on('p.id', '=', 'prp.product_id')
                    ->where('prp.status', '=', '1')
                    ->where('prp.date', '=', date('Y-m-d'));
            })->leftJoin('product_to_category as c', 'p.id', '=', 'c.product_id')
            ->where('p.status', '1')
            ->where('c.category_id', $category_id)->groupBY('p.id')->get();
		// print_r(DB::getQueryLog());exit;

		foreach($result as $key => $item) {
			$d = [
				'rooms' => DB::table('product_room_prices')->where('product_id', $item->id)->get(),
				'qty_stock' => 0,
				'low_level' => 0,
			];

			foreach ($d['rooms'] as $key => $value) {
				try {
					$dt = Carbon::createFromFormat('Y-m-d', $value->date);
					$dt_now = Carbon::now();
					if($dt_now->format('d-m-Y') == $dt->format('d-m-Y'))
					{
						$d['all_day'][] = $dt->format('d-m-Y');
						$d['qty_stock'] += $value->qty_stock;
						$d['low_level'] += $value->low_level;
						//dd($value->date);
					}
				}catch(\InvalidArgumentException $x) {
					//echo $x->getMessage();
				}
			}

			$item->quantity_in_stock = $d['qty_stock'];
			$item->low_level_in_stock = $d['low_level'];
			$new_res[] = $item;
		}

		if(count($new_res) > 0)
			echo json_encode(array('products' => $new_res));
	}

	function addQuantityDiscount(Request $request)
	{
		$formData = Input::get();

		$messages = [
        'required' => 'The :attribute field is required.',
    	];

    	$validator = Validator::make(Request::all(),[
	        'package_name' => 'required',
	        'package_code' => 'required',
	        'start_date' => 'required',
	        'end_date' => 'required',
	        // 'discount' => 'required',
	        // 'price' => 'required'
	    ], $messages);

	    if ($validator->fails()) {
	    	$json['error'] = $validator->errors()->all();
	    	return Redirect::back()->withInput()->withErrors($validator);
	    	exit;
	    }else{
	    	unset($formData['_token']);

	    	$formData['status'] = (isset($formData['status'])) ? '1' : '0';

			$formData['checkout_mo'] = (isset($formData['checkout_mo'])) ? '1' : '0';
			$formData['checkout_tu'] = (isset($formData['checkout_tu'])) ? '1' : '0';
			$formData['checkout_we'] = (isset($formData['checkout_we'])) ? '1' : '0';
			$formData['checkout_th'] = (isset($formData['checkout_th'])) ? '1' : '0';
			$formData['checkout_fr'] = (isset($formData['checkout_fr'])) ? '1' : '0';
			$formData['checkout_sa'] = (isset($formData['checkout_sa'])) ? '1' : '0';
			$formData['checkout_su'] = (isset($formData['checkout_su'])) ? '1' : '0';

			$formData['checkin_mo'] = (isset($formData['checkin_mo'])) ? '1' : '0';
			$formData['checkin_tu'] = (isset($formData['checkin_tu'])) ? '1' : '0';
			$formData['checkin_we'] = (isset($formData['checkin_we'])) ? '1' : '0';
			$formData['checkin_th'] = (isset($formData['checkin_th'])) ? '1' : '0';
			$formData['checkin_fr'] = (isset($formData['checkin_fr'])) ? '1' : '0';
			$formData['checkin_sa'] = (isset($formData['checkin_sa'])) ? '1' : '0';
			$formData['checkin_su'] = (isset($formData['checkin_su'])) ? '1' : '0';

			$formData['minimum_stay'] = (isset($formData['minimum_stay'])) ? $formData['minimum_stay'] : '';
			$formData['value_added_service'] = (isset($formData['value_added_service'])) ? $formData['value_added_service'] : '';

			DB::table('product_to_quantity_discount')->insert($formData);

			$this->data['success'] = 'Packages added successfully.';

			return Redirect::back()->with('data', $this->data);

	    }
	    return Redirect::back();
	}

	function updateQuantityDiscount()
	{
		$formData = Input::get();
		$messages = [
        'required' => 'The :attribute field is required.',
    	];

    	$validator = Validator::make(Request::all(),[
	        'package_name' => 'required',
	        'package_code' => 'required',
	        'start_date' => 'required',
	        'end_date' => 'required',
	        // 'discount' => 'required',
	        // 'price' => 'required'
	    ], $messages);

	    if ($validator->fails()) {
	    	$json['error'] = $validator->errors()->all();
	    	return Redirect::back()->withInput()->withErrors($validator);
	    	exit;
	    }else{

	    	unset($formData['_token']);

			$discount_id = $formData['discount_id'];
			unset($formData['discount_id']);

			$formData['discount'] = str_replace(',','',$formData['discount']);
			$formData['status'] = (isset($formData['status'])) ? '1' : '0';

			$formData['checkout_mo'] = (isset($formData['checkout_mo'])) ? '1' : '0';
			$formData['checkout_tu'] = (isset($formData['checkout_tu'])) ? '1' : '0';
			$formData['checkout_we'] = (isset($formData['checkout_we'])) ? '1' : '0';
			$formData['checkout_th'] = (isset($formData['checkout_th'])) ? '1' : '0';
			$formData['checkout_fr'] = (isset($formData['checkout_fr'])) ? '1' : '0';
			$formData['checkout_sa'] = (isset($formData['checkout_sa'])) ? '1' : '0';
			$formData['checkout_su'] = (isset($formData['checkout_su'])) ? '1' : '0';

			$formData['checkin_mo'] = (isset($formData['checkin_mo'])) ? '1' : '0';
			$formData['checkin_tu'] = (isset($formData['checkin_tu'])) ? '1' : '0';
			$formData['checkin_we'] = (isset($formData['checkin_we'])) ? '1' : '0';
			$formData['checkin_th'] = (isset($formData['checkin_th'])) ? '1' : '0';
			$formData['checkin_fr'] = (isset($formData['checkin_fr'])) ? '1' : '0';
			$formData['checkin_sa'] = (isset($formData['checkin_sa'])) ? '1' : '0';
			$formData['checkin_su'] = (isset($formData['checkin_su'])) ? '1' : '0';

			$formData['minimum_stay'] = (isset($formData['minimum_stay'])) ? $formData['minimum_stay'] : '';
			$formData['value_added_service'] = (isset($formData['value_added_service'])) ? $formData['value_added_service'] : '';

			DB::table('product_to_quantity_discount')->where('id',$discount_id)->update($formData);

			$this->data['success'] = 'Packages updated successfully.';

			return Redirect::back()->with('data', $this->data);

	    }
	    return Redirect::back();
	}

	function deleteQuantityDiscount()
	{
		$this->ProductModel->deleteQuantityDiscount($_POST['item_id']);
		Session::put('response', 'Item(s) deleted successfully.');
	}

	/*function listCategories()
	{
		//$categories = DB::table('categories')->get();
		$category = new Category();
		echo '<pre>';// print_r($category->getCategories());
		print_r($category->getCategoriesTree());
			exit;
	}*/

	/*function getUserDetails($id)
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
	}*/



	public function editProductAmenities(\Illuminate\Http\Request $request, $id){
	    if(!$request->ajax()){return false;}
	    $res = $this->ProductModel->saveAmenities($request->all(), $id);
	    return json_encode($res);
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

    /**
	 * Returns every date between two dates as an array
	 * @param string $startDate the start of the date range
	 * @param string $endDate the end of the date range
	 * @param string $format DateTime format, default is Y-m-d
	 * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
	 */
	function createDateRange($startDate, $endDate, $format = "Y-m-d")
	{
	    $begin = new \DateTime($startDate);
	    $end = new \DateTime($endDate);

	    $interval = new \DateInterval('P1D'); // 1 Day
	    $dateRange = new \DatePeriod($begin, $interval, $end);

	    $range = [];
	    foreach ($dateRange as $date) {
	        $range[] = $date->format($format);
	    }

	    return $range;
	}

}
