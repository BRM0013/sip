<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class JenisPersyaratan extends Model{
    protected $table = "jenis_persyaratan";
    protected $primaryKey = "id_jenis_persyaratan";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'jenis_persyaratan as jp';
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

    public function berkasPersyaratan()
    {
        return $this->belongsTo(BerkasPersyaratan::class, 'id_jenis_persyaratan');
    }
}


