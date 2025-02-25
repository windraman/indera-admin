<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetIotHostController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "maduls";        
				$this->permalink   = "get_iot_host";    
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
                foreach($result['data'] as $data){
                    $grups = DB::Table("grups")
                                ->where("modul_id",$data->id)
                                ->get();
                    
                    $data->grups = $grups;
                    
                }
                
                
		    }

		}