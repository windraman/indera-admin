<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiMyModulController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "my_modul";    
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
                    $moduls = DB::table('moduls')
                            ->where('owner', $postdata['id'])
                            ->get();
                            
                   // $result['moduls'] = $moduls;
                   if(!$moduls->isEmpty()){
                        foreach($moduls as $modul){
                            $newslug = $modul->slug;
                            $jsensor = 0;
    						$grups = DB::Table('grups')
    								->where('moduls_id',$modul->id)
    								->get();
    								
    						$modul->grups = $grups;
    						if($grups){
    							foreach($grups as $g){
    							    
    							    //$sensors = DB::raw('SELECT sensors.id, tipe_pin.io FROM `sensors` LEFT JOIN tipe_pin ON sensors.tipe_sensor_id = tipe_pin.id WHERE sensors.grups_id = ' . $g->id);
    								$sensors = DB::Table('sensors')
    									->join('tipe_pin','sensors.tipe_sensor_id','tipe_pin.id')
    									->where('sensors.grups_id',$g->id)
    									->select('sensors.*','tipe_pin.tipe_pin','tipe_pin.io','tipe_pin.script_path','tipe_pin.deskripsi')
    									->get();
    								$g->sensors = $sensors;
    								$g->jsensor = sizeof($sensors);
    								$jsensor = $jsensor +  sizeof($sensors);
    								
    								foreach($sensors as $sensor){
    									$sensor_value = DB::Table('sensors_value')
    										->where('sensor_id',$sensor->id)
    										->orderBy('id','desc')
    										->first();
    										
    									$sensor->last_db_value = $sensor_value;
    									$sensor->actual_pin_value = null;
    								}
    								
    							}
    							
    						}
    						
    						$modul->jgrup = sizeof($grups);
    						$modul->jsensor = $jsensor;
                        }
                        $result['moduls'] = $moduls;
                        
                        if($result['id_cms_privileges'] != 2){
                            if($postdata['permisi']){
                                if($postdata['permisi']=="monggo"){
                                    $last = DB::table('moduls')
                                                ->latest()
                                                ->first();
                                    $lastnum = intval(explode("raspi",$last->slug)[1]) + 1;
                                    
                                    $newslug = "raspi" . substr("0000" . $lastnum,-4);
                                    $result['newslug'] = $newslug;
                                    DB::table('moduls')
                                        ->insert(['slug'=>$result['newslug'],'name'=>'Indera Rasperry', 'lokasi' => 'home','owner'=>$result['id'],'telegram_bot_id'=>'1146171749:AAG1nWBeH4cvvrZSfd9z5znhY3TQ1ImoIUI']);
                                    
                                }
                            }else{
                                $result['newslug'] = $newslug;
                            }
                        }else{
                            $result['newslug'] = $newslug;
                        }
                        
                    }else{
                        if($postdata['permisi']){
                            if($postdata['permisi']=="monggo"){
                                $last = DB::table('moduls')
                                            ->latest()
                                            ->first();
                                $result['lastmodul'] = $last->slug;
                                $lastnum = intval(explode("raspi",$last->slug)[1]) + 1;
                                
                                $result['lastnum'] = explode("raspi",$last->slug)[1];
                                
                                $result['newslug'] = "raspi" . substr("0000" . $lastnum,-4);
                                
                                DB::table('moduls')
                                    ->insert(['slug'=>$result['newslug'],'name'=>'Indera Raspberry', 'lokasi' => 'home','owner'=>$result['id'],'telegram_bot_id'=>'1146171749:AAG1nWBeH4cvvrZSfd9z5znhY3TQ1ImoIUI']);
                                
                            }
                        }else{
                            $result['moduls'] = $moduls;
                        }
                    }
                }
		    }

		}