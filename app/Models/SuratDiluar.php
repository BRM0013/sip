<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class SuratDiluar extends Model{
    protected $table = "surat_diluar";
    protected $primaryKey = "id_surat_diluar";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'surat_diluar as sd';
		$select = "sd.*";
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
			return $data->where('sd.id_user', Auth::getUser()->id);
		});
		return $data;
	}
}
