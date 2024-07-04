<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiMonitorMyModulsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "monitor_my_moduls";    
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
		        $sensores = [];
                foreach($result['data'] as $data){
                    $grups = DB::table('grups')->where('moduls_id',$data->id)->get();
                    foreach($grups as $grup){
                        $sensors = DB::table('sensors')
                                    ->join('grups','sensors.grups_id','grups.id')
					                ->leftJoin('moduls','grups.moduls_id','moduls.id')
					                ->leftJoin('tipe_pin','sensors.tipe_sensor_id','tipe_pin.id')
					                ->select('sensors.*','tipe_pin.tipe_pin','tipe_pin.io','grups.name as grup', 'moduls.name as modul','moduls.lokasi','moduls.latitude','moduls.longitude')
                                    ->where('grups_id',$grup->id)
                                    ->get();
        
                        foreach($sensors as $sensor){
                            $values = DB::table('sensors_value')->where('sensor_id',$sensor->id)->orderBy('id','DESC')->limit($postdata['batas'])->get();
                            $sensor->values = $values;
                            array_push($sensores,$sensor);
                            
                        }
                    }
                }
                
                //$result['data'] = '';
                $result['data'] = $sensores;
                
		    }

		}