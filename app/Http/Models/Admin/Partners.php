<?php
namespace App\Http\Models\Admin;

use App\Http\Models\Admin\ActivityLogs;
use App\Http\Models\CreditManagement;
use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;
use Auth;

class Partners extends Model{

    public function getPartner($partner_id)
    {
        return Partners::where('id', '=', $partner_id)->with('credit')->first();
    }

    public function credit()
    {
        return $this->hasMany('App\Http\Models\CreditManagement', 'partner_id', 'id');
    }

    public function getPartners($limit, $page, $data)
    {
        $customers = DB::table('partners');

        if(isset($data['email']) && trim($data['email']) != ''){
            $customers->where('email', 'LIKE', '%' . $data['email'] . '%');
        }
        if(isset($data['customer_name']) && trim($data['customer_name']) != ''){
            $customers->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $data['customer_name'] . '%');
        }

        //Sorting Start
        $sort = 'DESC';
        $sort_by = 'createdate';

        if(isset($data['sort'])){
            $sort = $data['sort'];
        }

        if(isset($data['sort_by']) && in_array($data['sort_by'], array('id', 'name', 'email', 'createdate', 'status'))){
            $sort_by = $data['sort_by'];
        }

        if($sort_by == 'name'){
            $customers->orderBy('first_name', $sort);
            $customers->orderBy('last_name', $sort);
        }
        else{
            $customers->orderBy($sort_by, $sort);
        }
        //Sorting End

