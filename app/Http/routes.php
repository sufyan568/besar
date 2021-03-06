<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|vacancy
*/

use App\Models\Page;

Route::group(['middleware' => 'global.settings'], function () {


    /* Front routes*/
    Route::get('/', 'Front\HomeController@index');

    Route::get('/rooms-suites', 'Front\RoomSuiteController@index');
    Route::get('/rooms-suites/{id}', 'Front\RoomSuiteController@index');
    Route::post('/sort-rooms-suites', 'Front\RoomSuiteController@getList');

    Route::get('rooms-suites/show/{show}/{package?}', ['as' => 'rooms-suites/show', 'uses' => 'Front\RoomSuiteController@show']);

    Route::get('/apartments', 'Front\ApartmentController@index');
    Route::get('/apartments/{id}', 'Front\ApartmentController@index');

    Route::get('apartments/show/{show}/{package?}', ['as' => 'apartments/show', 'uses' => 'Front\RoomSuiteController@show']);


    Route::get('/promotions', 'Front\PromotionController@index');
    Route::get('/promotions/{id}', 'Front\PromotionController@index');

    Route::get('/dining', 'Front\DiningController@index');
    Route::get('/dining/{id}', 'Front\DiningController@index');

    Route::get('/facilities', 'Front\FacilitiesController@index');
    Route::get('/facilities/{id}', 'Front\FacilitiesController@index');

    Route::get('/weddings', 'Front\WeddingController@index');
    Route::get('/weddings/{id}', 'Front\WeddingController@index');

    Route::get('/events-meetings', 'Front\EventMeetingController@index');
    Route::get('/events-meetings/{id}', 'Front\EventMeetingController@index');

    Route::get('/about-us', 'Front\AboutUsController@index');
    Route::get('/about-us/{id}', 'Front\AboutUsController@index');

    Route::get('/gallery', 'Front\GalleryController@index');
    Route::get('/gallery/{id}', 'Front\GalleryController@index');

    Route::get('/contact-us', 'Front\ContactUsController@index');
    Route::post('/contact-us/create', 'Front\ContactUsController@create');
    // Route::get('/login', 'Front\LoginController@index');
    // Route::get('/create-account', 'Front\CreateAccountController@index');



    Route::get('/check-availability/check-discount', 'Front\CheckAvailController@checkDiscount');
    Route::get('/check-availability',                                               'Front\CheckAvailController@index');
    Route::get('/get_packages/{id}','Front\CheckAvailController@getPackages');
    Route::post('/check-availability',                                              'Front\CheckAvailController@check_avail');
    Route::post('/check-availability-left','Front\CheckAvailController@check_availability_left');
    Route::post('/make-cart','Front\CheckAvailController@saveCart');
    Route::post('/lr-make-cart','Front\CheckAvailController@saveLrCart');
    Route::post('/check-availability/signup','Front\CheckAvailController@signupForNotification');
    Route::get('/notify-success','Front\CheckAvailController@notifySuccess');

//    Feed Back
    Route::get('/feed-back', 'Front\FeedBackController@index');



    Route::get('/cache', function() {
        Artisan::call('cache:clear');
        return "Application cache cleared!";
    });
    Route::get('/route-clear', function() {
        Artisan::call('route:cache');
        return "Route cache cleared!";
    });
    Route::get('/optimize', function() {
        Artisan::call('optimize');
        return "optimized!";
    });



    // Route::get('/brand/{id}/{urlKey}', 'Front\BrandController@index');
    Route::get('/search_result', 'Front\HomeController@search_result');
    Route::post('/search_result', 'Front\HomeController@searchdata');
    Route::get('/search_lowest_rate/{checkin}/{checkout}', 'Front\HomeController@searchlowestrate');
    Route::post('/get-rooms', 'Front\HomeController@getRoomsFromLowestRate');
    Route::get('/category/{id}/{sort}/{item}/{page}', 'Front\CatalogController@index');

    Route::get('/create_account', 'Front\UserController@create_account');
    Route::post('/create_account', 'Front\UserController@create_account_register');
    Route::post('/users/getStates', 'Front\UserController@getStates');
    Route::get('/login', 'Front\UserController@login');
    Route::post('/login', 'Front\UserController@logincustomers');
    Route::post('user/sendOrderEmail', 'Front\UserController@sendemail');

    Route::post('/login/reset', 'Front\UserController@resetmail');
    Route::get('/passwordreset', 'Front\UserController@passwordreset');
    Route::post('/passwordreset', 'Front\UserController@passwordresetpost');


    Route::get('/partnerlogin',                                             'Front\partnerLoginController@login');
    Route::post('/partnerlogin',                                            'Front\partnerLoginController@partnerLogin');
    Route::get('partner/logout',                                            'Front\partnerLoginController@logout');
    Route::post('/partner-account-edit',                                    'Front\partnerLoginController@accountEdit');
    Route::get('/partner/billingaddress',                                   'Front\partnerLoginController@billingaddress');
    Route::post('/partner/billingaddress',                                  'Front\partnerLoginController@billingaddress');
    Route::get('/partner/shippingaddress',                                  'Front\partnerLoginController@shippingaddress');
    Route::post('/partner/shippingaddress',                                 'Front\partnerLoginController@shippingaddress');
    Route::post('/partner-client-status-update',                            'Front\partnerLoginController@partnerClientStatus');
    Route::post('/partner-client-payment-status-update',                    'Front\partnerLoginController@partnerClientPaymentStatus');
    Route::post('/partner/newsletter',                                      'Front\partnerLoginController@newsletter');
    Route::get('/partner-order-details/{id}',                               'Front\partnerLoginController@orderdetails');


    Route::get('/partner/order-history',            ['as' => 'order-history',       'uses' => 'Front\partnerLoginController@orderhistory']);
    Route::get('/partner-account-edit',             ['as' => 'partner-account-edit','uses' => 'Front\partnerLoginController@accountEdit']);




    Route::get('/orderhistory/{sort}/{page}',                                        'Front\UserController@orderhistory');
    Route::get('/partner-order-details-views/{id}',                                  'Front\UserController@partnerOrderdetails');
    Route::post('user/update-new-address',                                           'Front\UserController@updateNewAddress');
    Route::post('/newsletter/addSubscriber',                                         'Front\HomeController@addSubscriber');

    Route::get('/partnerDashboard',                 ['as' => 'dashboard',            'uses' => 'Front\partnerLoginController@dashboard' ]);
    Route::get('/logout',                                                            'Front\UserController@logout');


    Route::get('/dashboard',                        ['as' => 'dashboard',            'uses' => 'Front\UserController@dashboard'  ]);
    Route::get('/accountedit',                      ['as' => 'accountedit',          'uses' => 'Front\UserController@accountEdit'     ]);
    Route::post('/user/submit-feedback',                                             'Front\UserController@feedbackSubmit');

    Route::post('/accountedit', 'Front\UserController@accountEdit');
    Route::get('/billingaddress', 'Front\UserController@billingaddress');
    Route::post('/billingaddress', 'Front\UserController@billingaddress');
    Route::get('/shippingaddress', 'Front\UserController@shippingaddress');
    Route::post('/shippingaddress', 'Front\UserController@shippingaddress');
    Route::post('/newsletter', 'Front\UserController@newsletter');
    Route::get('/orderhistory', ['as' => 'orderhistory',
        'uses' => 'Front\UserController@orderhistory'
    ]);
    Route::get('/orderhistory/{sort}/{page}', 'Front\UserController@orderhistory');
    Route::get('/orderdetails/{id}', 'Front\UserController@orderdetails');
    Route::post('/newsletter/addSubscriber', 'Front\HomeController@addSubscriber');

    // index routes
    Route::get('web88cms/indexPopup', 'Admin\AdminController@popUp');
    Route::post('web88cms/indexPopup', 'Admin\AdminController@storePopUp');
    // Loader
    Route::get('web88cms/loader', 'Admin\AdminController@loader');
    Route::post('web88cms/loader', 'Admin\AdminController@storeLoader');

    // Index Dynamic (5th Sept 2019)
    Route::get('web88cms/index_edit',"Admin\AdminController@indexEdit");
    Route::post('web88cms/index_editor', 'Admin\AdminController@index_editor');
    Route::get('web88cms/index_edit_second',"Admin\AdminController@indexEditSecond");
    Route::post('web88cms/index_editor_second', 'Admin\AdminController@index_editor_second');
    Route::get('web88cms/index_edit_third',"Admin\AdminController@indexEditThird");
    Route::post('web88cms/index_editor_third', 'Admin\AdminController@index_editor_third');
    Route::get('web88cms/index_edit_fourth',"Admin\AdminController@indexEditFourth");
    Route::post('web88cms/index_editor_fourth', 'Admin\AdminController@index_editor_fourth');


//    feedback
    Route::get('web88cms/contacts/feedbacks', 'Admin\FeedbackController@adminFeedback');
    Route::get('web88cms/contacts/contactus', 'Admin\FeedbackController@adminContactus');
    Route::get('web88cms/contacts/deletefeedback/{id}', 'Admin\FeedbackController@adminSingleDeleteFeedback');
    Route::post('web88cms/contacts/deletefeedback', 'Admin\FeedbackController@adminDeleteFeedback');
    Route::post('web88cms/contacts/deleteallfeedback', 'Admin\FeedbackController@adminDeleteAllFeedback');
    // enquiry category set up

    // Gallery
    Route::get('web88cms/gallery_list', function () {
        $page_limit = isset($_GET['per_page']) ?  $_GET['per_page'] : 10;
        Session::put('gallery.per_page',$page_limit);
        $gallery = DB::table('gallery')->paginate($page_limit);
        $category = DB::table('gallery_category')->orderBy('updated_at','asc')->get();
        $admin_last_activity = DB::table('admin_last_activity')->where('section', 'gallery')->orderBy('updated_at','desc')->pluck('updated_at');
        //$last_updated_category = DB::table('gallery_category')->orderBy('updated_at','desc')->get();
        $header = DB::table('page_header')->where('page','gallery')->get();
        $latest_activity_date = date('d M, Y @ g.iA', strtotime($admin_last_activity));
        return view::make('admin.gallery.gallery_list')->with(['latest_activity_date' => $latest_activity_date, 'gallery'=> $gallery,'gallery_updated'=> $latest_activity_date,'category'=>$category,'header'=>$header]);
    });

    //Events
    Route::get('web88cms/events_edit',"Admin\AdminController@eventsEdit");
    Route::post('web88cms/events_editor', 'Admin\AdminController@events_editor');
    Route::post('web88cms/events_header_editor', 'Admin\AdminController@events_header_editor');
    //Route::post('web88cms/events_mid_editor', 'Admin\AdminController@events_mid_editor');
    Route::post('web88cms/events_left_editor', 'Admin\AdminController@events_left_editor');
    Route::put('web88cms/events_left_editor', 'Admin\AdminController@events_left_editor');

    // Dining
    Route::get('web88cms/dining_edit',"Admin\AdminController@diningEdit");
    Route::post('web88cms/dining_editor', 'Admin\AdminController@dinning_editor');
    Route::post('web88cms/dining_header_editor', 'Admin\AdminController@dinning_header_editor');
    Route::post('web88cms/dining_mid_editor', 'Admin\AdminController@dinning_mid_editor');
    Route::post('web88cms/dining_left_editor', 'Admin\AdminController@dinning_left_editor');

    // Facility
    Route::get('web88cms/facilities_edit',"Admin\AdminController@facilityEdit");
    Route::get('web88cms/facilities_edit/{index}/{page}',"Admin\AdminController@facilityEdit");
    Route::post('web88cms/facility_editor', 'Admin\AdminController@facility_editor');
    // for add facilities.
    Route::post('/web88cms/facilityAdd', 'Admin\AdminController@facilityAdd');
    Route::post('/web88cms/{index}/facilityAdd', 'Admin\AdminController@facilityAdd');
    Route::post('/web88cms/{item}/{page}/facilityAdd', 'Admin\AdminController@facilityAdd');
    Route::post('/web88cms/facilityUpdate', 'Admin\AdminController@facilityUpdate');
    Route::post('/web88cms/facilityDelete', 'Admin\AdminController@facilityDelete');
    // for video
    Route::post('/web88cms/videoAdd', 'Admin\AdminController@videoAdd');
    Route::post('/web88cms/videoUpdate', 'Admin\AdminController@videoUpdate');
    Route::post('/web88cms/videoDelete', 'Admin\AdminController@videoDelete');
    // for background
    Route::post('/web88cms/backgroundUpdate', 'Admin\AdminController@backgroundUpdate');
    Route::post('/web88cms/backgroundDelete', 'Admin\AdminController@backgroundDelete');

    // Video Title
    Route::get('web88cms/video_title_edit',"Admin\AdminController@videoTitleEdit");
    Route::post('web88cms/video_title_editor', 'Admin\AdminController@video_title_editor');

    // Wedding
    Route::get('web88cms/weddings_edit',"Admin\AdminController@weddingEdit");
    Route::post('web88cms/weddings_editor', 'Admin\AdminController@weddings_editor');
    Route::post('web88cms/wedding_header_editor', 'Admin\AdminController@wedding_header_editor');


    // Events
    Route::get('web88cms/events_edit',"Admin\AdminController@eventsEdit");
    Route::post('web88cms/events_editor', 'Admin\AdminController@events_editor');

    Route::post('web88cms/category', 'Admin\GalleryController@category');
    Route::post('web88cms/category_del','Admin\GalleryController@delCategory');
    Route::post('web88cms/category_all_del','Admin\GalleryController@category_all_del');
    Route::post('web88cms/category_selected_del','Admin\GalleryController@category_selected_del');
    Route::post('web88cms/gallery','Admin\GalleryController@gallery');
    Route::post('web88cms/gallery_del','Admin\GalleryController@delGallery');
    Route::post('web88cms/gallery_all_del','Admin\GalleryController@gallery_all_del');
    Route::post('web88cms/gallery_selected_del','Admin\GalleryController@gallery_selected_del');



    // Booking routes
    Route::get('web88cms/onScreenMessages', 'Admin\AdminController@booking');
    Route::post('web88cms/onScreenMessages', 'Admin\AdminController@updateBooking');
    Route::post('web88cms/booking/delete', 'Admin\AdminController@destroyBooking');
    Route::post('web88cms/add_on_screen_message', 'Admin\AdminController@storeOnScreenMessage');
    /* Admin Routes */

    Route::get('web88cms', 'Admin\AdminController@index');
    Route::get('/web88cms/login', 'Admin\AdminController@login');
    // Route::post('/auth/login', 'Auth\AuthController@postLogin');
    Route::get('/web88cms/logout', 'Admin\AdminController@logout');
//Route::get('/admin', 'Admin\AdminController@login');
//Route::get('admin', 'Admin\AdminController@index');
    Route::get('/web88cms/dashboard', 'Admin\AdminController@dashboard');
//    Route::get('/web88cms/checkInOut/{limit}', 'Admin\AdminController@checkInOutListing');
//    Route::get('/web88cms/checkInOut', 'Admin\AdminController@checkInOutListing');
    Route::get('/web88cms/checkInOut/{limit}', ['as' => 'checkInOut', 'uses' => 'Admin\AdminController@checkInOutListing']);
    Route::get('/web88cms/checkInOut', ['as' => 'checkInOut', 'uses' => 'Admin\AdminController@checkInOutListing']);
    Route::get('/web88cms/updatePassword/{id}', 'Admin\AdminController@updatePassword');
    Route::post('/web88cms/updatePassword/{id}', 'Admin\AdminController@updatePassword');

//Route::get('/admin/updateAvtar/{id}', 'Admin\AdminController@updatePassword');
    Route::post('/web88cms/updateAvtar/{id}', 'Admin\AdminController@updateAvtar');

    Route::get('/web88cms/analytics_reports', 'Admin\RoomsController@analytics_reports');


//Route::get('/admin/categories/list/', 'Admin\CategoryController@listCategories');

    Route::get('/web88cms/categories/list/', 'Admin\CategoryController@listCategories');
    Route::get('/web88cms/categories/listAjax', 'Admin\CategoryController@listAjax');
    Route::post('/web88cms/categories/listAjax', 'Admin\CategoryController@listAjax');
    Route::post('/web88cms/categories/editCategory/{category_id}', 'Admin\CategoryController@editCategory');
    Route::get('/web88cms/categories/copyCategory/{category_id}', 'Admin\CategoryController@copyCategory');
    Route::get('/web88cms/categories/deleteCategory/{category_id}', 'Admin\CategoryController@deleteCategory');
    Route::post('/web88cms/categories/uploadMenuImage/{category_id}', 'Admin\CategoryController@uploadMenuImage');

    Route::post('/web88cms/categories/saveCategoryChanges', 'Admin\CategoryController@saveCategoryChanges');

    Route::get('/web88cms/categories/category_home_list', 'Admin\CategoryController@categoryhomelist');
    Route::post('/web88cms/categories/category_home_list', 'Admin\CategoryController@categoryhomelistpostdata');

    Route::get('/web88cms/categories/categoryhomelisttabajax/{id}', 'Admin\CategoryController@categoryhomelisttabajax');
    Route::get('/web88cms/categories/deleteTabsWithNoCategory', 'Admin\CategoryController@deleteTabsWithNoCategory');

    Route::get('/web88cms/categories/deletetabcategoryhomelisttabajax/{id}', 'Admin\CategoryController@deletetabcategoryhomelisttabajax');
    Route::get('/web88cms/categories/deleteallhomelisttabajax/{id}', 'Admin\CategoryController@delehomealllisttabajax');
    Route::get('/web88cms/categories/editcategoryhomelisttabajaxfortab/{id}', 'Admin\CategoryController@editcategoryhomelisttabajaxfortab');
    Route::get('/web88cms/categories/updateeditcategoryhomelisttabajaxfortab/{id}', 'Admin\CategoryController@updateeditcategoryhomelisttabajaxfortab');
    Route::post('/web88cms/categories/category_home_list/tablisting', 'Admin\CategoryController@tablistinghomelistpostdata');
    Route::post('/web88cms/categories/category_home_list/tablisting/ateditcatwithtab', 'Admin\CategoryController@tablistinghomelistateditcatwithtab');
    Route::post('/web88cms/categories/category_home_list/deletecatlist', 'Admin\CategoryController@deletecategoryhomelistpostdata');
    Route::post('/web88cms/categories/category_home_list/edittabenable', 'Admin\CategoryController@edittabenablecategoryhomelistpostdata');
    Route::post('/web88cms/categories/category_home_list/editcatlist', 'Admin\CategoryController@editcategoryhomelistpostdata');
    Route::post('/web88cms/categories/category_home_list/updatealltaborderedit', 'Admin\CategoryController@edit_update_display_order_all_tab_cat_home');

    Route::post('/web88cms/categories/category_home_list/deleteselectedcatlist', 'Admin\CategoryController@deleteselectcategorydata');
    Route::get('/web88cms/categories/category_home_list/deleteselectedAllhomecategorylistdata', 'Admin\CategoryController@deleteAlltopmiddle');
    Route::post('/web88cms/categories/category_home_list/edittablist', 'Admin\CategoryController@edittablisthomedata');
    Route::post('/web88cms/categories/category_home_list/edittablistmain', 'Admin\CategoryController@edittablisthomedatamain');
    Route::post('/web88cms/categories/category_home_list/deletetablist', 'Admin\CategoryController@deletetabhomelistpostdata');
    Route::post('/web88cms/categories/category_home_list/deleteselectedtablist', 'Admin\CategoryController@deleteselecttabdata');
    Route::post('/web88cms/categories/category_home_list/deleteselectedAllhometablistdata', 'Admin\CategoryController@deleteAlltabhomedata');
    Route::post('/web88cms/categories/category_home_list/updatealltaborder', 'Admin\CategoryController@update_display_order_all_tab_cat_home');
    Route::post('/web88cms/categories/category_home_list/updatealltaborder/edit', 'Admin\CategoryController@editdataupdate_display_order_all_tab_cat_home');
    Route::post('/web88cms/categories/category_home_list/deleteselectedtablistedit/{category_id}', 'Admin\CategoryController@deleteselecttabdataedit');
    Route::get('/web88cms/categories/category_home_list/deleteselectedAllhometablistdataedit/{category_id}', 'Admin\CategoryController@deleteAlltabhomedataedit');

    Route::get('/web88cms/categories/category_home_products_list', 'Admin\CategoryController@categoryhomeproductslist');
    Route::post('/web88cms/categories/category_home_products_list/addtabproducts', 'Admin\CategoryController@addtabproductsdata');
    Route::post('/web88cms/categories/category_home_products_list/updateallhome_products_list', 'Admin\CategoryController@update_display_order_allategory_home_products_list');
    Route::post('/web88cms/categories/category_home_products_list/deletechoosenhomeproduct', 'Admin\CategoryController@deletechoosenhomeproductfrmlist');
    Route::get('/web88cms/categories/category_home_products_list/deleteAllhomecategrylistdata', 'Admin\CategoryController@deleteAllhomecatlist');
    Route::post('/web88cms/categories/category_home_products_list/deleteselectcats', 'Admin\CategoryController@deleteselectedcatsdata');


    Route::get('/web88cms/categories/homeList', 'Admin\CategoryController@homeList');
    Route::get('/web88cms/categories/homeList/{limit}', 'Admin\CategoryController@homeList');



//Administrators Start
    Route::post('/web88cms/administrators/newAdministrator', 'Admin\AdministratorsController@newAdministrator');
    Route::get('/web88cms/administrators/delete/{administrator_id}', 'Admin\AdministratorsController@delete');
    Route::post('/web88cms/administrators/deleteAllAdministrator', 'Admin\AdministratorsController@deleteAllAdministrator');
    Route::post('/web88cms/administrators/getStates', 'Admin\AdministratorsController@getStates');
    Route::post('/web88cms/administrators/editAdministrator/{administrator_id}', 'Admin\AdministratorsController@editAdministrator');
    Route::get('/web88cms/administrators/csv', 'Admin\AdministratorsController@csv');
    Route::get('/web88cms/administrators/{limit}', 'Admin\AdministratorsController@index');
    Route::get('/web88cms/administrators/', 'Admin\AdministratorsController@index');
//Administrators End

//Activity Logs Start
    Route::get('/web88cms/activiy_logs_list', 'Admin\ActivityLogsController@getActivityLogsList');
    Route::post('/web88cms/activiy_logs_list/delete/{type}', 'Admin\ActivityLogsController@removeRow');
    Route::get('/web88cms/activiy_logs_list/details/{id}', 'Admin\ActivityLogsController@getDetails');
    Route::get('/web88cms/activiy_logs_list/csv', 'Admin\ActivityLogsController@csv');
//Activity Logs End

//Customers Start
    Route::post('/web88cms/customers/newCustomer', 'Admin\CustomersController@newCustomer');
    Route::get('/web88cms/customers/delete/{customer_id}', 'Admin\CustomersController@delete');
    Route::post('/web88cms/customers/deleteAllCustomer', 'Admin\CustomersController@deleteAllCustomer');
    Route::get('/web88cms/customers/view/{customer_id}', 'Admin\CustomersController@view');
    Route::post('/web88cms/customers/view/{customer_id}', 'Admin\CustomersController@view');
    Route::post('/web88cms/customers/getStates', 'Admin\CustomersController@getStates');
    Route::post('/web88cms/customers/editCustomer/{customer_id}', 'Admin\CustomersController@editCustomer');
    Route::get('/web88cms/customers/deleteOrder/{customer_id}/{order_id}', 'Admin\CustomersController@deleteOrder');
    Route::get('/web88cms/customers/wishlistDetails/{wishlist_id}', 'Admin\CustomersController@wishlistDetails');
    Route::get('/web88cms/customers/specialListDetails/{special_id}', 'Admin\CustomersController@specialListDetails');
    Route::get('/web88cms/customers/csv', 'Admin\CustomersController@csv');
    Route::get('/web88cms/customers/{limit}', 'Admin\CustomersController@index');
    Route::get('/web88cms/customers/', 'Admin\CustomersController@index');
//Customers End


//    Partners

    Route::get('/web88cms/partners', 'Admin\PartnersController@index');
    Route::post('/web88cms/partners/new-partners', 'Admin\PartnersController@newPartners');
    Route::get('/web88cms/partners/delete/{partners_id}', 'Admin\PartnersController@delete');
    Route::post('/web88cms/partners/deleteAllPartners', 'Admin\PartnersController@deleteAllPartners');
    Route::get('/web88cms/partners/view/{partners_id}', 'Admin\PartnersController@view');
    Route::post('/web88cms/partners/view/{partners_id}', 'Admin\PartnersController@view');
    Route::post('/web88cms/partners/getStates', 'Admin\PartnersController@getStates');
    Route::post('/web88cms/partners/editPartners/{partners_id}', 'Admin\PartnersController@editPartners');
    Route::get('/web88cms/partners/deleteOrder/{partners_id}/{order_id}', 'Admin\PartnersController@deleteOrder');
    Route::get('/web88cms/partners/wishlistDetails/{wishlist_id}', 'Admin\PartnersController@wishlistDetails');
    Route::get('/web88cms/partners/specialListDetails/{special_id}', 'Admin\PartnersController@specialListDetails');
    Route::get('/web88cms/partners/csv', 'Admin\PartnersController@csv');
    Route::get('/web88cms/partners/{limit}', 'Admin\PartnersController@index');

    /******************************************************************************************************************************/

    /* pwp */
    Route::post('/web88cms/pwp_save', ['uses' => 'Admin\PwpProductsController@postSave', 'as' => 'pwp.save']);
    Route::post('/web88cms/pwp_list', ['uses' => 'Admin\PwpProductsController@postList', 'as' => 'pwp.list']);
    Route::post('/web88cms/pwp_delete', ['uses' => 'Admin\PwpProductsController@postDelete', 'as' => 'pwp.delete']);

//update csv content via ajax
    Route::post('web88cms/shipping_method/update_csv', ['uses' => 'Admin\ShippingMethodsController@updateCsv', 'as' => 'ship.update.csv']);

    // Route::get('/web88cms/shipping/csv_import_list/', 'Admin\ShippingController@csvImportList');
    // Route::any('/web88cms/shipping/addShipping', 'Admin\ShippingController@addShipping');
    // Route::post('/web88cms/shipping/addShipping', 'Admin\ShippingController@addShipping');
    // Route::get('/web88cms/shipping/poslaju_edit/', 'Admin\ShippingController@csvPoslajuEdit');
    // Route::get('/web88cms/shipping/citylink_edit/', 'Admin\ShippingController@csvCitylinkEdit');
    // Route::get('/web88cms/shipping/by_category_list/', 'Admin\ShippingController@byCategoryList');
    // Route::get('/web88cms/shipping/by_weight_list/', 'Admin\ShippingController@byWeightList');
    // Route::get('/web88cms/shipping/by_total_amount_list/', 'Admin\ShippingController@byTotalAmountList');

    /*shipping*/
    Route::post('web88cms/shipping_method/setup', ['uses' => 'Admin\ShippingMethodsController@setup', 'as' => 'ship.setup']);
    Route::post('web88cms/shipping_method/delete', ['uses' => 'Admin\ShippingMethodsController@delete', 'as' => 'ship.delete']);
    Route::get('web88cms/shipping_method/edit_csv/{id}', ['uses' => 'Admin\ShippingMethodsController@editCsv', 'as' => 'ship.edit.csv']);
    Route::post('web88cms/shipping_method/options', ['uses' => 'Admin\ShippingMethodsController@getShippingOptions', 'as' => 'ship.option']);
    Route::get('web88cms/shipping_method/delete_file/{name}', ['uses' => 'Admin\ShippingMethodsController@deleteFile', 'as' => 'ship.delete.file']);
    Route::get('/web88cms/shipping_method/{type?}/{limit?}', ['uses' => 'Admin\ShippingMethodsController@index', 'as' => 'ship.index']);

//billing

//Orders Start
    Route::get('/web88cms/orders/detail/{order_id}',['as'=>'orderDetailsBack','uses'=>'Admin\OrdersController@detail']);
    Route::get('/web88cms/orders/deleteOrder/{customer_id}', 'Admin\OrdersController@deleteOrder');
    Route::post('/web88cms/orders/deleteAllOrder', 'Admin\OrdersController@deleteAllOrder');
    Route::get('/web88cms/orders/invoice/{id}', 'Admin\OrdersController@invoice');
    Route::post('/web88cms/orders/saveShippingAddress/{id}', 'Admin\OrdersController@saveShippingAddress');
    Route::post('/web88cms/orders/saveBillingAddress/{id}', 'Admin\OrdersController@saveBillingAddress');
    Route::post('/web88cms/orders/saveBillingAddress/{id}', 'Admin\OrdersController@saveBillingAddress');
    Route::post('/web88cms/orders/updateOrderStatus/{id}', 'Admin\OrdersController@updateOrderStatus');
    //Route::get('/web88cms/orders/updateOrderStatus/{id}', 'Admin\OrdersController@updateOrderStatus');
    Route::post('/web88cms/orders/updatePaymentStatus/{id}', 'Admin\OrdersController@updatePaymentStatus');
    Route::post('/web88cms/orders/update-assignment-status/{orderId}', 'Admin\OrdersController@updateAssignmentStatus');


    Route::get('/web88cms/orders/shipmentsList/{limit}', 'Admin\OrdersController@shipmentsList');
    Route::get('/web88cms/orders/shipmentsList', 'Admin\OrdersController@shipmentsList');
    Route::get('/web88cms/orders/shipmentDetail/{id}', 'Admin\OrdersController@shipmentDetail');
    Route::post('/web88cms/orders/addNewShipment/{id}', 'Admin\OrdersController@addNewShipment');
    Route::post('/web88cms/orders/editNote/{id}', 'Admin\OrdersController@editNote');
    Route::get('/web88cms/orders/csv', 'Admin\OrdersController@csv');
    Route::get('/web88cms/orders/{limit}', 'Admin\OrdersController@index');
    Route::get('/web88cms/orders', 'Admin\OrdersController@index');
    Route::post('/web88cms/orders/viewPurchasedService', 'Admin\OrdersController@viewPurchasedService');
    Route::get('/web88cms/calendar_view', 'Admin\OrdersController@calendarView');
    Route::get('/web88cms/get-available-rooms','Admin\OrdersController@getAvailableRooms');
    Route::post('/web88cms/placeorder','Admin\OrdersController@placeOrder');

    Route::get('/web88cms/get_order', 'Admin\OrdersController@getOrder');
//Orders End

//promocodes Start
    Route::get('/web88cms/promocodes/addNew', 'Admin\PromocodesController@addNew');
    Route::post('/web88cms/promocodes/addNew', 'Admin\PromocodesController@addNew');
    Route::post('/web88cms/promocodes/deleteAllPromocode', 'Admin\PromocodesController@deleteAllPromocode');
    Route::get('/web88cms/promocodes/delete/{id}', 'Admin\PromocodesController@delete');
    Route::get('/web88cms/promocodes/{limit}', 'Admin\PromocodesController@index');
    Route::get('/web88cms/promocodes', 'Admin\PromocodesController@index');
    Route::get('/web88cms/promocodes/editPromoCode/{id}', 'Admin\PromocodesController@editPromoCode');
    Route::post('/web88cms/promocodes/editPromoCode/{id}', 'Admin\PromocodesController@editPromoCode');
    Route::post('/web88cms/promocodes/addPromoCodeCategory/{id}', 'Admin\PromocodesController@addPromoCodeCategory');
    Route::post('/web88cms/promocodes/addPromoCodeProduct/{id}', 'Admin\PromocodesController@addPromoCodeProduct');
    Route::post('/web88cms/promocodes/deletePromocodeToCategory/{id}', 'Admin\PromocodesController@deletePromocodeToCategory');
    Route::get('/web88cms/promocodes/deletePromocodeToCategory/{id}', 'Admin\PromocodesController@deletePromocodeToCategory');
    Route::post('/web88cms/promocodes/deletePromocodeToProduct/{id}', 'Admin\PromocodesController@deletePromocodeToProduct');
    Route::get('/web88cms/promocodes/deletePromocodeToProduct/{id}', 'Admin\PromocodesController@deletePromocodeToProduct');
//Orders End

// brands
    Route::get('/web88cms/brands/list/', 'Admin\BrandsController@listBrands');
    Route::post('/web88cms/brands/addBrand/', 'Admin\BrandsController@addBrand');
    Route::post('/web88cms/brands/updateBrand/', 'Admin\BrandsController@updateBrand');
    Route::post('/web88cms/brands/deleteBrands/', 'Admin\BrandsController@deleteBrands');

// colors
    Route::get('/web88cms/colors/list/', 'Admin\ColorsController@listColors');
    Route::get('/web88cms/colors/addColor/', 'Admin\ColorsController@addColor');
    Route::post('/web88cms/colors/addColor/', 'Admin\ColorsController@addColor');
    Route::get('/web88cms/colors/updateColor/{color_id}', 'Admin\ColorsController@updateColor');
    Route::post('/web88cms/colors/updateColor/{color_id}', 'Admin\ColorsController@updateColor');
    Route::post('/web88cms/colors/deleteColors/', 'Admin\ColorsController@deleteColors');

//property
    Route::get('/web88cms/property', 'Admin\PropertiesController@index');
    Route::post('/web88cms/property/manageproperty', 'Admin\PropertiesController@manageproperty');
    Route::post('/web88cms/property/deleteproperties/', 'Admin\PropertiesController@deleteproperties');


//    Drop of list
    Route::get('/web88cms/drop-off-list', 'Admin\DropOffController@indexDropOff');
    Route::post('/web88cms/store-drop-off-list', 'Admin\DropOffController@storeDropOff');
    Route::post('/web88cms/delete-drop-off-list', 'Admin\DropOffController@deleteDropOff');


// products
    Route::get('/web88cms/products/addProduct/', 'Admin\ProductsController@addProduct');
    Route::post('/web88cms/products/addProduct/', 'Admin\ProductsController@addProduct');
    Route::post('/web88cms/products/editProduct/amenities/{product_id}', 'Admin\ProductsController@editProductAmenities');
    Route::get('/web88cms/products/editProduct/{product_id}', 'Admin\ProductsController@editProduct');
    Route::post('/web88cms/products/editProduct/{product_id}', 'Admin\ProductsController@editProduct');
    Route::get('/web88cms/products/deleteImage/{type}/{product_id}', 'Admin\ProductsController@deleteImage');
    Route::post('/web88cms/products/updateShippingInfo/{product_id}', 'Admin\ProductsController@updateShippingInfo');
    Route::get('/web88cms/products/list/', 'Admin\ProductsController@listProducts');
    Route::get('/web88cms/products/advanced_room_setup/', 'Admin\ProductsController@advancedRoomSetup');
    Route::post('/web88cms/products/advanced_room_setup/', 'Admin\ProductsController@advancedRoomSetup');
    Route::post('/web88cms/products/updateDescription/{product_id}', 'Admin\ProductsController@updateDescription');
    Route::post('/web88cms/products/updateTermsConditions/{product_id}', 'Admin\ProductsController@updateTermsConditions');
    Route::post('/web88cms/products/updateCancellationPolicy/{product_id}', 'Admin\ProductsController@updateCancellationPolicy');
    Route::post('/web88cms/products/updateFeaturedVideo/{product_id}', 'Admin\ProductsController@updateFeaturedVideo');
    Route::post('/web88cms/products/updateWarrantyAndSupport/{product_id}', 'Admin\ProductsController@updateWarrantyAndSupport');
    Route::post('/web88cms/products/updateReturnPolicy/{product_id}', 'Admin\ProductsController@updateReturnPolicy');
    Route::post('/web88cms/products/addImages/{product_id}', 'Admin\ProductsController@addImages');
    Route::get('/web88cms/products/deleteAdditionalImage/{image_id}/{product_id}', 'Admin\ProductsController@deleteAdditionalImage');
    Route::post('/web88cms/products/deleteProducts/', 'Admin\ProductsController@deleteProducts');
    Route::post('/web88cms/products/addQuantityDiscount/', 'Admin\ProductsController@addQuantityDiscount');
    Route::post('/web88cms/products/updateQuantityDiscount/', 'Admin\ProductsController@updateQuantityDiscount');
    Route::post('/web88cms/products/deleteQuantityDiscount', 'Admin\ProductsController@deleteQuantityDiscount');
    //update order.
    Route::post('/web88cms/products/updateorder', 'Admin\ProductsController@update_display_order_all');

// filters
    Route::any('/web88cms/filters/list/', 'Admin\FiltersController@listFilters');

// promotions
    Route::get('web88cms/promotions_list', function () {

        $promotions = DB::table('promotions')->get();
        $header = DB::table('page_header')->where('page','promotions')->get();
        return view::make('admin.promotions.promotions_list')->with(['promotions'=> $promotions,'header'=>$header]);
    });

    Route::post('/web88cms/headerUpdate','Admin\AdminController@headerUpdate');

    Route::get('/web88cms/check_ota_rate', 'Admin\AdminController@check_ota_rate');
    Route::post('/web88cms/check_ota_rate', 'Admin\AdminController@check_ota_rate');

    Route::post('/web88cms/promotions_list', 'Admin\PromotionsController@promotions_list');
    Route::post('/web88cms/promotions_list_all_del', 'Admin\PromotionsController@promotions_list_all_del');

    Route::post('/web88cms/promotions_list_del', 'Admin\PromotionsController@promotions_list_del');

    Route::post('/web88cms/promotion_selected_all_del', 'Admin\PromotionsController@promotion_selected_all_del');
    Route::post('/web88cms/editpromotions_list', 'Admin\PromotionsController@editpromotions_list');

    Route::post('/web88cms/promotion_del_img', 'Admin\PromotionsController@promotion_del_img');
    Route::post('/web88cms/promotion_large_img', 'Admin\PromotionsController@promotion_large_img');

    Route::get('/view-clear', function() {
        $exitCode = Artisan::call('view:clear');
        return '<h1>View cache cleared</h1>';
    });
    Route::get('/web88cms/promotions/globalDiscounts/', 'Admin\PromotionsController@listGlobalDiscounts');
    Route::post('/web88cms/promotions/categoryProducts/', 'Admin\ProductsController@categoryProducts');
    Route::post('/web88cms/promotions/addGlobalDiscount/', 'Admin\PromotionsController@addGlobalDiscount');
    Route::post('/web88cms/promotions/deleteGlobalDiscounts/', 'Admin\PromotionsController@deleteGlobalDiscounts');
    Route::post('/web88cms/promotions/updateGlobalDiscount/', 'Admin\PromotionsController@updateGlobalDiscount');

// GST Rate start
    Route::post('/web88cms/taxrates/newGstrate', 'Admin\GstratesController@newGstrate');
    Route::get('/web88cms/taxrates/delete/{gstrate_id}', 'Admin\GstratesController@delete');
    Route::post('/web88cms/taxrates/deleteAllGstrate', 'Admin\GstratesController@deleteAllGstrate');
    Route::post('/web88cms/taxrates/editGstrate/{administrator_id}', 'Admin\GstratesController@editGstrate');
    Route::get('/web88cms/taxrates/{limit}', 'Admin\GstratesController@index');
    Route::get('/web88cms/taxrates/', 'Admin\GstratesController@index');

    //Notifications
    Route::get('/web88cms/notifications/{notification}/edit', 'Admin\NotificationController@edit');
    Route::get('/web88cms/notifications/', 'Admin\NotificationController@index');
    Route::get('/web88cms/notification_settings_new/', 'Admin\NotificationController@notification_settings_new');

    //Settings
    Route::get('/web88cms/settings', 'Admin\SettingsController@index');
    Route::post('/web88cms/settings', 'Admin\SettingsController@saveSettings');
    Route::get('/web88cms/sold_rooms_per_available', 'Admin\SettingsController@soldRoomsPerAvailable');
    Route::post('/web88cms/sold_rooms_per_available', 'Admin\SettingsController@saveSoldRoomsPerAvailable');


    Route::post('/web88cms/notification_settings_new/', 'Admin\NotificationController@notificationSettingsNewPost');
    Route::get('/web88cms/notification_settings_new/details/{id}', 'Admin\NotificationController@getDetails');
    Route::post('/web88cms/notification_settings_new/delete/{type}', 'Admin\NotificationController@removeRow');
    Route::get('/web88cms/notification_settings_new/csv', 'Admin\NotificationController@csv');
    Route::get('/web88cms/room_sales_list/csv/{year}/{month}/{room_type}', 'Admin\NotificationController@csv2');
    //End Notifications
// GST Rate End
// set records per page
    Route::get('/web88cms/products/setPerPage/{per_page}/{session_key}/{redirect_to}/{query_string}', 'Admin\ProductsController@setPerPage');

// search site
    Route::post('/web88cms/searchSite/', 'Admin\AdministratorsController@searchSite');

//Notify users
    Route::post('/web88cms/notify/deleteAllNotify', 'Admin\NotifyController@deleteAllNotify');
    Route::get('/web88cms/notify/csv', 'Admin\NotifyController@csv');
    Route::get('/web88cms/notify', 'Admin\NotifyController@index');
    Route::get('/web88cms/notify/{limit}', 'Admin\NotifyController@index');

// front products
    Route::get('/products/{category_id}/{sort}', 'Front\ProductsController@listProducts');
    Route::get('/viewType/{view_type}', 'Front\ProductsController@viewType');
    Route::get('/products/setPerPage/{per_page}/{session_key}/{redirect_to}/{query_string}', 'Front\ProductsController@setPerPage');
    Route::get('/search/{sort}', 'Front\ProductsController@search');
    Route::get('/saveSearchTerm', 'Front\ProductsController@saveSearchTerm');

// wishlist
    Route::post('/addToWishlist', 'Front\WishlistsController@addToWishlist'); // add product to wishlist
    Route::get('/wishlist', 'Front\WishlistsController@wishlist'); // list wishlist
    Route::any('/getProductColors/{product_id}', 'Front\WishlistsController@getProductColors');
    Route::post('/addWishlist', 'Front\WishlistsController@addWishlist'); // add list
    Route::post('/deleteWishlist', 'Front\WishlistsController@deleteWishlist'); // delete wishlist
    Route::get('/wishlistDetails/{wishlist_id}', 'Front\WishlistsController@wishlistDetails'); // list wishlist
    Route::post('/renameWishlist', 'Front\WishlistsController@renameWishlist'); // delete wishlist
    Route::post('/deleteWishlistItem', 'Front\WishlistsController@deleteWishlistItem'); // delete wishlist Item/Product
    Route::post('/moveToWishlist', 'Front\WishlistsController@moveToWishlist'); // move product to wishlist
    Route::post('/updateWishlistItemsPriority', 'Front\WishlistsController@updateWishlistItemsPriority'); // update wishlist item priority
    Route::post('/wishlist/share', 'Front\WishlistsController@shareWishlist'); // share wishlist
    Route::get('/wishlist/view', 'Front\WishlistsController@viewWishlist'); // view share wishlist

// special list
    Route::any('/createEvent', 'Front\Special_listController@createEvent'); // create special list event
    Route::any('/events', 'Front\Special_listController@events'); // list events
    Route::post('/deleteEvent', 'Front\Special_listController@deleteEvent'); // delete Event
    Route::post('/event/share', 'Front\Special_listController@shareEvent'); // share wishlist
    Route::get('/event/view', 'Front\Special_listController@viewEvent'); // view share wishlist
    Route::get('/eventDetails/{event_id}', 'Front\Special_listController@eventDetails'); // event details
    Route::post('/deleteEventItem', 'Front\Special_listController@deleteEventItem'); // delete special list Item/Product
    Route::any('/loginRequired', 'Front\Special_listController@loginRequired'); // loginRequired
    Route::any('/editEvent/{event_id}', 'Front\Special_listController@editEvent'); // edit event
    Route::post('/event/categoryProducts/', 'Front\ProductsController@categoryProducts');
    Route::post('/event/productColors/', 'Front\ProductsController@productColors');
    Route::post('/event/addGift/', 'Front\Special_listController@addGift');

    Route::any('/token_expired/', 'TokenController@index');

//Product detail
    Route::post('/product/notifyMe', 'Front\ProductController@notifyMe');
    Route::get('/product/{id}', 'Front\ProductController@index');
    Route::get('/product/', 'Front\ProductController@index');

//Cart Start
    Route::get('/cart/', 'Front\CartController@index');
    Route::post('/cart/', 'Front\CartController@index');
    Route::get('/cart/cartHtml/', 'Front\CartController@cartHtml');
    Route::post('/cart/addToCart', 'Front\CartController@addToCart');
    Route::post('/cart/deleteToCart', 'Front\CartController@deleteToCart');
    Route::get('/cart/removeItem/{key}', 'Front\CartController@removeItem');
    Route::post('/cart/addAllToCart', 'Front\CartController@addAllToCart');
    Route::post('/cart/applyCouponCode', 'Front\CartController@applyCouponCode');
    Route::post('/cart/applyEstimateShipping', 'Front\CartController@applyEstimateShipping');
//Cart Ens

//Checkout Start
    Route::get('/clear-cache', function() {
        $exitCode = Artisan::call('cache:clear');
        return '<h1>Cache facade value cleared</h1>';
    });
    //Route::get('/checkout', 'Front\CheckoutController@index');
    Route::post('/checkout', 'Front\CheckoutController@index');
    Route::post('/checkout/getStates', 'Front\CheckoutController@getStates');
    Route::get('/checkout/payment', 'Front\CheckoutController@payment');
    Route::get('/checkout/paymenteGHL', 'Front\CheckoutController@paymenteGHL');
    Route::post('/checkout/merchantCallbackURLeGHL', 'Front\CheckoutController@merchantCallbackURLeGHL');

    Route::get('/checkout/addedorder', 'Front\CheckoutController@addOrderIpay88');
    Route::get('/checkout/addedorderToeGHL', 'Front\CheckoutController@addOrdereGHL');
    Route::get('/billInfo','Front\CheckoutController@creditCard');
    Route::any('checkout/addedorderCC', 'Front\CheckoutController@addOrderCC');
    Route::get('checkout/addedorderbank', 'Front\CheckoutController@addOrderBank');
    Route::get('/checkout/successPayment', 'Front\CheckoutController@successPayment');
    Route::get('/checkout/successPaymenteGHL', 'Front\CheckoutController@successPaymenteGHL');
    Route::post('/checkout/successPayment', 'Front\CheckoutController@successPayment');
    Route::post('/checkout/successPaymenteGHL', 'Front\CheckoutController@successPaymenteGHL');
    //Route::get('/checkout/orderConfirmation', array('as' => 'orderConfirmation', 'uses' => 'Front\CheckoutController@orderConfirmation'));
    Route::get('/checkout/orderConfirmation', array('as' => 'orderConfirmation', 'uses' => 'Front\CheckoutController@orderConfirmation'));
    Route::get('/checkout/orderConfirmation2', array('as' => 'orderConfirmation2', 'uses' => 'Front\CheckoutController@orderConfirmation2'));
    // Route::get('paypal/post', array('as' => 'addmoney.paypal', 'uses' => 'PaypalController@postPaymentWithpaypal'));
    Route::get('/checkout',['as' => 'addmoney.paywithpaypal','uses' => 'Front\CheckoutController@index']);
    Route::post('paypal/post', array('as' => 'addmoney.paypal', 'uses' => 'PaypalController@postPaymentWithpaypal'));
    Route::get('paypal', array('as' => 'payment.status', 'uses' => 'PaypalController@getPaymentStatus'));

    Route::post('/checkout/sendEmail', 'Front\CheckoutController@sendEmail');
//Checkout End

//Compare Start
    Route::get('/compare', 'Front\CompareController@index');
    Route::post('/compare/addToCompare', 'Front\CompareController@addToCompare');
    Route::get('/compare/deleteToCompare/{product_id}', 'Front\CompareController@deleteToCompare');
//Compare End

    Route::get('/web88cms/aboutusEdit', 'Admin\AdminController@aboutusEdit');
    Route::any('/web88cms/aboutusUpdate', 'Admin\AdminController@aboutusUpdate');
    Route::post('/web88cms/aboutusObjective', 'Admin\AdminController@aboutusObjective');
    Route::post('/web88cms/aboutusUpdateObjective', 'Admin\AdminController@aboutusUpdateObjective');
    Route::post('/web88cms/aboutusDeleteObjective', 'Admin\AdminController@aboutusDeleteObjective');

    Route::get('/web88cms/about_us_edit', 'Admin\AdminController@aboutEdit');
    Route::post('/web88cms/about_editor', 'Admin\AdminController@about_editor');

    Route::post('/web88cms/page_images_list', 'Admin\AdminController@page_images_list');
    Route::post('/web88cms/edit_page_images_list', 'Admin\AdminController@edit_page_images_list');
    Route::post('/web88cms/page_images_list_all_del', 'Admin\AdminController@page_images_list_all_del');
    Route::post('/web88cms/page_images_list_del', 'Admin\AdminController@page_images_list_del');
    Route::post('/web88cms/page_images_list_selected_all_del', 'Admin\AdminController@page_images_list_selected_all_del');
    Route::post('/web88cms/page_images_del_img', 'Admin\AdminController@page_images_del_img');




    /*********** Newsletter ******************/
    Route::get('/web88cms/newsletter', 'Admin\NewsletterController@index');
    Route::get('/web88cms/newsletter/{item}/{page}', 'Admin\NewsletterController@index');
    Route::post('/web88cms/newsletter/editSubscriber', 'Admin\NewsletterController@editSubscriber');
    Route::post('/web88cms/newsletter/addSubscriber', 'Admin\NewsletterController@addSubscriber');
    Route::post('/web88cms/newsletter/deleteSubscriber', 'Admin\NewsletterController@deleteSubscriber');
    Route::get('/web88cms/newsletter/deleteAll', 'Admin\NewsletterController@deleteAll');
    Route::get('/web88cms/newsletter/csv', 'Admin\NewsletterController@csv');

    /*********************** User Group ******************/
    Route::get('/web88cms/usergroups', 'Admin\UsergroupController@index');
    Route::get('/web88cms/usergroups/{item}', 'Admin\UsergroupController@index');
    Route::post('/web88cms/usergroups/editUsergroup', 'Admin\UsergroupController@editUsergroup');
    Route::post('/web88cms/usergroups/addUsergroup', 'Admin\UsergroupController@addUsergroup');
    Route::post('/web88cms/usergroups/deleteUsergroup', 'Admin\UsergroupController@deleteUsergroup');
    Route::get('/web88cms/usergroups/deleteAll', 'Admin\UsergroupController@deleteAll');

    /*********************** Banners ******************/
    Route::get('/web88cms/index_banner_top_list', 'Admin\AdminController@bannertop');
//Route::post('/web88cms/index_banner_top_list',  'Admin\BannerController@topbanner');
    Route::post('/web88cms/index_banner_top_list/addtopbanner', 'Admin\BannerController@topbanner');
    Route::post('/web88cms/index_banner_top_list/updateTopBannerdata', 'Admin\BannerController@updateTopBannerdata');
    Route::post('/web88cms/index_banner_top_list/deleteTopBannerdata', 'Admin\BannerController@deleteTopBannerdata');
    Route::post('/web88cms/index_banner_top_list/updatealltopbanner', 'Admin\BannerController@update_display_order_alltopbanner');
    Route::post('/web88cms/index_banner_top_list/deleteTopbanner', 'Admin\BannerController@deletemytopbanner');
    Route::get('/web88cms/index_banner_top_list/deleteAlltopdata', 'Admin\BannerController@deleteAll');
    Route::post('/web88cms/index_banner_top_list/delete_enlarge', 'Admin\BannerController@delete_enlargeimage_topbanner');
    Route::post('/web88cms/index_banner_top_list/delete_pdf', 'Admin\BannerController@delete_pdflink_topbanner');

    Route::post('/web88cms/index_banner_top_list/delete_banner_image', 'Admin\BannerController@delete_banner_image');
    Route::post('/web88cms/index_banner_top_list/delete_selected_banners', 'Admin\BannerController@deleteSelectedBanners');

    Route::get('/web88cms/index_middle_top_list', 'Admin\AdminController@bannermiddletop');

    Route::post('/web88cms/index_middle_top_list/addMiddleTopBanner', 'Admin\BannerController@middletopbanner');
    Route::post('/web88cms/index_middle_top_list/updateMiddleTopBanner', 'Admin\BannerController@updateMiddleTopBanner');
    Route::post('/web88cms/index_middle_top_list/deleteMiddleTopBanner', 'Admin\BannerController@deleteMiddleTopBanner');
    Route::post('/web88cms/index_middle_top_list/deleteselectedmiddleTopbanner', 'Admin\BannerController@deleteselectedmiddletopbanner');
    Route::get('/web88cms/index_middle_top_list/deleteselectedAllmiddletopdata', 'Admin\BannerController@deleteAlltopmiddle');
    Route::post('/web88cms/index_middle_top_list/updateallmiddletopbanner', 'Admin\BannerController@update_display_order_allmiddletopbanner');
    Route::post('/web88cms/index_middle_top_list/delete_enlarge', 'Admin\BannerController@delete_enlargeimage_middletopbanner');
    Route::post('/web88cms/index_middle_top_list/delete_pdf', 'Admin\BannerController@delete_pdflink_middletopbanner');

    Route::get('/web88cms/index_middle_bottom_list', 'Admin\AdminController@bannermiddlebottom');
    Route::post('/web88cms/index_middle_bottom_list/addMiddleBottomBanner', 'Admin\BannerController@middlebottombanner');
    Route::post('/web88cms/index_middle_bottom_list/updateMiddleBottomBanner', 'Admin\BannerController@updateMiddleBottomBanner');
    Route::post('/web88cms/index_middle_bottom_list/deleteMiddleBottomBanner', 'Admin\BannerController@deleteMiddleBottomBanner');
    Route::post('/web88cms/index_middle_bottom_list/deleteselectedmiddlebottombanner', 'Admin\BannerController@deleteselectedmiddlebottombanner');
    Route::get('/web88cms/index_middle_bottom_list/deleteselectedAllmiddlebottomdata', 'Admin\BannerController@deleteAllbottommiddle');
    Route::post('/web88cms/index_middle_bottom_list/updateallmiddlebottombanner', 'Admin\BannerController@update_display_order_allmiddlebottombanner');
    Route::post('/web88cms/index_middle_bottom_list/delete_enlarge', 'Admin\BannerController@delete_enlargeimage_middlebottombanner');
    Route::post('/web88cms/index_middle_bottom_list/delete_pdf', 'Admin\BannerController@delete_pdflink_middlebottombanner');

    // Room Sales
    Route::get('/web88cms/room_sales_list', 'Admin\RoomsController@index');
    Route::get('/web88cms/room_sales_report_graph', 'Admin\RoomsController@room_sales_report_graph');
    Route::get('/web88cms/room_sales_list', 'Admin\RoomsController@room_sales_report');
    Route::post('/web88cms/room_sales_report_ajax', 'Admin\RoomsController@room_sales_report_ajax');
    Route::post('/web88cms/changeProductStatus', 'Admin\RoomsController@changeProductStatus');

//Route::get('/web88cms/left_banner_list', 'Admin\AdminController@leftbanner');
    Route::get('/web88cms/left_banner_list', 'Admin\AdminController@leftbanner');
    Route::post('/web88cms/left_banner_list/addLeftBanner', 'Admin\BannerController@addLeftBanner');
    Route::post('/web88cms/left_banner_list/updateLeftBanner', 'Admin\BannerController@updateLeftBanner');
    Route::post('/web88cms/left_banner_list/deleteLeftBanner', 'Admin\BannerController@deleteLeftBanner');
    Route::post('/web88cms/left_banner_list/deleteselectedleftbanner', 'Admin\BannerController@deleteselectedleftbanner');
    Route::get('/web88cms/left_banner_list/deleteselectedAllleftdata', 'Admin\BannerController@deleteAllleft');
    Route::post('/web88cms/left_banner_list/updateallleftbanner', 'Admin\BannerController@update_display_order_all_left_banner');
    Route::post('/web88cms/left_banner_list/delete_enlarge', 'Admin\BannerController@delete_enlargeimage_leftbanner');
    Route::post('/web88cms/left_banner_list/delete_pdf', 'Admin\BannerController@delete_pdflink_leftbanner');

//Route::get('/web88cms/left_promotion_banner_list', 'Admin\AdminController@leftpromotionbanner');
    Route::get('/web88cms/left_promotion_banner_list', 'Admin\AdminController@leftpromotionbanner');
    Route::post('/web88cms/left_promotion_banner_list/addleftpromotionbanner', 'Admin\BannerController@addleftpromotionbanner');
    Route::post('/web88cms/left_promotion_banner_list/updateleftpromotionbanner', 'Admin\BannerController@updateleftpromotionbanner');
    Route::post('/web88cms/left_promotion_banner_list/deleteleftpromotionbanner', 'Admin\BannerController@deleteleftpromotionbanner');
    Route::post('/web88cms/left_promotion_banner_list/deleteselectedleftpromotionbanner', 'Admin\BannerController@deleteselectedleftpromotionbanner');
    Route::get('/web88cms/left_promotion_banner_list/deleteselectedAllleftpromotiondata', 'Admin\BannerController@deleteAllleftpromotion');
    Route::post('/web88cms/left_promotion_banner_list/updateallleftpromotionbanner', 'Admin\BannerController@update_display_order_all_left_promotion_banner');
    Route::post('/web88cms/left_promotion_banner_list/delete_enlarge', 'Admin\BannerController@delete_enlargeimage_leftpromotionbanner');
    Route::post('/web88cms/left_promotion_banner_list/delete_pdf', 'Admin\BannerController@delete_pdflink_leftpromotionbanner');

//Route::get('/web88cms/product_banner_list', 'Admin\AdminController@productbanner');
    Route::get('/web88cms/product_banner_list', 'Admin\AdminController@productbanner');
    Route::post('/web88cms/product_banner_list/addproductbanner', 'Admin\BannerController@addproductbanner');
    Route::post('/web88cms/product_banner_list/updateproductbanner', 'Admin\BannerController@updateproductbanner');
    Route::post('/web88cms/product_banner_list/deleteproductbanner', 'Admin\BannerController@deleteproductbanner');
    Route::post('/web88cms/product_banner_list/deleteselectedproductbanner', 'Admin\BannerController@deleteselectedproductbanner');
    Route::get('/web88cms/product_banner_list/deleteselectedAllproductdata', 'Admin\BannerController@deleteAllproduct');

    /******************************************************************************************************************************/
    /*promotions*/
    Route::get('web88cms/promotions/globaldiscountslist/', 'Admin\PromotionsController@index');

    /******************************************************************************************************************************/
    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);


    /*Route::get('/admin/login', 'Admin\AdminController@login');
    Route::post('/admin/login', 'Admin\AdminController@login');
    Route::get('/admin/dashboard', 'Admin\AdminController@dashboard');
    Route::get('user/{id}', 'Admin\AdminController@getUserDetails');
    Route::get('albums', 'Admin\AdminController@getAlbums');
    Route::get('checkSession', 'Admin\AdminController@checkSession');*/

//Test
    Route::get('/mailTest', 'WelcomeController@mailTest');

    /******************************************************************************************************************************/
    /* web88 functions */
    Route::get('/about', 'Front\Web88Controller@about');
    Route::get('/vacancy', 'Front\Web88Controller@vacancy');
    Route::post('/vacancy', 'Front\Web88Controller@vacancy');
    Route::get('/services', 'Front\Web88Controller@services');
    Route::get('/stores', 'Front\Web88Controller@stores');
    Route::get('/contact', 'Front\Web88Controller@contact');
    Route::get('/contact/states', ['uses' => 'Front\Web88Controller@getStates', 'as' => 'Contact.states']);
    Route::post('/contact', 'Front\Web88Controller@postFeedback');
    Route::post('admin/addapplicant', 'Front\Web88Controller@Addapplicant');


    //web88 admin Settings
    Route::any('/web88cms/paymentGateWays/update/{id}', 'AdminController@paymentGatewaysUpdate');
    Route::any('web88cms/paymentGateWays', 'AdminController@paymentGateways');
    Route::any('web88cms/header_setup', 'AdminController@Headersetup');
    Route::any('web88cms/footer_setup', 'AdminController@Footersetup');
    // Route::post('admin/addapplicant', 'AdminController@Addapplicant');
    // Route::any('admin/bottom_animated_services_list', 'AdminController@Animatedlist');
    // Route::any('admin/newsletter_subscription_list', 'AdminController@Newsletter');
    // Route::any('admin/forgot_password', 'AdminController@Newpassword');
    // Route::get('admin/forgot/{data}', 'AdminController@Forgot');
    // Route::get('admin/postEditobj', 'AdminController@postEditobj');


// Exit Admin
    Route::get('admin/logout', function () {
        Auth::logout();
        return Redirect::to('admin/login');
    });
// Page login for Admin
    Route::any('admin/login', 'AdminController@login');
// ???????????????????? ????????????
    Route::group(array('before' => 'admin.auth'), function () {
        Route::controller('admin', 'AdminController');
    });
// ???????????? ??????????????
    Route::filter('admin.auth', function () {
        if (Auth::guest()) {
            return Redirect::to('admin/login');
        }
    });


    Route::get('/web88cms/globalsettings', function () {

        $settings = App\Http\Models\GlobalSettings::getSettings('global_open_close');
        $data = array(
            'success' => Session::get('global_settings.success'),
            'warning' => false,
            'setting' => json_decode($settings->value),
            'last_update' => $settings->updated_at,
        );
        Session::set('global_settings.success', '');

        return view('admin.global_settings_open_close', $data);
    });

    Route::get('/web88cms/prdouctglobalsetup', function () {
        $settings = \App\Http\Models\GlobalSettings::getSettings('product_global');
        $updated_at = $settings->updated_at;
        $settings = json_decode($settings->value);

        $data = array(
            'success' => Session::get('product_global_setup.success'),
            'warning' => Session::get('product_global_setup.warning'),
            'old_status' => ($settings->status) ? true : false,
            'last_update' => $updated_at,
        );
        Session::set('product_global_setup.success', '');
        Session::set('product_global_setup.warning', '');

        return view('admin.product_global_setup', $data);
    });

    Route::post('/web88cms/prdouctglobalsetup', 'Admin\ProductsController@importcsv');

    Route::post('/web88cms/globalsettings/save', function () {
        $json = array();
        $validation['password'] = 'required';
        $validator = Validator::make(Request::all(), $validation);

        $settings = array(
            'status' => (Request::get('status')) ? 1 : '0',
            'who' => Request::get('who'),
            'reopendate' => Request::get('reopendate'),
            'message' => Request::get('message'),
            'user_id' => Request::get('user_id'),
        );

        if ($validator->fails()) {

            $json['error'] = $validator->errors()->all();
        } else {
            if (Hash::check(Request::get('password'), Auth::user()->password)) {
                // save to model
                App\Http\Models\GlobalSettings::saveSettings('global_open_close', json_encode($settings));
                Session::put('global_settings.success', 'Setting saved successfully.');
                $json['success'] = 'TRUE';
            } else {
                $json['error'] = array('Password not matched with your password');
            }
        }
        return Response::json($json);
    });
});


Route::get('coming_soon', function () {
    $settings = \App\Http\Models\GlobalSettings::getSettings('global_open_close');

    $settings = json_decode($settings->value);
    $data = array(
        'reopendate' => $settings->reopendate,
        'message' => $settings->message,
    );

    return view('comming_soon', $data);
});
