<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin\Partners;
use App\Http\Models\Countries;
use Session;
use Input;
use Illuminate\Http\RedirectResponse;
use Auth;
use Validator;
use Hash;
use DB;
use Redirect;
use Illuminate\Http\Request;
use Response;

class PartnersController extends Controller {
    private $data = array();
    private $PartnersModel = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->PartnersModel = new Partners();
    }

    public function index(Request $request,$limit = 10)
    {
        $page = 0;

        if(Input::get('page')){
            $page = Input::get('page');
        }
        if(Input::get('sort')){
            $sort = Input::get('sort');
        }
        else{
            $sort = 'ASC';
        }
        if(Input::get('sort_by')){
            $sort_by = Input::get('sort_by');
        }
        else{
            $sort_by = 'createdate';
        }

        $this->data['success'] = Session::get('customer.success');
        Session::forget('customer.success');
        $this->data['warning'] = Session::get('customer.warning');
        Session::forget('customer.warning');

        $this->data['partners'] = $this->PartnersModel->getPartners($limit, $page, Input::get());
        $this->data['paginate_msg'] = $this->PartnersModel->get_paginate_msg($limit, $page, Input::get());
        $this->data['last_updated'] = $this->PartnersModel->getLastUpdated();
        $this->data['curr_url'] = $request->url(). '?' . $_SERVER['QUERY_STRING'];

        //Sorting URL Start
        $sortingUrl = url('web88cms/customers/' . $limit) . '?';
        if(Input::get('customer_name')){
            $sortingUrl .= '&customer_name=' . Input::get('customer_name');
        }
        if(Input::get('email')){
            $sortingUrl .= '&email=' . Input::get('email');
        }
        //Sorting URL End

        $CountriesModel = new Countries();
        $this->data['countries'] = $CountriesModel->getCountries();
        $this->data['limit'] = $limit;
        $this->data['page'] = $page;
        $this->data['sorting_url'] = $sortingUrl;
        $this->data['sort'] = $sort;
        $this->data['sort_by'] = $sort_by;

        $this->data['page_title'] = 'Partners:: Listing';

        return view('admin.partners.index', $this->data);
    }

    public function newPartners(Request $request){
        $json = array();

        $validation['first_name'] = 'required';
        $validation['last_name'] = 'required';
        $validation['email'] = 'required|email|unique:customers';
        $validation['telephone'] = 'required';
        $validation['birth_date'] = 'required';
        $validation['password'] = 'required|min:6|max:12';
        $validation['password_confirmation'] = 'required|same:password';

        $validator = Validator::make($request->all(), $validation);
        if ($validator->fails()) {
            $json['error'] = $validator->errors()->all();
        }
        else
        {
            $this->PartnersModel->addPartner($request->all());
            Session::put('customer.success', 'New Partner added successfuly.');
            $json['success'] = 'TRUE';
        }

        return Response::json($json);
    }

    public function delete($customer_id){
        $this->PartnersModel->deletePartner($customer_id);
        Session::put('customer.success', 'Partner deleted successfully.');

        if(Input::get('redirect')){
            return redirect(Input::get('redirect'));
        }
        else{
            return redirect(Input::get('web88cms/customers'));
        }
    }

    public function deleteAllPartners(){
        $customers = Input::get('partners');

        if($customers && is_array($customers)){
            foreach($customers as $customer_id){
                $this->PartnersModel->deletePartner($customer_id);
            }
        }

        Session::put('customer.success', 'Partner deleted successfully.');
        $json['success'] = 'TRUE';
        return Response::json($json);
    }

    public function view($customer_id){
        $tab = (Input::get('tab') ? Input::get('tab') : 'overview');

        $sort = (Input::get('sort'))?Input::get('sort'):'ASC';
        $sort_by = (Input::get('sort_by'))? Input::get('sort_by'): 'createdate';

        $this->data['success'] = Session::get('customer.success');
        Session::forget('customer.success');
        $this->data['warning'] = Session::get('customer.warning');
        Session::forget('customer.warning');

        $CountriesModel = new Countries();
        $customer = $this->PartnersModel->getPartner($customer_id);
//dd($customer->credit[0]->total_credit);
        $this->data['billing_states'] = array();
        $this->data['shipping_states'] = array();

        if($customer->billing_country){
            $this->data['billing_states'] = $CountriesModel->getStatesByCountry($customer->billing_country);
        }
        if($customer->shipping_country){
            $this->data['shipping_states'] = $CountriesModel->getStatesByCountry($customer->shipping_country);
        }

        $this->data['countries'] = $CountriesModel->getCountries();
        $this->data['customer'] = $customer;

        if($tab == 'orders'){
            $this->data['orders'] = $this->PartnersModel->getPartnerOrders($customer_id,Input::get());
        }else{
            $this->data['orders'] = $this->PartnersModel->getPartnerOrders($customer_id);
        }

        //Start wishlist
        $page = 0;
        $limit = 50;

        if(Input::get('page')){
            $page = Input::get('page');
            $tab = 'wishlist';
        }
        if($tab == 'wishlist'){
            $this->data['wishlists'] = $this->PartnersModel->getCustomerWishlist($customer_id, $limit,Input::get());
        }else{
            $this->data['wishlists'] = $this->PartnersModel->getCustomerWishlist($customer_id, $limit);
        }
        $this->data['wishlistPaginateMsg'] = $this->PartnersModel->getWishlistPaginateMsg($customer_id, $limit, $page);
        $this->data['page'] = $page;
        //End wishlist

        //Start special
        $page_s = 0;
        $limit_s = 50;

        if(Input::get('page_s')){
            $page_s = Input::get('page_s');
            $tab = 'special-list';
        }
        if($tab == 'special-list'){
            $this->data['specials'] = $this->PartnersModel->getPartnerSpecial($customer_id, $limit_s,Input::get());
        }else{
            $this->data['specials'] = $this->PartnersModel->getPartnerSpecial($customer_id, $limit_s);
        }
        $this->data['specialPaginateMsg'] = $this->PartnersModel->getSpecialPaginateMsg($customer_id, $limit_s, $page_s);
        $this->data['page_s'] = $page_s;
        //End wishlist

        $this->data['tab'] = $tab;
        $this->data['sorting_url'] = url('web88cms/customers/view/'.$customer_id) . '?';
        $this->data['sort'] = $sort;
        $this->data['sort_by'] = $sort_by;
        $this->data['page_title'] = 'Customers:: Edit Profile';

        return view('admin.partners.view', $this->data);
    }

    public function editPartners(Request $request,$customer_id){
        $json = array();
        $validation['first_name'] = 'required';
        $validation['last_name'] = 'required';
        $validation['email'] = 'required|email|unique:customers,email,'.$customer_id;
        $validation['telephone'] = 'required';
        $validation['birth_date'] = 'required';

        if(Input::get('password') != ''){
            $validation['password'] = 'required|min:6|max:12';
            $validation['password_confirmation'] = 'required|same:password';
        }

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            $json['error'] = $validator->errors()->all();
        }
        else
        {
            $this->PartnersModel->editPartner($customer_id, $request->all());
            Session::put('customer.success', 'Partner updated successfuly.');
            $json['success'] = 'TRUE';
        }

        return Response::json($json);
    }

    public function getStates(){
        $CountriesModel = new Countries();
        $json['states'] = $CountriesModel->getStatesByCountry(Input::get('country_id'));
        return Response::json($json);
    }

    public function deleteOrder($customer_id, $order_id){
        $this->PartnersModel->deletePartnerOrder($customer_id, $order_id);
        Session::put('customer.success', 'Order deleted successfuly.');
        return redirect('web88cms/partners/view/' . $customer_id);
    }

    public function wishlistDetails($wishlist_id){
        $this->data['success'] = Session::get('customer.success');
        Session::forget('customer.success');
        $this->data['warning'] = Session::get('customer.warning');
        Session::forget('customer.warning');

        $this->data['products'] = $this->PartnersModel->getWishlistProducts($wishlist_id);
        $this->data['wishlist'] = $this->PartnersModel->getWishlist($wishlist_id);

        $this->data['page_title'] = 'View Wishlist:: Details';

        return view('admin.partners.wishlistDetails', $this->data);
    }

    public function specialListDetails($special_id){
        $this->data['success'] = Session::get('customer.success');
        Session::forget('customer.success');
        $this->data['warning'] = Session::get('customer.warning');
        Session::forget('customer.warning');

        $this->data['products'] = $this->PartnersModel->getSpecialListProducts($special_id);
        $this->data['specialList'] = $this->PartnersModel->getSpecialList($special_id);

        $this->data['page_title'] = 'View Special List:: Details';

        return view('admin.partners.specialListDetails', $this->data);
    }

    function csv(){
        $table = DB::table('partners')->select('first_name', 'last_name', 'email', 'birth_date', 'status', 'createdate')->get();

        $filename = "partners.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('first_name', 'last_name', 'email', 'birth_date', 'status', 'createdate'));

        foreach($table as $row) {
            fputcsv($handle, array($row->first_name, $row->last_name, $row->email, $row->birth_date, $row->status, $row->createdate));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        );

        return Response::download($filename, $filename, $headers);
    }
}