        return $customers->paginate($limit);
    }

    public function get_paginate_msg($limit, $page, $data){
        $page = ($page ? ($page-1) * $limit : 0);

        //First query
        $customers = DB::table('partners')->select('id');
        if(isset($data['email']) && trim($data['email']) != ''){
            $customers->where('email', 'LIKE', '%' . $data['email'] . '%');
        }
        if(isset($data['customer_name']) && trim($data['customer_name']) != ''){
            $customers->where('first_name', 'LIKE', '%' . $data['customer_name'] . '%');
        }
        $results = $customers->skip($page)->take($limit)->get();

        //Second query
        $customers = DB::table('partners');
        if(isset($data['email']) && trim($data['email']) != ''){
            $customers->where('email', 'LIKE', '%' . $data['email'] . '%');
        }
        if(isset($data['customer_name']) && trim($data['customer_name']) != ''){
            $customers->where('first_name', 'LIKE', '%' . $data['customer_name'] . '%');
        }

        $count = $customers->count();

        if($results){
            $message = 'Showing ' . ($page + 1) . ' to ' . ($page + count($results)) . ' of ' . $count . ' entries';
        }
        else{
            $message = 'Showing 0 to 0 of ' . $count . ' entries';
        }

        return $message;
    }

    public function getLastUpdated(){
        $modifydate = DB::table('partners')->select('modifydate')->orderBy('modifydate', 'DESC')->take(1)->first();
        if($modifydate){
            return date('d M, Y @ h:i A', strtotime($modifydate->modifydate));
        }
        else{
            return false;
        }
    }

    public function addPartner($data){
        $insert = [
            'password' 				=> Hash::make($data['password']),
            'first_name' 			=> $data['first_name'],
            'last_name' 			=> $data['last_name'],
            'email' 				=> $data['email'],
            'telephone' 			=> $data['telephone'],
            'birth_date' 			=> date('Y-m-d', strtotime($data['birth_date'])),
            'billing_first_name' 	=> $data['first_name'],
            'billing_last_name' 	=> $data['last_name'],
            'billing_email' 		=> $data['email'],
            'billing_telephone' 	=> $data['telephone'],
            'billing_address' 		=> $data['email'],
            'billing_city' 			=> 'city',
            'billing_post_code' 	=> 'post_code',
            'billing_state' 		=> 'billing_state',
            'billing_country' 		=> 'malaysia',
            'shipping_first_name' 	=> $data['first_name'],
            'shipping_last_name' 	=> $data['last_name'],
            'shipping_email' 		=> $data['email'],
            'shipping_telephone' 	=> $data['telephone'],
            'shipping_address' 		=> $data['email'],
            'shipping_city' 		=> 'city',
            'shipping_post_code' 	=> 'post_code',
            'shipping_state' 		=> 'state',
            'shipping_country' 		=> 'malaysia',
            'status'				=> (isset($data['status']) ? '1' : '0'),
            'modifydate'			=> date('Y-m-d H:i:s'),
            'createdate'			=> date('Y-m-d H:i:s'),
        ];

        $partner=DB::table('partners')->insertGetId($insert);
//        dd($partner);
        $creditInsert=
            [
                'partner_id'=>$partner,
                'add_credit'=>$data['add_credit'],
                'old_credit'=>$data['add_credit'],
                'total_credit'=>$data['add_credit'],
                'debit'=>0,
            ];
        $customerCredit= DB::table('credit_management')->insert($creditInsert);
        if(Auth::user()){
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'IP not found';
            $activity = new ActivityLogs();

            $activity->user_id = Auth::user()->id;
            $activity->ip = $ip;
            $activity->action = 'Create';
            $activity->activity = 'New Customer';
            $activity->details = $insert['first_name'].' '.$insert['last_name'];
            $activity->save();
        }
    }

    public function editPartner($customer_id, $data){
        $update = [
            'first_name' 			=> $data['first_name'],
            'last_name' 			=> $data['last_name'],
            'email' 				=> $data['email'],
            'telephone' 			=> $data['telephone'],
            'birth_date' 			=> date('Y-m-d', strtotime($data['birth_date'])),
            'status'				=> (isset($data['status']) ? '1' : '0'),
            'modifydate'			=> date('Y-m-d H:i:s')
        ];

        if($data['password'] != ''){
            $update['password'] = Hash::make($data['password']);
        }

        $partner=DB::table('partners')->where('id', $customer_id)->update($update);


        $customerCredit=CreditManagement::where('partner_id', $customer_id)->first();
        if (count($customerCredit)>0) {
            $customerCredit->old_credit = $customerCredit->total_credit;
            $customerCredit->add_credit = $data['add_credit'];
            $customerCredit->total_credit = $customerCredit->add_credit + $customerCredit->old_credit;
            $customerCredit->update();
        }
        else
        {
            $creditInsert=
                [
                    'partner_id'=>$customer_id,
                    'add_credit'=>$data['add_credit'],
                    'old_credit'=>$data['add_credit'],
                    'total_credit'=>$data['add_credit'],
                    'debit'=>0,
                ];
            $customerCredit= DB::table('credit_management')->insert($creditInsert);

        }

        if(Auth::user()){
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'IP not found';
            $activity = new ActivityLogs();

            $activity->user_id = Auth::user()->id;
            $activity->ip = $ip;
            $activity->action = 'Update';
            $activity->activity = 'Edit Partner';
            $activity->details = $update['first_name'].' '.$update['last_name'];
            $activity->save();
        }
    }
    // For Get client ip address
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }



    public function deletePartner($customer_id){
        $cus = DB::table('partners')->where('id', '=', $customer_id)->first();
        if(Auth::user()){
            // $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'IP not found';
            $ip  = $this->get_client_ip();
            //dd($_SERVER);
            $activity = new ActivityLogs();

            $activity->user_id = Auth::user()->id;
            $activity->ip = $ip;
            $activity->action = 'Delete';
            $activity->activity = 'Delete Customer';
            $activity->details = $cus->first_name.' '.$cus->last_name;
            $activity->save();
        }
        DB::table('partners')->where('id', '=', $customer_id)->delete();
    }

    public function getPartnerOrders($customer_id, $data = null)
    {
        //Sorting Start
        $sort = 'DESC';
        $sort_by = 'createdate';

        if(isset($data['sort'])){
            $sort = $data['sort'];
        }

        if(isset($data['sort_by']) && in_array($data['sort_by'], array('order_id', 'billing_email', 'createdate', 'status', 'payment_status', 'totalPrice'))){
            $sort_by = $data['sort_by'];
        }
        if(isset($data['sort_by']) && ($data['sort_by']) == 'name'){
            $sort_by = 'billing_first_name';
        }
        return DB::table('orders')->where('partner_id', $customer_id)->orderBy($sort_by, $sort)->get();
    }

    public function deletePartnerOrder($customer_id, $order_id){
        $affected = DB::table('orders')->where('id', '=', $order_id)->where('partner_id', '=', $customer_id)->delete();

        if($affected){
            DB::table('order_to_product')->where('order_id', '=', $order_id)->delete();
        }
    }

    public function getCustomerWishlist($customer_id, $limit, $data=null)
    {
        //Sorting Start
        $sort = 'DESC';
        $sort_by = 'createdate';

        if(isset($data['sort'])){
            $sort = $data['sort'];
        }
        if(isset($data['sort_by']) && in_array($data['sort_by'], array('list_name', 'createdate', 'totalItems'))){
            $sort_by = $data['sort_by'];
        }

        $customers = DB::table('wishlist')->select('*', DB::raw('(SELECT COUNT(id) as total from wishlist_items WHERE wishlist_id = wishlist.id) as totalItems'));
        $customers->where('user_id', '=', $customer_id);
        $customers->orderBy($sort_by, $sort);

        return $customers->paginate($limit);
    }

    public function getWishlistPaginateMsg($customer_id, $limit, $page){
        $page = ($page ? ($page-1) * $limit : 0);

        //First query
        $results = DB::table('wishlist')->select('id')->where('user_id', '=', $customer_id)->skip($page)->take($limit)->get();

        //Second query
        $count = DB::table('wishlist')->where('user_id', '=', $customer_id)->count();

        if($results){
            $message = 'Showing ' . ($page + 1) . ' to ' . ($page + count($results)) . ' of ' . $count . ' entries';
        }
        else{
            $message = 'Showing 0 to 0 of ' . $count . ' entries';
        }

        return $message;
    }

    public function getWishlist($wishlist_id){
        return DB::table('wishlist')->where('id', '=', $wishlist_id)->first();
    }

    public function getWishlistProducts($wishlist_id){
        $result = DB::table('wishlist_items as wi');
        $result->select('wi.*', 'p.product_name', 'p.product_code', 'p.thumbnail_image_1', 'p.thumbnail_image_2', 'p.sale_price', 'p.quantity_in_stock', 'c.title as color_name');
        $result->leftjoin('colors as c','c.id', '=','wi.color_id' );
        $result->leftjoin('products as p','p.id', '=','wi.product_id' );
        $result->where('wi.wishlist_id', $wishlist_id);
        //$result->groupBy('p.id');

        return $result->get();
    }

    //Start special
    public function getPartnerSpecial($customer_id, $limit, $data = null)
    {
        //Sorting Start
        $sort = 'DESC';
        $sort_by = 'created';

        if(isset($data['sort'])){
            $sort = $data['sort'];
        }
        if(isset($data['sort_by']) && in_array($data['sort_by'], array('event_type', 'event_date', 'totalGifts'))){
            $sort_by = $data['sort_by'];
        }

        $customers = DB::table('special_events')->select('*', DB::raw('(SELECT COUNT(id) as total from special_events_items WHERE event_id = special_events.id) as totalGifts'));
        $customers->where('user_id', '=', $customer_id);
        $customers->orderBy($sort_by, $sort);

        return $customers->paginate($limit)->setPageName('page_s');
    }

    public function getSpecialPaginateMsg($customer_id, $limit, $page){
        $page = ($page ? ($page-1) * $limit : 0);

        //First query
        $results = DB::table('special_events')->select('id')->where('user_id', '=', $customer_id)->skip($page)->take($limit)->get();

        //Second query
        $count = DB::table('special_events')->where('user_id', '=', $customer_id)->count();

        if($results){
            $message = 'Showing ' . ($page + 1) . ' to ' . ($page + count($results)) . ' of ' . $count . ' entries';
        }
        else{
            $message = 'Showing 0 to 0 of ' . $count . ' entries';
        }

        return $message;
    }

    public function getSpecialList($special_id){
        return DB::table('special_events')->where('id', '=', $special_id)->first();
    }

    public function getSpecialListProducts($special_id){
        $result = DB::table('special_events_items as sei');
        $result->select('sei.*', 'p.product_name', 'p.product_code', 'p.thumbnail_image_1', 'p.thumbnail_image_2', 'p.sale_price', 'p.quantity_in_stock', 'c.title as color_name', DB::raw('(SELECT SUM(otp.quantity) as total from order_to_product as otp WHERE otp.product_id = sei.product_id AND otp.color_id = sei.color_id AND otp.special_event_id = sei.event_id) as totalGifts'));

        $result->leftjoin('colors as c','c.id', '=','sei.color_id' );
        $result->leftjoin('products as p','p.id', '=','sei.product_id' );

        $result->where('sei.event_id', $special_id);

        return $result->get();
    }
    //End special
}