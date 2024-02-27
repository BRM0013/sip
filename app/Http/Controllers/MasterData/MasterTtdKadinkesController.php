<?php
namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterData\MasterTtdKadinkes;
use File, Auth, Redirect, Validator, DB, PDF;

class MasterTtdKadinkesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        $data['mn_active'] = "master";
        $data['submn_active'] = "mst_ttd_kadinkes";
        return view('MasterData.MasterTtdKadinkes.main', $data);
    }

    public function create(Request $request){

        // return $request->all();
        $data['data']   = MasterTtdKadinkes::find($request->id);

        if (isset($request->id) && $request->id == '0') {
            $data['title']  = 'Tambah';
        }else{
            $data['title']  = 'Ubah';
        }

        // return $data;

        $content = view('MasterData.MasterTtdKadinkes.add', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request){



        $rules = array(
            'nama' => 'required',
            'nip' => 'required',
            'jabatan'=> 'required',
            'tanggal_menjabat'=> 'required',
            'tanggal_awal'=> 'required',
            'tanggal_akhir' => 'required',
        );

        $messages = array(
            'required'  => 'Kolom Harus Diisi',
        );

        $validator  = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $data = isset($request->id_master_ttd) ? MasterTtdKadinkes::find($request->id_master_ttd) : new MasterTtdKadinkes();
            $data->nama   = strip_tags($request->nama);
            $data->nip   = strip_tags($request->nip);
            $data->jabatan   = strip_tags($request->jabatan);
            $data->tanggal_menjabat   = strip_tags($request->tanggal_menjabat);
            $data->tanggal_awal   = strip_tags($request->tanggal_awal);
            $data->tanggal_akhir   = strip_tags($request->tanggal_akhir);
            $data->status   = 'Aktif';
            $saved = $data->save();
            if ($saved) {
                return Redirect::route('ttdkadinkes')
                                            ->with('title', 'Berhasil !')
                                            ->with('message', 'Berhasil Diinputkan')
                                            ->with('type', 'success');
            }else{
                return Redirect::route('ttdkadinkes')
                                            ->with('title', 'Gagal !')
                                            ->with('message', 'Ulangi Kembali')
                                            ->with('type', 'error');
            }

        } else {
            return $validator->messages();
        }       
    }

    public function delete(Request $request){
        $res = MasterTtdKadinkes::where('id_master_ttd', $request->id)->delete();
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
      $data = MasterTtdKadinkes::getJson($request);
      return $data;
    }
}
