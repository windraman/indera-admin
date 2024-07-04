<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSensorValueController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "sensors";        
				$this->permalink   = "sensor_value";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        if($result["api_status"]==1){
		            $owner = DB::table('grups')
		                        ->join('moduls','moduls.id','grups.moduls_id')
		                        ->where('grups.id',$result['grups_id'])
		                        ->select('grups.*','moduls.owner','moduls.telegram_bot_id','moduls.telegram_chat_id')
		                        ->first();
		                        
		            $result['owner'] = $owner;
		            $tipe_pin = DB::table('tipe_pin')
							->where('id',$result['tipe_sensor_id'])
							->first();
					$result['tipe_pin'] = $tipe_pin->tipe_pin;
					$result['io'] = $tipe_pin->io;
					$result['script_path'] = $tipe_pin->script_path;
				// 	$result['get_script'] = $tipe_pin->get_script;
				// 	$result['set_script'] = $tipe_pin->set_script;
					
	
				   	DB::table('sensors_value')->insert(
						['sensor_id' => $result['id'], 'nilai' => $postdata['nilai'], 'ovrd' => $postdata['ovrd'], 'is_read' => $postdata['is_read']]
					);
				
					$ovrd_txt = "";
					if($postdata['ovrd']==1 && $postdata['is_read'] == 0){
					    $ovrd_txt = "CLOUD OVERRIDE";
					}elseif ($postdata['ovrd']==0 && $postdata['is_read'] == 0){
					    $ovrd_txt = "AUTO";
					}elseif ($postdata['ovrd']==1 && $postdata['is_read'] == 1){
					    $ovrd_txt = "LOCAL OVERRIDE";
					}
				
				    // if($postdata['ovrd']==1){
    				//     $ovrd_txt = "CLOUD OVERRIDE";
    				// }else{
    				//     $ovrd_txt = "AUTO";
    				// }
					
					$telegrambotid = $owner->telegram_bot_id;
	                $telegramchatid= $owner->telegram_chat_id;
	           
		    
		            $grup = DB::Table('grups')
		                        ->where('id',$result['grups_id'])
		                        ->first();
		                        
		            $modul = DB::Table('moduls')
		                        ->where('id',$grup->moduls_id)
		                        ->first();       
		                        
    		        $config['content'] = "[" . $ovrd_txt . "]" . $modul->name . " at " . $modul->lokasi . " on " . $grup->name . ", sensor " . $result['name'] . " = " . $postdata['nilai'];
                    //$config['to'] = CRUDBooster::adminPath('iot_host/detail/'.$postdata['sensors_id']);
                    $config['to'] = "#";
                    if($owner->owner == 1){
                       $config['id_cms_users'] = [1]; 
                    }else{
                        $config['id_cms_users'] = [$owner->owner];
                    } //The Id of the user that is going to receive notification. This could be an array of id users [1,2,3,4,5]
                    CRUDBooster::sendNotification($config);
                    
                    if($result['noc']==1){
                        $ch = curl_init();
    
                        curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot".$telegrambotid."/sendMessage");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        
                        // In real life you should use something like:
                        curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                 http_build_query(array('chat_id' => $telegramchatid, 'text' => $config['content'] )));
                        
                        // Receive server response ...
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        
                        $server_output = curl_exec($ch);
                        
                        curl_close ($ch);
                    }
                    
				}

		    }

		}