<?php



namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use Auth;

class Surat extends Model{
    protected $table = "surat";
    protected $primaryKey = "id_surat";
    public $timestamps = false;

    // GET PENDIDIKAN TERAKHIR
    public function jenis_praktik(){
      return $this->belongsTo('App\Models\MasterData\JenisPraktik','id_jenis_praktik');
    }

    // GET PENDIDIKAN TERAKHIR
    public function jenis_sarana(){
      return $this->belongsTo('App\Models\MasterData\JenisSarana','id_jenis_sarana');
    }

	public static function getJson($input){
		$table  = 'surat as s';
		$select = "*";
		$replace_field  = [
							['old_name' => 'SetNama', 'new_name' => 'u.name'],
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
				return $data->join('users as u','u.id','s.id_user')
				->where('u.id', Auth::getUser()->id)
				->orderby('s.tanggal_pengajuan','ASC');
			}else{

				if ($input->filter == 'Semua') {
					if (in_array(Auth::getUser()->id_level_user, [1,8,9])) {
						$result = $data->join('users as u','s.id_user','u.id')
						->where('s.id_jenis_surat', $input['id'])
						->where('s.status_simpan', 'simpan');

						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}

						return $result->orderby('s.tanggal_pengajuan','ASC');

					}else if (Auth::getUser()->id_level_user == 3) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('s.id_jenis_surat', $input['id']);

						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						return $result->orderby('s.tanggal_pengajuan','ASC');

					}else if (Auth::getUser()->id_level_user == 4) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('s.id_jenis_surat', $input['id'])
						->where('disetujui_admin', 'Disetujui');

						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						return $result->orderby('s.tanggal_pengajuan','ASC');

					}else if (Auth::getUser()->id_level_user == 5) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('s.id_jenis_surat', $input['id']);
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						return $result->orderby('s.tanggal_pengajuan','ASC');

					}
				}else{
					if (in_array(Auth::getUser()->id_level_user, [1,8,9])) {
						$result = $data->join('users as u','s.id_user','u.id')
						->where('s.id_jenis_surat', $input['id'])
						->where('s.status_simpan', 'simpan');

						if ($input->tanggal_terbit != '') {
							$result->where('s.tanggal_terbit', $input->tanggal_terbit)
										 ->where('s.status_aktif', 'Aktif')
										 ->where('s.status_simpan', 'simpan');
						}else{
							$result->where('s.status_aktif', $input->filter)
										 ->where('s.status_simpan', 'simpan');
						}
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}						
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 3) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('s.id_jenis_surat', $input['id'])
						->where('status_aktif', $input->filter);

						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}

						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 4) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('s.id_jenis_surat', $input['id'])
						->where('status_aktif', $input->filter)
						->where('disetujui_admin', 'Disetujui');

						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}

						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 5) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('s.id_jenis_surat', $input['id'])
						->where('status_aktif', $input->filter);

						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}
				}
			}
		});
		return $data;
	}

	public static function getJsonAll($input){
		$table  = 'surat as s';
		$select = "*";
		$replace_field  = [
							['old_name' => 'SetNama', 'new_name' => 'u.name'],
							['old_name' => 'statusAktif', 'new_name' => 's.status_aktif'],
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
				return $data->join('users as u','u.id','s.id_user')
				->where('u.id', Auth::getUser()->id)
				->orderby('s.tanggal_pengajuan','ASC');
				// ->orderby('s.tanggal_pengajuan','DESC');

			}else{
				if ($input->filter == 'Semua') {
					if (in_array(Auth::getUser()->id_level_user, [1,8,9])) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('s.status_simpan', 'simpan');
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}

						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 3) {
						$result = $data->join('users as u','u.id','s.id_user');
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 4) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('disetujui_admin', 'Disetujui');
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 5) {
						$result = $data->join('users as u','u.id','s.id_user');
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}					
				}else{
					if (in_array(Auth::getUser()->id_level_user, [1,8,9])) {
						$result = $data->join('users as u','u.id','s.id_user');

						if ($input->tanggal_terbit != '') {
							$result->where('s.tanggal_terbit', $input->tanggal_terbit)
										 ->where('s.status_aktif', 'Aktif')
										 ->where('s.status_simpan', 'simpan');
						}else{
							$result->where('s.status_aktif', $input->filter)
										 ->where('s.status_simpan', 'simpan');
						}
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}						
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 3) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('status_aktif', $input->filter);
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 4) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('status_aktif', $input->filter)
						->where('disetujui_admin', 'Disetujui');
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}else if (Auth::getUser()->id_level_user == 5) {
						$result = $data->join('users as u','u.id','s.id_user')
						->where('status_aktif', $input->filter);
						
						if ($input->praktik != 'Semua') {
							$result->where('id_jenis_praktik', $input->praktik);
						}
						
						return $result->orderby('s.tanggal_pengajuan','ASC');
					}
				}
			}
		});
		return $data;
	}

	public static function getJsonRS($input){
		$table  = 'surat as s';
		$select = "*";
		$replace_field  = [
							['old_name' => 'SetNama', 'new_name' => 'u.name'],
							['old_name' => 'statusAktif', 'new_name' => 's.status_aktif'],
						  ];
		$param = [
					'input'         => $input->all(),
					'select'        => $select,
					'table'         => $table,
					'replace_field' => $replace_field
				 ];

		$datagrid = new Datagrid;
		$data = $datagrid->datagrid_query($param, function($data) use ($input){
				return $data->join('users as u','u.id','s.id_user')
										->where('s.fasyankes_id', Auth::getUser()->fasyankes_id)
										->orderby('s.tanggal_pengajuan','DESC');
		});
		return $data;
	}

}

