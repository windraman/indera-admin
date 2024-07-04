<?php namespace App\Http\Controllers;


		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetnewstbController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "stb";        
				$this->permalink   = "getnewstb";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
				$stb = DB::table('stb')->where('remoteip',$postdata['remoteip'])->whereRaw('TIME_TO_SEC(TIMEDIFF(NOW(),updated_at)) < 4')->count();
				if($stb==0){
					$data = [];
					$resp = response()->json(['api_status'=>1,'api_message'=>'Tidak ditemukan !','data'=>$data]);
					$resp->send();
					exit;
				}
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
                if($result['api_status']==1){
                    $stb = DB::table('stb')->where('remoteip',$postdata['remoteip'])->whereRaw('TIME_TO_SEC(TIMEDIFF(NOW(),updated_at)) < 4')->get();
                    $result  = json_decode(json_encode($pelanggan), true);
                    $result['api_status'] = 1;
                    $result['data'] = $stb;
                }

		    }

		}