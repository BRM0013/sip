<label>Berkas berkas Pengajuan Pindah Tempat</label>

<section class="panel panel-default m-b-0">

    <div class="panel-body">

        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

            <?php

              $no = 1;

              $jumlah = count($berkas_persyaratan_pindah_tempat)/2;

            ?>

            <?php for ($i = 0; $i < count($berkas_persyaratan_pindah_tempat); $i++){ ?>

                <?php if (isset($syarat_persyaratan_pindah_tempat)): ?>                                    

                      <?php if (!empty($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan) && $syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_jenis_persyaratan == $berkas_persyaratan_pindah_tempat[$i]->id_jenis_persyaratan): ?>

                            <div class="form-group m-t-0 m-b-25">

                                <button class="accordion"><?php echo $no++ ?>. <?php echo $berkas_persyaratan_pindah_tempat[$i]->nama_jenis_persyaratan; ?></button>

                                <div class="panel-accordion">

                                    @if(!empty($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->nama_file_persyaratan))

                                      @if(file_exists('upload/file_berkas/'.$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->nama_file_persyaratan))

                                        <?php $ext = explode('.',$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->nama_file_persyaratan); ?>

                                        @if($ext[1]=='pdf')

                                          <iframe src="{{ url('/') }}/upload/file_berkas/{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->nama_file_persyaratan}}" width="100%" height="550px"></iframe>

                                          <!-- <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/') }}/upload/file_berkas/{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->nama_file_persyaratan}}" width="100%" height="550px"></iframe> -->

                                        @else

                                          <img src="{{ url('/') }}/upload/file_berkas/{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->nama_file_persyaratan}}" alt="" style="width: 100%">

                                        @endif

                                      @else

                                        <?php $status = 'Tidak ada file'; ?>
                                      @endif

                                    @endif

                                    <?php if ((in_array(Auth::getUser()->id_level_user, [1,8]) && $syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_admin == 'Menunggu') || (Auth::getUser()->id_level_user == 4 && $syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kabid == 'Menunggu')): ?>

                                        <div id="form_penolakan_{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}"></div>

                                        <div class="btn-group" style="margin: 10px; float: right;">

                                          <button type="button" id="tolak_{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}" onclick="tampilkan_form_penolakan('{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}', 'Merah')" class="btn btn-danger">Tolak</button>

                                          <button type="button" id="ragu_{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}" onclick="tampilkan_form_penolakan('{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}', 'Kuning')" class="btn btn-warning">Ragu - Ragu</button>

                                          <button type="button" id="disetujui_{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}" onclick="setujui_berkas('{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}')" class="btn btn-success">Setuju</button>

                                        </div>

                                    <?php endif ?>



                                    <div id="progress_pengajuan_{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->id_syarat_pengajuan}}">

                                        <input type="hidden" class="status_disetujui_admin" value="{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_admin}}">

                                        <input type="hidden" class="status_disetujui_kasi" value="{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kasi}}">

                                        <input type="hidden" class="status_disetujui_kabid" value="{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kabid}}">

                                        <input type="hidden" class="status_disetujui_kadinkes" value="{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kadinkes}}">



                                        <label style="margin-top: 15px;">Persetujuan Berkas : </label>

                                        <table border="1px" style="padding: 10px; width: 100%; border-collapse: collapse;">

                                            <tr style="background: #ababab; color: white; text-align: center;">

                                                <td style="text-align: center;">No</td>

                                                <td>Oleh</td>

                                                <td>Status</td>

                                                <td>Tanggal</td>

                                            </tr>

                                            <tr>

                                                <td style="text-align: center;">1</td>

                                                <td>Admin</td>

                                                <td>

                                                    <?php if ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_admin == 'Menunggu'): ?>

                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_admin == 'Hijau'): ?>

                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_admin == 'Kuning'): ?>

                                                         <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_admin.")" ?>

                                                    <?php else: ?>

                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_admin.")" ?>

                                                    <?php endif ?>

                                                </td>

                                                <td  style="text-align: center;">{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->tanggal_disetujui_admin}}</td>

                                            </tr>

                                            <!-- <tr>

                                                <td style="text-align: center;">2</td>

                                                <td>Kasi</td>

                                                <td>

                                                    <?php if ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kasi == 'Menunggu'): ?>

                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kasi == 'Hijau'): ?>

                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kasi == 'Kuning'): ?>

                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_kasi.")" ?>

                                                    <?php else: ?>

                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_kasi.")" ?>

                                                    <?php endif ?>

                                                </td>

                                                <td  style="text-align: center;">{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->tanggal_disetujui_kasi}}</td>

                                            </tr> -->

                                            <tr>

                                                <td style="text-align: center;">3</td>

                                                <td>Kabid</td>

                                                <td>

                                                    <?php if ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kabid == 'Menunggu'): ?>

                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kabid == 'Hijau'): ?>

                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kabid == 'Kuning'): ?>

                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_kabid.")" ?>

                                                    <?php else: ?>

                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_kabid.")" ?>

                                                    <?php endif ?>

                                                </td>

                                                <td  style="text-align: center;">{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->tanggal_disetujui_kabid}}</td>

                                            </tr>

                                            <!-- <tr>

                                                <td style="text-align: center;">4</td>

                                                <td>Kadinkes</td>

                                                <td>

                                                    <?php if ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kadinkes == 'Menunggu'): ?>

                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kadinkes == 'Hijau'): ?>

                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                    <?php elseif ($syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->disetujui_kadinkes == 'Kuning'): ?>

                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_kadinkes.")" ?>

                                                    <?php else: ?>

                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->keterangan_kadinkes.")" ?>

                                                    <?php endif ?>

                                                </td>

                                                <td  style="text-align: center;">{{$syarat_persyaratan_pindah_tempat[$i]->syarat_pengajuan->tanggal_disetujui_kadinkes}}</td>

                                            </tr> -->

                                        </table>

                                    </div>

                                </div>

                            </div>

                      <?php else : ?>
                        <div class="form-group m-t-0 m-b-25">

                            <button class="accordion"><?php echo $no++ ?>. <?php echo $berkas_persyaratan_pindah_tempat[$i]->nama_jenis_persyaratan; ?></button>

                            <div class="panel-accordion">
                                <div class="text-center text-danger">Pemohon Tidak Mengupload</div>
                            </div>
                        </div>
                      <?php endif ?>


                <?php endif ?>

            <?php } ?>

        </div>

    </div>

</section>