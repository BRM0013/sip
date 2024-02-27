<?php

namespace App\Models;

use App\Http\Libraries\Datagrid;
use Illuminate\Database\Eloquent\Model;

class Users extends Model{
    protected $table = "users";
    protected $primaryKey = "id";
    public $timestamps = false;

    // GET DESA
    public function desa(){
      return $this->belongsTo('App\Models\Desa','id_desa');
    }

    // GET KECAMATAN
    public function kecamatan(){
      return $this->belongsTo('App\Models\Kecamatan','id_kecamatan');
    }

    // GET KABUPATEN
    public function kabupaten(){
      return $this->belongsTo('App\Models\Kabupaten','id_kabupaten');
    }

    // GET PROVINSI
    public function provinsi(){
      return $this->belongsTo('App\Models\Provinsi','id_provinsi');
    }

    // GET PENDIDIKAN TERAKHIR
    public function PendidikanTerakhir(){
      return $this->belongsTo('App\Models\MasterData\PendidikanTerakhir','id_pendidikan_terakhir');
    }

    // GET JABATAN
    public function Jabatan(){
      return $this->belongsTo('App\Models\MasterData\Jabatan','id_jabatan');
    }

    public static function getJson($input){
    $table  = 'users as u';
    $select = "*";
    $replace_field  = [
      ['old_name' => 'SetNama', 'new_name' => 'u.name'],
    ];

    $param = [
      'input'         => $input->all(),
      'select'        => $select,
      'table'         => $table,
      'replace_field' => $replace_field
    ];

    $datagrid = new Datagrid;

    $data = $datagrid->datagrid_query($param, function($data){
      return $data->join('level_user as l','l.id_level_user','u.id_level_user');
    });

    return $data;
  }

  public static function getJsonOP($input){
    $table  = 'users as u';
    $select = "*";
    $replace_field  = [
      ['old_name' => 'SetNama', 'new_name' => 'u.name'],
    ];

    $param = [
      'input'         => $input->all(),
      'select'        => $select,
      'table'         => $table,
      'replace_field' => $replace_field
    ];

    $datagrid = new Datagrid;

    $data = $datagrid->datagrid_query($param, function($data){
      return $data->join('level_user as l','l.id_level_user','u.id_level_user')
                  ->join('jenis_surat','jenis_surat.id_jenis_surat','u.id_jenis_surat')
                  ->where('u.id_level_user','6');
    });

    return $data;
  }

  public static function getJsonRS($input){
    $table  = 'users as u';
    $select = "*";
    $replace_field  = [
      ['old_name' => 'SetNama', 'new_name' => 'u.name'],
    ];

    $param = [
      'input'         => $input->all(),
      'select'        => $select,
      'table'         => $table,
      'replace_field' => $replace_field
    ];

    $datagrid = new Datagrid;

    $data = $datagrid->datagrid_query($param, function($data){
      return $data->join('level_user as l','l.id_level_user','u.id_level_user')                  
                  ->where('u.id_level_user','7');
    });

    return $data;
  }

  public static function is_complite($input){
    if (empty($input->profesi) or empty($input->id_kabupaten) or empty($input->id_kecamatan) or empty($input->id_desa) or empty($input->id_provinsi) or empty($input->jenis_kelamin) or empty($input->tempat_lahir) or empty($input->tanggal_lahir) or empty($input->status_perkawinan) or empty($input->id_jabatan) or empty($input->alamat_jalan_rt_rw) or empty($input->alamat_domisili) or empty($input->nomor_telpon) or empty($input->nomor_ktp) or empty($input->agama) or empty($input->photo) or empty($input->id_pendidikan_terakhir) or empty($input->tahun_lulus) or empty($input->dusun)) {
      return false;
    }

    return true;
  }
}
