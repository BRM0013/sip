<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class TemplateSurat extends Model{
    protected $table = "template_surat";
    protected $primaryKey = "id_template_surat";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'template_surat as t';
		$select = "t.*";
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
