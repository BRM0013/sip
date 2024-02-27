<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kelengkapan Berkas Pencabutan Oleh Pemohon Surat Izin Praktik</div>

                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <span class="invalid-feedback" role="alert">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">Atas Nama. <b>{{ $surat_pencabutan->name }} </b></label>
                                        <label for="email" class="col-md-4 col-form-label text-md-right">
                                            <br>Sebagai Berikut : <br>                                            
                                        </label>
                                        <label for="email" class="col-md-4 col-form-label text-md-right">
                                           <p style="margin: 0;">                                           
                                            <ol>
                                            @foreach ($syarat as $keyform)
                                              <li>{{ $keyform->nama_jenis_persyaratan }} => <a href="{{url('/')}}/upload/file_berkas/{{ $keyform->nama_file_persyaratan }}">Download</a></li>
                                            @endforeach          
                                            </ol>
                                          </p>                                    
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
