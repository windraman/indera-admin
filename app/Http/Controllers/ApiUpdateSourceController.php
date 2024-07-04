<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUpdateSourceController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_auth";        
				$this->permalink   = "update_source";    
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
                    if(strlen($postdata['source_to_update']) > 0){
                        $update = DB::table('cctv_auth')->where('scrap_key',$postdata['scrap_key'])->update(['source'=>$postdata['source_to_update']]);
                        $result['updated'] = $update;
                    } 
                    if(strlen($postdata['internal_to_update']) > 0){
                        $update = DB::table('cctv_auth')->where('scrap_key',$postdata['scrap_key'])->update(['internal'=>$postdata['internal_to_update']]);
                        $result['internal_updated'] = $update;
                    }
                }
		    }

		}