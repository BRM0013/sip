<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth, DB;

class SendMailableTolak extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $email, $nama, $sip, $keterngan;
    public function __construct($email, $nama, $sip, $keterngan)
    {
        $this->$email = $email;
        $this->nama = $nama;
        $this->sip = $sip;
        $this->keterngan = $keterngan;
    }


    public function build()
    {
        $data['nama']   = $this->nama;
        $data['sip']  = $this->sip;
        $data['keterngan']  = $this->keterngan;

        return $this->subject('Pengajuan Surat Izin Praktik Ditolak Dinas Kesehatan Kabupaten Sidoarjo')->view('auth.email_tolak', $data);
    }
}
