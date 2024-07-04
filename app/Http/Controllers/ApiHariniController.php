<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiHariniController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "sejarah";        
				$this->permalink   = "harini";    
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
		        
		        $sumklik = DB::Table('cctv_auth')->sum('clicked');
		        
		        DB::table('sejarah')
                ->insert(
                    ["created_at" => now(), "todays_hit" => $sumklik]
                );

		    }

		}