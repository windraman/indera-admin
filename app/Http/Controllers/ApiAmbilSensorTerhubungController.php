<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiAmbilSensorTerhubungController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "sensors";        
				$this->permalink   = "ambil_sensor_terhubung";    
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
                    foreach($result['data'] as $data){
                        $value = DB::table('sensors_value')->where('sensor_id',$data->id)->orderBy('id','DESC')->first();
                        $data->value = $value->nilai . " " . $data->satuan;
                    }
                }
		    }

		}