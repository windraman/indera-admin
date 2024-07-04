<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetGrupsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "get_grups";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        if ($result["api_status"] == 1){
					$grups = DB::Table('grups')
								->where('moduls_id',$result['id'])
								->get();
					if($grups){
						foreach($grups as $gp){
							$sensors = DB::Table('sensors')
								->where('grups_id',$gp->id)
								->get();
							
							$gp->sensors = $sensors;
							$gp->jsensor= sizeof($sensors);
						
						}
						$result['grups'] = $grups;
					}else{
						$result['grups'] = null;
					}
				}

		    }

		}