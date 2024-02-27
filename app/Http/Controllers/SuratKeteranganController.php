<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Surat;
use App\Models\Suket;
use App\Models\SuratKeterangan;
use App\Models\SyaratPengajuan;
use App\Models\MasterData\JenisSurat;
use App\Models\MasterData\JenisPersyaratan;
use App\Models\MasterData\TemplateSurat;
use App\Models\MasterData\JenisSarana;
use Illuminate\Support\Facades\Mail;
use App\Http\Libraries\Whatsapp;
use App\Http\Libraries\Formatters;

use File, Auth, Redirect, Validator, DB, PDF;

class SuratKeteranganController extends Controller{

  public function __construct(){
    $this->middleware('auth');
  }

  public function index(){
    $data['mn_active'] = "keterangan";
    return view('SuratKeterangan.surat_keterangan', $data);
  }

  public function create(Request $request){
    $data['surat_keterangan'] = SuratKeterangan::find($request->id_surat_keterangan);
    $data['users'] = isset($request->id_surat_keterangan) ? Users::find($data['surat_keterangan']->id_user) : Users::find(Auth::getUser()->id);
    $data['jenis_surat'] = isset($data['surat_keterangan']) ? JenisSurat::find($data['surat_keterangan']->id_jenis_surat) : JenisSurat::find('28');
    $data['list_surat'] = Surat::where('id_user', $data['users']->id)->where('status_aktif', 'Aktif')->get();

    if (isset($data['surat_keterangan'])) {
      $data['syarat_persyaratan'] = SyaratPengajuan::where('id_surat_keterangan', $data['surat_keterangan']->id_surat_keterangan)->get();
    }

    $data['suratKeterangan'] = Suket::where('user_id',Auth::getUser()->id)->get();
    $persyratan = $data['jenis_surat']->syarat_pengajuan;
    $data['berkas_persyaratan'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");


    return view('SuratKeterangan.add_surat_keterangan', $data);
  }

  public function store(Request $request){
    $SuratKeterangan = $request->id_surat_keterangan == 0 ? new SuratKeterangan() : SuratKeterangan::find($request->id_surat_keterangan);

    $SuratKeterangan->id_user   = $request->id_user;
    $SuratKeterangan->keperluan = strip_tags($request->keperluan);

    if ($request->id_surat_keterangan == 0) {
      $JenisSurat = JenisSurat::find('28');
      $JenisSurat->nomor_surat = $JenisSurat->nomor_surat+1;
      $JenisSurat->save();

      $SuratKeterangan->nomor_surat      = $JenisSurat->nomor_surat;
    }

    if ($request->id_surat_keterangan != 0) {
      $SuratKeterangan->tanggal_pengajuan  = date('Y-m-d H:i:s');
      $SuratKeterangan->disetujui_admin    = 'Menunggu';
      $SuratKeterangan->disetujui_kasi     = 'Menunggu';
      $SuratKeterangan->disetujui_kabid    = 'Menunggu';
      $SuratKeterangan->disetujui_kadinkes = 'Menunggu';
      $SuratKeterangan->status_aktif       = 'Menunggu';


      $SyaratPengajuan = SyaratPengajuan::where('id_surat_keterangan', $request->id_surat_keterangan)
      ->update([
        'disetujui_admin' => 'Menunggu',
        'disetujui_kasi' => 'Menunggu',
        'disetujui_kabid' => 'Menunggu',
        'disetujui_kadinkes' => 'Menunggu',
      ]);
    }

    $saved = $SuratKeterangan->save();

    if ($saved) {

      $persyratan = JenisSurat::find('28')->syarat_pengajuan;
      $berkas_persyaratan = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");

      foreach ($berkas_persyaratan as $row) {
        if (!empty($request->file($row->nama_variable))) {
          $ext = $request->file($row->nama_variable)->getClientOriginalExtension();
          if ($ext != 'pdf' && $ext != 'PDF' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
             return Redirect::route('add_surat_keterangan')
              ->with('title', 'Gagal !')
              ->with('message', 'Maaf! file Upload tidak sesuai')
              ->with('type', 'error');
            }
          $filename                               = $row->nama_variable.strtolower(str_replace(" ", "-", $request->namaLengkap)).'-'.date('Ymd-His').'.'.$ext;
          $temp_foto                              = 'upload/file_berkas/';
          $proses                                 = $request->file($row->nama_variable)->move($temp_foto, $filename);

          $SyaratPengajuan = ($request->id_surat_keterangan != 0) ? SyaratPengajuan::where('id_surat_keterangan', $SuratKeterangan->id_surat_keterangan)->where('id_jenis_persyaratan', $row->id_jenis_persyaratan)->where('id_user', $SuratKeterangan->id_user)->first() : new SyaratPengajuan();

          if(empty($SyaratPengajuan)){ $SyaratPengajuan = new SyaratPengajuan(); }
          $SyaratPengajuan->id_user               = $request->id_user;
          $SyaratPengajuan->id_surat_keterangan   = $SuratKeterangan->id_surat_keterangan;
          $SyaratPengajuan->id_jenis_persyaratan  = $row->id_jenis_persyaratan;
          $SyaratPengajuan->nama_file_persyaratan = $filename;
          $SyaratPengajuan->save();
        }
      }
    }

    if ($saved) {
      return Redirect::route('surat_keterangan')
      ->with('title', 'Berhasil !')
      ->with('message', 'Data berhasil ditambahkan')
      ->with('type', 'success');
    }else{
      return Redirect::route('surat_keterangan')
      ->with('title', 'Gagal !')
      ->with('message', 'Data gagal ditambahkan')
      ->with('type', 'error');
    }
  }

  public function edit(Request $request){

  }

  public function delete(Request $request){
    $res = SuratKeterangan::where('id_surat_keterangan', $request->id)->delete();
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
    $data['surat_keterangan'] = SuratKeterangan::find($request->id);
    $data['users'] = isset($request->id) ? Users::find($data['surat_keterangan']->id_user) : Users::find(Auth::getUser()->id);
    $data['jenis_surat'] = isset($data['surat_keterangan']) ? JenisSurat::find($data['surat_keterangan']->id_jenis_surat) : JenisSurat::find(Auth::getUser()->id_jenis_surat);
    $data['list_surat'] = Surat::where('id_user', $data['users']->id)->where('status_aktif', 'Aktif')->get();

    if (isset($data['surat_keterangan'])) {
      $data['syarat_persyaratan'] = SyaratPengajuan::where('id_surat_keterangan', $data['surat_keterangan']->id_surat_keterangan)->get();
    }

    $persyratan = $data['jenis_surat']->syarat_pengajuan;
    $data['berkas_persyaratan'] = DB::select("SELECT * FROM `jenis_persyaratan` WHERE `id_jenis_persyaratan` IN ($persyratan)");

    $content = view('SuratKeterangan.detail_surat_keterangan', $data)->render();
    return ['status'=>'success','content'=>$content];
  }

  //silfi

  public function upload_surat(Request $request){
    // return $request->all();
    $data['surat_keterangan'] = SuratKeterangan::find($request->id);
    $content = view('SuratKeterangan.upload_surat', $data)->render();
    return ['status'=>'success','content'=>$content];
  }

  public function save_nomor_ebuddy(Request $request)
  {
    // return $request->all();
    $surat = SuratKeterangan::find($request->id);
    $users = Users::find($surat->id_user);
    $save_data = SuratKeterangan::where('id_surat_keterangan', $surat->id_surat_keterangan)->first();

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

   public function verifikasi_berkas_ket(Request $request){

    // return $request->all();

    $SyaratPengajuan = SyaratPengajuan::find($request->id_syarat_pengajuan);

    if ($request->persetujuan == 'Merah') {
        $surat = SuratKeterangan::find($SyaratPengajuan->id_surat_keterangan);
        $users = Users::find($surat->id_user);

        $surat->keterangan   = strip_tags($request->keterangan);

        if ($surat->status_aktif == 'Menunggu') {

            $surat->status_aktif = 'Ditolak';

            $data_email = [
                'email' => $users->email,
                'nama'   => $users->name,
                'keterngan'  => $surat->keterangan,
            ];

            $sendMail = Mail::send('auth.email_tolak_keterangan',$data_email, function ($mail) use ($data_email){
                      // $mail->to('zeinsaedi.92@gmail.com');
                      $mail->to($data_email['email']);
                      // $mail->to($tujuan);
                      $mail->subject('Pengajuan Surat Keterangan Ditolak Dinas Kesehatan Kabupaten Sidoarjo');
                      // silvyaanggraini99@gmail.com
            });
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
    // return $request->all();
    $surat = SuratKeterangan::find($request->id);
    $users = Users::find(Auth::getUser()->id);
    $users_pemohon = Users::find($surat->id_user);

    $surat->keterangan         = strip_tags(strtoupper($request->keterangan));

    if ($request->status_verifikasi == "Ditolak") {
      $surat->status_aktif       = 'Ditolak';
      $warna = 'Merah';

        $data_email = [
            'email' => $users_pemohon->email,
            'nama'   => $users_pemohon->name,
            'keterngan'  => $surat->keterangan,
        ];

        // $sendMail = Mail::send('auth.email_tolak_keterangan',$data_email, function ($mail) use ($data_email){
        //           // $mail->to('zeinsaedi.92@gmail.com');
        //           $mail->to($data_email['email']);
        //           // $mail->to($tujuan);
        //           $mail->subject('Pengajuan Surat Keterangan Ditolak Dinas Kesehatan Kabupaten Sidoarjo');
        //           // silvyaanggraini99@gmail.com
        // });
    }else{
      $warna = 'Hijau';

        $data_email = [
          'keterangan'  => SuratKeterangan::join('users','surat_keterangan.id_user', '=', 'users.id')->where('id_surat_keterangan',$surat->id_surat_keterangan)->first(),
          'syarat' => SyaratPengajuan::selectRaw('syarat_pengajuan.nama_file_persyaratan,jenis_persyaratan.nama_jenis_persyaratan')->join('jenis_persyaratan','syarat_pengajuan.id_jenis_persyaratan', '=', 'jenis_persyaratan.id_jenis_persyaratan')->where('syarat_pengajuan.id_surat_keterangan',$surat->id_surat_keterangan)->orderBy('jenis_persyaratan.id_jenis_persyaratan','Asc')->get(),
        ];

        // return $data_email;

        $sendMail = Mail::send('auth.email_berkas',$data_email, function ($mail) use ($data_email){
          // $mail->to('silvyaanggraini99@gmail.com');
          $mail->to('dinkessidoarjo46@gmail.com');
          $mail->subject('Kelengkapan Dokumen Pemohon Surat Keterangan Praktik');
          // $mail->to($tujuan);
        });
        // end silfi
    }

    if ($users->id_level_user == 1) {
        $surat->disetujui_admin          = $request->status_verifikasi;
        $surat->disetujui_kabid          = $request->status_verifikasi;
        $surat->tanggal_disetujui_admin  = date('Y-m-d H:i:s');
        $surat->tanggal_terbit   = date('Y-m-d H:i:s');

        //silfi
        $SyaratPengajuan = SyaratPengajuan::where('id_surat_keterangan', $surat->id_surat_keterangan)->update([
          'disetujui_admin' => $warna,
          'tanggal_disetujui_admin' => date('Y-m-d H:i:s'),
          'disetujui_kabid' => $warna,
          'tanggal_disetujui_kabid' => date('Y-m-d H:i:s'),
          'keterangan_admin' => $request->keterangan,
          'keterangan_kabid' => $request->keterangan
        ]);

        if ($request->status_verifikasi == 'Ditolak') {
          $surat->status_aktif       = 'Ditolak';
        }else{
          $surat->status_aktif          = 'Aktif';
          $this->cetak_pdf($surat);
        }
    }elseif ($users->id_level_user == 3) {
        $surat->disetujui_kasi = $request->status_verifikasi;
        $surat->tanggal_disetujui_kasi    = date('Y-m-d H:i:s');

        $SyaratPengajuan = SyaratPengajuan::where('id_surat_keterangan', $surat->id_surat_keterangan)->update([
          'disetujui_kasi' => 'Hijau',
          'tanggal_disetujui_kasi' => date('Y-m-d H:i:s')
        ]);

    }elseif ($users->id_level_user == 4) {
        $surat->disetujui_kabid = $request->status_verifikasi;
        $surat->tanggal_disetujui_kabid   = date('Y-m-d H:i:s');

        $SyaratPengajuan = SyaratPengajuan::where('id_surat_keterangan', $surat->id_surat_keterangan)->update([
          'disetujui_kabid' => 'Hijau',
          'tanggal_disetujui_kabid' => date('Y-m-d H:i:s')
        ]);

        $surat->status_aktif          = 'Aktif';
        $this->cetak_pdf($surat);
    }elseif ($users->id_level_user == 5) {
        $surat->disetujui_kadinkes        = $request->status_verifikasi;
        $surat->tanggal_disetujui_kadinkes  = date('Y-m-d H:i:s');
        $surat->status_aktif          = 'Aktif';
        $surat->tanggal_terbit 		= date('Y-m-d H:i:s');

        $SyaratPengajuan = SyaratPengajuan::where('id_surat_keterangan', $surat->id_surat_keterangan)->update([
          'disetujui_kadinkes' => 'Hijau',
          'tanggal_disetujui_kadinkes' => date('Y-m-d H:i:s')
        ]);

        $this->cetak_pdf($surat);
    }

      $surat->save();
      if ($surat){
        return Redirect::route('surat_keterangan')->with('title', 'Berhasil !')->with('message', 'Berhasil '.$request->status_verifikasi)->with('type', 'success');
      }else{
        return Redirect::route('surat_keterangan')->with('title', 'Gagal !')->with('message', 'Gagal '.$request->status_verifikasi)->with('type', 'error');
      }
  }

  private function cetak_pdf($SuratKeterangan){
    $users = Users::find($SuratKeterangan->id_user);
    $JenisSurat = JenisSurat::find($SuratKeterangan->id_jenis_surat);
    $data['content'] = TemplateSurat::where('id_jenis_surat', $SuratKeterangan->id_jenis_surat)->where('jenis', 'PENGAJUAN')->first()->template_surat;

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
    $data['content'] = str_replace('[[photo]]', url('/').'/img/foto3x6.png', $data['content']);
    $data['content'] = str_replace('[[tahun-lulus]]', $users->tahun_lulus, $data['content']);
    $data['content'] = str_replace('[[jenis-kelamin]]', $users->jenis_kelamin, $data['content']);
    $data['content'] = str_replace('[[desa]]', strtoupper($users->desa->nama_desa), $data['content']);
    $data['content'] = str_replace('[[dusun]]', strtoupper($users->dusun), $data['content']);
    $data['content'] = str_replace('[[kecamatan]]', strtoupper($users->kecamatan->nama_kecamatan), $data['content']);
    $data['content'] = str_replace('[[kabupaten]]', strtoupper(str_replace('KABUPATEN', '', $users->kabupaten->nama_kabupaten)), $data['content']);
    $data['content'] = str_replace('[[provinsi]]', strtoupper($users->provinsi->nama_provinsi), $data['content']);
    $data['content'] = str_replace('[[jabatan]]', $users->Jabatan->jabatan, $data['content']);
    $data['content'] = str_replace('[[tempat-lahir]]', strtoupper(str_replace('KABUPATEN', '', $users->tempat_lahir)), $data['content']);
    $data['content'] = str_replace('[[tanggal-lahir]]', Formatters::tgl_indo($users->tanggal_lahir), $data['content']);
    $data['content'] = str_replace('[[status-perkawinan]]', $users->status_perkawinan, $data['content']);
    $data['content'] = str_replace('[[status-kepegawaian]]', $users->status_kepegawaian, $data['content']);
    $data['content'] = str_replace('[[agama]]', $users->agama, $data['content']);
    $data['content'] = str_replace('[[profesi]]', $users->profesi, $data['content']);

    //Surat
    $data['content'] = str_replace('[[tanggal-pengajuan]]', $SuratKeterangan->tanggal_pengajuan, $data['content']);
    $data['content'] = str_replace('[[keperluan]]', $SuratKeterangan->keperluan, $data['content']);
    $data['content'] = str_replace('[[nomor-surat]]', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $data['content']);
    $data['content'] = str_replace('[[tanggal-terbit]]', Formatters::tgl_indo($SuratKeterangan->tanggal_terbit), $data['content']);
    $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    $data['content'] = str_replace('[[tahun-terbit]]', date('Y', strtotime($SuratKeterangan->tanggal_terbit)), $data['content']);
    $data['content'] = str_replace('[[bulan-terbit]]', $romawi[date('m', strtotime($SuratKeterangan->tanggal_terbit))*1], $data['content']);

    if ($SuratKeterangan->status_aktif == 'Menunggu') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/konsep.png', $data['content']);
    }elseif ($SuratKeterangan->status_aktif == 'Kedaluarsa') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/Kedaluwarsa.png', $data['content']);
    }elseif ($SuratKeterangan->status_aktif == 'Ditolak') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/tolak.png', $data['content']);
    }else{
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/blank_watermark.png', $data['content']);
    }

    //ttd dan qrcode
    $data['content'] = str_replace('[[paraf-kasi]]', url('/').'/img/blank_kasi.png', $data['content']);
    $data['content'] = str_replace('[[paraf-kabid]]', url('/').'/img/blank_kabid.png', $data['content']);
    $data['content'] = str_replace('[[paraf-kadinkes]]', url('/').'/img/blank_ttd.png', $data['content']);
    $data['content'] = str_replace('[[stempel-dinkes]]', url('/').'/img/blank_stempel.png', $data['content']);
    // qrcode [[qrcode]]
    $data['content'] = str_replace('[[logo-dinkes]]', url('/').'/img/KOP SIP besar.png', $data['content']);

	  //list surat izin
    $list_surat = Surat::where('id_user', $users->id)->where('status_aktif', 'Aktif')->get();

    $html = "Tidak memiliki Tempat Praktik di wilayah Kabupaten Sidoarjo.";
    if($list_surat->count()!=0){
      $sip_ke_terbilang = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM'];
      $i=1;
      $html = "Telah memiliki Surat Ijin Praktik (SIP) di ".count($list_surat)." (".$sip_ke_terbilang[count($list_surat)].") sarana kesehatan di wilayah Dinas Kesehatan Kabupaten Sidoarjo. Sarana praktik tersebut adalah : <table style='width: 100%; margin-bottom: -7px'>";
      foreach($list_surat as $ls){
        $html .= "<tr>".
        "<td rowspan='3' style='vertical-align: top'>$i.</td>".
        "<td style='width:33%'>Nama Praktik</td>".
        "<td>:</td>".
        "<td style='width:64%'>$ls->nama_tempat_praktik</td>".
        "</tr>".
        "<tr>".
        "<td style='vertical-align: top'>Alamat</td>".
        "<td style='vertical-align: top'>:</td>".
        "<td style='vertical-align: top'>$ls->alamat_tempat_praktik</td>".
        "</tr>".
        "<tr>".
        "<td>No SIP</td>".
        "<td>:</td>".
        "<td>$ls->nomor_str</td>".
        "</tr>";
        $i++;
      }
      $html .= "</table>";
    }

	  // $html = "Telah memiliki ".count($list_surat)." tempat praktik di sidoarjo sebagai berikut : <br><br><table>";
	  // for ($i=0; $i < count($list_surat); $i++) {
	  // 	$html =  $html."<tr>
		 //  					<td>$i. </td>
			//   				<td>
			//   					$list_surat[$i]->nama_tempat_praktik
			//   					<br>
			//   					$list_surat[$i]->alamat_tempat_praktik
			//   					<br>
			//   					$list_surat[$i]->nomor_str
			//   				</td>
		 //  				</tr>";
	  // }
	  // $html = $html."</table>";

    //  $html = "<table border='1'><tr><td>Hello World</td></tr></table>";



    $data['content'] = str_replace('[[list-tempat-praktik]]', $html, $data['content']);
    $data['content'] = str_replace('[[jumlah-tempat-praktik]]', count($list_surat), $data['content']);



    $file_name_asli = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-keterangan-asli.pdf');
    $file_name_salinan = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-keterangan-salinan.pdf');

    if ($SuratKeterangan->file_surat_keterangan_asli != '') {
      $file_name_asli = $SuratKeterangan->file_surat_keterangan_asli;
    }

    if ($SuratKeterangan->file_surat_keterangan_salinan != '') {
      $file_name_salinan = $SuratKeterangan->file_surat_keterangan_salinan;
    }


    //qrcode surat asli
    $data['url_surat_asli'] = url('/').'/upload/file_sip_asli/'.$file_name_asli;
    $data['jenis_file'] = 'asli';

    //Viewne gabung k2 surat

    $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
    $pdf->save('upload/file_sip_asli/'.$file_name_asli);


    //========================================================================================================================
    //qrcode surat asli
    $data['url_surat_salinan'] = url('/').'/upload/file_sip_salinan/'. $file_name_salinan;
    $data['jenis_file'] = 'salinan';

    //ttd dan qrcode
    $data['content'] = str_replace(url('/').'/img/foto3x6.png', url('/').'/upload/users/'.$users->photo, $data['content']);


    if ($SuratKeterangan->status_aktif == 'Aktif') {
      $data['content'] = str_replace(url('/').'/img/blank_ttd.png', url('/').'/upload/file_master/'.$JenisSurat->ttd_kadinkes, $data['content']);
      $data['content'] = str_replace(url('/').'/img/blank_stempel.png', url('/').'/upload/file_master/'.$JenisSurat->stempel_dinkes, $data['content']);
    }

    $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
    $pdf->save('upload/file_sip_salinan/'.$file_name_salinan);

    $SuratKeterangan->file_surat_keterangan_asli = $file_name_asli;
    $SuratKeterangan->file_surat_keterangan_salinan = $file_name_salinan;

    $SuratKeterangan->save();

    if (Auth::User()->id_level_user == 5) {
      $send = Whatsapp::kirimBerkasTte($users->id, $surat->id_surat, $file_name_asli);
    }
  }

  private function cetak_doc($SuratKeterangan){
    $users = Users::find($SuratKeterangan->id_user);
    $JenisSurat = JenisSurat::find($SuratKeterangan->id_jenis_surat);
    $data['content'] = TemplateSurat::where('id_jenis_surat', $SuratKeterangan->id_jenis_surat)->where('jenis', 'PENGAJUAN')->first()->template_surat;

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
    $data['content'] = str_replace('[[photo]]', url('/').'/img/foto3x6.png', $data['content']);
    $data['content'] = str_replace('[[tahun-lulus]]', $users->tahun_lulus, $data['content']);
    $data['content'] = str_replace('[[jenis-kelamin]]', $users->jenis_kelamin, $data['content']);
    $data['content'] = str_replace('[[desa]]', strtoupper($users->desa->nama_desa), $data['content']);
    $data['content'] = str_replace('[[dusun]]', strtoupper($users->dusun), $data['content']);
    $data['content'] = str_replace('[[kecamatan]]', strtoupper($users->kecamatan->nama_kecamatan), $data['content']);
    $data['content'] = str_replace('[[kabupaten]]', strtoupper(str_replace('KABUPATEN', '', $users->kabupaten->nama_kabupaten)), $data['content']);
    $data['content'] = str_replace('[[provinsi]]', strtoupper($users->provinsi->nama_provinsi), $data['content']);
    $data['content'] = str_replace('[[jabatan]]', $users->Jabatan->jabatan, $data['content']);
    $data['content'] = str_replace('[[tempat-lahir]]', strtoupper(str_replace('KABUPATEN', '', $users->tempat_lahir)), $data['content']);
    $data['content'] = str_replace('[[tanggal-lahir]]', Formatters::tgl_indo($users->tanggal_lahir), $data['content']);
    $data['content'] = str_replace('[[status-perkawinan]]', $users->status_perkawinan, $data['content']);
    $data['content'] = str_replace('[[status-kepegawaian]]', $users->status_kepegawaian, $data['content']);
    $data['content'] = str_replace('[[agama]]', $users->agama, $data['content']);
    $data['content'] = str_replace('[[profesi]]', $users->profesi, $data['content']);

    //Surat
    $data['content'] = str_replace('[[tanggal-pengajuan]]', $SuratKeterangan->tanggal_pengajuan, $data['content']);
    $data['content'] = str_replace('[[keperluan]]', $SuratKeterangan->keperluan, $data['content']);
    $data['content'] = str_replace('[[nomor-surat]]', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $data['content']);
    $data['content'] = str_replace('[[tanggal-terbit]]', Formatters::tgl_indo($SuratKeterangan->tanggal_terbit), $data['content']);
    $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    $data['content'] = str_replace('[[tahun-terbit]]', date('Y', strtotime($SuratKeterangan->tanggal_terbit)), $data['content']);
    $data['content'] = str_replace('[[bulan-terbit]]', $romawi[date('m', strtotime($SuratKeterangan->tanggal_terbit))*1], $data['content']);

    if ($SuratKeterangan->status_aktif == 'Menunggu') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/konsep.png', $data['content']);
    }elseif ($SuratKeterangan->status_aktif == 'Kedaluarsa') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/Kedaluwarsa.png', $data['content']);
    }elseif ($SuratKeterangan->status_aktif == 'Ditolak') {
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/tolak.png', $data['content']);
    }else{
      $data['content'] = str_replace('[[watermark]]', url('/').'/img/blank_watermark.png', $data['content']);
    }

