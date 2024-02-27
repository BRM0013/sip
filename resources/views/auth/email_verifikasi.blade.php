<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verifikasi Email</div>

                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <div class="form-group row">
                            <!-- <label for="email" class="col-md-4 col-form-label text-md-right">Helo, (nama penggguna)</label> -->

                            <div class="col-md-6">
                                <span class="invalid-feedback" role="alert">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">Bapak / Ibu. <b>{{ $name }} </b>Yth</label>
                                        <br>
                                        <label for="email" class="col-md-4 col-form-label text-md-right">
                                            <br>
                                            Terima kasih telah mendaftarkan diri Anda pada layanan Dinas Kesehatan Kab Sidoarjo.
                                            <br>
                                            Silakan menekan tombol di bawah ini untuk mengonfirmasi bahwa email Anda aktif:
                                            <!-- <div class="form-group row mb-0">
                                                <div class="col-md-6 offset-md-4">
                                                    <a href="{!! url('/') !!}/verifikasi/{{ $id }}" target="_blank">
                                                        <button class="btn btn-primary">
                                                            Verifikasi Sekarang
                                                        </button>
                                                    </a>
                                                </div>
                                            </div> -->
                                            <!-- <small>Klik Tombol diatas untuk pengguna Gmail</small> -->
                                            <br>
                                            <br>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-6 offset-md-4">
                                                    <a href="{!! url('/') !!}/verifikasi/{{ $id }}" target="_blank"><h4><i class="fa fa link"></i><b>Klik Link untuk Verifikasi.</b></h4></a>
                                                </div>
                                            </div>

                                            <br>
                                            <br>Email: <b>{{ $email }}</b>
                                        </label>
                                        <br>

                                        <br>

                                        <label for="email" class="col-md-4 col-form-label text-md-right">
                                            Semoga informasi ini dapat bermanfaat bagi anda. Untuk informasi
                                        lebih lanjut, silakan menghubungi kami melalui fasilitas [Sentra Pesan]
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
