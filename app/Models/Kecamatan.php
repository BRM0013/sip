<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Kecamatan extends Model{
    protected $table = "kecamatan";
    protected $primaryKey = "id_kecamatan";
    public $timestamps = false;

    public static function getJson($input){
  		$table  = 'kecamatan';
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
  			return $data->join('kabupaten','kabupaten.id_kabupaten','kecamatan.id_kabupaten')
                    ->join('provinsi','provinsi.id_provinsi','kabupaten.id_provinsi');

  		});
  		return $data;
  	}
}
