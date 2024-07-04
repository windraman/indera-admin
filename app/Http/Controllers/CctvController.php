<?php namespace App\Http\Controllers;

    use Session;
	use Request;
	use DB;
	use Carbon;
	use Illuminate\Pagination\LengthAwarePaginator;

    class CctvController extends Controller {
        public function getIndex() {
            $data = [];
            $data['app_name'] = "CCTV";
            $data['title'] = "BERANDA";
            
            // $data['lokasi'] = DB::table('informasi')
            //                     ->join('cms_users','informasi.created_by','cms_users.id')
            //                     ->join('kategori','informasi.kategori_id','kategori.id')
            //                     ->select(DB::raw('informasi.*,cms_users.name as author, kategori.nama_kategori'))
            //                     ->get();
                                

                                
        //var_dump(asset('vendor/blog/vendor/bootstrap/css/bootstrap.min.css'));
             return view('intel.index',$data);
        }
        
        public function getPeta($id) {
            $data = [];
            $data['app_name'] = "INTEL";
            $data['title'] = "PETA";
            $data['strategis_bidang_id'] = $id;
            $data['bidang'] = DB::table('strategis_bidang')->where('id',$id)->first();
                                

            return view('intel.osm.index',$data);
        }
        
        public function getStruktur() {
            $data = [];
            $data['app_name'] = "INTEL";
            $data['title'] = "STRUKTUR ORGANISASI";
                                

            return view('intel.struktur',$data);
        }
        
        // public function getGallery() {
        //     $data = [];
        //     $data['app_name'] = "INTEL";
        //     $data['title'] = "KEGIATAN";
            
        //     $data['kegiatan'] = DB::table('kegiatan_links')
        //                         ->join('kegiatan','kegiatan.id','kegiatan_links.kegiatan_id')
        //                         ->selectRaw('kegiatan_links.*,kegiatan_links.link as photo, kegiatan.judul, kegiatan.deskripsi, kegiatan.tanggal')
        //                         ->orderBy('kegiatan.tanggal','desc')
        //                         ->paginate(8);
                                
        //     foreach($data['kegiatan'] as $keg){
        //         if(!$keg->photo){
        //             $keg->photo = $keg->gambar;
        //         }else{
        //             $keg->photo = 'https://drive.google.com/uc?export=view&id=' . $keg->photo;
        //         }
        //     }
        //     // dd($data['kegiatan']);
        //     return view('gallery.index',$data);
        // }
        
        public function getGrid($owner,$status,$publik,$keyword) {
            $data = [];
            $data['app_name'] = "CCTV";
            $data['title'] = "PANTAU CCTV (". $owner .")";
            $data['owner'] = $owner;
            $data['status'] = $status;
            $data['publik'] = $publik;
            $data['keyword'] = $keyword;

            $data['back'] = 'https://pantaucctv.online?'.$owner;

            if($keyword=="all"){
                $data['kegiatan'] = DB::table('cctv_auth')
                                    //->rightJoin('cctv_counter','cctv_auth.id','cctv_counter.cctv_auth_id')
                                    ->where('cctv_auth.owner',$owner)
                                    ->where('cctv_auth.status',$status)
                                    ->where('cctv_auth.publik',$publik)
                                    //->whereRaw('DATE(cctv_counter.updated_at) = DATE(now())')
                                     //->select('cctv_auth.*', DB::raw("(SELECT COALESCE(COUNT(cctv_counter.id),0) FROM cctv_counter WHERE cctv_counter.cctv_auth_id = cctv_auth.id AND DATE(cctv_counter.updated_at) = DATE(now()) ) as harian"))
                                    //  ->select('cctv_auth.*', DB::raw("(SELECT COALESCE(COUNT(cctv_counter.id),0) FROM cctv_counter WHERE cctv_counter.cctv_auth_id = cctv_auth.id AND DATE(cctv_counter.updated_at) = DATE(now()) ) as harian"))
                                    // ->groupBy('cctv_auth.id')
                                    ->orderBy('display_name','asc')
                                    ->paginate(8);
            }else{
                $data['kegiatan'] = DB::table('cctv_auth')
                                    //->rightJoin('cctv_counter','cctv_auth.id','cctv_counter.cctv_auth_id')
                                    ->where('cctv_auth.owner',$owner)
                                    ->where('cctv_auth.status',$status)
                                    ->where('cctv_auth.publik',$publik)
                                    ->where('display_name','like','%'. $keyword .'%')
                                    ->orWhere('deskripsi','like','%'. $keyword .'%')
                                    ->orWhere('name','like','%'. $keyword .'%')
                                    //->whereRaw('DATE(cctv_counter.updated_at) = DATE(now())')
                                     //->select('cctv_auth.*', DB::raw("(SELECT COALESCE(COUNT(cctv_counter.id),0) FROM cctv_counter WHERE cctv_counter.cctv_auth_id = cctv_auth.id AND DATE(cctv_counter.updated_at) = DATE(now()) ) as harian"))
                                    //  ->select('cctv_auth.*', DB::raw("(SELECT COALESCE(COUNT(cctv_counter.id),0) FROM cctv_counter WHERE cctv_counter.cctv_auth_id = cctv_auth.id AND DATE(cctv_counter.updated_at) = DATE(now()) ) as harian"))
                                    // ->groupBy('cctv_auth.id')
                                    ->orderBy('display_name','asc')
                                    ->paginate(8);
            }
                                
            
          // $data['total_played'] = DB::table('cctv_auth')->where('owner',$owner)->sum('clicked');
        //    $data['todays_played'] = DB::table('cctv_counter')->join('cctv_auth','cctv_auth.id','cctv_counter.cctv_auth_id')->where('cctv_auth.owner',$owner)->whereRaw('DATE(cctv_counter.updated_at) = DATE(now())')->count('cctv_counter.id');
            
            

            return view('gallery.index',$data);
        }
        
        public function postGrid($owner,$status,$publik) {
            $data = [];
            $data['app_name'] = "CCTV";
            $data['title'] = "CARI";
            $data['owner'] = $owner;
            $data['status'] = $status;
            $data['publik'] = $publik;
            $data['keyword'] = Request::input('cari');
            
            $data['back'] = 'https://pantaucctv.online?'.$owner;
                  
                                
         
                $data['kegiatan'] = DB::table('cctv_auth')
                                    //->rightJoin('cctv_counter','cctv_auth.id','cctv_counter.cctv_auth_id')
                                    ->where('cctv_auth.owner',$owner)
                                    ->where('cctv_auth.status',$status)
                                    ->where('cctv_auth.publik',$publik)
                                    ->where('display_name','like','%'. Request::input('cari') .'%')
                                    ->orWhere('deskripsi','like','%'. Request::input('cari') .'%')
                                    ->orWhere('name','like','%'. Request::input('cari') .'%')
                                    //->whereRaw('DATE(cctv_counter.updated_at) = DATE(now())')
                                     //->select('cctv_auth.*', DB::raw("(SELECT COALESCE(COUNT(cctv_counter.id),0) FROM cctv_counter WHERE cctv_counter.cctv_auth_id = cctv_auth.id AND DATE(cctv_counter.updated_at) = DATE(now()) ) as harian"))
                                     ->select('cctv_auth.*', DB::raw("(SELECT COALESCE(COUNT(cctv_counter.id),0) FROM cctv_counter WHERE cctv_counter.cctv_auth_id = cctv_auth.id AND DATE(cctv_counter.updated_at) = DATE(now()) ) as harian"))
                                    ->groupBy('cctv_auth.id')
                                    ->orderBy('harian','desc')
                                    ->paginate(8);
       
            //dd($data);

            return view('gallery.index',$data);
        }
        
        public function getGridCari() {
            $data = [];
            $data['app_name'] = "CCTV";
            $data['title'] = "CARI";
                                
                                
               

            return view('gallery.index',$data);
        }
        
        public function getPhotoDetail($id) {
            $data = [];
            $data['app_name'] = "INTEL";
            $data['title'] = "DETAIL";
            
            $data['detail'] = DB::table('kegiatan_links')
                                ->join('kegiatan','kegiatan.id','kegiatan_links.kegiatan_id')
                                ->where('kegiatan_links.id',$id)
                                ->selectRaw('kegiatan_links.*,kegiatan_links.link as photo, kegiatan.judul, kegiatan.deskripsi, kegiatan.tanggal')
                                ->orderBy('kegiatan.tanggal','desc')
                                ->first(); 
           
            //$data['detail'] = DB::table('kegiatan')->where('id',$id)->first();    
            
            DB::table('kegiatan_links')->where('id',$id)->update(['clicked'=>$data['detail']->clicked+1]);  
            
            $data['kegiatan'] = DB::table('kegiatan_links')
                                ->join('kegiatan','kegiatan.id','kegiatan_links.kegiatan_id')
                                ->where('kegiatan_links.id','<>',$id)
                                ->where('kegiatan.judul','like','%' . $data['detail']->judul . '%')
                                ->selectRaw('kegiatan_links.*,kegiatan_links.link as photo, kegiatan.judul, kegiatan.deskripsi, kegiatan.tanggal')
                                ->orderBy('kegiatan.tanggal','desc')
                                ->limit(8)
                                ->get(); 
            
           // $data['kegiatan'] = DB::table('kegiatan')->where('id','<>',$id)->where('judul','like','%' . $data['detail']->judul . '%')->orderBy('tanggal','desc')->get();       

            return view('gallery.photo_detail',$data);
        }
        
        public function postCariKegiatan() {
            $data = [];
            $data['app_name'] = "INTEL";
            $data['title'] = "CARI";
                                
                                
            $data['kegiatan'] = DB::table('kegiatan_links')
                                ->join('kegiatan','kegiatan.id','kegiatan_links.kegiatan_id')
                                ->where('kegiatan.judul','like','%'. Request::input('cari') .'%')
                                ->selectRaw('kegiatan_links.*,kegiatan_links.link as photo, kegiatan.judul, kegiatan.deskripsi, kegiatan.tanggal')
                                ->orderBy('kegiatan.tanggal','desc')
                                ->paginate(8);                 

            return view('gallery.index',$data);
        }
        
        public function getAddLapdu() {
            $data = [];
            $data['app_name'] = "INTEL";
            $data['title'] = "LAPDU";
                                

            return view('intel.lapdu',$data);
        }
        
    }