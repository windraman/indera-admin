<?php namespace App\Http\Controllers;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiNearByPelangganController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "pelanggan";        
				$this->permalink   = "near_by_pelanggan";    
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
    				$result['data'] = DB::Table('pelanggan')
    					->select('pelanggan.*',
                        DB::raw('111.111 *
                            DEGREES(ACOS(LEAST(1.0, COS(RADIANS(pelanggan.lat))
                                 * COS(RADIANS('.$postdata['user_lat'].'))
                                 * COS(RADIANS(pelanggan.lon - '.$postdata['user_lon'].'))
                                 + SIN(RADIANS(pelanggan.lat))
                                 * SIN(RADIANS('.$postdata['user_lat'].'))))) AS jarak_km'),
                        DB::raw('(IF(EXISTS(SELECT pelanggan_tagihan.bulan FROM pelanggan_tagihan WHERE pelanggan_tagihan.id_pelanggan = pelanggan.id AND  pelanggan_tagihan.bulan = MONTH(NOW())),IF(DATEDIFF(LAST_DAY(NOW()), DATE(NOW())) >= 5,"aktif", CONCAT("Berkahir dalam ",DATEDIFF(LAST_DAY(NOW()), DATE(NOW()))," Hari")),"expired")) as status')
                                 )          
    					->where(DB::raw('111.111 *
                            DEGREES(ACOS(LEAST(1.0, COS(RADIANS(pelanggan.lat))
                                 * COS(RADIANS('.$postdata['user_lat'].'))
                                 * COS(RADIANS(pelanggan.lon - '.$postdata['user_lon'].'))
                                 + SIN(RADIANS(pelanggan.lat))
                                 * SIN(RADIANS('.$postdata['user_lat'].')))))'),'<=',$postdata['jarak'])
                        ->where('cms_privileges_id', $postdata['id'])
    					->get();
						
			
		    }

		}