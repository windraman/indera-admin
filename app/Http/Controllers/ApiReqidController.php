<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiReqidController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_settings";        
				$this->permalink   = "reqid";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
                if($result['api_status']==1){
                    if($postdata['email']){
                        $exist = DB::table('cms_users')
                                ->where('email',$postdata['email'])
                                ->first();
                                
                        if($exist){
                             $result['user'] = $exist;
                        }else{
                            $lastuser = DB::table('cms_users')->latest('id')->first();
                            
                            $newid = DB::table('cms_users')
                                        ->insertGetId(['name'=>'indera_user','email' => $postdata['email'], 'id_cms_privileges' => 2,'password'=>'123456']);
                                        
                            $newuser = DB::table('cms_users')
                                    ->where('id',$newid)
                                    ->first();
                                        
                            $result['user'] =$newuser;
                        }
                    }else{
                        $lastuser = DB::table('cms_users')->latest('id')->first();
                        
                        $newid = DB::table('cms_users')
                                        ->insertGetId(['name'=>'indera_user' ,'email' =>'tamu_'.$lastuser->id.'@wahyu.top', 'id_cms_privileges' => 2,'password'=>'123456']);
                                        
                        $newuser = DB::table('cms_users')
                                ->where('id',$newid)
                                ->first();

                                    
                        $result['user'] =$newuser;
                    }
                }
		    }

		}