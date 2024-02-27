<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

use App\Http\Libraries\Whatsapp;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'jenis_surat' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_wa' => ['required', 'integer'],
            'no_ktp' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $users = User::create([
            'id_level_user' => '2',
            'name' => strip_tags($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'id_jenis_surat' => $data['jenis_surat'],
            'nomor_telpon' => $this->generate_number($data['no_wa']),
            'nomor_ktp' => strip_tags($data['no_ktp']),
        ]);
        
        $getUser = User::orderBy('id','DESC')->first();
        $data['id'] = $getUser->id;

        Whatsapp::verifikasiWA($data['no_wa'],$getUser->id);
        
        // Mail::to($data['email'])->send(new SendMailable($data['email'], $data['name'], $data['password']));
        // $sendMail = Mail::send('auth.email_verifikasi',$data, function ($mail) use ($data){
        //           // $mail->to('zeinsaedi.92@gmail.com'); 
        //           $mail->to($data['email']);
        //           // $mail->to($tujuan);
        //           $mail->subject('Email verifikasi Dinas Kesehatan Kabupaten Sidoarjo');
        //           // silvyaanggraini99@gmail.com
        // });
        
        return $users;
    }

    public static function generate_number($number){
        $noWa = $number;
        if($noWa!=''){
            if($noWa[0]=='0'){
                $no = '';
                for ($i=1; $i < strlen($noWa); $i++) {
                    $no .= $noWa[$i];
                }
                $noWa = '62'.$no;
            }else if($noWa[0]=='6'){
                $noWa = $number;
            }else if($noWa[0]=='+'){
                $no = '';
                for ($i=1; $i < strlen($noWa); $i++) {
                    $no .= $noWa[$i];
                }
                $noWa = $no;
            }else{
                $noWa = '62'.$noWa;
            }
        }else{
            $noWa = '';
        }
        return $noWa;
    }
}
