<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailableTolak;

use App\Models\Surat;
use App\Models\SuratTemp;

use App\Models\Users;
use App\Models\LogRevisiSurat;
use App\Models\SyaratPengajuan;
use App\Models\MasterData\JenisSurat;
use App\Models\MasterData\JenisPersyaratan;
use App\Models\MasterData\TemplateSurat;
use App\Models\MasterData\JenisSarana;
use App\Models\SuratDiluar;
use App\Models\MasterData\MasterTtdKadinkes;
use App\Models\MasterData\Fasyankes;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Http\Libraries\Formatters;
use App\Http\Libraries\Whatsapp;

use File, Auth, Redirect, Validator, DB, PDF;

class SuratController extends Controller{
  public function __construct() {
    $this->middleware('auth');
  }

  public function index(Request $request){

    if (Auth::User()->id_level_user == 7) {
      return view('Surat.surat_rs');

    }else{
      $data['jenis_surat'] = $jenis_surat = !isset($request->id_jenis_surat) ?
      JenisSurat::find(Auth::getUser()->id_jenis_surat) :
      JenisSurat::find($request->id_jenis_surat);

      $jumlah_surat_didalam = isset(Auth::getUser()->id_jenis_surat) ? count(Surat::where('id_user', Auth::getUser()->id)->where('status_aktif', 'Menunggu')->orWhere('status_aktif', 'Aktif')->where('id_user', Auth::getUser()->id)->orWhere('status_aktif', 'Revisi')->where('id_user', Auth::getUser()->id)->get()) : 5;
      $jumlah_surat_diluar = count(SuratDiluar::where('id_user', Auth::getUser()->id)->where('status_aktif', 'Aktif')->get());
      $data['maksimal_pengajuan'] = $jumlah_surat_didalam + $jumlah_surat_diluar;
      $data['list_jenis_praktik'] = DB::select("SELECT * FROM `jenis_praktik` WHERE `id_jenis_praktik` IN ($jenis_surat->jenis_praktik)");
      $data['id_surat'] = !isset($request->id_jenis_surat) ? '' : $request->id_jenis_surat;

      return view('Surat.surat', $data);
    }

  }

