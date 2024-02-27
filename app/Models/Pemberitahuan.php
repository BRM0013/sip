<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemberitahuan extends Model{
    protected $table = "pemberitahuan";
    protected $primaryKey = "id_pemberitahuan";
    public $timestamps = false;
}
