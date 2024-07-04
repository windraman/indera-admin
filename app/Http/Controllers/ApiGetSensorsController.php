<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetSensorsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "grups";        
				$this->permalink   = "get_sensors";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		         if ($result["api_status"] == 1){
					$sensors = DB::Table('sensors')
						->join('tipe_pin','sensors.tipe_sensor_id','tipe_pin.id')
						->where('sensors.grups_id',$result['id'])
						->select('sensors.*','tipe_pin.tipe_pin','tipe_pin.io','tipe_pin.script_path','tipe_pin.deskripsi')
						->get();
					
					foreach($sensors as $sensor){
						$sensor_value = DB::Table('sensors_value')
							->where('sensor_id',$sensor->id)
							->orderBy('id','desc')
							->first();
							
						$sensor->last_db_value = $sensor_value;
					
						$sensor->actual_pin_value = null;
					}
					
					$result['sensors'] = $sensors;
					$result['jsensor']= sizeof($sensors);

				}
		    }

		}