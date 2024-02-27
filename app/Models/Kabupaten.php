<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Kabupaten extends Model{
    protected $table = "kabupaten";
    protected $primaryKey = "id_kabupaten";
    public $timestamps = false;

    public static function getJson($input){
      $table  = 'kabupaten';
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
        return $data->join('provinsi','provinsi.id_provinsi','kabupaten.id_provinsi');
      });
      return $data;
    }
}
