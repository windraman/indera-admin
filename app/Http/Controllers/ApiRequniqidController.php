<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiRequniqidController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_counter";        
				$this->permalink   = "requniqid";    
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
                if($result["api_status"]==0){
                    $newuid = uniqid();
                    $result["newuid"] = $newuid;
                }else{
                    $result["newuid"] = $postdata["uniqueid"];
                }

		    }
		    

		}