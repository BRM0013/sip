<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Libraries\CompressFile;
use App\Http\Libraries\Whatsapp;


use App\Models\Users;
use App\Mail\SendMailable;
use App\Models\Surat;
use App\Models\SyaratPengajuan;


use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\MasterData\LevelUsers;
use App\Models\MasterData\Jabatan;
use App\Models\MasterData\PendidikanTerakhir;
use App\Models\MasterData\JenisSurat;

use Intervention\Image\ImageManagerStatic as Image;


use File, Auth, Redirect, Validator, DB;

class UsersController extends Controller{

  public function __construct(){
    $this->middleware('auth');
  }

  public function index(){
    $data['mn_active'] = "users";
    return view('users.users', $data);
  }

  public function create(Request $request){
    $data['list_provinsi']            = Provinsi::all();
    $data['list_level_user']          = LevelUsers::all();
    $data['list_jabatan']             = Jabatan::all();
    $data['list_pendidikan_terakhir'] = PendidikanTerakhir::all();
    $data['list_jenis_surat']         = JenisSurat::where('id_jenis_surat', '!=', '28')->get();
    $data['list_kabupaten']           = Kabupaten::all();
    $data['users']                    = isset($request->setting) ? Users::find(Auth::getUser()->id) : Users::find($request->id);

    $data['setting']                  = isset($request->setting) ? 'setting' : '';


    if (isset($request->setting)) {
      $data['title']                  = 'Pengaturan';
      $data['mn_active'] = "pengaturan";
    }else if(isset($request->id)){
      $data['title']                  = 'Ubah Data Pengguna';
      $data['mn_active'] = "pengguna";
    }else{
      $data['title']                  = 'Tambah Pengguna';
      $data['mn_active'] = "pengguna";
    }

    return view('users.add_users', $data);
  }

