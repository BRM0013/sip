<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class JenisSurat extends Model{
    protected $table = "jenis_surat";
    protected $primaryKey = "id_jenis_surat";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'jenis_surat as js';
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
			return $data->orderby('urutan', 'ASC');
		});
		return $data;
	}
}