    //ttd dan qrcode
    $data['content'] = str_replace('[[paraf-kasi]]', url('/').'/img/blank_kasi.png', $data['content']);
    $data['content'] = str_replace('[[paraf-kabid]]', url('/').'/img/blank_kabid.png', $data['content']);
    $data['content'] = str_replace('[[paraf-kadinkes]]', url('/').'/img/blank_ttd.png', $data['content']);
    $data['content'] = str_replace('[[stempel-dinkes]]', url('/').'/img/blank_stempel.png', $data['content']);
    // qrcode [[qrcode]]
    $data['content'] = str_replace('[[logo-dinkes]]', url('/').'/img/KOP SIP besar.png', $data['content']);

    //list surat izin
    $list_surat = Surat::where('id_user', $users->id)->where('status_aktif', 'Aktif')->get();

    $html = "Tidak memiliki Tempat Praktik di wilayah Kabupaten Sidoarjo.";
    if($list_surat->count()!=0){
      $sip_ke_terbilang = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM'];
      $i=1;
      $html = "Telah memiliki Surat Ijin Praktik (SIP) di ".count($list_surat)." (".$sip_ke_terbilang[count($list_surat)].") sarana kesehatan di wilayah Dinas Kesehatan Kabupaten Sidoarjo. Sarana praktik tersebut adalah : <table style='width: 100%; margin-bottom: -7px'>";
      foreach($list_surat as $ls){
        $html .= "<tr>".
        "<td rowspan='3' style='vertical-align: top'>$i.</td>".
        "<td style='width:33%'>Nama Praktik</td>".
        "<td>:</td>".
        "<td style='width:64%'>$ls->nama_tempat_praktik</td>".
        "</tr>".
        "<tr>".
        "<td style='vertical-align: top'>Alamat</td>".
        "<td style='vertical-align: top'>:</td>".
        "<td style='vertical-align: top'>$ls->alamat_tempat_praktik</td>".
        "</tr>".
        "<tr>".
        "<td>No SIP</td>".
        "<td>:</td>".
        "<td>$ls->nomor_str</td>".
        "</tr>";
        $i++;
      }
      $html .= "</table>";
    }

