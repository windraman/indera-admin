<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetNotifController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_notifications";        
				$this->permalink   = "get_notif";    
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
		        foreach($result['data'] as $data){
                    if($postdata['is_read'] == 0){
                        DB::Table($this->table)
                            ->where('id', $data->id)
                            ->update(['is_read' => 1]);
                    }
                    
                }

		    }

		}