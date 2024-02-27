<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

use App\Http\Libraries\Datagrid;
use Auth;

class ChatBot extends Model{
    protected $table = "chatbot";
    protected $primaryKey = "id_chatbot";
    public $timestamps = false;
}
