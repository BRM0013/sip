<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class FormatSurat extends Model{
    protected $table = "format_surat";
    protected $primaryKey = "id_format_surat";
    public $timestamps = false;

	public static function getJson($input){
		$table  = 'format_surat as fs';
		$select = "*,jns.nama_surat as jenis_surat_id";
		$replace_field  = [
							['old_name' => 'jenis_surat_id', 'new_name' => 'jns.nama_surat'],
						  ];
		$param = [
		'input'         => $input->all(),
		'select'        => $select,
		'table'         => $table,
		'replace_field' => $replace_field
		];

		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data) use ($input){
			if (Auth::getUser()->id_level_user != 2) {
				return $data->leftjoin('jenis_surat as jns','jns.id_jenis_surat','fs.jenis_surat_id')
						->orderby('id_format_surat', 'ASC');
			}else{
				return $data->leftjoin('jenis_surat as jns','jns.id_jenis_surat','fs.jenis_surat_id')
							->where('fs.jenis_surat_id', Auth::getUser()->id_jenis_surat)
							->orderby('id_format_surat', 'ASC');
			}
		});
		return $data;


	}
}
