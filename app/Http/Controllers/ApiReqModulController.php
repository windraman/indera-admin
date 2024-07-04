<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiReqModulController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "req_modul";    
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
                if($result['api_status']==1){
                    $priv = DB::table('cms_users')
                            ->select('id','id_cms_privileges')
                            ->where('id',$postdata['id'])
                            ->first();
                    $result['id_cms_privileges'] = $priv->id_cms_privileges;
                    
                    if($result['id_cms_privileges'] != 2){
                        $last = DB::table('moduls')
                                    ->latest()
                                    ->first();
                        $lastnum = intval(explode("raspi",$last->slug)[1]) + 1;
                        
                        $newslug = "raspi" . substr("0000" . $lastnum,-4);
                        
                        // DB::table('moduls')
                        //     ->insert(['slug'=>$result['newslug'],'name'=>'Indera Rasperry', 'lokasi' => 'home','owner'=>$result['id'],'telegram_bot_id'=>'1146171749:AAG1nWBeH4cvvrZSfd9z5znhY3TQ1ImoIUI']);
                         $result['newslug'] = $newslug;  

                    }else{
                        $owned = DB::table('moduls')
                                    ->where('owner',$postdata['id'])
                                    ->first();
                       // $result['last'] = $last->slug;
                        if($owned){
                            $result['newslug'] = $last->slug;
                         }else{
                            $last = DB::table('moduls')
                                    ->latest()
                                    ->first();
                            $lastnum = intval(explode("raspi",$last->slug)[1]) + 1;
                            
                            $newslug = "raspi" . substr("0000" . $lastnum,-4);
                            
                            // DB::table('moduls')
                            //     ->insert(['slug'=>$result['newslug'],'name'=>'Indera Rasperry', 'lokasi' => 'home','owner'=>$result['id'],'telegram_bot_id'=>'1146171749:AAG1nWBeH4cvvrZSfd9z5znhY3TQ1ImoIUI']);
                             $result['newslug'] = $newslug;  
                        }
                    }
                   
                }
		    }

		}