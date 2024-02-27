<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;

class Provinsi extends Model{
    protected $table = "provinsi";
    protected $primaryKey = "id_provinsi";
    public $timestamps = false;

    public static function getJson($input){
  		$table  = 'provinsi';
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
  			return $data;
  		});
  		return $data;
  	}
}
