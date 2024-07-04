<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetModulInfoController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "get_modul_info";    
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
		            
		           
					$sensor_value = DB::table('sensors_value')
					                ->join('sensors','sensors_value.sensor_id','sensors.id')
					                ->leftJoin('grups','sensors.grups_id','grups.id')
					                ->leftJoin('moduls','grups.moduls_id','moduls.id')
					                ->leftJoin('tipe_pin','sensors.tipe_sensor_id','tipe_pin.id')
					                ->select('sensors_value.*','sensors.slug','sensors.name','sensors.pin','sensors.tipe_sensor_id','tipe_pin.tipe_pin','tipe_pin.io','grups.name as grup', 'moduls.name as modul')
								 	->where('moduls.slug',$postdata['slug'])
								 	->where('sensors_value.ovrd',$postdata['ovrd'])
								 	->where('sensors_value.is_read',$postdata['is_read'])
								 	->limit($postdata['batas'])
								 	->orderBy('sensors_value.id', 'ASC')
									->get();
									
					$result['sensor_value'] = $sensor_value;
				 	
			    	foreach($sensor_value as $sv){
			    	
						DB::table('sensors_value')
							->where('id',$sv->id)
							->update(
								["is_read" => 1]
							);
					}
					
					
				}
		    }

		}