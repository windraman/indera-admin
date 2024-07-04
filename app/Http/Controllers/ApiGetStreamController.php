<?php namespace App\Http\Controllers;
header("Access-Control-Allow-Origin","*");
header("Access-Control-Allow-Headers","*");

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetStreamController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_auth";        
				$this->permalink   = "get_stream";    
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

	                $iklan = DB::table('cctv_iklan')
	                            ->join('iklan','iklan.id','cctv_iklan.iklan_id')
	                            ->where('start', '<=', date("Y-m-d H:i:s"))
                                ->where('end', '>=', date("Y-m-d H:i:s"))
	                            ->where('cctv_iklan.cctv_auth_id',$result['id'])
	                            ->get();
	                $result['iklan'] = $iklan;

	                if($postdata['uniqueid']){
                        $result['myactivity'] = DB::table('cctv_counter')->join('cctv_auth','cctv_auth.id','cctv_counter.cctv_auth_id')->where('cctv_counter.uniqueid',$postdata['uniqueid'])->where('cctv_counter.cctv_auth_name',$result['name'])->count('cctv_counter.id');
                    }else{
                        $result['myactivity'] = 0;
                    }
                    
                    if($result['internal']==1){
                        unset($data->source);
                    }
                    
                    if($postdata['clicked']==1){
    		            DB::table('cctv_auth')
    		                    ->where('id',$postdata['id'])
                                ->update(['clicked'=> DB::raw('clicked+1')]);
                                
                        // $cctvid = DB::table('cctv_auth')
                        //         ->where('name',$postdata['name'])
                        //         ->first();
                                
                        // DB::table('cctv_counter')->insert(['cctv_auth_id'=>$cctvid->id, 'cctv_auth_name' => $postdata['name'],'uniqueid' => $postdata['uniqueid']]);
    		        }
    		        if($postdata['played']==1){
    		            DB::table('cctv_auth')
    		                    ->where('name',$postdata['id'])
                                ->update(['played'=> DB::raw('played+1'),'clicked'=> DB::raw('clicked+1')]);
                                
                        $cctv = DB::table('cctv_auth')
                                ->where('id',$postdata['id'])
                                ->first();
                        
                        DB::table('cctv_counter')->insert(['cctv_auth_id'=>$postdata['id'], 'cctv_auth_name' => $cctv->name,'uniqueid' => $postdata['uniqueid']]);
    		        }
    		        $result["uniqueid"] = $postdata['uniqueid'];
		        }

		    }

		}