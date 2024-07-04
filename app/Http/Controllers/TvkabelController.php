<?php namespace App\Http\Controllers;

    use Session;
	use Request;
	use DB;
	use Carbon;
	use Illuminate\Pagination\LengthAwarePaginator;

    class TvkabelController extends Controller {
        public function getCari() {
            $data = [];
            $data['app_name'] = "TV KABEL";
            $data['title'] = "PENCARIAN";
            
             return view('tvkabel.cari.index',$data);
        }

        
        public function postCari() {
            $data = [];
            $data['app_name'] = "TV KABEL";
            $data['title'] = "PENCARIAN";
            $data['keyword'] = Request::input('id_pelanggan');
            $sekarang = date("Y-m-d H:i:s");
            
            // $data['back'] = 'https://pantaucctv.online?'.$owner;
                  
                                
         
            $data['pelanggan'] = DB::table('pelanggan')
                                    ->where('id_pelanggan',Request::input('id_pelanggan'))
                                    ->first();


            $data['terbayar'] = [];
            $num = 0;
            // dd(intval(date('m')));
            for ($m = 1; $m <= 12; $m++) {
                $num++;
                $cari = DB::table('pelanggan_tagihan')
                                ->where('pelanggan_tagihan.id_pelanggan',$data['pelanggan']->id)
                                ->where("pelanggan_tagihan.bulan",$m)
                                ->where("pelanggan_tagihan.tahun",date('Y'))
                                ->selectRaw("pelanggan_tagihan.*")
                                ->first();
                
                $beda = date_diff(date_create(date('y') . "-" . $m . "-" . $data['pelanggan']->siklus), date_create(now()));
                $mulai = date_diff( date_create(date('y') . "-" . $m . "-" . $data['pelanggan']->siklus),date_create($data['pelanggan']->tanggal_mendaftar));
                if($cari){
                    $cari->no = $num;
                    $cari->status = 'lunas';
                }else{
                    $cari->no = $num;
                    $cari->bulan = $m;
                    $cari->jumlah = "30.000";
                    
                    if($beda->invert == 0 && $beda->days <= $data['pelanggan']->siklus){
                        if($beda->days == 0){
                            $cari->status = "berakhir dalam " . (24 - $beda->h) . " jam.";
                        }else{
                            $cari->status = "berakhir dalam " . $beda->days . " hari.";
                        }
                    }elseif($beda->invert == 0 && $beda->days >= $data['pelanggan']->siklus){
                        if($mulai->invert==1 && $mulai->m == 0){
                            $cari->status = "pelanggan baru";
                        }elseif($mulai->invert==0){
                            $cari->status = "belum langganan";
                        }else{
                            $cari->status = "belum bayar";
                        }
                        
                    }elseif($beda->invert == 1 && $beda->days > $data['pelanggan']->siklus){
                        $cari->status = "belum masuk bulan";
                    }elseif($beda->invert == 1 && $beda->days <= $data['pelanggan']->siklus){
                        if($beda->days == 0){
                            $cari->status = "berakhir dalam " . (24 - $beda->h) . " jam.";
                        }else{
                            $cari->status = "berakhir dalam " . $beda->days . " hari.";
                        }
                    }
                    
                }
                $cari->siklus = $data['pelanggan']->siklus;
                $cari->beda =$beda;
                $cari->mulai =$mulai;
                array_push($data['terbayar'],$cari);
            }
            
            // dd($data);

            return view('tvkabel.cari.index',$data);
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