<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiRegisterdoneController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "stb";        
				$this->permalink   = "registerdone";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                
		    }

		    public function hook_query(&$query) {
		        $query->where('id',0);
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
                
                    $stb = DB::table('stb')->where('id',$postdata['id'])->first();
                    if($stb){
                        $result  = json_decode(json_encode($stb), true);
                        $result['api_status'] = 1;
                        DB::table('stb')->where('id',$postdata['id'])->delete();
                        $result['api_massage'] = "Data " . $stb->id . "  registered";
                    }else{
                        $result['api_status'] = 0;
                        $result['api_massage'] = "Data not found";
                    }
		    }

		}