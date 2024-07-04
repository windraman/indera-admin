<?php namespace App\Http\Controllers;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiAksesController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_auth";        
				$this->permalink   = "akses";    
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
		        if($postdata['clicked']==1){
		            DB::table('cctv_auth')
		                    ->where('name',$postdata['name'])
                            ->update(['clicked'=> DB::raw('clicked+1')]);
                            
                    // $cctvid = DB::table('cctv_auth')
                    //         ->where('name',$postdata['name'])
                    //         ->first();
                            
                    // DB::table('cctv_counter')->insert(['cctv_auth_id'=>$cctvid->id, 'cctv_auth_name' => $postdata['name'],'uniqueid' => $postdata['uniqueid']]);
		        }
		        if($postdata['played']==1){
		            DB::table('cctv_auth')
		                    ->where('name',$postdata['name'])
                            ->update(['played'=> DB::raw('played+1'),'clicked'=> DB::raw('clicked+1')]);
                            
                    $cctvid = DB::table('cctv_auth')
                            ->where('name',$postdata['name'])
                            ->first();
                    
                    DB::table('cctv_counter')->insert(['cctv_auth_id'=>$cctvid->id, 'cctv_auth_name' => $postdata['name'],'uniqueid' => $postdata['uniqueid']]);
		        }
		        $result["uniqueid"] = $postdata['uniqueid'];

		    }

		}