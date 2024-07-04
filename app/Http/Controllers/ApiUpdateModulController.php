<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUpdateModulController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "update_modul";    
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
                if($result['api_status'] == 1){
                    if($postdata['local_ip']){
                        if($postdata['local_ip'] != $result['local_ip']){
                            $ip_change = DB::table('moduls')
                                    ->where('id',$result['id'])
                                    ->update(['local_ip' => $postdata['local_ip']]);
                            if($ip_change){
                                $result['local_ip'] = $postdata['local_ip'];
                                $result['ip_changed'] = 1;
                                $pesan = "IP Address ". $result['slug'] . " - " . $result['name'] . " di " . $result['lokasi'] . " changed to " . $postdata['local_ip'];
                                $this->notifyUser($pesan,$result['owner'],$result['telegram_bot_id'],$result['telegram_chat_id']);
                            }
                        }else{
                            $result['ip_changed'] = 0;
                        }
                    }
                }
		    }
		    
		    public function notifyUser($pesan,$users,$telegrambotid,$telegramchatid){
		        //	$telegrambotid = "1146171749:AAG1nWBeH4cvvrZSfd9z5znhY3TQ1ImoIUI";
	              //  $telegramchatid="-408380139";
	           
		                        
    		        $config['content'] = $pesan;
                    $config['to'] = "#";
                    $config['id_cms_users'] = [$users];
                    CRUDBooster::sendNotification($config);
                    
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot".$telegrambotid."/sendMessage");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    // curl_setopt($ch, CURLOPT_POSTFIELDS,
                    //             "chat_id=-408380139&text=".$config['content']);
                    
                    // In real life you should use something like:
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 
                             http_build_query(array('chat_id' => $telegramchatid, 'text' => $config['content'] )));
                    
                    // Receive server response ...
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                    $server_output = curl_exec($ch);
                    
                    curl_close ($ch);
		    }

		}