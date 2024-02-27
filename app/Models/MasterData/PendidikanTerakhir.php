<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class PendidikanTerakhir extends Model{
    protected $table = "pendidikan_terakhir";
    protected $primaryKey = "id_pendidikan_terakhir";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'pendidikan_terakhir as pt';
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
