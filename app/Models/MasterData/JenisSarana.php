<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class JenisSarana extends Model{
    protected $table = "jenis_sarana";
    protected $primaryKey = "id_jenis_sarana";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'jenis_sarana as js';
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
