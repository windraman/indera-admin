<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetSensorValueController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "sensors";        
				$this->permalink   = "get_sensor_value";    
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
					$tipe_pin = DB::table('tipe_pin')
							->where('id',$result['tipe_sensor_id'])
							->first();
					$result['tipe_pin'] = $tipe_pin->tipe_pin;
					$result['io'] = $tipe_pin->io;
					$result['script_path'] = $tipe_pin->script_path;
				// 	$result['get_script'] = $tipe_pin->get_script;
				// 	$result['set_script'] = $tipe_pin->set_script;
					if($postdata['batas']==1){
						$sensor_value = DB::table('sensors_value')
									->where('sensor_id',$result['id'])
									->orderBy('id', 'DESC')
									->limit($postdata['batas'])
									->first();
									
						$result['sensor_value'] = $sensor_value->nilai;
						$result['updated_at'] = $sensor_value->updated_at;
						$result['sensor_values'] = $sensor_value;
					}else{
						$sensor_value = DB::table('sensors_value')
										->where('sensor_id',$result['id'])
										->orderBy('id', 'DESC')
										->limit($postdata['batas'])
										->get();
						$result['sensor_values'] = $sensor_value;
					}
									
					
					$result['actual_pin_value'] = null;
				}
		    }

		}