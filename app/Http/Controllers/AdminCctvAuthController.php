<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminCctvAuthController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "owner,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "cctv_auth";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ID","name"=>"name"];
			$this->col[] = ["label"=>"Display Name","name"=>"display_name"];
			$this->col[] = ["label"=>"Source","name"=>"source"];
			if(CRUDBooster::myPrivilegeId()==1){
			    $this->col[] = ["label"=>"ID","name"=>"id"];
			    $this->col[] = ["label"=>"Owner","name"=>"owner"];
			    $this->col[] = ["label"=>"Owner Id","name"=>"owner_id"];
			    $this->col[] = ["label"=>"Token","name"=>"token"];
			    $this->col[] = ["label"=>"Server","name"=>"stream_server"];
			}
			$this->col[] = ["label"=>"LAT","name"=>"lat"];
			$this->col[] = ["label"=>"LON","name"=>"lon"];
// 			$this->col[] = ["label"=>"Server","name"=>"stream_server"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Publik","name"=>"publik"];
// 			$this->col[] = ["label"=>"Percobaan","name"=>"req_count"];
// 		    $this->col[] = ["label"=>"Berhasil","name"=>"success"];
// 		    $this->col[] = ["label"=>"Gagal","name"=>"fail"];
		    $this->col[] = ["label"=>"Log","name"=>"sensor"];
			
			//$this->col[] = ["label"=>"Player Klik","name"=>"clicked"];
			if(CRUDBooster::myPrivilegeId()==1){
			    $this->col[] = ["label"=>"Angle","name"=>"angle"];
			    $this->col[] = ["label"=>"Internal","name"=>"internal"];
			    $this->col[] = ["label"=>"Percobaan","name"=>"req_count"];
			    $this->col[] = ["label"=>"Berhasil","name"=>"success"];
			    $this->col[] = ["label"=>"Gagal","name"=>"fail"];
			    $this->col[] = ["label"=>"Terbatas","name"=>"pwdreq"];
    			//$this->col[] = ["label"=>"Expired At","name"=>"expired_at"];
    			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
    			$this->col[] = ["label"=>"Owner Id","name"=>"owner_id","join"=>"cms_privileges,name"];
			}
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'ID','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-6','help'=>'Gunakan huruf kecil tanpa spasi dan special character'];
			$this->form[] = ['label'=>'Display Name','name'=>'display_name','type'=>'text','width'=>'col-sm-7'];
			$this->form[] = ['label'=>'Source','name'=>'source','type'=>'text','width'=>'col-sm-8'];
