<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterData\ChatBot;

use Auth, Redirect, Validator;

class ChatBotController extends Controller{
	public function __construct() {
		$this->middleware('auth');
	}

	public function index(){
		$data['mn_active'] = "master";
		$data['submn_active'] = "chatbot";
		return view('MasterData.ChatBot.chatbot', $data);
	}

	public function loadData(Request $request)
	{
		$lvl1 = ChatBot::where('level',1)->get();
		foreach ($lvl1 as $key1 => $lv1) {
			$lvl2 = ChatBot::where('parent_id',$lv1->id_chatbot)->where('level',2)->get();
			$lv1->lvl2 = $lvl2;

			foreach ($lvl2 as $key2 => $lv2) {
				$lvl3 = ChatBot::where('parent_id',$lv2->id_chatbot)->where('level',3)->get();
				$lv2->lvl3 = $lvl3;
			}
		}

		return ['status'=>'success','code'=>200,'message'=>'Berhasil Mengambil Data','lvl1'=>$lvl1];
	}

	public function create(Request $request){
		$data['chatbot'] 	= ChatBot::find($request->id);
		$data['parent_id'] 	= $request->parent_id;

		if (isset($request->id) && $request->id == 0) {
			$data['title']	= 'TAMBAH';
		}else{
			$data['title'] 	= 'UBAH';
		}

		$content = view('MasterData.ChatBot.add', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function store(Request $request){
		// return $request->all();
		// return $request->jawaban;
		$cek_chatbot = ChatBot::find($request->id_chatbot);
		$parent = ChatBot::find($request->parent_id);

		if (empty($cek_chatbot)) {
			$chatbot = new ChatBot;
			if (empty($parent)) {
				$chatbot->level = 1;
			} else {
				$chatbot->level = $parent->level + 1;
			}
			
			$chatbot->parent_id = $request->parent_id;
		} else {
			$chatbot = ChatBot::find($request->id_chatbot);
		}

		$chatbot->angka = $request->angka;
		$chatbot->pertanyaan = $request->pertanyaan;
		$chatbot->jawaban = $request->jawaban;
		$chatbot->save();

		if ($chatbot) {
			$return = ['status'=>'success','code'=>200,'message'=>(!empty($request->id_chatbot)) ? 'Berhasil Mengubah ChatBot' : 'Berhasil Menambahkan ChatBot'];
		} else {
			$return = ['status'=>'error','code'=>250,'message'=>($request->id_chatbot) ? 'Gagal Mengubah ChatBot' : 'Gagal Menambahkan ChatBot'];
		}

		return $return;
	}

	public function delete(Request $request){
		$chatbot = ChatBot::find($request->id);

		if($chatbot){
			$cek_chatbot = ChatBot::where('parent_id', $chatbot->id_chatbot)->get();
			foreach ($cek_chatbot as $key => $cb) {
				$d_chatbot = ChatBot::find($cb->id_chatbot)->delete();
			}

			$chatbot->delete();

			$return = [
				'status'=>'success',
				'message'=>'Data berhasil dihapus!'
			];
		}else{
			$return = [
				'status'=>'error',
				'message'=>'Data gagal dihapus!'
			];
		}
		return $return;
	}
}
