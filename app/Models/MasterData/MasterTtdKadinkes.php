<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use App\Http\Libraries\Datagrid;
use Auth;

class MasterTtdKadinkes extends Model
{
    protected $table = "master_ttd_kadinkes";
    protected $primaryKey = "id_master_ttd";
    public $timestamps = false;

    public static function getJson($input){
        $table  = 'master_ttd_kadinkes as mtk';
        $select = "mtk.*";
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

