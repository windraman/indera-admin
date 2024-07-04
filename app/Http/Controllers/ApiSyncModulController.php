<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSyncModulController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "sync_modul";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                $ini = DB::table('moduls')
                            ->where('slug',$postdata['slug'])
                            ->first();
                
                
                if($ini){
                    $postdata['telegram_bot_id'] = $ini->telegram_bot_id;
                    $postdata['telegram_chat_id'] = $ini->telegram_chat_id;
                    $grupini = DB::table('grups')
                                ->where('moduls_id',$ini->id)
                                ->get();
                    if($grupini){
                        foreach($grupini as $gi){
                            $delsensorini = DB::table('sensors')->where('grups_id', $gi->id)->delete();
                        }
                        
                        $delgrupini = DB::table('grups')->where('moduls_id',$ini->id)->delete();
                        
                        $delini = DB::table($this->table)->where('slug', $ini->slug)->delete();
                        
                    }
                }
                $postdata['grup_json'] = str_replace(chr(39), chr(34),$postdata['grup_json']);
                
                  //chr(39) implies single quote 
                  //chr(34) implies double quote
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		        if($result['api_status']==1){
                    $result['sync'] = $result['id'];
                 
                    $grups =  json_decode($postdata['grup_json']);
                    $result['decoded'] = $grups;
                    $result['type'] = gettype($grups);
                    $result['error'] = json_last_error();
                    foreach($grups as $gp){
                        $grup_id = DB::table('grups')
                                        ->insertGetId(['slug' => $gp->slug, 'name' => $gp->name, 'moduls_id' => $result['id']]);
                        
                        $sensors = $gp->sensors;
                        foreach($sensors as $sensor){
                            DB::table('sensors')
                                        ->insert(['slug' => $sensor->slug, 'name' => $sensor->name, 'pin' => $sensor->pin, 'pin2' => $sensor->pin2, 'tipe_sensor_id' => $sensor->tipe_sensor_id, 'max_value' => $sensor->max_value, 'offset' => $sensor->offset, 'grups_id' => $grup_id]);
                        }
                    }
		        }
		    }

		}