    $data['content'] = str_replace('[[list-tempat-praktik]]', $html, $data['content']);
    $data['content'] = str_replace('[[jumlah-tempat-praktik]]', count($list_surat), $data['content']);

    $file_name_doc = str_replace(" ", "-", strtolower($JenisSurat->nama_surat).'-'.date('Ymd His').'-'.$users->name.'-keterangan-doc.pdf');

    if ($SuratKeterangan->file_surat_keterangan_doc != '') {
      $file_name_doc = $SuratKeterangan->file_surat_keterangan_doc;
    }

    //qrcode surat asli
    $data['url_surat_doc'] = url('/').'/upload/file_doc/'.$file_name_doc;
    $data['jenis_file'] = 'doc';

    //ttd dan qrcode
    $data['content'] = str_replace(url('/').'/img/foto3x6.png', url('/').'/upload/users/'.$users->photo, $data['content']);

    if ($SuratKeterangan->status_aktif == 'Aktif') {
      // $data['content'] = str_replace(url('/').'/img/blank_ttd.png', url('/').'/upload/file_master/'.$JenisSurat->ttd_kadinkes, $data['content']);
      // $data['content'] = str_replace(url('/').'/img/blank_stempel.png', url('/').'/upload/file_master/'.$JenisSurat->stempel_dinkes, $data['content']);
    }