  public function create(Request $request){
    // return $request->id_surat;
    $data['surat'] = Surat::find($request->id_surat);
    $data['users'] = isset($request->id_surat) ? Users::find($data['surat']->id_user) : Users::find(Auth::getUser()->id);

    $data['jenis_surat'] = isset($data['surat']) ? JenisSurat::find($data['surat']->id_jenis_surat) : JenisSurat::find(Auth::getUser()->id_jenis_surat);
    $data['list_surat'] = Surat::where('id_user', $data['users']->id)->where('status_aktif', 'Aktif')->orWhere('status_aktif', 'Menunggu')->where('id_user', $data['users']->id)->get();
    $data['list_jenis_sarana'] = JenisSarana::all();
    $data['list_fasyankes']           = Fasyankes::all();

    if($request->jenis== 'perpanjangan'){
      $data['perpanjangan'] = '/perpanjangan';
    }else if($request->jenis== 'pencabutan'){
      $data['perpanjangan'] = '/pencabutan';
    }else if($request->jenis== 'pencabutan_pindah'){
      $data['perpanjangan'] = '/pencabutan_pindah';
    }else{
      $data['perpanjangan'] = '';
    }

    if (isset($data['surat']) && $request->jenis != 'perpanjangan') {
      $data['syarat_persyaratan'] = SyaratPengajuan::where('id_surat', $data['surat']->id_surat)->get();
    }

    $ts_izin_praktik = JenisSurat::find($data['users']->id_jenis_surat);
    $data['list_ts_praktik'] = DB::select("SELECT * FROM `jenis_praktik` WHERE `id_jenis_praktik` IN ($ts_izin_praktik->jenis_praktik)");

    if ($request->jenis == 'pencabutan') {
      $persyratan = $data['jenis_surat']->syarat_pencabutan;
    }else if ($request->jenis == 'perpanjangan' || (!empty($data['surat']) && $data['surat']->status_perpanjangan=='Perpanjangan')) {
      $persyratan = $data['jenis_surat']->syarat_perpanjangan;
    }else{
      $persyratan = $data['jenis_surat']->syarat_pengajuan;
    }

    if (!empty($request->id_surat) && $request->jenis == 'pencabutan_pindah') {
      $persyratan_pindah_praktik = $data['jenis_surat']->syarat_pindah_tempat;

      if (empty($persyratan_pindah_praktik)) {
        return ['status'=>'warning','code'=>500,'message'=>'Untuk Persyaratan Pindah Tempat praktek Tenaga Kesehatan '.$data['jenis_surat']->nama_surat. ' Mohon hubungi admin','data'=>''];
      }

      $data['pencabutan_pindah'] = 'true';
      $data['berkas_persyaratan_baru'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan_pindah_praktik)");
    }else{
      $data['pencabutan_pindah'] = 'false';
    }

    $data['berkas_persyaratan'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");

    if ($request->jenis == 'pencabutan' || $request->jenis == 'pencabutan_pindah' ) {
      return view('Surat.pencabutan_surat', $data);
    }else{
      return view('Surat.add_surat', $data);
    }

  }

  public function storePreview(Request $request)
  {
    // return $request->all();
    if ($request->id_surat == 0) {
      $id_jenis_surat = ($request->jenis == 'perpanjangan') ? Surat::find($request->id_surat)->id_jenis_surat : Auth::getUser()->id_jenis_surat;
      $JenisSurat = JenisSurat::find($id_jenis_surat);

      $check_jumlah_str_didalam = Surat::where('nomor_str', $request->nomor_str)->where('status_aktif','Aktif')->get();
      $check_jumlah_str_diluar = SuratDiluar::where('nomor_str', $request->nomor_str)->where('status_aktif','Aktif')->get();
      if ((count($check_jumlah_str_diluar)+count($check_jumlah_str_didalam)) >= $JenisSurat->maksimal_pengajuan) {
        return Redirect::to('home/surat/list/'.$JenisSurat->id_jenis_surat)
        ->with('title', 'Maaf!')
        ->with('message', 'Nomor STR ini telah digunakan!')
        ->with('type', 'success');
      }
    }


    $surat = new SuratTemp();
    $surat_asli = null;

    if ($request->id_surat != 0) {
      $surat_asli = Surat::find($request->id_surat);
    }

    $surat->id_user = $request->id_pemohon;
    $surat->id_jenis_surat = $request->id_jenis_surat;
    $surat->nomor_surat      = 0;


    if ($request->pencabutan_pindah == 'true' && $request->id_surat != 0) {

      $surat_lama = Surat::find($request->id_surat);
      $JenisSurat = JenisSurat::find($surat_lama->id_jenis_surat);

      $surat->id_jenis_sarana            = $surat_lama->jenis_sarana;
      $surat->nama_tempat_praktik        = strip_tags(strtoupper($request->nama_tempat_praktik_terbaru));
      $surat->alamat_tempat_praktik      = strip_tags(strtoupper($request->alamat_tempat_praktik_terbaru));
      $surat->keterangan_jenis_praktik   = $surat_lama->keterangan_jenis_praktik;
      $surat->nomor_str                  = $surat_lama->nomor_str;
      $surat->nomor_op                   = strip_tags($request->nomor_rekom_terbaru);
      $surat->waktu_praktik              = $surat_lama->waktu_praktik;
      $surat->id_jenis_praktik           = $surat_lama->id_jenis_praktik;
      $surat->sip_ke                     = $surat_lama->sip_ke;
      $surat->sebagai_jabatan            = $surat_lama->sebagai_jabatan;
      $surat->tanggal_berlaku_str        = $surat_lama->tanggal_berlaku_str;
      $surat->id_jenis_surat             = $surat_lama->id_jenis_surat;
    }else{
      $surat->id_jenis_sarana            = $request->jenis_sarana;
      $surat->nama_tempat_praktik        = strip_tags(strip_tags($request->nama_tempat_praktik));
      $surat->alamat_tempat_praktik      = strip_tags(strip_tags($request->alamat_tempat_praktik));
      $surat->keterangan_jenis_praktik   = strip_tags(strip_tags($request->keterangan_jenis_praktik));
      $surat->nomor_str                  = strip_tags(strip_tags($request->nomor_str));
      $surat->nomor_op                   = strip_tags($request->nomor_op);
      $surat->waktu_praktik              = isset($request->waktu_praktik) ? $request->waktu_praktik : '';
      $surat->id_jenis_praktik           = strip_tags(strtoupper($request->jenis_praktik));
      $surat->sip_ke                     = $request->sip_ke ?? '';
      $surat->sebagai_jabatan            = strip_tags(strtoupper($request->sebagai_jabatan));
      $surat->tanggal_berlaku_str        = $request->tanggal_berlaku_str;
      $surat->id_jenis_surat             = $request->id_jenis_surat;


      if ($request->pencabutan_pindah == 'false') {
        $surat->status_aktif             = 'Dicabut';
      }

    }

    $surat->status_simpan              = 'preview';
    $surat->tanggal_pengajuan          = date('Y-m-d H:i:s');

    $saved = $surat->save();
    if ($saved) {
      if ($request->btn_type == 'preview') {
        // return "atas";
        $this->cetak_pdf($surat, $request->btn_type);
        $messages = "Preview";
        return [
          'status'=>'success',
          'code'=>'200',
          'message'=>$messages,
          'data'=>$surat
        ];
      }else{
        // return "bawah";
        return [
          'status'=>'success',
          'code'=>'200',
          'message'=>'Data berhasil ditambahkan',
          'data'=>$surat
        ];
      }
    }else{
      return [
        'status'=>'success',
        'code'=>'500',
        'message'=>'Gagal',
        'data'=>''
      ];
    }
  }

  public function store(Request $request){
    // return $request->all();
    if (!empty($request->file('sip_1'))) {
      $ext = $request->file('sip_1')->getClientOriginalExtension();
      if ($ext != 'pdf' && $ext != 'PDF' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
         return Redirect::route('add_surat')
              ->with('title', 'Gagal !')
              ->with('message', 'Maaf! file Upload tidak sesuai')
              ->with('type', 'error');
      }
    }

    if (!empty($request->file('sip_2'))) {
      $ext = $request->file('sip_2')->getClientOriginalExtension();
      if ($ext != 'pdf' && $ext != 'PDF' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
         return Redirect::route('add_surat')
              ->with('title', 'Gagal !')
              ->with('message', 'Maaf! file Upload tidak sesuai')
              ->with('type', 'error');
      }
    }

    $check_jumlah_str_didalam = Surat::where('nomor_str', $request->nomor_str)->where('status_aktif','Aktif')->get();
    $check_jumlah_str_diluar = SuratDiluar::where('nomor_str', $request->nomor_str)->where('status_aktif','Aktif')->get();

    if ($request->id_surat == 0 || $request->jenis == 'perpanjangan') {
      $id_jenis_surat = ($request->jenis == 'perpanjangan') ? Surat::find($request->id_surat)->id_jenis_surat : Auth::getUser()->id_jenis_surat;
      $JenisSurat = JenisSurat::find($id_jenis_surat);
      if ((count($check_jumlah_str_diluar)+count($check_jumlah_str_didalam)) >= $JenisSurat->maksimal_pengajuan) {
        return Redirect::to('home/surat/list/'.$JenisSurat->id_jenis_surat)
        ->with('title', 'Maaf!')
        ->with('message', 'Nomor STR ini telah digunakan!')
        ->with('type', 'success');
      }
    }

    $surat = ($request->id_surat != 0 && $request->jenis != 'perpanjangan') ? Surat::find($request->id_surat) : new Surat();

    //perpanjangan
    if ($request->jenis == 'perpanjangan') {
      $surat_lama = Surat::find($request->id_surat);
      $surat_lama->status_aktif = 'Kedaluarsa';
      $surat_lama->save();
      $this->cetak_pdf($surat_lama);

      $surat->status_perpanjangan = 'Perpanjangan';
    }

    if ($request->id_surat == 0 || $request->jenis == 'perpanjangan') {
      $surat->id_user = isset($surat_lama) ? $surat_lama->id_user : Auth::getUser()->id;
    }
    if ($request->id_surat == 0 || $request->jenis == 'perpanjangan') {
      $surat->id_jenis_surat = isset($surat_lama) ? $surat_lama->id_jenis_surat : Auth::getUser()->id_jenis_surat;
    }
    if ($request->id_surat == 0 || $request->jenis == 'perpanjangan') {
      $JenisSurat = JenisSurat::find(isset($surat_lama) ? $surat_lama->id_jenis_surat : Auth::getUser()->id_jenis_surat);
      $JenisSurat->nomor_surat = $JenisSurat->nomor_surat+1;
      $JenisSurat->save();

      $surat->nomor_surat      = 0;
      // $surat->nomor_surat     = $JenisSurat->nomor_surat;
    }

    // strtoupper($request->jabatan);
    // return $surat;

    $surat->id_jenis_sarana            = strip_tags($request->jenis_sarana);
    $surat->nama_tempat_praktik        = strip_tags(strtoupper($request->nama_tempat_praktik));
    $surat->alamat_tempat_praktik      = strip_tags(strtoupper($request->alamat_tempat_praktik));
    $surat->keterangan_jenis_praktik   = strip_tags(strtoupper($request->keterangan_jenis_praktik));
    $surat->nomor_str                  = strip_tags(strip_tags($request->nomor_str));
    $surat->nomor_op                   = strip_tags($request->nomor_op);
    $surat->waktu_praktik              = isset($request->waktu_praktik) ? $request->waktu_praktik : '';
    $surat->id_jenis_praktik           = strip_tags(strtoupper($request->jenis_praktik));
    $surat->sip_ke                     = $request->sip_ke;
    $surat->sebagai_jabatan            = strip_tags(strtoupper( $request->sebagai_jabatan));
    $surat->tanggal_berlaku_str        = $request->tanggal_berlaku_str;
    // $surat->fasyankes_id               = $request->fasyankes_id;


    if (!$request->has('status_simpan')) {
      $surat->status_simpan      = 'draf';
    }elseif($request->status_simpan == 'Simpan' && $request->btn_type == 'preview'){
      $surat->status_simpan      = 'preview';
    }else {
      $surat->status_simpan      = 'simpan';
    }
    // return $surat->status_simpan;

    if(!empty($request->file('sip_1'))){
      $ukuran = filesize($request->sip_1);

      if ($ukuran > 2000000) {
        return ['status'=>'error','message'=>'Maaf Ukuran lebih dari 2MB!'];
      }else{
        $ext                                  = $request->file('sip_1')->getClientOriginalExtension();
        $filename                               = str_replace(["/"],["_"],'SIP_1_'.strtolower(str_replace(" ", "-", Auth::getUser()->name)).'-'.date('Ymd-His').'.'.$ext);
        $temp_foto = 'uploads/file_berkas';
        $proses                                 = $request->file('sip_1')->move($temp_foto, $filename);

        $surat->sip_1 = $filename;
      }
    }

    if(!empty($request->file('sip_2'))){
      $ukuran = filesize($request->sip_2);

      if ($ukuran > 2000000) {
        return ['status'=>'error','message'=>'Maaf Ukuran lebih dari 2MB!'];
      }else{
        $ext                                  = $request->file('sip_2')->getClientOriginalExtension();
        $filename                               = str_replace(["/"],["_"],'SIP_2_'.strtolower(str_replace(" ", "-", Auth::getUser()->name)).'-'.date('Ymd-His').'.'.$ext);
        $temp_foto = 'uploads/file_berkas';
        $proses                                 = $request->file('sip_2')->move($temp_foto, $filename);

        $surat->sip_2 = $filename;
      }
    }


    //    if ($ukuran > 200000) {
    //   return ['status'=>'error','message'=>'Maaf Ukuran lebih dari 2MB!'];
    // }else{
    //      if (!empty($request->sip_2)) {
    //            $ext_foto   = $request->sip_2->getClientOriginalExtension();
    //            $filename   = date('Ymd-His')."_".Auth::getUser()->name.".".$ext_foto;
    //            $temp_foto = 'uploads/file_berkas/';
    //            $proses = $request->sip_2->move($temp_foto, $filename);
    //            $surat->sip_2 = $filename;
    //        }
    //    }

    if ($request->id_surat != 0 && $surat->status_aktif != 'Revisi') {
      $surat->status_aktif        = 'Menunggu';
      $surat->disetujui_admin           = 'Menunggu';
      $surat->disetujui_kasi          = 'Menunggu';
      $surat->disetujui_kabid         = 'Menunggu';
      $surat->disetujui_kadinkes        = 'Menunggu';

      $SyaratPengajuan = SyaratPengajuan::where('id_surat', $request->id_surat)
      ->update([
      'disetujui_admin' => 'Menunggu',
      'disetujui_kasi' => 'Menunggu',
      'disetujui_kabid' => 'Menunggu',
      'disetujui_kadinkes' => 'Menunggu',
      ]);
    }

    if (Auth::User()->id_level_user != 1) {
      $surat->tanggal_pengajuan = date('Y-m-d H:i:s');
    }
    $saved = $surat->save();

    if ($saved) {
      $surat = $surat;
      $persyratan = JenisSurat::find($surat->id_jenis_surat)->syarat_pengajuan;
      if(($request->jenis == 'perpanjangan') || $surat->status_perpanjangan=='Perpanjangan'){
        $persyratan = JenisSurat::find($surat->id_jenis_surat)->syarat_perpanjangan;
      }

      $berkas_persyaratan = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");
      foreach ($berkas_persyaratan as $row) {
        if (!empty($request->file($row->nama_variable))) {
          $ukuran = filesize($request->file_submission);
          if ($ukuran > 2000000) {
            return ['status'=>'error','message'=>'Maaf Ukuran lebih dari 2MB!'];
          }else{
            $ext        = $request->file($row->nama_variable)->getClientOriginalExtension();
            if ($ext != 'pdf' && $ext != 'PDF' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
              return Redirect::route('add_surat')
              ->with('title', 'Gagal !')
              ->with('message', 'Maaf! file Upload tidak sesuai')
              ->with('type', 'error');
            }
            $filename   = str_replace(["/"],["_"],$row->nama_variable.strtolower(str_replace(" ", "-", Auth::getUser()->name)).'-'.date('Ymd-His').'.'.$ext);
            $temp_foto  = 'upload/file_berkas';
            $proses     = $request->file($row->nama_variable)->move($temp_foto, $filename);
          }

          $SyaratPengajuan = ($request->id_surat != 0 && $request->jenis != 'perpanjangan') ? SyaratPengajuan::where('id_surat', $surat->id_surat)->where('id_jenis_persyaratan', $row->id_jenis_persyaratan)->where('id_user', $surat->id_user)->first() : new SyaratPengajuan();

          if(empty($SyaratPengajuan)){ $SyaratPengajuan = new SyaratPengajuan(); }
          // echo $surat->id_user.'<br>';

          $SyaratPengajuan->id_user         = $surat->id_user;
          $SyaratPengajuan->id_surat        = $surat->id_surat;
          $SyaratPengajuan->id_jenis_persyaratan  = $row->id_jenis_persyaratan;
          $SyaratPengajuan->nama_file_persyaratan = $filename;

          $SyaratPengajuan->save();
        }
      }

      // return '';

    }

    if ($saved) {
      if ($request->btn_type == 'preview') {
        $this->cetak_pdf($surat, $request->btn_type);
        $messages = "Preview";
        return [
          'status'=>'success',
          'code'=>'200',
          'message'=>$messages,
          'data'=>$surat
        ];
      }else{
        // return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)
        // ->with('status', 'success')
        // ->with('code', '200')
        // ->with('message', 'Data berhasil ditambahkan')
        // ->with('data', 'success');
        return [
          'status'=>'success',
          'code'=>'200',
          'message'=>'Data berhasil ditambahkan',
          'data'=>$surat
        ];
      }
    }else{
      return [
        'status'=>'success',
        'code'=>'500',
        'message'=>'Gagal',
        'data'=>''
      ];

      // return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)
      // ->with('title', 'Gagal !')
      // ->with('message', 'Data gagal ditambahkan')
      // ->with('type', 'error');
    }
  }

  public function edit(Request $request){

  }

  public function save_nomor(Request $request)
  {
    $surat = Surat::find($request->id);
    $insertNomor = Surat::where('id_surat', $surat->id_surat)->update(['nomor_surat' => $request->nomor_surat]);

    $surat->save();
    if ($surat) {
      $get_sur = Surat::find($request->id);
      $cekAja = $this->cetak_pdf($get_sur);
      // return "INI RETURN : ".$cekAja;
      return ['status'=>'success', 'code'=>'200', 'message'=>'Nomor Surat Di tambahkan!!', 'nomer_surat'=>$get_sur->nomor_surat];
    }else{
      return ['status'=>'error', 'code'=>'500', 'message'=>'Ulangi Kembali!!'];
    }

  }

  public function revocation(Request $request){
    // return $request->all();
    $surat = Surat::find($request->id_surat);
    $surat->status_aktif          = 'Menunggu Pencabutan';
    $surat->tanggal_permohonan_pencabutan   = date('Y-m-d H:i:s');
    $SyaratPengajuan = SyaratPengajuan::where('id_surat', $request->id_surat)
    ->update([
    'disetujui_admin' => 'Menunggu',
    'disetujui_kasi' => 'Menunggu',
    'disetujui_kabid' => 'Menunggu',
    'disetujui_kadinkes' => 'Menunggu',
    ]);

    $saved = $surat->save();
    if ($saved) {
      $persyratan = JenisSurat::find($surat->id_jenis_surat)->syarat_pencabutan;
      $berkas_persyaratan = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");

      foreach ($berkas_persyaratan as $row) {
        if (!empty($request->file($row->nama_variable))) {
          $ext                                  = $request->file($row->nama_variable)->getClientOriginalExtension();
          $filename                               = $row->nama_variable.strtolower(str_replace(" ", "-", Auth::getUser()->name)).'-'.date('Ymd-His').'.'.$ext;
          $temp_foto                              = 'upload/file_berkas';
          $proses                                 = $request->file($row->nama_variable)->move($temp_foto, $filename);

          $SyaratPengajuan = ($request->id_surat != 0 && $request->jenis != 'perpanjangan') ? SyaratPengajuan::where('id_surat', $surat->id_surat)->where('id_jenis_persyaratan', $row->id_jenis_persyaratan)->where('id_user', $surat->id_user)->first() : new SyaratPengajuan();

          if(empty($SyaratPengajuan)){ $SyaratPengajuan = new SyaratPengajuan(); }

          $SyaratPengajuan->id_user         = $surat->id_user;
          $SyaratPengajuan->id_surat        = $surat->id_surat;
          $SyaratPengajuan->id_jenis_persyaratan  = $row->id_jenis_persyaratan;
          $SyaratPengajuan->nama_file_persyaratan = $filename;

          $SyaratPengajuan->save();
        }
      }

      //store pengajuan baru
      if ($request->pencabutan_pindah == 'true') {
          $surat_lama = Surat::find($request->id_surat);
          // return $surat_lama;
          $surat_baru = new Surat();
          $surat_baru->id_user                    = Auth::getUser()->id;
          $surat_baru->id_jenis_surat             = $surat_lama->id_jenis_surat;
          $surat_baru->id_jenis_praktik           = $surat_lama->id_jenis_praktik;
          $surat_baru->id_jenis_sarana            = $surat_lama->id_jenis_sarana;
          if (Auth::User()->id_level_user == 2) {
            $surat_baru->tanggal_pengajuan = date('Y-m-d H:i:s');
          }
          $surat_baru->nama_tempat_praktik        = strip_tags(strtoupper($request->nama_tempat_praktik_terbaru));
          $surat_baru->alamat_tempat_praktik      = strip_tags(strtoupper($request->alamat_tempat_praktik_terbaru));
          $surat_baru->nomor_op                   = strip_tags(strtoupper($request->nomor_rekom_terbaru));

          $surat_baru->sebagai_jabatan            = $surat_lama->sebagai_jabatan;
          $surat_baru->nomor_str                  = $surat_lama->nomor_str;
          $surat_baru->tanggal_berlaku_str        = $surat_lama->tanggal_berlaku_str;
          $surat_baru->waktu_praktik              = isset($surat_lama->waktu_praktik) ? $surat_lama->waktu_praktik : '';
          $surat_baru->status_aktif               = 'Menunggu';
          $surat_baru->sip_ke                     = $surat_lama->sip_ke;
          $surat_baru->status_simpan              = 'simpan';
          $surat_baru->keterangan_jenis_praktik   = $surat_lama->keterangan_jenis_praktik;
          $surat_baru->surat_sebelum_id           = $surat_lama->id_surat;
          $saved_lama = $surat_baru->save();

          if ($saved_lama) {
            $syarat_pengajuan_lama = SyaratPengajuan::where('id_surat',$request->id_surat)->get();
            // return $syarat_pengajuan_lama;
            if (count($syarat_pengajuan_lama) > 0) {
              foreach ($syarat_pengajuan_lama as $key => $value) {
                $syarat_pengajuan_baru = new SyaratPengajuan();
                $syarat_pengajuan_baru->id_user               = $surat_baru->id_user;
                $syarat_pengajuan_baru->id_surat              = $surat_baru->id_surat;
                $syarat_pengajuan_baru->id_jenis_persyaratan  = $value->id_jenis_persyaratan;

                $lowercase_nama_file = strtolower($value->nama_file_persyaratan);
                $exploded_nama_file = explode('_', $lowercase_nama_file);
                $syarat_pengajuan_baru->nama_file_persyaratan = $value->nama_file_persyaratan;

                $syarat_pengajuan_baru->disetujui_admin     = 'Menunggu';
                $syarat_pengajuan_baru->disetujui_kasi      = 'Menunggu';
                $syarat_pengajuan_baru->disetujui_kabid     = 'Menunggu';
                $syarat_pengajuan_baru->disetujui_kadinkes  = 'Menunggu';
                $syarat_pengajuan_baru->save();
              }
            }

            // upload file pindah tempat praktik
            $persyratan_pindah_praktik = JenisSurat::find($surat_baru->id_jenis_surat)->syarat_pindah_tempat;
            $berkas_persyaratan_pindah_tempat = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan_pindah_praktik)");
            foreach ($berkas_persyaratan_pindah_tempat as $row) {

              if (!empty($request->file($row->nama_variable.'_terbaru'))) {
                $ukuran = filesize($request->file_submission);
                if ($ukuran > 2000000) {
                  return ['status'=>'error','message'=>'Maaf Ukuran lebih dari 2MB!'];
                }else{
                  $ext        = $request->file($row->nama_variable.'_terbaru')->getClientOriginalExtension();
                  if ($ext != 'pdf' && $ext != 'PDF' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
                    return Redirect::route('add_surat')
                    ->with('title', 'Gagal !')
                    ->with('message', 'Maaf! file Upload tidak sesuai')
                    ->with('type', 'error');
                  }
                  $filename   = str_replace(["/"],["_"],$row->nama_variable.strtolower(str_replace(" ", "-", Auth::getUser()->name)).'-'.date('Ymd-His').'.'.$ext);
                  $temp_foto  = 'upload/file_berkas';
                  $proses     = $request->file($row->nama_variable.'_terbaru')->move($temp_foto, $filename);
                }

                $syarat_pengajuan_baru = new SyaratPengajuan();
                $syarat_pengajuan_baru->id_user               = $surat_baru->id_user;
                $syarat_pengajuan_baru->id_surat              = $surat_baru->id_surat;
                $syarat_pengajuan_baru->id_jenis_persyaratan  = $row->id_jenis_persyaratan;

                $syarat_pengajuan_baru->disetujui_admin     = 'Menunggu';
                $syarat_pengajuan_baru->disetujui_kasi      = 'Menunggu';
                $syarat_pengajuan_baru->disetujui_kabid     = 'Menunggu';
                $syarat_pengajuan_baru->disetujui_kadinkes  = 'Menunggu';
                $syarat_pengajuan_baru->nama_file_persyaratan = $filename;
                $syarat_pengajuan_baru->save();
              }
            }
          }
      }

    }

    if ($saved) {
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)
      ->with('title', 'Berhasil !')
      ->with('message', 'Data berhasil ditambahkan')
      ->with('type', 'success');
    }else{
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)
      ->with('title', 'Gagal !')
      ->with('message', 'Data gagal ditambahkan')
      ->with('type', 'error');
    }
  }

  public function delete(Request $request){
    $res = Surat::where('id_surat', $request->id)->delete();
    if($res){
      $return = [
      'status'=>'success',
      'message'=>'Data berhasil dihapus!'
      ];
    }else{
      $return = [
      'status'=>'error',
      'message'=>'Data gagal dihapus!'
      ];
    }
    return $return;
  }

  public function detail(Request $request){

    $data['surat'] = Surat::find($request->id);

    $cekAuth = Users::where('id_level_user',8)
    ->where('id',Auth::getUser()->id)
    ->first();

    $data['warning'] = false;
    if (!empty($data['surat']->verifikator_pengajuan_id) && $cekAuth) {
      if ($cekAuth->id != $data['surat']->verifikator_pengajuan_id) {
        $data['warning'] = true;
        // return [
        //     'status'=>'warning',
        //     'code'=>'500',
        //     'message'=> 'Maaf! Pengajuan Ini Sudah di Verifikasi oleh Admin Permohonan Lainnya',
        //     'data'=>''
        //   ];
      }

    }


    // $getNomorSIP = Surat::where('id_jenis_surat',$data['surat']->id_jenis_surat)
    // ->whereYear('tanggal_pengajuan', date('Y',strtotime($data['surat']->tanggal_pengajuan)))
    // ->where('id_jenis_praktik', $data['surat']->id_jenis_praktik)
    // ->max('nomor_surat');
    // $data['no_urut'] = sprintf("%03d",(string)$getNomorSIP+1);


    $data['users'] = isset($request->id) ? Users::find($data['surat']->id_user) : Users::find(Auth::getUser()->id);
    $data['jenis_surat'] = isset($data['surat']) ? JenisSurat::find($data['surat']->id_jenis_surat) : JenisSurat::find(Auth::getUser()->id_jenis_surat);
    $data['list_surat'] = Surat::where('id_user', $data['users']->id)->get();
    $data['list_log_revisi'] = LogRevisiSurat::where('id_surat', $data['surat']->id_surat)->get();
    $data['list_surat_diluar'] = SuratDiluar::where('id_user', $data['users']->id)->where('status_aktif', 'Aktif')->get();

    $persyratan = $data['jenis_surat']->syarat_pengajuan;
    if($data['surat']->status_perpanjangan=='Perpanjangan'){
      $persyratan = $data['jenis_surat']->syarat_perpanjangan;
    }

    if (!empty($data['surat']->surat_sebelum_id)) {
      $persyratan_pindah_tempat = $data['jenis_surat']->syarat_pindah_tempat;

      $data['syarat_persyaratan_pindah_tempat'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan_pindah_tempat)");

      foreach ($data['syarat_persyaratan_pindah_tempat'] as $key => $sp) {
        $syarat_pengajuan = SyaratPengajuan::where('id_jenis_persyaratan', $sp->id_jenis_persyaratan)->where('id_surat', $data['surat']->id_surat)->first();
        if (!empty($syarat_pengajuan)) {
          $sp->syarat_pengajuan = $syarat_pengajuan;
        } else {
          $sp->syarat_pengajuan = Null;
        }
      }

      $data['berkas_persyaratan_pindah_tempat'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan_pindah_tempat)");

    }

    if (isset($data['surat'])) {
      $data['syarat_persyaratan'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");
      foreach ($data['syarat_persyaratan'] as $key => $sp) {
        $syarat_pengajuan = SyaratPengajuan::where('id_jenis_persyaratan', $sp->id_jenis_persyaratan)->where('id_surat', $data['surat']->id_surat)->first();
        if (!empty($syarat_pengajuan)) {
          $sp->syarat_pengajuan = $syarat_pengajuan;
        } else {
          $sp->syarat_pengajuan = Null;
        }
      }
    }

    $data['berkas_persyaratan'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");
    // return $data;
    $content = view('Surat.detail_surat', $data)->render();
    return ['status'=>'success','content'=>$content];
  }

  public function detail_pencabutan(Request $request){
    $data['surat'] = Surat::find($request->id);
    $surat_permohonan_pindah_tempat = Surat::where('surat_sebelum_id',$request->id)->first();

    $data['users'] = isset($request->id) ? Users::find($data['surat']->id_user) : Users::find(Auth::getUser()->id);
    $data['jenis_surat'] = isset($data['surat']) ? JenisSurat::find($data['surat']->id_jenis_surat) : JenisSurat::find(Auth::getUser()->id_jenis_surat);
    $data['list_surat'] = Surat::where('id_user', $data['users']->id)->get();

    if (isset($data['surat'])) {
      $data['syarat_persyaratan'] = SyaratPengajuan::where('id_surat', $data['surat']->id_surat)->get();
    }

    $persyratan = $data['jenis_surat']->syarat_pencabutan;
    $data['berkas_persyaratan'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");

    if (!empty($surat_permohonan_pindah_tempat->id_surat)) {
      $persyratan_pindah_tempat = $data['jenis_surat']->syarat_pindah_tempat;

      $data['syarat_persyaratan_pindah_tempat'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan_pindah_tempat)");

      foreach ($data['syarat_persyaratan_pindah_tempat'] as $key => $sp) {
        $syarat_pengajuan = SyaratPengajuan::where('id_jenis_persyaratan', $sp->id_jenis_persyaratan)->where('id_surat', $surat_permohonan_pindah_tempat->id_surat)->first();
        if (!empty($syarat_pengajuan)) {
          $sp->syarat_pengajuan = $syarat_pengajuan;
        } else {
          $sp->syarat_pengajuan = Null;
        }
      }

      $data['berkas_persyaratan_pindah_tempat'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan_pindah_tempat)");

      $data['surat_permohonan_baru_id'] = $surat_permohonan_pindah_tempat->id_surat;

    }
    $content = view('Surat.detail_pencabutan_surat', $data)->render();
    return ['status'=>'success','content'=>$content];
  }

   //silfi

  public function upload_detail_file_pencabutan(Request $request){
    // return $request->all();
    $data['surat'] = Surat::find($request->id);
    $content = view('Surat.upload_pencabutan', $data)->render();
    return ['status'=>'success','content'=>$content];
  }

  public function save_pencabutan_ebuddy(Request $request)
  {
    // return $request->all();
    $surat = Surat::find($request->id);
    $users = Users::find($surat->id_user);
    $save_data = Surat::where('id_surat', $surat->id_surat)->first();

    if (!empty($save_data)) {
      $save_data->nomor_surat = $request->nomor_surat;

      if (!empty($request->file_ebuddy)) {
        $ext_foto               = $request->file_ebuddy->getClientOriginalExtension();
        $filename               = 'file-ebuddy-'.strtolower(str_replace(" ", "-", $users->name)).'-'.date('Ymd-His').'.'.$ext_foto;
        $temp_foto              = 'upload/file_ebuddy/';
        $proses                 = $request->file_ebuddy->move($temp_foto, $filename);
        $save_data->file_ebuddy = $filename;
      }
    }

    $save_data->save();

    if ($save_data) {
      return ['status'=>'success', 'code'=>'200', 'message'=>'Nomor Surat Di tambahkan!!'];
    }else{
      return ['status'=>'error', 'code'=>'500', 'message'=>'Ulangi Kembali!!'];
    }

  }

  //end silfi

  public function datagrid(Request $request){
    $data = Surat::getJson($request);
    for ($i=0; $i < count($data['rows']); $i++) {

      // $tanggal_kadaluarsa = $data['rows'][$i]->tanggal_kedaluarsa;
      // $tanggal_tenggang = date('Y-m-d',strtotime('-6 month',strtotime($tanggal_kadaluarsa)));
      $tanggal_kadaluarsa = $data['rows'][$i]->tanggal_berlaku_str;
      $tanggal_tenggang = date('Y-m-d',strtotime('-6 month',strtotime($tanggal_kadaluarsa)));
      $status = ($tanggal_kadaluarsa >= date('Y-m-d') && $tanggal_tenggang <= date('Y-m-d')) ? true : false;

      $baris = $data['rows'][$i];
      $update_kadal = Surat::where('id_surat',$baris->id_surat)
                           ->where('tanggal_berlaku_str', '<=', date('Y-m-d'))
                           ->update(['status_aktif' => 'Kedaluarsa']);

      $data['rows'][$i]->status_tenggang = $status;
      $data['rows'][$i]->tanggal_kadaluarsa = $tanggal_kadaluarsa;
      $data['rows'][$i]->tanggal_tenggang = $tanggal_tenggang;
    }
    return $data;
  }

  public function datagrid_rs(Request $request){
    $data = Surat::getJsonRS($request);
    for ($i=0; $i < count($data['rows']); $i++) {

      $tanggal_kadaluarsa = $data['rows'][$i]->tanggal_berlaku_str;
      $tanggal_tenggang = date('Y-m-d',strtotime('-6 month',strtotime($tanggal_kadaluarsa)));
      $status = ($tanggal_kadaluarsa >= date('Y-m-d') && $tanggal_tenggang <= date('Y-m-d')) ? true : false;

      $baris = $data['rows'][$i];
      $update_kadal = Surat::where('id_surat',$baris->id_surat)
                           ->where('tanggal_berlaku_str', '<=', date('Y-m-d'))
                           ->update(['status_aktif' => 'Kedaluarsa']);

      $data['rows'][$i]->status_tenggang = $status;
      $data['rows'][$i]->tanggal_kadaluarsa = $tanggal_kadaluarsa;
      $data['rows'][$i]->tanggal_tenggang = $tanggal_tenggang;
    }
    return $data;
  }

  public function verifikasi_berkas(Request $request){

    // return $request->all();
    $SyaratPengajuan = SyaratPengajuan::find($request->id_syarat_pengajuan);

    if ($request->persetujuan == 'Merah') {
        $surat = Surat::find($SyaratPengajuan->id_surat);
        $users = Users::find($surat->id_user);
        $JenisSurat = JenisSurat::find($surat->id_jenis_surat);

        $surat->keterangan   = strip_tags($request->keterangan);

        if ($surat->status_aktif == 'Menunggu') {
          $surat->status_aktif = 'Tolak';
          // Mail::to($users->email)->send(new SendMailableTolak($users->email, $users->name, $JenisSurat->nama_surat, $surat->keterangan));

          $data_email = [
              'email' => $users->email,
              'nama'   => $users->name,
              'sip'  => $JenisSurat->nama_surat,
              'keterangan'  => $surat->keterangan,
          ];

          // $sendMail = Mail::send('auth.email_tolak',$data_email, function ($mail) use ($data_email){
          //           // $mail->to('zeinsaedi.92@gmail.com');
          //           $mail->to($data_email['email']);
          //           // $mail->to($tujuan);
          //           $mail->subject('Pengajuan Surat Izin Praktik Ditolak Dinas Kesehatan Kabupaten Sidoarjo');
          //           // silvyaanggraini99@gmail.com
          // });

          Whatsapp::kirim($users->nomor_telpon, $users->id, 'Tolak', $data_email);
        }else if ($surat->status_aktif == 'Menunggu Pencabutan') {
          $surat->status_aktif = 'Aktif';
        }

      if (Auth::getUser()->id_level_user == 1) {
        $surat->disetujui_admin          = 'Ditolak';
        $surat->tanggal_disetujui_admin  = date('Y-m-d H:i:s');
      }elseif (Auth::getUser()->id_level_user == 3) {
        $surat->disetujui_kasi        = 'Ditolak';
        $surat->tanggal_disetujui_kasi    = date('Y-m-d H:i:s');
      }elseif (Auth::getUser()->id_level_user == 4) {
        $surat->disetujui_kabid       = 'Ditolak';
        $surat->tanggal_disetujui_kabid   = date('Y-m-d H:i:s');
      }elseif (Auth::getUser()->id_level_user == 5) {
        $surat->disetujui_kadinkes       = 'Ditolak';
        $surat->tanggal_disetujui_kadinkes = date('Y-m-d H:i:s');
      }

      $surat->save();
    }

    if (Auth::getUser()->id_level_user == '1') {
      $SyaratPengajuan->disetujui_admin       = $request->persetujuan;
      $SyaratPengajuan->keterangan_admin      = strip_tags($request->keterangan);
      $SyaratPengajuan->tanggal_disetujui_admin   = date('Y-m-d H:i:s');
    }else if (Auth::getUser()->id_level_user == '3') {
      $SyaratPengajuan->disetujui_kasi      = $request->persetujuan;
      $SyaratPengajuan->keterangan_kasi       = strip_tags($request->keterangan);
      $SyaratPengajuan->tanggal_disetujui_kasi  = date('Y-m-d H:i:s');
    }else if (Auth::getUser()->id_level_user == '4') {
      $SyaratPengajuan->disetujui_kabid       = $request->persetujuan;
      $SyaratPengajuan->keterangan_kabid      = strip_tags($request->keterangan);
      $SyaratPengajuan->tanggal_disetujui_kabid   = date('Y-m-d H:i:s');
    }else if (Auth::getUser()->id_level_user == '5') {
      $SyaratPengajuan->disetujui_kadinkes      = $request->persetujuan;
      $SyaratPengajuan->keterangan_kadinkes       = strip_tags($request->keterangan);
      $SyaratPengajuan->tanggal_disetujui_kadinkes  = date('Y-m-d H:i:s');
    }

    $saved = $SyaratPengajuan->save();
    if($saved){
      $data['syarat_pengajuan'] = $SyaratPengajuan;
      $content = view('Surat.table_progress', $data)->render();

      $return = [
      'status'=>'success',
      'content'=>$content
      ];

      //send mail

    }else{

      $return = [
      'status'=>'error'
      ];
    }
    return $return;
  }

  public function verifikasi_pengajuan(Request $request){

    $surat = Surat::find($request->id);
    $users = Users::find(Auth::getUser()->id);
    $users_pemohon = Users::find($surat->id_user);

    $JenisSurat = JenisSurat::find($surat->id_jenis_surat);
    $tahun = substr($surat->tanggal_berlaku_str,0,4);
    $surat->keterangan                 = $request->keterangan;


    if ($request->status_verifikasi == "Ditolak") {
      $surat->status_aktif       = 'Tolak';
      $warna = 'Merah';
    //   Mail::to($users->email)->send(new SendMailableTolak($users->email, $users->name, $JenisSurat->nama_surat, $surat->keterangan));
        $data_email = [
            'email' => $users_pemohon->email,
            'nama'   => $users_pemohon->name,
            'sip'  => $JenisSurat->nama_surat,
            'keterangan'  => $surat->keterangan,
        ];

        // $sendMail = Mail::send('auth.email_tolak',$data_email, function ($mail) use ($data_email){
        //           // $mail->to('zeinsaedi.92@gmail.com');
        //           $mail->to($data_email['email']);
        //           // $mail->to($tujuan);
        //           $mail->subject('Pengajuan Surat Izin Praktik Ditolak Dinas Kesehatan Kabupaten Sidoarjo');
        //           // silvyaanggraini99@gmail.com
        // });

        Whatsapp::kirim($users_pemohon->nomor_telpon, $users_pemohon->id, 'Tolak', $data_email);
    }else{
      $warna = 'Hijau';
    }

    if (in_array($users->id_level_user, [1,8])) {
      $surat->disetujui_admin          = $request->status_verifikasi;
      $surat->tanggal_disetujui_admin  = date('Y-m-d H:i:s');
      $surat->tanggal_terbit        = date('Y-m-d H:i:s');
      if ($tahun == '2100') {
        $surat->tanggal_kedaluarsa = date('Y-m-d',strtotime('+5 Years',strtotime($surat->tanggal_terbit )));
      }
      else{
        $surat->tanggal_kedaluarsa      = date('Y-m-d', strtotime($surat->tanggal_berlaku_str));
      }

      //save verifikator surat
      $surat->verifikator_pengajuan_id = $users->id;

      $SyaratPengajuan = SyaratPengajuan::where('id_surat', $surat->id_surat)
      ->update([
      'disetujui_admin' => $warna,
      'tanggal_disetujui_admin' => date('Y-m-d H:i:s')
      ]);


      $this->cetak_pdf($surat);
    }elseif ($users->id_level_user == 3) {
      $surat->disetujui_kasi = $request->status_verifikasi;
      $surat->tanggal_disetujui_kasi    = date('Y-m-d H:i:s');

      $SyaratPengajuan = SyaratPengajuan::where('id_surat', $surat->id_surat)
      ->update([
      'disetujui_kasi' => $warna,
      'tanggal_disetujui_kasi' => date('Y-m-d H:i:s')
      ]);

      $this->cetak_pdf($surat);
    }elseif ($users->id_level_user == 4) {

      $getNomorSIP = Surat::where('id_jenis_surat',$surat->id_jenis_surat)
                            ->selectRaw('max(nomor_surat) as nomor_surat')
                            ->whereYear('tanggal_terbit', date('Y',strtotime($surat->tanggal_terbit)))
                            // ->whereYear('tanggal_pengajuan', date('Y',strtotime($surat->tanggal_pengajuan)))
                            ->where('id_jenis_praktik', $surat->id_jenis_praktik)
                            ->where('status_aktif','!=','Tolak')
                            ->first()->nomor_surat;
                            // ->max('nomor_surat_int');

      $nomor_SIP = sprintf("%03d",(string)$getNomorSIP+1);
      // $nomor_SIP = sprintf("%04d",(string)$getNomorSIP+1);

      $surat->disetujui_kabid = $request->status_verifikasi;
      $surat->tanggal_disetujui_kabid   = date('Y-m-d H:i:s');
      $surat->tanggal_terbit        = date('Y-m-d H:i:s');
      if ($tahun == '2100') {
        $surat->tanggal_kedaluarsa = date('Y-m-d',strtotime('+5 Years',strtotime($surat->tanggal_terbit )));
      }
      else{
        $surat->tanggal_kedaluarsa      = date('Y-m-d', strtotime($surat->tanggal_berlaku_str));
      }

      if ($request->status_verifikasi == "Ditolak") {
        $surat->status_aktif          = 'Tolak';
      }else{
        $surat->status_aktif          = 'Aktif';
        $surat->nomor_surat           = $nomor_SIP;
      }

      $SyaratPengajuan = SyaratPengajuan::where('id_surat', $surat->id_surat)
      ->update([
      'disetujui_kabid' => $warna,
      'tanggal_disetujui_kabid' => date('Y-m-d H:i:s')
      ]);

      // $surat->status_aktif           = 'Aktif';
      $this->cetak_pdf($surat);
    }elseif ($users->id_level_user == 5) {
      $surat->disetujui_kadinkes        = $request->status_verifikasi;
      $surat->tanggal_disetujui_kadinkes  = date('Y-m-d H:i:s');
      $surat->tanggal_terbit        = date('Y-m-d H:i:s');
      if ($tahun == '2100') {
        $surat->tanggal_kedaluarsa = date('Y-m-d',strtotime('+5 Years',strtotime($surat->tanggal_terbit )));
      }
      else{
        $surat->tanggal_kedaluarsa      = date('Y-m-d', strtotime($surat->tanggal_berlaku_str));
      }
      $surat->status_aktif          = 'Aktif';

      $SyaratPengajuan = SyaratPengajuan::where('id_surat', $surat->id_surat)
      ->update([
      'disetujui_kadinkes' => $warna,
      'tanggal_disetujui_kadinkes' => date('Y-m-d H:i:s')
      ]);

      $this->cetak_pdf($surat);
    }

    $surat->save();
    if ($surat){
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Berhasil !')->with('message', 'Berhasil '.$request->status_verifikasi)->with('type', 'success');
    }else{
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Gagal !')->with('message', 'Gagal '.$request->status_verifikasi)->with('type', 'error');
    }
  }

  public function verifikasi_pencabutan(Request $request){
    // return $request->all();

    $surat = Surat::find($request->id);
    $users = Users::find(Auth::getUser()->id);
    $users_pemohon = Users::find($surat->id_user);

    $surat->keterangan_pencabutan                 = strip_tags($request->keterangan);

    if ($request->status_verifikasi == "Ditolak") {

      $surat->status_aktif       = 'Pencabutan Ditolak';
      $warna = 'Merah';

       $data_email = [
            'email' => $users_pemohon->email,
            'nama'   => $users_pemohon->name,
            'keterangan'  => $surat->keterangan_pencabutan,
        ];

        // $sendMail = Mail::send('auth.email_tolak_pencabutan',$data_email, function ($mail) use ($data_email){
        //           // $mail->to('zeinsaedi.92@gmail.com');
        //           $mail->to($data_email['email']);
        //           // $mail->to($tujuan);
        //           $mail->subject('Pengajuan Surat Pencabutan Ditolak Dinas Kesehatan Kabupaten Sidoarjo');
        //           // silvyaanggraini99@gmail.com
        // });

        Whatsapp::kirim($users_pemohon->nomor_telpon, $users_pemohon->id, 'Tolak Pencabutan', $data_email);
    }else{
      $warna = 'Hijau';

      $data_email = [
            'surat_pencabutan'  => Surat::join('users','surat.id_user', '=', 'users.id')->where('id_surat',$surat->id_surat)->first(),
            'syarat' => SyaratPengajuan::selectRaw('syarat_pengajuan.nama_file_persyaratan,jenis_persyaratan.nama_jenis_persyaratan')->join('jenis_persyaratan','syarat_pengajuan.id_jenis_persyaratan', '=', 'jenis_persyaratan.id_jenis_persyaratan')->where('syarat_pengajuan.id_surat',$surat->id_surat)->orderBy('jenis_persyaratan.id_jenis_persyaratan','Asc')->get(),
        ];

        // return $data_email;

        // $sendMail = Mail::send('auth.email_pencabutan',$data_email, function ($mail) use ($data_email){
        //           // $mail->to('silvyaanggraini99@gmail.com');
        //           $mail->to('dinkessidoarjo46@gmail.com');
        //           $mail->subject('Kelengkapan Dokumen Pencabutan');
        //           // $mail->to($tujuan);
        // });
        // end silfi

        Whatsapp::kirim($users_pemohon->nomor_telpon, $users_pemohon->id, 'Pencabutan', $data_email);
    }
       if (in_array($users->id_level_user, [1,9])) {
        $surat->pencabutan_disetujui_admin          = $request->status_verifikasi;
        $surat->tanggal_pencabutan_disetujui_admin  = date('Y-m-d H:i:s');

         $SyaratPengajuan = SyaratPengajuan::where('id_surat', $surat->id_surat)->update([
          'disetujui_admin' => $warna,

          'tanggal_disetujui_admin' => date('Y-m-d H:i:s'),
          'keterangan_admin' => $request->keterangan,
        ]);


        if ($request->status_verifikasi == 'Ditolak') {
          $surat->status_aktif       = 'Pencabutan Ditolak';
        }else{
          $surat->status_aktif          = 'Dicabut';
          $this->cetak_pdf($surat);
        }

      }elseif ($users->id_level_user == 3) {
        $surat->pencabutan_disetujui_kasi = $request->status_verifikasi;
        $surat->tanggal_pencabutan_disetujui_kasi   = date('Y-m-d H:i:s');

         if ($request->status_verifikasi == 'Ditolak') {
          $surat->status_aktif       = 'Pencabutan Ditolak';
        }else{
          $surat->status_aktif          = 'Dicabut';
          $this->cetak_pdf($surat);
        }

      }elseif ($users->id_level_user == 4) {
        $surat->pencabutan_disetujui_kabid = $request->status_verifikasi;
        $surat->tanggal_pencabutan_disetujui_kabid   = date('Y-m-d H:i:s');
        $surat->tanggal_dicabut             = date('Y-m-d H:i:s');

         if ($request->status_verifikasi == 'Ditolak') {
          $surat->status_aktif       = 'Pencabutan Ditolak';
        }else{
          $surat->status_aktif          = 'Dicabut';
          $this->cetak_pdf($surat);
        }

      }elseif ($users->id_level_user == 5) {
        $surat->pencabutan_disetujui_kadinkes         = $request->status_verifikasi;
        $surat->tanggal_pencabutan_disetujui_kadinkes   = date('Y-m-d H:i:s');
        $surat->tanggal_dicabut             = date('Y-m-d H:i:s');

        if ($request->status_verifikasi == 'Ditolak') {
          $surat->status_aktif       = 'Pencabutan Ditolak';
        }else{
          $surat->status_aktif          = 'Dicabut';
          $this->cetak_pdf($surat);
        }

      }

    $surat->save();

    if ($surat){
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Berhasil !')->with('message', 'Berhasil '.$request->status_verifikasi)->with('type', 'success');
    }else{
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Gagal !')->with('message', 'Gagal '.$request->status_verifikasi)->with('type', 'error');
    }
  }

  public function form_revisi(Request $request){
    $data['surat']  = Surat::find($request->id);
    $content = view('Surat.revisi_surat', $data)->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function save_revisi(Request $request){
    $surat  = Surat::find($request->id_surat);

    $surat->status_aktif = 'Revisi';
    $surat->keterangan_revisi = strtoupper($request->keterangan_revisi);

    $surat->save();
    if ($surat){
      $LogRevisiSurat = new LogRevisiSurat();
      $LogRevisiSurat->id_surat = $surat->id_surat;
      $LogRevisiSurat->keterangan = $request->keterangan_revisi;
      $LogRevisiSurat->save();

      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Berhasil !')->with('message', 'Pengajuan revisi berhasil ')->with('type', 'success');
    }else{
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Gagal !')->with('message', 'Pengajuan revisi gagal ')->with('type', 'error');
    }
  }

  public function setujui_revisi(Request $request){
    $surat  = Surat::find($request->id_surat);

    $LogRevisiSurat = LogRevisiSurat::where('keterangan', $surat->keterangan_revisi)->first();
    $LogRevisiSurat->status = 'Telah Diperbaiki';
    $LogRevisiSurat->save();

    $surat->status_aktif = 'Aktif';
    $surat->keterangan_revisi = '';
    $surat->save();

    $this->cetak_pdf($surat);
    if ($surat){
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Berhasil !')->with('message', 'Pengajuan revisi berhasil ')->with('type', 'success');
    }else{
      return Redirect::to('home/surat/list/'.$surat->id_jenis_surat)->with('title', 'Gagal !')->with('message', 'Pengajuan revisi gagal ')->with('type', 'error');
    }
  }

  public function form_pilih_pencabutan(Request $request){
    $data['surat']  = Surat::find($request->id);
    $content = view('Surat.pilih_', $data)->render();
    return ['status' => 'success', 'content' => $content];
  }

  public function perbarui_surat(Request $request)
  {
    // return $request->all();
    $surat  = Surat::find($request->id_surat);

    $this->cetak_pdf($surat);

    return ['status'=>'success','code'=>200,'message'=>'Berhasil Memperbarui Berkas'];
  }

  private function cetak_pdf($surat, $type='')
    {

      // return $surat;
      set_time_limit(3000);
      $users = Users::find($surat->id_user);
      $JenisSurat = JenisSurat::find($surat->id_jenis_surat);

      // return $surat->tanggal_disetujui_kabid;
      // $ttd_kadinkes = MasterTtdKadinkes::where('tanggal_awal', '<=', $surat->tanggal_terbit)->where('tanggal_akhir', '>=', $surat->tanggal_terbit)->first();
     // return $ttd_kadinkes;

      if ($surat->status_aktif == 'Dicabut' || $surat->status_aktif == 'Menunggu Pencabutan') {
        $data['content'] = TemplateSurat::where('id_jenis_surat', $surat->id_jenis_surat)->where('id_jenis_praktik', $surat->id_jenis_praktik)->where('jenis', 'PENCABUTAN')->first()->template_surat;
      }else{
        $data['content'] = TemplateSurat::where('id_jenis_surat', $surat->id_jenis_surat)->where('id_jenis_praktik', $surat->id_jenis_praktik)->where('jenis', 'PENGAJUAN')->first()->template_surat;
      }

      //data users
      $nama = '';
      if(!empty($users->gelar_depan)){
        $nama = $users->gelar_depan.'. ';
      }

      $nama = $nama.''.$users->name;

      if(!empty($users->gelar_belakang)){
        $nama = $nama.', '.$users->gelar_belakang;
      }
      $data['content'] = str_replace('[[nama-lengkap]]', $users->name, $data['content']);
      $data['content'] = str_replace('[[nama-lengkap-dan-gelar]]', $nama, $data['content']);
      $data['content'] = str_replace('[[gelar-depan]]', $users->gelar_depan, $data['content']);
      $data['content'] = str_replace('[[gelar-belakang]]', $users->gelar_belakang, $data['content']);
      $data['content'] = str_replace('[[email]]', $users->email, $data['content']);
      $data['content'] = str_replace('[[pendidikan-terakhir]]', $users->PendidikanTerakhir->pendidikan_terakhir, $data['content']);
      $data['content'] = str_replace('[[alamat-jalan-rt-rw]]', substr($users->alamat_jalan_rt_rw, 0, 3), $data['content']);
      $data['content'] = str_replace('[[alamat-jalan-rt-rw1]]', substr($users->alamat_jalan_rt_rw, 4, 3), $data['content']);
      $data['content'] = str_replace('[[alamat-domisili]]', $users->alamat_domisili, $data['content']);
      $data['content'] = str_replace('[[nomor-telpon]]', $users->nomor_telpon, $data['content']);
      $data['content'] = str_replace('[[nomor-ktp]]', $users->nomor_ktp, $data['content']);
      $data['content'] = str_replace('[[photo]]',public_path('/upload/users/').$users->photo, $data['content']);

      // $data['content'] = str_replace('[[photo]]', url('/').'/img/foto3x6.png', $data['content']);
      $data['content'] = str_replace('[[tahun-lulus]]', $users->tahun_lulus, $data['content']);
      $data['content'] = str_replace('[[jenis-kelamin]]', $users->jenis_kelamin, $data['content']);
      $data['content'] = str_replace('[[jenis-kelamin1]]', ($users->jenis_kelamin=='Perempuan') ? '2' : '1', $data['content']);

      $data['content'] = str_replace('[[desa]]', ucwords(strtoupper($users->desa->nama_desa)), $data['content']);
      $data['content'] = str_replace('[[dusun]]', ucwords(strtoupper($users->dusun)), $data['content']);
      $data['content'] = str_replace('[[kecamatan]]', ucwords(strtoupper($users->kecamatan->nama_kecamatan)), $data['content']);
      $data['content'] = str_replace('[[kabupaten]]', ucwords(strtoupper(str_replace('KABUPATEN', '', $users->kabupaten->nama_kabupaten))), $data['content']);
      $data['content'] = str_replace('[[provinsi]]', ucwords(strtoupper($users->provinsi->nama_provinsi)), $data['content']);
      $data['content'] = str_replace('[[jabatan]]', $users->Jabatan->jabatan, $data['content']);
      $data['content'] = str_replace('[[tempat-lahir]]', ucwords(strtoupper(str_replace('KABUPATEN', '', $users->tempat_lahir))), $data['content']);
      $data['content'] = str_replace('[[tanggal-lahir]]', Formatters::tgl_indo($users->tanggal_lahir), $data['content']);
      $data['content'] = str_replace('[[tanggal-lahir1]]', date('Ymd',strtotime($users->tanggal_lahir)), $data['content']);
      $data['content'] = str_replace('[[status-perkawinan]]', $users->status_perkawinan, $data['content']);
      $data['content'] = str_replace('[[status-kepegawaian]]', $users->status_kepegawaian, $data['content']);
      $data['content'] = str_replace('[[agama]]', $users->agama, $data['content']);
      $data['content'] = str_replace('[[profesi]]', $users->profesi, $data['content']);



      //surat
      $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
      $data['content'] = str_replace('[[tahun-terbit]]', date('Y', strtotime($surat->tanggal_terbit)), $data['content']);
      $data['content'] = str_replace('[[bulan-terbit]]', $romawi[date('m', strtotime($surat->tanggal_terbit))*1], $data['content']);
      $data['content'] = str_replace('[[sebagai-jabatan]]', $surat->sebagai_jabatan, $data['content']);
      $data['content'] = str_replace('[[tanggal-berlaku-str]]', Formatters::tgl_indo($surat->tanggal_berlaku_str), $data['content']);
      $data['content'] = str_replace('[[nomor-surat]]', $surat->nomor_surat, $data['content']);

      if($surat->tanggal_dicabut!='' || $surat->tanggal_dicabut!=null){
        $tgl_cabut = Formatters::tgl_indo($surat->tanggal_dicabut);
        $cbt = explode(" ",$tgl_cabut);
      }else{
        $cbt = ['','',''];
      }

      $data['content'] = str_replace('[[tanggal-permohonan-pencabutan]]', Formatters::tgl_indo($surat->tanggal_permohonan_pencabutan), $data['content']);
      $data['content'] = str_replace('[[tanggal-pengajuan]]', $surat->tanggal_pengajuan, $data['content']);
      $data['content'] = str_replace('[[jenis-praktik]]', $surat->jenis_praktik->nama_jenis_praktik, $data['content']);
      $data['content'] = str_replace('[[nama-tempat-praktik]]', $surat->nama_tempat_praktik, $data['content']);
      $data['content'] = str_replace('[[alamat-tempat-praktik]]', $surat->alamat_tempat_praktik, $data['content']);
      $data['content'] = str_replace('[[nomor-str]]', $surat->nomor_str, $data['content']);
      $data['content'] = str_replace('[[nomor-op]]', $surat->nomor_op, $data['content']);
      $data['content'] = str_replace('[[jenis-pekerjaan]]', $surat->jenis_pekerjaan, $data['content']);
      $data['content'] = str_replace('[[tanggal-terbit]]', Formatters::tgl_indo($surat->tanggal_terbit), $data['content']);
      $data['content'] = str_replace('[[tanggal-kedaluarsa]]', Formatters::tgl_indo($surat->tanggal_kedaluarsa), $data['content']);
      $data['content'] = str_replace('[[sip-ke]]', $surat->sip_ke, $data['content']);

      $sip_ke_terbilang = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM'];
      $data['content'] = str_replace('[[sip-ke-terbilang]]', $sip_ke_terbilang[$surat->sip_ke], $data['content']);

      $sip_ke_terbilang_kecil = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam'];
      $data['content'] = str_replace('[[sip-ke-terbilang-kecil]]', $sip_ke_terbilang_kecil[$surat->sip_ke], $data['content']);

      $data['content'] = str_replace('[[status-aktif]]', $surat->status_aktif, $data['content']);
      $data['content'] = str_replace('[[tanggal-dicabut]]', '&nbsp;&nbsp;&nbsp;&nbsp; '.$cbt[1].' '.$cbt[2], $data['content']);
      $data['content'] = str_replace('[[waktu-praktik]]', $surat->waktu_praktik, $data['content']);

      $data['content'] = str_replace('[[berlaku-sampai]]', 'berlaku sampai dengan tanggal '.Formatters::tgl_indo($surat->tanggal_kedaluarsa), $data['content']);

      //ttd kadinkes
      // $data['content'] = str_replace('[[nama-kadinkes]]', $ttd_kadinkes->nama, $data['content']);
      // $data['content'] = str_replace('[[nip-kadinkes]]', $ttd_kadinkes->nip, $data['content']);
      // $data['content'] = str_replace('[[jabatan-kadinkes]]', $ttd_kadinkes->jabatan, $data['content']);


      // $data['content'] = str_replace('[[berlaku-sampai]]', $surat->status_aktif == 'Aktif' ? 'berlaku sampai dengan tanggal '.Formatters::tgl_indo($surat->tanggal_kedaluarsa) : 'belum berlaku.', $data['content']);

      $data['content'] = str_replace('[[tanggal-berlaku]]', Formatters::tgl_indo($surat->tanggal_kedaluarsa), $data['content']);

      // if ($surat->status_aktif == 'Menunggu') {
      //   $data['content'] = str_replace('[[watermark]]', url('/').'/img/konsep.png', $data['content']);
      // }elseif ($surat->status_aktif == 'Kedaluarsa') {
      //   $data['content'] = str_replace('[[watermark]]', url('/').'/img/Kedaluwarsa.png', $data['content']);
      // }elseif ($surat->status_aktif == 'Dicabut') {
      //   $data['content'] = str_replace('[[watermark]]', url('/').'/img/dicabut.png', $data['content']);
      // }elseif ($surat->status_aktif == 'Tolak') {
      //   $data['content'] = str_replace('[[watermark]]', url('/').'/img/tolak.png', $data['content']);
      // }else{
      //   $data['content'] = str_replace('[[watermark]]', url('/').'/img/blank_watermark.png', $data['content']);
      // }
      if ($surat->status_aktif == 'Menunggu') {
        $data['content'] = str_replace('[[watermark]]', public_path('/img/konsep.png'), $data['content']);
      }elseif ($surat->status_aktif == 'Kedaluarsa') {
        $data['content'] = str_replace('[[watermark]]', public_path('/img/Kedaluwarsa.png'), $data['content']);
      }elseif ($surat->status_aktif == 'Dicabut') {
        $data['content'] = str_replace('[[watermark]]', public_path('/img/dicabut.png'), $data['content']);
      }elseif ($surat->status_aktif == 'Tolak') {
        $data['content'] = str_replace('[[watermark]]', public_path('/img/tolak.png'), $data['content']);
      }else{
        $data['content'] = str_replace('[[watermark]]', public_path('/img/blank_watermark.png'), $data['content']);
      }


      //list surat izin
      $list_surat = Surat::where('id_user', $users->id)->where('status_aktif', 'Aktif')->get();
      $html = "<table>";
        for ($i=0; $i < count($list_surat); $i++) {
          $html =  $html."<tr>
            <td>$i. </td>
            <td>
              $list_surat[$i]->nama_tempat_praktik
              <br>
              $list_surat[$i]->alamat_tempat_praktik
              <br>
              $list_surat[$i]->nomor_str
            </td>
          </tr>";
        }
        $html = $html."</table>";
        $data['content'] = str_replace('[[list-tempat-praktik]]', $html, $data['content']);
        $data['content'] = str_replace('[[jumlah-tempat-praktik]]', count($list_surat), $data['content']);


        //ttd dan qrcode
        $data['content'] = str_replace('[[paraf-kasi]]', public_path('/img/blank_kasi.png'), $data['content']);
        $data['content'] = str_replace('[[paraf-kabid]]', public_path('/img/blank_kabid.png'), $data['content']);
        $data['content'] = str_replace('[[paraf-kadinkes]]', public_path('/img/blank_ttd.png'), $data['content']);
        $data['content'] = str_replace('[[stempel-dinkes]]', public_path('/img/blank_stempel.png'), $data['content']);
        // qrcode [[qrcode]]
        // $data['content'] = str_replace('[[logo-dinkes]]', public_path('/img/KOP SIP besar.png'), $data['content']);
        $data['content'] = str_replace('[[logo-dinkes]]', public_path('/img/KOPSIPbesar.png'), $data['content']);

        if ($surat->status_aktif == 'Dicabut' || $surat->status_aktif == 'Menunggu Pencabutan') {
          $file_name_asli = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-pencabutan_asli.pdf');
          $file_name_salinan = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-pencabutan_salinan.pdf');

          if ($surat->file_surat_pencabutan_sip_asli != '') {
            $file_name_asli = $surat->file_surat_pencabutan_sip_asli;
          }

          if ($surat->file_surat_pencabutan_sip_salinan != '') {
            $file_name_salinan = $surat->file_surat_pencabutan_sip_salinan;
          }

          $JenisSarana = JenisSarana::find($surat->id_jenis_sarana);

          switch ($JenisSarana->id_jenis_sarana) {
            case '19':
            $jenis_sarana = '';
            break;
            case '11':
            $jenis_sarana = 'Apoteker Penanggung Jawab '.ucwords($surat->nama_tempat_praktik);
            break;
            case '10':
            $jenis_sarana = 'Dokter Penanggung Jawab '.ucwords($surat->nama_tempat_praktik);
            break;
            case '9':
            $jenis_sarana = 'Direktur '.ucwords($surat->nama_tempat_praktik);
            break;

            default:
            $jenis_sarana = 'Kepala '.ucwords($surat->nama_tempat_praktik);
            break;
          }
          $data['content'] = str_replace('[[tembusan]]', $jenis_sarana, $data['content']);

        }else{
          $file_name_asli = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-asli.pdf');
          $file_name_salinan = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-salinan.pdf');

          if ($surat->file_surat_sip_asli != '') {
            $file_name_asli = $surat->file_surat_sip_asli;
          }

          if ($surat->file_surat_sip_salinan != '') {
            $file_name_salinan = $surat->file_surat_sip_salinan;
          }
        }

        //qrcode surat asli
        $data['url_surat_asli'] = url('/').'/upload/file_sip_asli/'.$file_name_asli;
        $data['jenis_file'] = 'asli';

        if ($type == 'ajukan' || $type == '') {
          $data['content'] = str_replace("[[qrcode]]", 'data:image/png;base64, {!! '.base64_encode(QrCode::format('svg')->encoding('UTF-8')->size(100)->errorCorrection('H')->generate($data['url_surat_asli'])).' !!}',  $data['content']);
        }

        // return $file_name_asli;
        $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
        $pdf->save(public_path('upload/file_sip_asli/').$file_name_asli);

        //qrcode surat asli
        $data['url_surat_salinan'] = url('/').'/upload/file_sip_salinan/'. $file_name_salinan;
        $data['jenis_file'] = 'salinan';

        //ttd dan qrcode
        $data['content'] = str_replace(public_path('/upload/users/').$users->photo, public_path('/upload/users/').$users->photo, $data['content']);
        // $data['content'] = str_replace(url('/').'/img/foto3x6.png', url('/').'/upload/users/'.$users->photo, $data['content']);

        if ($surat->disetujui_kasi == 'Disetujui') {
          // $data['content'] = str_replace(url('/').'/img/blank_kasi.png', url('/').'/upload/file_master/'.$JenisSurat->paraf_kasi, $data['content']);
        }

        if ($surat->disetujui_kabid == 'Disetujui') {
          // $data['content'] = str_replace(url('/').'/img/blank_kabid.png', url('/').'/upload/file_master/'.$JenisSurat->paraf_kabid, $data['content']);
        }

        if (($surat->status_aktif == 'Aktif' && $surat->disetujui_kadinkes == 'Disetujui') || ($surat->status_aktif == 'Dicabut' && $surat->pencabutan_disetujui_kadinkes == 'Disetujui')) {
          $data['content'] = str_replace(public_path('/img/blank_ttd.png'), public_path('/upload/file_master/').$JenisSurat->ttd_kadinkes, $data['content']);
          $data['content'] = str_replace(public_path('/img/blank_stempel.png'), public_path('/upload/file_master/').$JenisSurat->stempel_dinkes, $data['content']);
        }

        if ($type == 'ajukan') {
          $data['content'] = str_replace("[[qrcode]]", 'data:image/png;base64, {!! '.base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($data['url_surat_salinan'])).' !!}',  $data['content']);
        }

        $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');

        $pdf->save(public_path('upload/file_sip_salinan/').$file_name_salinan);

        if ($surat->status_aktif == 'Dicabut' || $surat->status_aktif == 'Menunggu Pencabutan') {
          $surat->file_surat_pencabutan_sip_asli      = $file_name_asli;
          $surat->file_surat_pencabutan_sip_salinan     = $file_name_salinan;
        }else{
          $surat->file_surat_sip_asli     = $file_name_asli;
          $surat->file_surat_sip_salinan    = $file_name_salinan;
        }

        $surat->save();
    }

    public function deleteBerkas(Request $request)
    {

      // return $request->id_surat_preview;
      $surat = SuratTemp::find($request->id_surat_preview);

      if (!empty($surat)) {
        if(file_exists(public_path("upload/file_sip_salinan/$surat->file_surat_sip_salinan"))){
          unlink(public_path("upload/file_sip_salinan/$surat->file_surat_sip_salinan"));
        }
        if(file_exists(public_path("upload/file_sip_asli/$surat->file_surat_sip_asli"))){
          unlink(public_path("upload/file_sip_asli/$surat->file_surat_sip_asli"));
        }

        if (!empty($surat->sip_1)) {
          if(file_exists(public_path("uploads/file_berkas/$surat->sip_1"))){
            unlink(public_path("uploads/file_berkas/$surat->sip_1"));
          }
        }

        if (!empty($surat->sip_2)) {
          if(file_exists(public_path("uploads/file_berkas/$surat->sip_2"))){
            unlink(public_path("uploads/file_berkas/$surat->sip_2"));
          }
        }

        // $SyaratPengajuan = SyaratPengajuan::where('id_surat',$request->id_surat_preview);
        // $filenames = $SyaratPengajuan->get()->pluck("nama_file_persyaratan");

        // if (count($filenames) > 0) {
        //   $SyaratPengajuan->delete();
        //   foreach ($filenames as $key => $value) {
        //     // do delete file here
        //     if(file_exists(public_path("upload/file_berkas/$value"))){
        //       unlink(public_path("upload/file_berkas/$value"));
        //     }
        //   }
        // }

        $surat->delete();
      }

    }


  }
