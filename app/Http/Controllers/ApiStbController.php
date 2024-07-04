<?php namespace App\Http\Controllers;


		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiStbController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "pelanggan";        
				$this->permalink   = "stb";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
                $pelanggan = DB::table('pelanggan')->where('id_pelanggan',$postdata['id_pelanggan'])->first();
		        if($postdata['id_pelanggan']==0 || !$pelanggan){
		            $sekarang = date("Y-m-d H:i:s");
		            $remoteip = getenv("REMOTE_ADDR");
		            $stbexist = DB::table('stb')->where('alamatip',$postdata['alamatip'])->where('remoteip',$remoteip)->first();
		            if(!$stbexist){
		                DB::table('stb')->insert(['hostname'=>$postdata['hostname'],'alamatip'=>$postdata['alamatip'],'remoteip'=>$remoteip]);
		            }else{
		                DB::table('stb')->where('alamatip',$postdata['alamatip'])->where('remoteip',$remoteip)->update(['updated_at'=>$sekarang]);
		            }
                    // $resp = response()->json(['api_stastus'=>1,'api_message'=>'STB Baru !']);
					// $resp->send();
					// exit;
		        }
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
                
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
                if($result['api_status']==1){
                    $pelanggan = DB::table('pelanggan')->where('id_pelanggan',$postdata['id_pelanggan'])->first();
                    $result  = json_decode(json_encode($pelanggan), true);
                    $result['api_status'] = 1;
                    $remote_update = strtotime($pelanggan->remote_update);
                    $sekarang = date("Y-m-d H:i:s");
                    
                    if($postdata['alamatip'] && !$postdata['display'] && !$postdata['power']){
                        $terbayar = DB::table('pelanggan_tagihan')->join('pelanggan','pelanggan.id','pelanggan_tagihan.id_pelanggan')->where('pelanggan_tagihan.id_pelanggan',$pelanggan->id)->whereRaw("DATEDIFF(DATE(CONCAT(YEAR(NOW()),'-', pelanggan_tagihan.bulan,'-',pelanggan.siklus)) + INTERVAL 1 MONTH,DATE(NOW())) >= 0")->first();
                        if($terbayar){
                            DB::table('pelanggan')->where('id_pelanggan',$postdata['id_pelanggan'])->update(['alamatip'=>$postdata['alamatip'],'hostname'=>$postdata['hostname'],'display'=>'true','power'=>'true','updated_at'=>$sekarang,'remote_update'=>$sekarang]);
                            $result['display'] = "true";
                            $result['power'] = "true";
                        }else{
                            DB::table('pelanggan')->where('id_pelanggan',$postdata['id_pelanggan'])->update(['alamatip'=>$postdata['alamatip'],'hostname'=>$postdata['hostname'],'display'=>'false','power'=>'false','updated_at'=>$sekarang,'remote_update'=>$sekarang]);
                            $result['display'] = "false";
                            $result['power'] = "false";
                        }
                        $result['terbayar'] = $terbayar;
                        
                    }
                    if($postdata['display']){
                        DB::table('pelanggan')->where('id_pelanggan',$postdata['id_pelanggan'])->update(['display'=>$postdata['display'],'updated_at'=>$sekarang]);
                        $result['display'] = $postdata['display'];
                    }
                    if($postdata['power']){
                        DB::table('pelanggan')->where('id_pelanggan',$postdata['id_pelanggan'])->update(['power'=>$postdata['power'],'updated_at'=>$sekarang]);
                        $result['power'] = $postdata['power'];
                    }
                    
                    
                    $result['remote_update'] = strtotime($sekarang) - $remote_update;
                    $result['updated'] = $sekarang;
                    $result['id_pelanggan'] = $postdata['id_pelanggan'];
                }
		    }

		}