  public function store(Request $request){
    $rules = array(
      'name'=> 'required',
      'email' => 'required',
      'nomor_telpon' => 'required',
      'nomor_ktp' => 'required',
      'tempat_lahir' => 'required',
      'tanggal_lahir' => 'required',
      'agama' => 'required',
      'jenis_kelamin' => 'required',
      'status_perkawinan' => 'required',
      'provinsi' => 'required',
      'kabupaten' => 'required',
      'kecamatan' => 'required',
      'desa' => 'required',
      'dusun' => 'required',
      'desa' => 'required',
      'alamat_jalan_rt_rw' => 'required',
      'alamat_domisili' => 'required',
      'pendidikan_terakhir' => 'required',
      'tahun_lulus' => 'required',
      'profesi' => 'required',
      'status_kepegawaian' => 'required',
      'jabatan' => 'required',
    );
    
    $messages = array(
      'required'  => 'Kolom Harus Diisi',
    );

    $validator  = Validator::make($request->all(), $rules, $messages);

    if (!$validator->fails()) {
      if (empty($request->tempat_lahir)) {
        // return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/users/setting' : 'home/users')
        // ->with('title', 'Gagal !')
        // ->with('message', 'Maaf, Tempat Lahir Harus Diisi')
        // ->with('type', 'error');
        return ['status'=>'error','code'=>250,'data'=>'','message'=>'Maaf, Tempat Lahir Harus Diisi'];
      }

      if (empty($request->tanggal_lahir)) {
        // return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/users/setting' : 'home/users')
        // ->with('title', 'Gagal !')
        // ->with('message', 'Maaf, Tanggal Lahir Harus Diisi')
        // ->with('type', 'error');
        return ['status'=>'error','code'=>250,'data'=>'','message'=>'Maaf, Tanggal Lahir Harus Diisi'];
      }

      if (!empty($request->file('photo'))) {
        $ext = $request->file('photo')->getClientOriginalExtension();
        if ($ext != 'pdf' && $ext != 'PDF' && $ext != 'jpg' && $ext != 'JPG' && $ext != 'jpeg' && $ext != 'JPEG' && $ext != 'png' && $ext != 'PNG') {
          // return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/users/setting' : 'home/users')
          // ->with('title', 'Gagal !')
          // ->with('message', 'Maaf! file Upload tidak sesuai')
          // ->with('type', 'error');
          return ['status'=>'error','code'=>250,'data'=>'','message'=>'Maaf! file Upload tidak sesuai'];
        }
      }

      $users = isset($request->id) ? Users::find($request->id) : new Users();

      $users->id                     = $request->id;
      if(isset($request->level_user)) {$users->id_level_user           = $request->level_user;}
      if(isset($request->jenis_surat)) {$users->id_jenis_surat         = $request->jenis_surat;}
      $users->id_jabatan             = strtoupper($request->jabatan);
      $users->id_pendidikan_terakhir = $request->pendidikan_terakhir;
      $users->id_desa                = strtoupper($request->desa);
      $users->dusun                  = strtoupper($request->dusun);
      $users->id_kecamatan           = strtoupper($request->kecamatan);
      $users->id_kabupaten           = strtoupper($request->kabupaten);
      $users->id_provinsi            = strtoupper($request->provinsi);
      $users->name                   = strtoupper($request->name);
      $users->email                  = $request->email;
      if (!empty($request->password)) { $users->password                = Hash::make($request->password);}
      if(isset($request->jenis_kelamin)) {$users->jenis_kelamin         = $request->jenis_kelamin;}
      if(isset($request->tempat_lahir)) {$users->tempat_lahir           = strtoupper($request->tempat_lahir);}
      $users->tanggal_lahir          = $request->tanggal_lahir;
      $users->status_perkawinan      = strtoupper($request->status_perkawinan);
      $users->alamat_jalan_rt_rw     = strip_tags(strtoupper($request->alamat_jalan_rt_rw));
      $users->alamat_domisili        = strip_tags(strtoupper($request->alamat_domisili));
      $users->nomor_telpon           = $request->nomor_telpon;
      $users->nomor_ktp              = $request->nomor_ktp;
      $users->agama                  = strtoupper($request->agama);
      $users->gelar_depan            = strip_tags($request->gelar_depan);
      $users->gelar_belakang         = strip_tags($request->gelar_belakang);
      $users->tahun_lulus            = $request->tahun_lulus;
      $users->status_kepegawaian     = $request->status_kepegawaian;
      $users->profesi                = strip_tags(strtoupper($request->profesi)); 

      if (Auth::getUser()->id_level_user == 1) {
        $users->status_verifikasi    = 'Terverifikasi';
      }

      if ($request->file('photo')) {
        $avatar = $request->file('photo');         
        $filename = "stempel-dinkes-uploaded-at-".strtolower(str_replace(" ", "-", $request->name)).'-'.date('Ymd-His').'.'.$avatar->getClientOriginalExtension();
        Image::make($avatar)->resize(130, 180)->save( public_path('/upload/users/' . $filename));
        $users->photo = $filename;
      }

      $saved = $users->save();
      if ($saved) {
        // return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/users/setting' : 'home/users')
        // ->with('title', 'Berhasil !')
        // ->with('message', 'Data berhasil ditambahkan')
        // ->with('type', 'success');
        return ['status'=>'success','code'=>200,'data'=>'','message'=>'Data berhasil ditambahkan'];
      }else{
        // return Redirect::to($request->back_to_set == 'Pengaturan' ? 'home/users/setting' : 'home/users')
        // ->with('title', 'Gagal !')
        // ->with('message', 'Data gagal ditambahkan')
        // ->with('type', 'error');
        return ['status'=>'error','code'=>250,'data'=>'','message'=>'Data gagal ditambahkan'];
      }
    } else {
      return $validator->messages();
    }
  }

  public function edit(Request $request){

  }

  public function delete(Request $request){
    $res = Users::where('id', $request->id)->delete();
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

  }

  public function datagrid(Request $request){
    $data = Users::getJson($request);
    return $data;
  } 

  public function kirim_verifikasi_ulang(Request $request)
  {
    $users = Users::where('status_verifikasi', 'Belum Terverfikasi')->where('created_at','>=','2022-01-01 00:00:00')->get();

   if ($users->count() > 0) {
    foreach ($users as $key => $u) {
     if (!empty($u->nomor_telpon) || $u->nomor_telpon != '-') {
       Whatsapp::verifikasiWA($u->nomor_telpon,$u->id);
     }
   }

   $return  = ['status'=>'success','code'=>200,'message'=>'Link Verifikasi Berhasil Dikirim'];
 } else {
  $return  = ['status'=>'error','code'=>250,'message'=>'Semua Akun sudah mendapatkan Link Verifikasi'];
}

return $return;
} 
}
