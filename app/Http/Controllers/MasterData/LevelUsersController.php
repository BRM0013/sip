<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\LevelUsers;
use Auth;

class LevelUsersController extends Controller{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index(){
		return view('MasterData.LevelUsers.level_users');
	}

	public function datagrid(Request $request){
	  $data = LevelUsers::getJson($request);
      return $data;
	}
}
