<?php namespace App\Http\Controllers;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDetailCctvController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_auth";        
				$this->permalink   = "detail_cctv";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        if($result['api_status']==1){
		          //  $names = explode("-",$result['name']);
	           //     if(sizeof($names)==1){
		                $iklan = DB::table('cctv_iklan')
		                            ->join('iklan','iklan.id','cctv_iklan.iklan_id')
		                            ->where('start', '<=', date("Y-m-d H:i:s"))
                                    ->where('end', '>=', date("Y-m-d H:i:s"))
		                            ->where('cctv_iklan.cctv_auth_id',$result['id'])
		                            ->get();
		                $result['iklan'] = $iklan;
	                //}
	              // $result['sekarang'] = date("Y-m-d H:i:s");
	                if($postdata['uniqueid']){
                        $result['myactivity'] = DB::table('cctv_counter')->join('cctv_auth','cctv_auth.id','cctv_counter.cctv_auth_id')->where('cctv_counter.uniqueid',$postdata['uniqueid'])->where('cctv_counter.cctv_auth_name',$result['name'])->count('cctv_counter.id');
                    }else{
                        $result['myactivity'] = 0;
                    }
                    
                    if($result['internal']==1){
                        unset($data->source);
                    }
		        }
		    }

		}