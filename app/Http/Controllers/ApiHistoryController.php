<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiHistoryController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_history";        
				$this->permalink   = "history";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                $cctv = DB::table('cctv_auth')->where('name',$postdata['cctv_auth_name'])->first();
                if($cctv){
                    $postdata['cctv_auth_id'] = $cctv->id;
                }
                
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
                $result['id'] = $postdata['cctv_auth_id'];
		    }

		}