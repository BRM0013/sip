<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Users;
use App\Models\MasterData\JenisSurat;
use App\Models\Provinsi;
use App\Models\SyaratPengajuan;
use DB, Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){        
        $data['mn_active'] = "dashboard";
        
        $data['list_provinsi']              = Provinsi::all();
        // $data['total_pengajuan']            = count(Surat::all());    

        $data['total_pengajuan']            = Surat::join('users as u','u.id','surat.id_user')
                                                   ->where('status_aktif', 'Menunggu')
                                                   ->where('status_simpan','simpan')
                                                   // ->where('disetujui_admin','Menunggu')
                                                   // ->where('disetujui_kasi','Menunggu')
                                                   // ->where('disetujui_kabid','Menunggu')
                                                   // ->where('disetujui_kabid','Menunggu')
                                                   // ->where('disetujui_kadinkes','Menunggu')
                                                   // ->where('file_surat_sip_asli',null)
                                                   // ->where('file_surat_sip_salinan',null)
                                                   ->count();

        $data['total_pengajuan_disetujui']  = count(Surat::where('status_aktif', 'Sudah Diambil')->get());
        $data['total_pengajuan_ditolak']    = count(Surat::where('status_aktif', 'Tolak')->get());
        $data['total_pengajuan_dicabut']    = count(Surat::where('status_aktif', 'Dicabut')->get());
        $data['total_pengajuan_kedaluarsa'] = count(Surat::where('tanggal_kedaluarsa', '<=', date('Y-m-d').' 23:59:59')->get());
        $data['total_pengajuan_revisi']     = count(Surat::where('status_aktif', 'Revisi')->get());        
        $data['recordAktif'] = Surat::select(DB::raw('count(status_aktif) as jumlah, YEAR(tanggal_disetujui_kabid) AS tahun'))
                                      ->where('surat.status_aktif', 'Sudah Diambil')
                                      ->groupBy('tahun')
                                      ->orderBy('tahun', 'DESC')
                                      ->get();

        $data['cekusers'] = Users::where('id', Auth::getUser()->id)->first();
        $data['surat'] = Surat::join('users','surat.id_user', 'users.id')
                                       ->where('surat.id_user',$data['cekusers']->id)
                                       ->groupBy('users.id')
                                       ->get();        
        $noS = 0;
        foreach ($data['surat'] as $key) {
          $data['surat'][$noS]['row'] = SyaratPengajuan::join('users','syarat_pengajuan.id_user','users.id')
                                                       ->join('surat','syarat_pengajuan.id_surat','surat.id_surat')
                                                       ->join('jenis_persyaratan','syarat_pengajuan.id_jenis_persyaratan','jenis_persyaratan.id_jenis_persyaratan')
                                                       ->where('syarat_pengajuan.id_surat', $key->id_surat)
                                                       ->groupBy('users.id')
                                                       ->get();
          $noS++;
        }
        $data['list_surat'] = $data['surat'];      
        return view('home', $data);
    }

    public function grafik(Request $request){
        $tanggal_awal = $request->tanggal_awal == '' ? date('Y-m-d').' 00:00:00' : $request->tanggal_awal.' 00:00:00';
        $tanggal_akhir = $request->tanggal_akhir == '' ? date('Y-m-d').' 23:59:59' : $request->tanggal_akhir.' 23:59:59';;

        $grafik_objek = array();
        $keys = array();

        $data['jenis_surat'] = JenisSurat::where('id_jenis_surat', '!=', '28')->get();

        foreach ($data['jenis_surat'] as $row) {
            $all = Surat::join('users as u', 'u.id', '=', 'surat.id_user')->whereBetween('surat.tanggal_pengajuan', [$tanggal_awal, $tanggal_akhir])->where('surat.id_jenis_surat', $row->id_jenis_surat);
            $disetujui = Surat::join('users as u', 'u.id', '=', 'surat.id_user')->whereBetween('surat.tanggal_pengajuan', [$tanggal_awal, $tanggal_akhir])->where('surat.id_jenis_surat', $row->id_jenis_surat);
            $tolak = Surat::join('users as u', 'u.id', '=', 'surat.id_user')->whereBetween('surat.tanggal_pengajuan', [$tanggal_awal, $tanggal_akhir])->where('surat.id_jenis_surat', $row->id_jenis_surat);
            $cabut = Surat::join('users as u', 'u.id', '=', 'surat.id_user')->whereBetween('surat.tanggal_pengajuan', [$tanggal_awal, $tanggal_akhir])->where('surat.id_jenis_surat', $row->id_jenis_surat);


            if (!empty($request->status_kepegawaian)) {
                $all->where('u.status_kepegawaian', $request->status_kepegawaian);
                $disetujui->where('u.status_kepegawaian', $request->status_kepegawaian);
                $tolak->where('u.status_kepegawaian', $request->status_kepegawaian);
                $cabut->where('u.status_kepegawaian', $request->status_kepegawaian);
            }

            if (!empty($request->provinsi)) {
                $all->where('u.id_provinsi', $request->provinsi);
                $disetujui->where('u.id_provinsi', $request->provinsi);
                $tolak->where('u.id_provinsi', $request->provinsi);
                $cabut->where('u.id_provinsi', $request->provinsi);
            }


            if (!empty($request->kabupaten) && $request->wilayah == 'Diwilayah') {
                $all->where('u.id_kabupaten', $request->kabupaten);
                $disetujui->where('u.id_kabupaten', $request->kabupaten);
                $tolak->where('u.id_kabupaten', $request->kabupaten);
                $cabut->where('u.id_kabupaten', $request->kabupaten);
            }else if(!empty($request->kabupaten) && $request->wilayah == 'Di Luar wilayah'){
                $all->where('u.id_kabupaten', '!=', $request->kabupaten);
                $disetujui->where('u.id_kabupaten', '!=', $request->kabupaten);
                $tolak->where('u.id_kabupaten', '!=', $request->kabupaten);
                $cabut->where('u.id_kabupaten', '!=', $request->kabupaten);
            }


            if (!empty($request->kecamatan) && $request->wilayah == 'Diwilayah') {
                $all->where('u.id_kecamatan', $request->kecamatan);
                $disetujui->where('u.id_kecamatan', $request->kecamatan);
                $tolak->where('u.id_kecamatan', $request->kecamatan);
                $cabut->where('u.id_kecamatan', $request->kecamatan);
            }else if (!empty($request->kecamatan) && $request->wilayah == 'Di Luar wilayah') {
                $all->where('u.id_kecamatan', '!=', $request->kecamatan);
                $disetujui->where('u.id_kecamatan', '!=', $request->kecamatan);
                $tolak->where('u.id_kecamatan', '!=', $request->kecamatan);
                $cabut->where('u.id_kecamatan', '!=', $request->kecamatan);
            }


            if (!empty($request->desa) && $request->wilayah == 'Diwilayah') {
                $all->where('u.id_desa', $request->desa);
                $disetujui->where('u.id_desa', $request->desa);
                $tolak->where('u.id_desa', $request->desa);
                $cabut->where('u.id_desa', $request->desa);
            }else if (!empty($request->desa) && $request->wilayah == 'Di Luar wilayah') {
                $all->where('u.id_desa', '!=', $request->desa);
                $disetujui->where('u.id_desa', '!=', $request->desa);
                $tolak->where('u.id_desa', '!=', $request->desa);
                $cabut->where('u.id_desa', '!=', $request->desa);
            }        


            $total_pengajuan = count($all->get());
            $total_pengajuan_disetujui = count($disetujui->where('status_aktif', 'Aktif')->get());
            $total_pengajuan_ditolak = count($tolak->where('status_aktif', 'Tolak')->get());
            $total_pengajuan_dicabut = count($cabut->where('status_aktif', 'Dicabut')->get());
            
            array_push($keys, $row->nama_surat);
            $grafik_objek[$row->nama_surat] = [$total_pengajuan, $total_pengajuan_disetujui, $total_pengajuan_ditolak, $total_pengajuan_dicabut];
        }

        return json_encode(array('data' => $grafik_objek, 'keys' => $keys));
    }
}