// 			$this->form[] = ['label'=>'Stream Server','name'=>'stream_server','type'=>'text','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Deskripsi','name'=>'deskripsi','type'=>'textarea','width'=>'col-sm-6'];
// 			$this->form[] = ['label'=>'Latitude','name'=>'latitude','type'=>'text','width'=>'col-sm-10'];
// 			$this->form[] = ['label'=>'Longitude','name'=>'longitude','type'=>'text','width'=>'col-sm-10'];
			
			if(CRUDBooster::myPrivilegeId()==1){
			    $this->form[] = ['label'=>'Stream Server','name'=>'stream_server','type'=>'text','width'=>'col-sm-8'];
    			$this->form[] = ['label'=>'Koordinat','name'=>'gps','type'=>'osm','validation'=>'string|min:5|max:5000','width'=>'col-sm-10','value'=>CRUDBooster::me()->lat.','.CRUDBooster::me()->lon];
    			$this->form[] = ['label'=>'Lat','name'=>'lat','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
    			$this->form[] = ['label'=>'Lon','name'=>'lon','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
    			$this->form[] = ['label'=>'Angle','name'=>'angle','type'=>'number','width'=>'col-sm-2'];
    			$this->form[] = ['label'=>'Owner','name'=>'owner','type'=>'text','width'=>'col-sm-6'];
    			$this->form[] = ['label'=>'Token','name'=>'token','type'=>'text','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-8'];
    			$this->form[] = ['label'=>'Internal','name'=>'internal','type'=>'radio','validation'=>'required|integer|min:0','width'=>'col-sm-6','dataenum'=>'1|True;0|False'];
			    $this->form[] = ['label'=>'Terbatas','name'=>'pwdreq','type'=>'radio','validation'=>'required|integer|min:0','width'=>'col-sm-6','dataenum'=>'0|False;1|True'];
			    $this->form[] = ['label'=>'Projection','name'=>'projection','type'=>'text','width'=>'col-sm-4'];
			    $this->form[] = ['label'=>'Input Protocol','name'=>'input_protocol','type'=>'text','width'=>'col-sm-4'];
			    $this->form[] = ['label'=>'Metode','name'=>'metode','type'=>'text','width'=>'col-sm-4'];
			    $this->form[] = ['label'=>'Custom Icon','name'=>'custom_icon','type'=>'upload','width'=>'col-sm-4','validation'=>'max:300'];
    			$this->form[] = ['label'=>'Expired At','name'=>'expired_at','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=> 'col-sm-6'];
			}else if(CRUDBooster::myPrivilegeId()==4){
			    $this->form[] = ['label'=>'Koordinat','name'=>'gps','type'=>'osm','validation'=>'string|min:5|max:5000','width'=>'col-sm-10','value'=>'-2.165227,115.382979'];
    			$this->form[] = ['label'=>'Lat','name'=>'lat','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
    			$this->form[] = ['label'=>'Lon','name'=>'lon','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
			    $this->form[] = ['label'=>'Owner','name'=>'owner','type'=>'hidden','width'=>'col-sm-6','value'=>'BATARA'];
			    $this->form[] = ['label'=>'Token','name'=>'token','type'=>'hidden','validation'=>'string|min:5|max:5000','width'=>'col-sm-8','value'=>'PUNWAHYU'];
			}else if(CRUDBooster::myPrivilegeId()==5){
			    $this->form[] = ['label'=>'Koordinat','name'=>'gps','type'=>'osm','validation'=>'string|min:5|max:5000','width'=>'col-sm-10','value'=>'-2.165227,115.382979'];
    			$this->form[] = ['label'=>'Lat','name'=>'lat','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
    			$this->form[] = ['label'=>'Lon','name'=>'lon','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
    			$this->form[] = ['label'=>'Angle','name'=>'angle','type'=>'number','width'=>'col-sm-2'];
			}else if(CRUDBooster::myPrivilegeId()==6){
			    $this->form[] = ['label'=>'Koordinat','name'=>'gps','type'=>'osm','validation'=>'string|min:5|max:5000','width'=>'col-sm-10','value'=>'-3.453323,114.797993'];
    			$this->form[] = ['label'=>'Lat','name'=>'lat','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
    			$this->form[] = ['label'=>'Lon','name'=>'lon','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-4'];
    			$this->form[] = ['label'=>'Angle','name'=>'angle','type'=>'number','width'=>'col-sm-2'];
			}
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'radio','validation'=>'required|integer|min:0','width'=>'col-sm-6','dataenum'=>'1|True;0|False'];
			$this->form[] = ['label'=>'Publik','name'=>'publik','type'=>'radio','validation'=>'required|integer|min:0','width'=>'col-sm-6','dataenum'=>'1|True;0|False'];
			
			
			$this->form[] = ['label'=>'Created By','name'=>'created_by','type'=>'hidden','validation'=>'required|integer|min:0','width'=>'col-sm-10','value'=>CRUDBooster::myId()];
			if(CRUDBooster::myPrivilegeId()!=1){
			    $this->form[] = ['label'=>'Owner id','name'=>'owner_id','type'=>'hidden','validation'=>'required|integer|min:0','width'=>'col-sm-10','value'=>CRUDBooster::myPrivilegeId()];
			}else{
			    $this->form[] = ['label'=>'Owner id','name'=>'owner_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>"cms_privileges,name"];
			}
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Name","name"=>"name","type"=>"text","required"=>TRUE,"validation"=>"required|string|min:3|max:70","placeholder"=>"You can only enter the letter only"];
			//$this->form[] = ["label"=>"Token","name"=>"token","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Expired At","name"=>"expired_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Created By","name"=>"created_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();
	        if(CRUDBooster::myPrivilegeId()==1){
	            $this->sub_module[] = ['label'=>'Iklan','path'=>'cctv_iklan','parent_columns'=>'name,owner','foreign_key'=>'cctv_auth_id','button_color'=>'info','button_icon'=>'fa fa-hacker-news'];
	            $this->sub_module[] = ['label'=>'Hist','path'=>'cctv_history','parent_columns'=>'display_name,id','foreign_key'=>'cctv_auth_id','button_color'=>'success'];
	        }


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();
	        $this->addaction[] = ['label'=>'Play', 'url'=>CRUDBooster::mainPath('play/[id]')];


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        if(CRUDBooster::myPrivilegeId()!=1){
	            $query->where('owner_id',CRUDBooster::myPrivilegeId());
	        }
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        if(CRUDBooster::myPrivilegeId()==5){
	            $postdata['owner'] = 'TABALONG';
	            $postdata['token'] = 'WAHYUINDRAMAN';
	            $postdata['stream_server'] = 'https://streaming.indera.id';
	        }
	        if(CRUDBooster::myPrivilegeId()==6){
	            $postdata['owner'] = 'INDERA';
	            $postdata['token'] = 'PUNWAHYU';
	            $postdata['stream_server'] = 'https://streaming.indera.id';
	        }
	       $postdata['name'] = preg_replace('/\s+/', '', $postdata['name']);
	       $taken=DB::table('cctv_auth')->where('name',$postdata['name'])->first();
	       if($taken){
	           CRUDBooster::redirectBack('Name tidak tersedia silahkan gunakan name lain ! ','danger');
	       }
            
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	    
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        if(CRUDBooster::myPrivilegeId()==5){
	            $postdata['owner'] = 'TABALONG';
	            $postdata['token'] = 'WAHYUINDRAMAN';
	            $postdata['stream_server'] = 'https://streaming.indera.id';
	        }
	        if(CRUDBooster::myPrivilegeId()==6){
	            $postdata['owner'] = 'bjb';
	            $postdata['token'] = 'PUNWAHYU';
	            $postdata['stream_server'] = 'https://streaming.indera.id';
	        }
	        $postdata['name'] = preg_replace('/\s+/', '', $postdata['name']);
            $taken=DB::table('cctv_auth')->where('name',$postdata['name'])->first();
            if($id!=$taken->id){
                if($taken){
                   CRUDBooster::redirectBack('Name tidak tersedia silahkan gunakan name lain ! ','danger');
                }
            }
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }
	    
	    public function getPlay($id) {
	        $cctv = DB::table('cctv_auth')->where('id',$id)->first();
	        $data= [];
	        $data['title'] = "Player";
	        $data['cctv'] = $cctv;
	        return view('cctv.player',$data);

	    }
	    
	    



	    //By the way, you can still create your own method in here... :) 


	}