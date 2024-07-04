<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUpdateTelegramChatIdController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "update_telegram_chat_id";    
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
                   // $result['slug'] = $result['id'];
                    DB::table('moduls')->where('slug',$postdata['slug'])->update(['telegram_chat_id'=>$postdata['telegram_chat_id']]);
                }
		    }

		}