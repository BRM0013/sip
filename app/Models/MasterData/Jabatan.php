<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class Jabatan extends Model{
    protected $table = "jabatan";
    protected $primaryKey = "id_jabatan";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'jabatan as j';
		$select = "j.*";
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
