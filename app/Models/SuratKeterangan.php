<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class SuratKeterangan extends Model{
    protected $table = "surat_keterangan";
    protected $primaryKey = "id_surat_keterangan";
    public $timestamps = false;


	public static function getJson($input){
		$table  = 'surat_keterangan as s';
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

		$data = $datagrid->datagrid_query($param, function($data) use ($input){
			if (Auth::getUser()->id_level_user == 2) {
				return $data->join('users as u','u.id','s.id_user')->where('u.id', Auth::getUser()->id);
			}else{
				if ($input->filter == 'Semua') {

					if (in_array(Auth::getUser()->id_level_user, [1,8])) {
						return $data->join('users as u','u.id','s.id_user');
					}else if (Auth::getUser()->id_level_user == 3) {
						return $data->join('users as u','u.id','s.id_user');
					}else if (Auth::getUser()->id_level_user == 4) {
						return $data->join('users as u','u.id','s.id_user')->where('disetujui_admin', 'Disetujui');
					}else if (Auth::getUser()->id_level_user == 5) {
						return $data->join('users as u','u.id','s.id_user');
					}

					/*if (Auth::getUser()->id_level_user == 1) {
						return $data->join('users as u','u.id','s.id_user');
					}else if (Auth::getUser()->id_level_user == 3) {
						return $data->join('users as u','u.id','s.id_user')->where('disetujui_admin', 'Disetujui');
					}else if (Auth::getUser()->id_level_user == 4) {
						return $data->join('users as u','u.id','s.id_user')->where('disetujui_admin', 'Disetujui')->where('disetujui_kasi', 'Disetujui');
					}else if (Auth::getUser()->id_level_user == 5) {
						return $data->join('users as u','u.id','s.id_user')->where('disetujui_admin', 'Disetujui')->where('disetujui_kasi', 'Disetujui')->where('disetujui_kabid', 'Disetujui');
					}*/
				}else{

					if (in_array(Auth::getUser()->id_level_user, [1,8])) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter);
					}else if (Auth::getUser()->id_level_user == 3) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter);
					}else if (Auth::getUser()->id_level_user == 4) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter)->where('disetujui_admin', 'Disetujui');
					}else if (Auth::getUser()->id_level_user == 5) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter);
					}

					/*if (Auth::getUser()->id_level_user == 1) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter);
					}else if (Auth::getUser()->id_level_user == 3) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter)->where('disetujui_admin', 'Disetujui');
					}else if (Auth::getUser()->id_level_user == 4) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter)->where('disetujui_admin', 'Disetujui')->where('disetujui_kasi', 'Disetujui');
					}else if (Auth::getUser()->id_level_user == 5) {
						return $data->join('users as u','u.id','s.id_user')->where('status_aktif', $input->filter)->where('disetujui_admin', 'Disetujui')->where('disetujui_kasi', 'Disetujui')->where('disetujui_kabid', 'Disetujui');
					}*/
				}
			}
		});
		return $data;
	}
}
