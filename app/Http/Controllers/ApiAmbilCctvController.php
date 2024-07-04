<?php namespace App\Http\Controllers;
header('Access-Control-Allow-Origin','*');
header("Access-Control-Allow-Headers","*");

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiAmbilCctvController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cctv_auth";        
				$this->permalink   = "ambil_cctv";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
		        $query->whereRaw("LENGTH(lat)>0");
                $query->orderBy('display_name','asc');
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
		        if($result['api_status']==1){
		            foreach($result['data'] as $key=>$data){
		                $names = explode("-",$data->name);
		                //if(sizeof($names)==1){
		                  if($postdata['gen']==1){
		                      if($data->internal==0){
    		                      unset($result['data'][$key]); 
		                      }
		                      if(sizeof($names)>1){
		                            unset($result['data'][$key]);
		                      }
    		              //      $gentoken = $this->generateRandomString();
    		              //      DB::table('cctv_auth')->where('id',$data->id)->update(['token'=>$gentoken]);
    		              //      $data->token = $gentoken;
		                  }else{
		                      if($data->internal==1){
    		                      unset($data->source);  
		                      }
		                      unset($data->token);
		                      $counterexist = DB::table('cctv_counter')->where('cctv_auth_id',$data->id)->first();
    		                  if($counterexist){
    		                      if($postdata['gen']!=1){
    		                          if($postdata['stats']==1){
    		                            $data->harian = DB::table('cctv_counter')->where('cctv_auth_id',$data->id)->whereRaw('DATE(updated_at) = DATE(now())')->count('id');
    		                          }
    		                      }
    		                  }else{
    		                      $data->harian = 0;
    		                  }
    		                  $iklan = DB::table('cctv_iklan')
    		                            ->join('iklan','iklan.id','cctv_iklan.iklan_id')
    		                            ->where('cctv_iklan.start', '<=', date("Y-m-d H:i:s"))
                                        ->where('cctv_iklan.end', '>=', date("Y-m-d H:i:s"))
    		                            ->where('cctv_iklan.cctv_auth_id',$data->id)
    		                            ->get();
    		                  $data->iklan = $iklan;
    		                  $data->online = true;
		                  }
		                  if($postdata['sconline']==1 ){
		                      if($data->internal == 1){
		                        $source = "https://streaming.indera.id:4433/hls/" . $data->name . ".m3u8";
		                        $data->online = $this->cekLocal($source);
		                      }else{
		                        $source = $data->source;
		                        $data->online = $this->isSiteAvailible($source);
		                      }
    		
    		                  

    		              }
    		                
		            }
		            
    		            if($postdata['gen']!=1 ){
    		                if($postdata['stats']==1 ){
            		            $result['total_view'] = DB::table('cctv_auth')->where('owner',$postdata['owner'])->sum('clicked');
                                $result['today_view'] = DB::table('cctv_counter')->join('cctv_auth','cctv_auth.id','cctv_counter.cctv_auth_id')->where('cctv_auth.owner',$postdata['owner'])->whereRaw('DATE(cctv_counter.updated_at) = DATE(now())')->count('cctv_counter.id');
                                $result['month_view'] = DB::table('cctv_counter')->join('cctv_auth','cctv_auth.id','cctv_counter.cctv_auth_id')->where('cctv_auth.owner',$postdata['owner'])->whereRaw('MONTH(cctv_counter.updated_at) = MONTH(now())')->whereRaw('YEAR(cctv_counter.updated_at) = YEAR(now())')->count('cctv_counter.id');
                                $result['year_view'] = DB::table('cctv_counter')->join('cctv_auth','cctv_auth.id','cctv_counter.cctv_auth_id')->where('cctv_auth.owner',$postdata['owner'])->whereRaw('YEAR(cctv_counter.updated_at) = YEAR(now())')->count('cctv_counter.id');
            		            $result['devices'] = sizeof(DB::table('cctv_counter')->join('cctv_auth','cctv_auth.id','cctv_counter.cctv_auth_id')->where('cctv_auth.owner',$postdata['owner'])->whereRaw('LENGTH(cctv_counter.uniqueid) > 0')->selectRaw('cctv_counter.uniqueid,COUNT(cctv_counter.uniqueid)')->groupBy('cctv_counter.uniqueid')->get());
    		                    
    		                }
    		                
    		            }

		        }
		    }
		    
		    private function generateRandomString($length = 7) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[random_int(0, $charactersLength - 1)];
                }
                return $randomString;
            }
            
            private function isSiteAvailible($url){
                // Check, if a valid url is provided
                if(!filter_var($url, FILTER_VALIDATE_URL)){
                    return false;
                }
            
                // Initialize cURL
                $curlInit = curl_init($url);
                
                // Set options
                curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
                curl_setopt($curlInit,CURLOPT_HEADER,true);
                curl_setopt($curlInit,CURLOPT_NOBODY,true);
                curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
            
                // Get response
                $response = curl_exec($curlInit);
                
                // Close a cURL session
                curl_close($curlInit);

                return $response?true:false;

            }
            
            private function cekLocal($url){
                try {
                    // Check if the URL is valid
                    if (!filter_var($url, FILTER_VALIDATE_URL)) {
                        return "invalid";
                        throw new InvalidArgumentException("Invalid URL");
                    }
                    
                    // Open the URL and read the contents
                    $stream = file_get_contents($url);
                    
                    // Return the contents of the HLS stream
                    return $stream;
                } catch (Exception $e) {
                    // Log the error
                    error_log("Error: " . $e->getMessage());
                    return "";
                }

            }

		}