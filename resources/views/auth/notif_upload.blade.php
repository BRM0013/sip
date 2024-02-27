<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pemberitahuan !, Validasi Berkas persyaratan SIP dan Foto</div>

                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <span class="invalid-feedback" role="alert">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Atas Nama. {{$name}} <b></b></label>                                                                        
                                </span>
                                <span class="invalid-feedback" role="alert">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">
                                        </br>Segera Login akun SIP anda dan Upload Foto serta Berkas-berkas Pengajuan SIP anda sebelumnya.</br>
                                    </label>                                        
                                </span>
                            </div>
                        </div>

                        <div class="card-header">
                            Hormat Kami,
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