    //Viewne gabung k2 surat

    return view('Surat.print_doc', $data);
    // $pdf;

    // $pdf = PDF::loadView('Surat.print_pdf', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
    // $pdf->save('upload/file_sip_salinan/'.$file_name_salinan);

    // $pdf = view('Surat.print_doc', $data)->setPaper([0, 0, 609.4488, 935.433], 'portrait');
    // $pdf->save('upload/file_doc/'.$file_name_doc);

    // $SuratKeterangan->file_surat_keterangan_doc = $file_name_doc;
    // $SuratKeterangan->save();
  }

  public function datagrid(Request $request){
    $data = SuratKeterangan::getJson($request);
    return $data;
  }

  public function formSuket(Request $request)
  {
    $content = view('SuratKeterangan.formSuket')->render();
    return ['status'=>'success','content'=>$content];
  }

  public function simpanFormSuket(Request $request)
  {
    $newdata = New Surat;
    $newdata->id_user               = Auth::getUser()->id;
    $newdata->id_jenis_surat        = Auth::getUser()->id_jenis_surat;
    $newdata->id_jenis_praktik      = '7'; // I think its wrong
    $newdata->id_jenis_sarana       = '5'; // I think its wrong
    $newdata->tanggal_pengajuan     = $request->tanggal_pengajuan;
    $newdata->status_simpan         = 'simpan';
    $newdata->nama_tempat_praktik   = strip_tags($request->nama_tempat_praktik);
    $newdata->alamat_tempat_praktik = strip_tags($request->alamat_tempat_praktik);
    $newdata->waktu_praktik         = '-';
    $newdata->status_aktif          = 'Aktif';
    $newdata->sip_ke                = $request->sip_ke;
    $newdata->nomor_str             = strip_tags($request->no_str);

    if (!empty($request->file_sip)) {
      if ($request->edit=='true') {
        if ($newdata->file_sip != null) {
          if (is_file($newdata->file_surat_sip_asli)) {
            File::delete($newdata->file_surat_sip_asli);
          }
        }
      }
      $ext_foto       = $request->file_sip->getClientOriginalExtension();
      $filename       = 'file-sip-'.strtolower(str_replace(" ", "-", Auth::getUser()->name)).'-'.date('Ymd-His').'.'.$ext_foto;
      $temp_foto      = 'upload/file_sip_praktek/';
      $proses         = $request->file_sip->move($temp_foto, $filename);
      $newdata->file_surat_sip_asli = $temp_foto.$filename;
    }
    $newdata->save();

    $dataAll = Surat::where('id_user',Auth::getUser()->id)->where('status_simpan','simpan')->where('status_aktif','Aktif')->get();

    if ($newdata) {
      return ['status'=>'success','message'=>'Berhasil Ditambahkan!', 'data'=>$dataAll];
    }
    return ['status'=>'false','message'=>'Gagal Ditambahkan!'];
  }

  public function jadwalkanTanggal(Request $request)
  {

    if (count((array)$request->id) > 0) {

      $error = 0;

      foreach ((array)$request->id as $key => $val) {
        $cek_surat = SuratKeterangan::where('id_surat_keterangan',$val)->first();
          if (!empty($cek_surat)) {            
            if ($cek_surat->status_aktif != 'Aktif') {
              $error +=1;
            }
          }

      }


      if ($error > 0) {
        return ['status'=>'error','code'=>250,'content'=>'','message'=>'Status Data yang dipilih harus Proses Tanda Tangan Basah'];
      } else {
        $data['id'] = (array)$request->id;
        $content = view('SuratKeterangan.jadwalkan_tanggal', $data)->render();

        return ['status'=>'success','code'=>200,'content'=>$content];
      }
    } else {
      return ['status'=>'error','code'=>250,'content'=>'','message'=>'Tidak ada data yang Dipilih'];        
    }

  }

  public function simpanJadwalkanTanggal(Request $request)
  {
    // return $request->all();
    $rules = array(
      'tanggal' => 'required',
      'jadwal_keterangan' => 'required'
    );

    $messages = array(
      'required'  => 'Kolom Harus Diisi',
    );
    $validator  = Validator::make($request->all(), $rules, $messages);
    if (!$validator->fails()) {
      $tanggal = date('Y-m-d', strtotime($request->tanggal));
      foreach ((array)$request->id_surat_keterangan as $key => $val) {
        
        $surat = SuratKeterangan::find($val);

        if (!empty($surat)) {
          $surat->status_aktif = 'Dijadwalkan Tanggal';
          $surat->jadwalkan_tanggal = $tanggal;
          $surat->jadwal_keterangan = $request->jadwal_keterangan;
          $surat->save();          
          
          $c_surat = SuratKeterangan::find($val);
          $user = Users::find($c_surat->id_user);
          Whatsapp::jadwalkanTanggal($user->nomor_telpon, $user->id, $tanggal, $request->jadwal_keterangan);
        }

      }

      return ['status'=>'success','code'=>200,'data'=>'','message'=>'Berhasil Menjadwalkan Tanggal'];
    } else {
      return $validator->messages();
    }
  }

  public function sudahAmbil(Request $request)
  {

    if (count((array)$request->id) > 0) {

      $error = 0;

      foreach ((array)$request->id as $key => $val) {
        $cek_surat = SuratKeterangan::where('id_surat_keterangan',$val)->first();
          if (!empty($cek_surat)) {
            if ($cek_surat->status_aktif != 'Dijadwalkan Tanggal') {
              $error +=1;
            }            
          }

      }


      if ($error > 0) {
        return ['status'=>'error','code'=>250,'content'=>'','message'=>'Status Data yang dipilih harus (Dijadwalkan Tanggal)'];
      } else {
        $id = (array)$request->id;

        foreach ($id as $key => $val) {
          $surat = SuratKeterangan::find($val);
          $surat->status_aktif = 'Sudah Diambil';
          $surat->save();
        }

        return ['status'=>'success','code'=>200,'content'=>'','message'=>'Berhasil Mengubah Status Menjadi Sudah Diambil'];
      }
    } else {
      return ['status'=>'error','code'=>250,'content'=>'','message'=>'Tidak ada data yang Dipilih'];        
    }
  }

  public function batalkan(Request $request)
  {
    if (count((array)$request->id) > 0) {
      // $cek_surat = Surat::where('id_surat',$val)->first();

      if ($request->status == 'Dijadwalkan Tanggal') {
        $data['status'] = ['Proses Tanda Tangan Basah'];
        $data['status_val'] = ['Aktif'];
      } elseif ($request->status == 'Sudah Diambil') {
        $data['status'] = ['Proses Tanda Tangan Basah','Dijadwalkan Tanggal'];
        $data['status_val'] = ['Aktif','Dijadwalkan Tanggal'];
      } else {
        return ['status'=>'error','code'=>250,'content'=>'','message'=>'Untuk saat ini status yang bisa dibatalkan hanya status (Dijadwalkan Tanggal & Sudah Diambil)'];        
      }

      $data['id'] = (array)$request->id;
      $content = view('SuratKeterangan.batalkan', $data)->render();

      return ['status'=>'success','code'=>200,'content'=>$content];
    } else {
      return ['status'=>'error','code'=>250,'content'=>'','message'=>'Tidak ada data yang Dipilih'];        
    }
  }

  public function simpanBatalkanKeterangan(Request $request)
  {
    // return $request->all();
    $rules = array(
      'status' => 'required',
    );

    $messages = array(
      'required'  => 'Kolom Harus Diisi',
    );
    $validator  = Validator::make($request->all(), $rules, $messages);
    if (!$validator->fails()) {
      foreach ((array)$request->id_surat_keterangan as $key => $val) {
        $surat = SuratKeterangan::find($val);
        if (!empty($surat)) {
          $surat->status_aktif = $request->status;
          $surat->save();
        }

        // $c_surat = Surat::find($val);
        // $user = Users::find($c_surat->id_user);
        // Whatsapp::jadwalkanTanggal($user->nomor_telpon, $user->id, $tanggal, $request->jadwal_keterangan);
      }

      return ['status'=>'success','code'=>200,'data'=>'','message'=>'Berhasil Mengembalikan status Surat'];
    } else {
      return $validator->messages();
    }
  }
}
