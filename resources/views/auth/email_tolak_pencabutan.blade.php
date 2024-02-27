<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Permohonan Pencabutan Surat Izin Praktik</div>

                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <span class="invalid-feedback" role="alert">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">Bapak / Ibu. <b>{{ $nama }} </b>Yth</label>
                                        <br>
                                        <label for="email" class="col-md-4 col-form-label text-md-right">
                                            <br>
                                            Mohon maaf kepada bapak atau ibu, kami tidak dapat memberikan Surat Pencabutan Izin Praktik, silahkan lakukan perbaikan sesuai dengan keterangan beriku : 
                                            <br>
                                            {{ $keterngan }}
                                            <br>
                                        </label>
                                        <br>

                                        <br>

                                        <label for="email" class="col-md-4 col-form-label text-md-right">
                                            Semoga informasi ini dapat bermanfaat bagi anda.
                                        <br>
                                        <br>
                                        Dengan senang hati kami akan melayani anda.
                                        <br>
                                        Terima kasih.
                                        <br>
                                        <br>
                                        
                                        </label>
                                        
                                    
                                </span>
                            </div>
                        </div>


                        <div class="card-header">
                            Hormat Kami,
                            <br>
                            <br>
                            <br>
                            Dinas Kesehatan Kabupaten Sidoarjo
                        </div>

                        <br>
                        <label for="email" class="col-md-4 col-form-label text-md-right">
                            Surat ini dihasilkan oleh komputer dan tidak perlu dijawab kembali ke alamat email di atas.
                        </label>
                    </div>
                </form>    
            </div>
        </div>
    </div>
</div>
