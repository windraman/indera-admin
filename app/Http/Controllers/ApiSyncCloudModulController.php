<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSyncCloudModulController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "sync_cloud_modul";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        $ini = DB::table($this->table)
                            ->where('slug',$postdata['slug'])
                            ->first();
                
                if($ini){
                    $grupini = DB::table('grups')
                                ->where('moduls_id',$ini->id)
                                ->get();
                    
                    foreach($grupini as $gi){
                        $delsensorini = DB::table('sensors')->where('grups_id', $gi->id)->delete();
                    }
                    
                    $delgrupini = DB::table('grups')->where('moduls_id',$ini->id)->delete();
                    
                    $delini = DB::table($this->table)->where('slug', $ini->slug)->delete();
                    
                    if($delini){
                        $postdata['sync'] = 'updated';
                    }
                }

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        if($result['api_status']==1){
		            $result['sync'] = $postdata['sync'];
		            $result['grups'] = $postdata['grups'];
		        }else{
		            $result['grups'] = json_decode($postdata['grups']);
		        }
		    }

		}