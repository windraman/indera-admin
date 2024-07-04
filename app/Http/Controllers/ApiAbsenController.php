<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiAbsenController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "absen";        
				$this->permalink   = "absen";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        $employee = DB::table('employee')->where('userid',$postdata['userid'])->first();
		        //$overshift = DB::table('overshift_member')->where('employee_id',$employee->id)->get();
                $vendor = DB::table('vendor')->where('id',$employee->vendor_id)->first();
                if($vendor){
                    $postdata['vendor_id'] = $vendor->id;
                    $lokasi_absen = DB::table('lokasi_absen')->where('vendor_id',$vendor->id)->where('konteks',$postdata['konteks'])->get();
                    if(sizeof($lokasi_absen)>0){
                        $jaraks = array();
                        $match = 0;
                        foreach($lokasi_absen as $lokab){
                            $jarak = $this->getDistanceBetweenPointsNew($lokab->lat,$lokab->lon,$postdata['lat'],$postdata['lon']);
                            if($jarak <= $lokab->jarak){
                                $match++;
                                $absen = DB::table('absen')
                                        ->where('employee_id',$employee->id)
                                        ->where('konteks',$postdata['konteks'])
                                        //->whereDate('waktu', date('Y-m-d'))
                                        //->whereRaw('DATE(waktu) = CURDATE()')
                                        ->whereDay('waktu', '=', date('d'))
                                        ->whereMonth('waktu', '=', date('m'))
                                        ->whereYear('waktu', '=', date('Y'))
                                        ->first();
                                // $absen = DB::table('absen')->where('employee_id',$employee->id)->where('konteks',$postdata['konteks'])->whereDate('waktu', '=', now())->first
                                // $resp = response()->json(['api_status'=>0,'api_message'=>$absen,'id'=>0]);
                                // $resp->send();
                                // exit;
                                if($absen){
                                    if($postdata['konteks']=="IN"){
                                        $resp = response()->json(['api_status'=>0,'api_message'=>'Anda sudah absen masuk hari ini !','id'=>0]);
                                        $resp->send();
                                        exit;
                                    }
                                    if($postdata['konteks']=="OUT"){
                                        $resp = response()->json(['api_status'=>0,'api_message'=>'Anda sudah absen keluar hari ini !','id'=>0]);
                                        $resp->send();
                                        exit;
                                    }
                                }else{
                                    $postdata['employee_id'] = $employee->id;
                                    $postdata['lokasi_absen_id'] = $lokab->id;
                                    $postdata['jarak'] = $jarak;
                                }
                            }
                        }
                        if($match==0){
                            $resp = response()->json(['api_status'=>0,'api_message'=>'Lokasi Anda Salah !' ,'id'=>0, 'jaraks'=>$jarak]);
                            $resp->send();
                            exit;
                        }
                    }else{
                        $resp = response()->json(['api_status'=>0,'api_message'=>'Lokasi Absen Tidak Ditemukan !','id'=>0]);
                        $resp->send();
                        exit;
                    }
                }else{
                    $postdata['vendor_id'] = 0;
                    $resp = response()->json(['api_status'=>0,'api_message'=>'invalid','id'=>0]);
                    $resp->send();
                    exit;
                }
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        if($result['api_status']==1){
		            $absen = DB::table('absen')->where('id',$result['id'])->first();
		            $lokasi = DB::table('lokasi_absen')->where('id',$absen->lokasi_absen_id)->first();
		            $result['api_message'] = "Absen di " . $lokasi->name . " berhasil terkirim pada " . $absen->waktu . " jarak " . $absen->jarak . " meter." ;
		        }
		    }
		    
		    public function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'kilometers') {
              $theta = $longitude1 - $longitude2; 
              $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))); 
              $distance = acos($distance); 
              $distance = rad2deg($distance); 
              $distance = $distance * 60 * 1.1515; 
              switch($unit) { 
                case 'miles': 
                  break; 
                case 'kilometers' : 
                  $distance = $distance * 1.609344; 
              } 
              return (round($distance,2)*1000); 
}

		}