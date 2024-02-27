<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterData\Fasyankes;
use File, Auth, Redirect, Validator, DB, PDF;

class FasyankesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        $data['mn_active'] = "master";
        $data['submn_active'] = "fasyankes";
        return view('MasterData.Fasyankes.fasyankes', $data);
    }

    public function create(Request $request){
        $data['fasyankes']   = Fasyankes::find($request->id);

        if (isset($request->id) && $request->id == 0) {
            $data['title']  = 'Tambah';
        }else{
            $data['title']  = 'Ubah';
        }
        $content = view('MasterData.Fasyankes.add_fasyankes', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request){
        $fasyankes = isset($request->id_fasyankes) ? Fasyankes::find($request->id_fasyankes) : new Fasyankes();

        $fasyankes->nama   = strip_tags(strtoupper($request->nama));
        $fasyankes->alamat   = strip_tags(strtoupper($request->alamat));
        $fasyankes->kelas   = strip_tags(strtoupper($request->kelas));
        $saved = $fasyankes->save();
        if ($saved) {
            return Redirect::route('fasyankes')
                                        ->with('title', 'Berhasil !')
                                        ->with('message', 'Data berhasil ditambahkan')
                                        ->with('type', 'success');
        }else{
            return Redirect::route('fasyankes')
                                        ->with('title', 'Gagal !')
                                        ->with('message', 'Data gagal ditambahkan')
                                        ->with('type', 'error');
        }
    }

    public function delete(Request $request){
        $res = Fasyankes::where('id_fasyankes', $request->id)->delete();
        if($res){
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

    public function datagrid(Request $request){
      $data = Fasyankes::getJson($request);
      return $data;
    }
}
