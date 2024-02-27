<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth, DB;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $email, $nama, $password;

    public function __construct($email, $nama, $password)
    {
        $this->email = $email;  
        $this->nama = $nama;
        $this->password = $password;
    }

    public function build()
    {   
        $users = DB::table('users')
            ->where('email',$this->email)
            ->orderBy('id','DESC')
            ->first();

        $data['id']     = $users->id;
        $data['nama']   = $users->name;
        $data['email']  = $this->email;
        // $data['password'] = $this->password;
        return $this->subject('Email verifikasi Dinas Kesehatan Kabupaten Sidoarjo')->view('auth.email_verifikasi', $data);
    }
}
