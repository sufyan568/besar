<?php

class ContactController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Contact Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/       
	       
        
        public function contactusFront()
        {

          $page = Page::where('type', '=', 'contactus')->first();

           $tagLine = "Oil Palm Plantation Investment Holdings";
           $mainMenuTitle = "Contact Us";
           $metaTitle = $mainMenuTitle;
           $contactFrame = '<iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3980.957579963727!2d103.32426521475924!3d3.8192358972216884!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sLevel+23%2C+Menara+Zenith%2C+Jalan+Putra+Square+6%2C+!5e0!3m2!1sen!2smy!4v1529503747992" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>';
           $masthead = "";
           $pageTitle = "<h1>Contact Us</h1>";
           $categories_arr = array('' => 'Enquiry Related to');
           $subcategories_arr = array('' => 'Sub Category');
           $cats = Enquirycategory::where('parent_id', '=', 0)->where('active', 1)->lists('title', 'id');
           foreach ($cats as $key => $val) {
               $categories_arr[$key] = $val;
           }
           $breadcrumbs = array(
                                      0 => array 
                                                (
                                                   "title" => "Home",
                                                   "url" => ""
                                                ),
                                     1 => array 
                                                (
                                                   "title" => "Contact Us",
                                                   "url" => ""
                                                )
                                   
                                   
                                    );
               
                return View::make('frontcontactus',array(
                                                       'mainMenuTitle' => $mainMenuTitle,
                                                       'contactFrame' => $contactFrame,
                                                       'pageTitle' => $pageTitle ,
                                                       'breadcrumbs' => $breadcrumbs,
                                                       'tagLine' => $tagLine,
                                                       'page' => $page,
                                                       'categories_arr' => $categories_arr,
                                                       'subcategories_arr' => $subcategories_arr,
                                                     ));
        }
        
        public function contactsubmit()
        {
        $name = Input::get('name');
        $email = Input::get('email');
        $contact_number = Input::get('contact_number');
        $company_name = Input::get('company_name');
        $company_address = Input::get('company_address');
        $city = Input::get('city');
        $state = Input::get('state');
        $post_code = Input::get('post_code');
        $country = Input::get('country');
        $cat_id = Input::get('cat_id');
        $subcat_id = Input::get('subcat_id');
        $subject = Input::get('subject');
        $questions_comments = Input::get('questions_comments');


        $category = Enquirycategory::where('id', '=', $cat_id)->first();
        $category_name = $category->title;
        $subcategory_name = '';
        if (!empty($subcat_id)) {
            $subcategory = Enquirycategory::where('id', '=', $subcat_id)->first();
            $subcategory_name = $subcategory->title;
        } else {
            $subcat_id = 0;
        }
        $matchThese = ['cat_id' => $cat_id, 'subcat_id' => $subcat_id];
        $enquiry_email = Enquiryemail::where($matchThese)->get();
        $email_arr = array();
        foreach($enquiry_email as $em)
        {
            array_push($email_arr,$em->email);
        }    

        if (!empty($enquiry_email) && count($enquiry_email)>0){
            $email_sent_arr = $email_arr;
        }else{
            $email_arr = array('support@webqom.com');
            $email_sent_arr = $email_arr;
        }
        if (!empty($enquiry_email) || empty($enquiry_email)) {
            $emailcontent = array(
                'name' => $name,
                'email' => $email,
                'contact_number' => $contact_number,
                'company_name' => $company_name,
                'company_address' => $company_address,
                'city' => $city,
                'state' => $state,
                'post_code' => $post_code,
                'country' => $country,
                'category_name' => $category_name,
                'subcategory_name' => $subcategory_name,
                'subject' => $subject,
                'questions_comments' => $questions_comments
            );
            //fareast@fareh.po.my
            Mail::send('email', $emailcontent, function($message) use ($email_sent_arr) {
                $message->to($email_sent_arr, 'Special Enquiry') 
                        ->subject($_ENV['MAIL_SUBJECT']);
                $message->from($_ENV['MAIL_FROM_ADDRESS_BURSA'],$_ENV['MAIL_FROM_NAME_BURSA']);
            });
        }
        $feedbacks = Feedback::create(Input::all());
        if ($feedbacks) {
            return Redirect::back()->with('message', '<br><br><br><div class="alert alert-success nomargin"><i class="icon-flag"></i>Success! Thank you for submitting the feedback.</div>');
        } else {
            return Redirect::back()->with('message', '<div class="alert alert-error">
								<i class="icon-warning-sign"></i>
								Error! Please correct the errors in the form below.
							</div>
                            ');
        }
 }          
 public function addToFeedbackList(){
             $rules = array(
                        'g-recaptcha-response' => 'required',
		);
            $validator = Validator::make(Input::all(), $rules);
                
                
                

		// process the login
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator->errors())->withInput();
                }
        $input = Input::all();
        $feedback = Feedback::create($input);

        if ($feedback) {
            return Redirect::back()->with('message', '<div class="alert alert-success alert-dismissable">
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">??</button>
                <i class="fa fa-check-circle"></i> <strong>Success!</strong>
                <p>The submission is successful. Thank you!.</p>
              </div><script>$(document).ready(function(){ $("html,body").animate({ scrollTop: 600 }, "slow");});</script>');
        } else {
            return Redirect::back()->with('message', '<div class="alert alert-error alert-dismissable">
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">??</button>
                <i class="fa fa-check-circle"></i> <strong>Fail!</strong>
                <p>Something happened try again.</p>
              </div><script>$(document).ready(function(){ $("html,body").animate({ scrollTop:  500 }, "slow");});</script>');
        }



        dd($input);
    }
    public function adminFeedback(){
        $page = Page::where('type', '=', 'contactus')->where('published', '=', 1)->get();

        /* Paginate Count Section */
        $pgCount = Feedback::count();
        for ($i = 1; $i <= $pgCount; $i++) {
            if ($i == 1) {
                $cntArr[10] = 10;
            }

            if ($i == 11) {
                $cntArr[20] = 20;
            }

            if ($i == 21) {
                $cntArr[30] = 30;
            }

            if ($i == 31) {
                $cntArr[50] = 50;
            }

            if ($i == 51) {
                $cntArr[100] = 100;
                //exit;
            }
        }
        if (Input::get('noperpage1')) {
            $noperpage = Input::get('noperpage1');
        } else {

            $noperpage = 100;
        }
        /* End of Paginate Count Section */

        if ($pgCount > 0) {
            $cntarray1 = $cntArr;
        } else {
            $cntarray1 = 0;
        }

        $feedbacks = Feedback::orderBy('id', 'DESC')->paginate($noperpage);
        return View::make('admin.feedback_new', array('page' => $page, 'feedbacks' => $feedbacks, 'cntarray1' => $cntarray1));
    }
    public function adminDeleteFeedback() {
        $id = Input::get('feedbackid');
        $pressrelease = Feedback::findOrFail($id);
        $pressrelease->delete();
        if ($pressrelease) {
            return Redirect::back()->with('message', '<div class="alert alert-success alert-dismissable">
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">??</button>
                <i class="fa fa-check-circle"></i> <strong>Success!</strong>
                <p>Deleted Successfully.</p>
              </div>');
        } else {
            return Redirect::back()->with('message', '<div class="alert alert-error alert-dismissable">
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">??</button>
                <i class="fa fa-check-circle"></i> <strong>failed!</strong>
                <p>Something happened try again.</p>
              </div>');
        }
        // $banners = Banner::paginate(2);
        // $corebusinesses = Corebusiness::paginate(2);
        // return View::make('adminindex', array ( 'banners' => $banners, 'corebusinesses' => $corebusinesses ));
    }

    public function adminDeleteAllFeedback() {
        $pressrelease = Feedback::truncate();
        if ($pressrelease) {
            return Redirect::back()->with('message', '<div class="alert alert-success alert-dismissable">
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">??</button>
                <i class="fa fa-check-circle"></i> <strong>Success!</strong>
                <p>All Records Deleted Successfully.</p>
              </div>');
        } else {
            return Redirect::back()->with('message', '<div class="alert alert-error alert-dismissable">
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">??</button>
                <i class="fa fa-check-circle"></i> <strong>failed!</strong>
                <p>Something happened try again.</p>
              </div>');
        }
    }
    public function getSubcategory($id) {
        $sub_categories = Enquirycategory::where('parent_id', $id)->lists('title', 'id');
        ?>
        <option value=''>--Please Select--</option>
        <?php
        foreach ($sub_categories as $key => $val) {
            ?>
            <option value='<?php echo $key; ?>'><?php echo $val ?></option>
            <?php
        }
    }
  public function regionaloffices() {
            
                $pageTitle = "Regional Offices";
                $masthead = url().'/images/banner_subpage/banner13.jpg';
                $breadcrumbs = array(
                                    0 => array 
                                                (
                                                   "title" => "Home",
                                                   "url" => ""
                                                ),
                                     1 => array 
                                                (
                                                   "title" => "Contact Us",
                                                   "url" => "http://bursa.fareastholdingsbhd.com/contactus"
                                                ),
                                    2 => array 
                                                (
                                                   "title" => "Regional Offices",
                                                   "url" => ""
                                                )
                                   
                                    );
                $tagLine = "Oil Palm Plantation Investment Holdings";
                $mainMenuTitle = $pageTitle;
                $metaTitle = $mainMenuTitle;
		       
                   return View::make('frontregionaloffices',array(
                                                   'pageTitle' => $pageTitle,
                                                   'masthead' => $masthead,
                                                   'breadcrumbs' => $breadcrumbs,
                                                   'tagLine' => $tagLine,                                       
                                                   'metaTitle' => $metaTitle,
                                                   
												   
												  
												     )
                                 );
             
        }
}

