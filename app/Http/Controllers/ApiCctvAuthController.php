<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiCctvAuthController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_auth";        
				$this->permalink   = "cctv_auth";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		        DB::table('cctv_auth')
		                    ->where('name',$postdata['name'])
                            ->update(['req_count'=> DB::raw('req_count+1')]);
		        if($result['api_status']==1){
		            DB::table('cctv_auth')
		                    ->where('name',$postdata['name'])
                            ->update(['success'=> DB::raw('success+1')]);
		            http_response_code(201);
                    
		        }else{
		            DB::table('cctv_auth')
		                    ->where('name',$postdata['name'])
                            ->update(['fail'=> DB::raw('fail+1')]);
		            abort(404,"kamu tidak punya hak !");
		            
		        }

		    }

		}