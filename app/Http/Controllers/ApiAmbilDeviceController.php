<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiAmbilDeviceController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_devices";        
				$this->permalink   = "ambil_device";    
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
                if($result["api_status"]==1){
                     $cctv = DB::table('cctv_auth')->where('cctv_device_id',$result['id'])->get();
                     foreach($cctv as $data){
		                $names = explode("-",$data->name);
		                //if(sizeof($names)==1){
		                  if($postdata['gen']==1){
		                      
    		              //      $gentoken = $this->generateRandomString();
    		              //      DB::table('cctv_auth')->where('id',$data->id)->update(['token'=>$gentoken]);
    		              //      $data->token = $gentoken;
		                  }else{
		                      if($result['internal']==1){
    		                      unset($data->source);  
		                      }
		                      unset($data->token);
		                  }
		                  $counterexist = DB::table('cctv_counter')->where('cctv_auth_id',$data->id)->first();
		                  if($counterexist){
		                    $data->harian = DB::table('cctv_counter')->where('cctv_auth_id',$data->id)->whereRaw('DATE(updated_at) = DATE(now())')->count('id');
		                  }else{
		                      $data->harian = 0;
		                  }
		                  $iklan = DB::table('cctv_iklan')
		                            ->join('iklan','iklan.id','cctv_iklan.iklan_id')
		                            ->where('cctv_iklan.start', '<=', date("Y-m-d H:i:s"))
                                    ->where('cctv_iklan.end', '>=', date("Y-m-d H:i:s"))
		                            ->where('cctv_iklan.cctv_auth_id',$data->id)
		                            ->get();
		                  $data->iklan = $iklan;
    		                
		              //  }else{
		              //    //  if(($key = array_search($data->id, $result['data'])) !== false) {
                //     //             unset($result['data'][$key]);
                //     //         }
		              //    //  $data->akey = key($data);
		              //    //  unset($data);
		              //  }
		            }
                    $result['data'] = $cctv;
                }
		    }

		}