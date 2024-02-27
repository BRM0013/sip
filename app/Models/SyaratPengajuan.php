<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class SyaratPengajuan extends Model{
    protected $table = "syarat_pengajuan";
    protected $primaryKey = "id_syarat_pengajuan";
    public $timestamps = false;
}
