<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiNotifyModulController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "moduls";        
				$this->permalink   = "notify_modul";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        if($result["api_status"]==1){
				// 	$telegrambotid = "1146171749:AAG1nWBeH4cvvrZSfd9z5znhY3TQ1ImoIUI";
	   //             $telegramchatid="-408380139";
	                $telegrambotid = $result["telegram_bot_id"];
	                $telegramchatid = $result["telegram_chat_id"];
	           
		                        
    		        $config['content'] = $postdata['pesan'];
                    //$config['to'] = CRUDBooster::adminPath('iot_host/detail/'.$postdata['sensors_id']);
                    $config['to'] = "#";
                    if($owner->owner == 1){
                       $config['id_cms_users'] = [1]; 
                    }else{
                        $config['id_cms_users'] = [$result['owner']];
                    }
                   //The Id of the user that is going to receive notification. This could be an array of id users [1,2,3,4,5]
                    CRUDBooster::sendNotification($config);
                    
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot".$telegrambotid."/sendMessage");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    
                    // In real life you should use something like:
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 
                             http_build_query(array('chat_id' => $telegramchatid , 'text' => $config['content'] )));
                    
                    // Receive server response ...
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                    $server_output = curl_exec($ch);
                    
                    curl_close ($ch);
                    
				}

		    }

		}