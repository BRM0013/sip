<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTemp extends Model
{
    protected $table = "surat_temp";
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
}
