<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class LogAktifitas extends Model{
    protected $table = "log_aktifitas";
    protected $primaryKey = "id_log_aktifitas";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'log_aktifitas as ak';
		$select = "ak.*";
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
