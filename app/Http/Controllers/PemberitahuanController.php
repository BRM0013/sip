<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pemberitahuan;
use File, Auth, Redirect;

class PemberitahuanController extends Controller{
	public function set_dibaca(Request $request){
		if (Auth::getUser()->id_level_user == 2) {
			Pemberitahuan::where('user_id', Auth::getUser()->id)->where('dibaca', 'Tidak')->where('id_pemberitahuan', $request->id)->update(['dibaca' => 'Ya']);
		}else{
			Pemberitahuan::where('id_level_user', Auth::getUser()->id_level_user)->where('id_pemberitahuan', $request->id)->where('dibaca', 'Tidak')->update(['dibaca' => 'Ya']);
		}
		return '';
	}
}
