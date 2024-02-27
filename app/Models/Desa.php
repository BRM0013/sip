<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Desa extends Model{
    protected $table = "desa";
    protected $primaryKey = "id_desa";
    public $timestamps = false;

    public static function getJson($input){
      $table  = 'desa';
      $select = "*";
      $replace_field  = [
                // ['old_name' => 'status', 'new_name' => 'users.is_banned'],
                ];

      $param = [
      'input'         => $input->all(),
      'select'        => $select,
      'table'         => $table,
      'replace_field' => $replace_field
      ];

      $datagrid = new Datagrid;

      $data = $datagrid->datagrid_query($param, function($data){
        return $data->join('kecamatan','kecamatan.id_kecamatan','desa.id_kecamatan')
                    ->join('kabupaten','kabupaten.id_kabupaten','kecamatan.id_kabupaten')
                    ->join('provinsi','provinsi.id_provinsi','kabupaten.id_provinsi');
      });
      return $data;
    }
}
