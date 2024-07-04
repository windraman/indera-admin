<?php

namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

class AdminPelangganController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "nama_pelanggan";
		$this->limit = "20";
		$this->orderby = "id,desc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "dropdown";
		$this->button_add = true;
		$this->button_edit = true;
		$this->button_delete = true;
		$this->button_detail = true;
		$this->button_show = false;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "pelanggan";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		if (CRUDBooster::myPrivilegeId() == 1) {
			$this->col[] = ["label" => "Owner", "name" => "cms_privileges_id", "join" => "cms_privileges,name"];
			$this->col[] = ["label" => "ID data", "name" => "pelanggan.id"];
		}

		$this->col[] = ["label" => "ID", "name" => "id_pelanggan"];
		$this->col[] = ["label" => "Nama Pelanggan", "name" => "nama_pelanggan"];
		$this->col[] = ["label" => "No Telp", "name" => "no_telp"];
		$this->col[] = ["label" => "Alamat", "name" => "alamat"];
		$this->col[] = ["label" => "Mode", "name" => "(SELECT IF(mode=0,'Analog',IF(mode=1,'STB','K-Vision'))) as model", "callback_php" => '$row->model'];
		
		$this->col[] = ["label" => "Last pay", "name" => "(SELECT IF(EXISTS(SELECT bulan FROM pelanggan_tagihan WHERE pelanggan_tagihan.id_pelanggan = pelanggan.id),bulan,0) FROM pelanggan_tagihan WHERE pelanggan_tagihan.id_pelanggan = pelanggan.id ORDER BY pelanggan_tagihan.bulan DESC LIMIT 1) as last_pay" , "callback_php" => '$row->last_pay', "visible" => false];
		$this->col[] = ["label" => "Terbayar bulan", "name" => "(SELECT GROUP_CONCAT(pelanggan_tagihan.bulan ORDER BY pelanggan_tagihan.bulan ASC) from pelanggan_tagihan WHERE pelanggan_tagihan.id_pelanggan = pelanggan.id  GROUP BY pelanggan.id) as last_month", "callback_php" => '$row->last_month'];
		$this->col[] = ["label" => "Siklus", "name" => "siklus"];
		// $this->col[] = ["label" => "Status", "name" => "(SELECT IF(EXISTS(SELECT bulan FROM pelanggan_tagihan WHERE pelanggan_tagihan.id_pelanggan = pelanggan.id AND DATEDIFF(DATE(CONCAT(YEAR(NOW()),'-', pelanggan_tagihan.bulan,'-',pelanggan.siklus)) + INTERVAL 1 MONTH,DATE(NOW())) >= 0),IF(DATEDIFF(LAST_DAY(NOW() + INTERVAL pelanggan.siklus DAY), DATE(NOW())) >= 5,'aktif', CONCAT('Berkahir dalam ',DATEDIFF(DATE(CONCAT(YEAR(NOW()),'-', pelanggan_tagihan.bulan,'-',pelanggan.siklus)) + INTERVAL 1 MONTH,DATE(NOW())),' Hari')),CONCAT('expired')) FROM pelanggan pelanggan JOIN pelanggan_tagihan ON pelanggan_tagihan.id_pelanggan = pelanggan.id ORDER BY pelanggan_tagihan.bulan DESC LIMIT 1) as status", "callback_php" => '$row->status'];
		$this->col[] = ["label" => "Status", "name" => "(SELECT IF(DATEDIFF(DATE(CONCAT(YEAR(NOW()),'-', pelanggan_tagihan.bulan,'-',pelanggan.siklus)) + INTERVAL 1 MONTH,DATE(NOW())) >= 0,IF(DATEDIFF(DATE(CONCAT(YEAR(NOW()),'-', pelanggan_tagihan.bulan,'-',pelanggan.siklus)) + INTERVAL 1 MONTH,DATE(NOW())) <= 5,CONCAT('Berakhir dalam ', DATEDIFF(DATE(CONCAT(YEAR(NOW()),'-', pelanggan_tagihan.bulan,'-',pelanggan.siklus)) + INTERVAL 1 MONTH,DATE(NOW())),' hari'),'aktif'),'expired') FROM pelanggan_tagihan WHERE pelanggan_tagihan.id_pelanggan = pelanggan.id ORDER BY pelanggan_tagihan.bulan DESC LIMIT 1) as status", "callback_php" => '$row->status'];
		
		$this->col[] = ["label" => "Display", "name" => "display"];
		$this->col[] = ["label" => "Power", "name" => "power"];
		$this->col[] = ["label" => "Last Online", "name" => "(SELECT TIMESTAMPDIFF(SECOND,pelanggan.remote_update,CURRENT_TIMESTAMP())) as last_update", "callback_php" => 'Carbon\CarbonInterval::seconds($row->last_update)->cascade()->forHumans()'];
		$this->col[] = ["label" => "Catatan", "name" => "keterangan"];
		$this->col[] = ["label" => "Ip Local", "name" => "alamatip", "visible" => false];
		$this->col[] = ["label" => "LAT", "name" => "lat", "visible" => false];
		$this->col[] = ["label" => "LON", "name" => "lon", "visible" => false];
		//$this->col[] = ["label"=>"Lon","name"=>"lon"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		if (CRUDBooster::myPrivilegeId() == 1) {
			$this->form[] = ['label' => 'Owner', 'name' => 'cms_privileges_id', 'type' => 'select2', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-5', 'datatable' => 'cms_privileges,name'];
			$this->form[] = ['label' => 'ID', 'name' => 'id_pelanggan', 'type' => 'text', 'validation' => 'required|min:1|max:20', 'width' => 'col-sm-4', 'value' => $this->gen_id()];
		} else {
			$this->form[] = ['label' => 'ID', 'name' => 'id_pelanggan', 'type' => 'hidden', 'validation' => 'required|min:1|max:20', 'width' => 'col-sm-4', 'value' => $this->gen_id()];
		}
		$this->form[] = ['label' => 'Nama Pelanggan', 'name' => 'nama_pelanggan', 'type' => 'text', 'validation' => 'required|min:1|max:100', 'width' => 'col-sm-5'];
		$this->form[] = ['label' => 'No Telp', 'name' => 'no_telp', 'type' => 'number', 'validation' => 'required|numeric', 'width' => 'col-sm-3', 'placeholder' => 'Anda hanya dapat memasukkan angka saja'];
		$this->form[] = ['label' => 'Alamat', 'name' => 'alamat', 'type' => 'textarea', 'validation' => 'required|string|min:5|max:5000', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'Tanggal mendaftar', 'name' => 'tanggal_mendaftar', 'type' => 'date', 'validation' => 'required|date', 'width' => 'col-sm-4', 'value' => '2024-01-01'];
		$this->form[] = ['label' => 'Tanggal Tagih', 'name' => 'siklus', 'type' => 'number', 'validation' => 'numeric|min:1|max:31', 'width' => 'col-sm-1', 'value' => '1'];
		$this->form[] = ['label' => 'Gps', 'name' => 'gps', 'type' => 'osm', 'validation' => 'min:1|max:255', 'width' => 'col-sm-10', 'value' => '-2.165227,115.382979'];
		$this->form[] = ['label' => 'Lat', 'name' => 'lat', 'type' => 'text', 'validation' => 'min:1|max:255', 'width' => 'col-sm-5'];
		$this->form[] = ['label' => 'Lon', 'name' => 'lon', 'type' => 'text', 'validation' => 'min:1|max:255', 'width' => 'col-sm-5'];
		$this->form[] = ['label' => 'Mode', 'name' => 'mode', 'type' => 'select', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-5', 'dataenum' => '0|Analog;1|STB;2|K-Vision'];
		// 			$this->form[] = ['label'=>'Cms Users Id','name'=>'cms_users_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-10','datatable'=>'cms_users,name'];
		// $this->form[] = ['label' => 'Photo', 'name' => 'photo', 'type' => 'upload', 'width' => 'col-sm-5'];
		$this->form[] = ['label' => 'Catatan', 'name' => 'keterangan', 'type' => 'textarea', 'validation' => 'min:1|max:255', 'width' => 'col-sm-5'];

		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ["label"=>"Pelanggan","name"=>"id_pelanggan","type"=>"select2","required"=>TRUE,"validation"=>"required|min:1|max:255","datatable"=>"pelanggan,nama_pelanggan"];
		//$this->form[] = ["label"=>"Nama Pelanggan","name"=>"nama_pelanggan","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"No Telp","name"=>"no_telp","type"=>"number","required"=>TRUE,"validation"=>"required|numeric","placeholder"=>"Anda hanya dapat memasukkan angka saja"];
		//$this->form[] = ["label"=>"Alamat","name"=>"alamat","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
		//$this->form[] = ["label"=>"Gps","name"=>"gps","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Lat","name"=>"lat","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Lon","name"=>"lon","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Cms Users Id","name"=>"cms_users_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"cms_users,name"];
		//$this->form[] = ["label"=>"Updated By","name"=>"updated_by","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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
		$this->sub_module[] = ['label' => 'Tagihan', 'path' => 'pelanggan_tagihan', 'parent_columns' => 'id_pelanggan,nama_pelanggan,no_telp,alamat', 'foreign_key' => 'id_pelanggan', 'button_color' => 'warning', 'button_icon' => 'fa fa-bill'];
		$this->sub_module[] = ['label' => 'Penjualan', 'path' => 'pelanggan_pembelian', 'parent_columns' => 'id_pelanggan,nama_pelanggan,no_telp', 'foreign_key' => 'id_pelanggan', 'button_color' => 'success', 'button_icon' => 'fa fa-bill'];


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
		$this->addaction[] =  ['label' => 'Maps', 'url' => 'https://www.google.com/maps/place/[lat],[lon]', 'icon' => 'fa fa-reg', 'showIf' => '[lat] <> ""', 'style' => 'dropdown'];
		if (CRUDBooster::myParentPriv() == 0) {
			$this->addaction[] =  ['label' => 'Remote', 'url' => 'pelanggan/remote/[id_pelanggan]', 'icon' => 'fa fa-remote', 'color' => 'info', 'showIf' => '[last_update] <= 3'];
			$this->addaction[] =  ['label' => 'Registrasi', 'url' => 'pelanggan/register/[id_pelanggan]', 'icon' => 'fa fa-reg', 'color' => 'danger', 'showIf' => '[last_update] >= 3', 'confirmation' => true];
		}




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
		$this->index_button[] = ['name' => 'Refresh', 'url' => Request::fullUrl(), 'icon' => 'fa fa-refresh'];


		/* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
		$this->table_row_color = array();
		$this->table_row_color[] = ['condition' => '[status] == expired', 'color' => 'danger'];
		$this->table_row_color[] = ['condition' => '[status] == ""', 'color' => 'danger'];
		$this->table_row_color[] = ['condition' => '[last_update] <= 3', 'color' => 'success'];


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
		$this->script_js = "";


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
		$this->load_js[] = asset("remotemon.js");



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
	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here

	}


	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	public function hook_query_index(&$query)
	{
		if (CRUDBooster::myPrivilegeId() != 1) {
			if (CRUDBooster::myParentPriv() == 0) {
				$query->where('cms_privileges_id', CRUDBooster::myPrivilegeId());
			} else {
				$query->where('cms_privileges_id', CRUDBooster::myParentPriv());
			}
		}

		//Your code here

	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */
	public function hook_row_index($column_index, &$column_value)
	{
		//Your code here
	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	public function hook_before_add(&$postdata)
	{
		if (CRUDBooster::myPrivilegeId() != 1) {
			if (CRUDBooster::myParentPriv() == 0) {
				$postdata['cms_privileges_id'] = CRUDBooster::myPrivilegeId();
			} else {
				$postdata['cms_privileges_id'] = CRUDBooster::myParentPriv();
			}
			$postdata['updated_by'] = CRUDBooster::myId();
		}
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	public function hook_after_add($id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	public function hook_before_edit(&$postdata, $id)
	{
		if (CRUDBooster::myPrivilegeId() != 1) {
			if (CRUDBooster::myParentPriv() == 0) {
				$postdata['cms_privileges_id'] = CRUDBooster::myPrivilegeId();
			} else {
				$postdata['cms_privileges_id'] = CRUDBooster::myParentPriv();
			}
			$postdata['updated_by'] = CRUDBooster::myId();
		}
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_edit($id)
	{

		//Your code here 

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_before_delete($id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_delete($id)
	{
		//Your code here

	}


	public function gen_id()
	{
		$num_str = sprintf("%06d", mt_rand(1, 999999));

		$notunik = DB::table('pelanggan')->where('id', $num_str)->first();

		while ($notunik) {
			$this->gen_id();
		}

		return $num_str;
	}

	public function getRemote($id)
	{
		$data = [];
		$data['pelanggan'] = DB::table('pelanggan')->where('id_pelanggan', $id)->first();
		// $remote_update = strtotime($data['pelanggan']->remote_update);
		// $sekarang = date("Y-m-d H:i:s");
		// $data['remote_update'] = strtotime($sekarang) - $remote_update;
		return view('remote.index', $data);
	}

	public function getRegister($id)
	{
		$data = [];
		$data['pelanggan'] = DB::table('pelanggan')->where('id_pelanggan', $id)->first();
		return view('register.index', $data);
	}

	public function postRegister()
	{
		$data = [];
		$data['pelanggan'] = DB::table('pelanggan')->where('id_pelanggan', $id)->first();
		return view('register.index', $data);
	}

	public function getTagihan()
	{
		$data = [];
		if (CRUDBooster::myPrivilegeId() != 1) {
			if (CRUDBooster::myParentPriv() == 0) {
				$data['pelanggan'] = DB::table('pelanggan')->where('cms_privileges_id', CRUDBooster::myPrivilegeId())->orderBy('nama_pelanggan', 'DESC')->get();
			} else {
				$data['pelanggan'] = DB::table('pelanggan')->where('cms_privileges_id', CRUDBooster::myParentPriv())->orderBy('nama_pelanggan', 'DESC')->get();
			}
		}else{
			$data['pelanggan'] = DB::table('pelanggan')->orderBy('nama_pelanggan', 'ASC')->get();
		}
		
		return view('tagihan.index', $data);
	}


	//By the way, you can still create your own method in here... :) 


}
