<style type="text/css">

    td{

        padding: 5px;

    }



    .accordion {

      background-color: #eee;

      color: #444;

      cursor: pointer;

      padding: 18px;

      width: 100%;

      border: none;

      text-align: left;

      outline: none;

      font-size: 15px;

      transition: 0.4s;

    }



    .active, .accordion:hover {

      background-color: #ccc; 

    }

    *{
        text-transform: uppercase;
    }



    .panel-accordion{

      padding: 0 18px;

      display: none;

      background-color: white;

      overflow: hidden;

    }

</style>



<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">

    <div class="modal-dialog modal-lg" >

        <div class="modal-content">

            <div class="modal-header" style="background: #b6d1f2; text-shadow: 0 1px 0 #aecef4">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="product-detail-dialog" style="color: #fff; font-size: 18px; font-weight: 600;"><i class="fa fa-user m-r-15 m-l-5"></i>  Rincian Pengajuan </h4>

            </div>

            <div class="modal-body">



                <div class="form-group m-t-0 m-b-25">

                    <button class="accordion">Progress Pengajuan</button>

                    <div class="panel-accordion">

                        <section class="panel panel-default m-b-0">

                            <div class="panel-body">

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

                                            <?php if ($surat_keterangan->disetujui_admin == 'Menunggu'): ?>

                                                <?php echo "Menunggu <b>persetujuan</b>" ?>

                                            <?php elseif ($surat_keterangan->disetujui_admin == 'Disetujui'): ?>

                                                <?php echo "Telah <b>Disetujui</b>" ?>

                                            <?php else: ?>

                                                <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat_keterangan->keterangan.")" ?>

                                            <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat_keterangan->tanggal_disetujui_admin}}</td>

                                    </tr>

                                    <!-- <tr>

                                        <td style="text-align: center;">2</td>

                                        <td>Kasi</td>

                                        <td>

                                            <?php if ($surat_keterangan->disetujui_kasi == 'Menunggu'): ?>

                                                <?php echo "Menunggu <b>persetujuan</b>" ?>

                                            <?php elseif ($surat_keterangan->disetujui_kasi == 'Disetujui'): ?>

                                                <?php echo "Telah <b>Disetujui</b>" ?>

                                            <?php else: ?>

                                                <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat_keterangan->keterangan.")" ?>

                                            <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat_keterangan->tanggal_disetujui_kasi}}</td>

                                    </tr> -->

                                    <tr>

                                        <td style="text-align: center;">3</td>

                                        <td>Kabid</td>

                                        <td>

                                            <?php if ($surat_keterangan->disetujui_kabid == 'Menunggu'): ?>

                                                <?php echo "Menunggu <b>persetujuan</b>" ?>

                                            <?php elseif ($surat_keterangan->disetujui_kabid == 'Disetujui'): ?>

                                                <?php echo "Telah <b>Disetujui</b>" ?>

                                            <?php else: ?>

                                                <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat_keterangan->keterangan.")" ?>

                                            <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat_keterangan->tanggal_disetujui_kabid}}</td>

                                    </tr>

                                    <!-- <tr>

                                        <td style="text-align: center;">4</td>

                                        <td>Kadinkes</td>

                                        <td>

                                                <?php if ($surat_keterangan->disetujui_kadinkes == 'Menunggu'): ?>

                                                    <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                <?php elseif ($surat_keterangan->disetujui_kadinkes == 'Disetujui'): ?>

                                                    <?php echo "Telah <b>Disetujui</b>" ?>

                                                <?php else: ?>

                                                    <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat_keterangan->keterangan.")" ?>

                                                <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat_keterangan->tanggal_disetujui_kadinkes}}</td>

                                    </tr> -->

                                </table>

                            </div>

                        </section>

                    </div>

                </div>

                <!-- =================================================================================================== -->



                <label>Data Permohonan Keterangan</label>

                <section class="panel panel-default m-b-0">

                    <div class="panel-body">

                        <div class='col-lg-8 col-md-7 col-sm-12 col-xs-12'>                      

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Keperluan</label>: {{$surat_keterangan->keperluan}}

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <label>Daftar Surat Izin Praktik</label>

                <section class="panel panel-default m-b-0">

                    <div class="panel-body">

                        <?php if (count($list_surat) > 0): ?>

                          <?php foreach ($list_surat as $row): ?>

                            <div class="form-group" style="margin: 0px;">

                                <div class="col-sm-1"><?php echo $row->sip_ke ?>.</div>

                                <div class="col-sm-11">

                                  <b><?php echo $row->nama_tempat_praktik ?></b>

                                  <br>

                                  (<?php echo $row->nomor_str ?>)

                                  <br>

                                  <?php echo $row->alamat_tempat_praktik ?>

                                </div>

                            </div>

                          <?php endforeach ?>

                        <?php else: ?>

                          <?php echo 'Tidak memiliki tempat praktik.' ?>

                        <?php endif ?>

                    </div>

                </section>



                <label>Data Pemohon</label>

                <section class="panel panel-default m-b-0">

                    <div class="panel-body">

                        <div class='col-lg-8 col-md-7 col-sm-12 col-xs-12'>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Nama</label>: {{$users->name}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Email</label>: {{$users->email}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Jenis Kelamin</label>: {{$users->jenis_kelamin}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Alamat Rumah</label>: {{$users->alamat_jalan_rt_rw}}, Ds. {{$users->desa->nama_desa}}, Kec. {{$users->kecamatan->nama_kecamatan}}, Kab. {{$users->kabupaten->nama_kabupaten}}, {{$users->provinsi->nama_provinsi}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Alamat Domisili</label>: {{$users->alamat_domisili}}

                                </div>

                            </div>

                            <div style="margin-top: 20px;" class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">No Telepon</label>: {{$users->nomor_telpon}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Nomor KTP</label>: {{$users->nomor_ktp}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Agama </label>: {{$users->agama}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Status Perkawinan</label>: {{$users->status_perkawinan}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Pendidikan Terakhir</label>: {{$users->PendidikanTerakhir->pendidikan_terakhir}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Tahun Lulus</label>: {{$users->tahun_lulus}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Jabatan</label>: {{$users->Jabatan->jabatan}}

                                </div>

                            </div>

                        </div>

                        <div style="text-align: center;" class='col-lg-4 col-md-5 col-sm-12 col-xs-12 t-a-center'>

                             @if($users->photo != null && is_file('upload/users/'.$users->photo))

                                <img style="height: 180px; width: 150px;" id="preview-photo" src="{!! url('upload/users/'.$users->photo) !!}" class="img-polaroid" width="80%">

                            @else

                                <img style="height: 180px; width: 150px;" id="preview-photo" src="{{ url('/') }}/dist/img/user2-160x160.jpg" class="img-polaroid" width="184" height="186">  

                            @endif

                        </div>    

                    </div>

                </section>



                <?php if (isset($surat_keterangan->file_surat_keterangan_salinan)): ?>
                    @if(Auth::getUser()->id_level_user != 2)
                    <label>Surat Permohonan</label>

                    <section class="panel panel-default m-b-0">

                        <div class="panel-body">

                            <iframe src="{{ url('/')}}/upload/file_sip_salinan/{{ $surat_keterangan->file_surat_keterangan_salinan }}" width="100%" height="550px"></iframe>

                            <!-- <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/')}}/upload/file_sip_salinan/{{ $surat_keterangan->file_surat_keterangan_salinan }}" width="100%" height="550px"></iframe> -->

                        </div>

                    </section>
                    @endif
                <?php endif ?>



                <!-- Verifikasi berkas jadi satu sama yang ada di surat -->

                <label>Berkas berkas</label>

                <section class="panel panel-default m-b-0">

                    <div class="panel-body">

                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                            <?php 

                              $no = 1;

                              $jumlah = count($berkas_persyaratan)/2;

                            ?>

                            <?php for ($i = 0; $i < count($berkas_persyaratan); $i++){ ?>

                                <?php if (isset($syarat_persyaratan)): ?>

                                    <?php foreach ($syarat_persyaratan as $row2): ?>

                                      <?php if ($row2->id_jenis_persyaratan == $berkas_persyaratan[$i]->id_jenis_persyaratan): ?>

                                            <div class="form-group m-t-0 m-b-25">

                                                <button class="accordion"><?php echo $no++ ?>. <?php echo $berkas_persyaratan[$i]->nama_jenis_persyaratan; ?></button>

                                                <div class="panel-accordion">

                                                    @if($row2->nama_file_persyaratan != '')

                                                      @if(file_exists('upload/file_berkas/'.$row2->nama_file_persyaratan))

                                                        <?php $ext = explode('.',$row2->nama_file_persyaratan); ?>

                                                        @if($ext[1]=='pdf')

                                                          <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/') }}/upload/file_berkas/{{$row2->nama_file_persyaratan}}" width="100%" height="550px"></iframe>

                                                        @else

                                                          <img src="{{ url('/') }}/upload/file_berkas/{{$row2->nama_file_persyaratan}}" alt="" style="width: 100%">

                                                        @endif

                                                      @else

                                                        <?php $status = 'Tidak ada file'; ?>

                                                      @endif

                                                    @endif



                                                    <?php if ((Auth::getUser()->id_level_user == 1 and $row2->disetujui_admin == 'Menunggu') or (Auth::getUser()->id_level_user == 4 and $row2->disetujui_kabid == 'Menunggu')): ?>

                                                        <div id="form_penolakan_{{$row2->id_syarat_pengajuan}}"></div>

                                                        <div class="btn-group" style="margin: 10px; float: right;">

                                                          <button type="button" id="tolak_{{$row2->id_syarat_pengajuan}}" onclick="tampilkan_form_penolakan('{{$row2->id_syarat_pengajuan}}', 'Merah')" class="btn btn-danger">Tolak</button>

                                                          <button type="button" id="ragu_{{$row2->id_syarat_pengajuan}}" onclick="tampilkan_form_penolakan('{{$row2->id_syarat_pengajuan}}', 'Kuning')" class="btn btn-warning">Ragu - Ragu</button>

                                                          <button type="button" id="disetujui_{{$row2->id_syarat_pengajuan}}" onclick="setujui_berkas('{{$row2->id_syarat_pengajuan}}')" class="btn btn-success">Setuju</button>

                                                        </div>

                                                    <?php endif ?>



                                                    <div id="progress_pengajuan_{{$row2->id_syarat_pengajuan}}">

                                                        <input type="hidden" class="status_disetujui_admin" value="{{$row2->disetujui_admin}}">

                                                        <input type="hidden" class="status_disetujui_kasi" value="{{$row2->disetujui_kasi}}">

                                                        <input type="hidden" class="status_disetujui_kabid" value="{{$row2->disetujui_kabid}}">

                                                        <input type="hidden" class="status_disetujui_kadinkes" value="{{$row2->disetujui_kadinkes}}">

                                                        

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

                                                                    <?php if ($row2->disetujui_admin == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($row2->disetujui_admin == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($row2->disetujui_admin == 'Kuning'): ?>

                                                                         <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$row2->keterangan_admin.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$row2->keterangan_admin.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$row2->tanggal_disetujui_admin}}</td>

                                                            </tr>

                                                            <!-- <tr>

                                                                <td style="text-align: center;">2</td>

                                                                <td>Kasi</td>

                                                                <td>

                                                                    <?php if ($row2->disetujui_kasi == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($row2->disetujui_kasi == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($row2->disetujui_kasi == 'Kuning'): ?>

                                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$row2->keterangan_kasi.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$row2->keterangan_kasi.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$row2->tanggal_disetujui_kasi}}</td>

                                                            </tr> -->

                                                            <tr>

                                                                <td style="text-align: center;">3</td>

                                                                <td>Kabid</td>

                                                                <td>

                                                                    <?php if ($row2->disetujui_kabid == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($row2->disetujui_kabid == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($row2->disetujui_kabid == 'Kuning'): ?>

                                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$row2->keterangan_kabid.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$row2->keterangan_kabid.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$row2->tanggal_disetujui_kabid}}</td>

                                                            </tr>

                                                            <!-- <tr>

                                                                <td style="text-align: center;">4</td>

                                                                <td>Kadinkes</td>

                                                                <td>

                                                                    <?php if ($row2->disetujui_kadinkes == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($row2->disetujui_kadinkes == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($row2->disetujui_kadinkes == 'Kuning'): ?>

                                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$row2->keterangan_kadinkes.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$row2->keterangan_kadinkes.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$row2->tanggal_disetujui_kadinkes}}</td>

                                                            </tr> -->

                                                        </table>

                                                    </div>

                                                </div>

                                            </div>

                                      <?php endif ?>

                                    <?php endforeach ?>

                                <?php endif ?>

                            <?php } ?>

                        </div>       

                    </div>

                </section>

            </div>

            <div class="modal-footer">

                <?php if ((Auth::getUser()->id_level_user == 1 and $surat_keterangan->disetujui_admin == 'Menunggu') or (Auth::getUser()->id_level_user == 4 and $surat_keterangan->disetujui_kabid == 'Menunggu')): ?>

                    <form method="post" action="{{ route('verifikasi_surat_keterangan') }}">

                        @csrf

                        <input type="hidden" name="id" value="{{$surat_keterangan->id_surat_keterangan}}">

                        <input type="hidden" name="status_verifikasi" value="Disetujui">

                        <input type="hidden" name="keterangan" value="">

                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default-admin">Tolak</button>

                        <button type="submit" class="btn btn-info">Setujui</button>

                      </div>

                    </form>

                <?php else: ?>

                    <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>

                <?php endif ?>

            </div>

        </div>

    </div>

</div>

<!-- /.modal -->



<div class="modal fade" id="modal-default-admin">

  <div class="modal-dialog">

    <div class="modal-content">

        <form method="post" action="{{ route('verifikasi_surat_keterangan') }}">

            @csrf

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Tolak pengajuan</h4>

          </div>

          <div class="modal-body">

            <input type="hidden" name="id" value="{{$surat_keterangan->id_surat_keterangan}}">

            <input type="hidden" name="status_verifikasi" value="Ditolak">

            <textarea style="text-transform:uppercase" class="form-control" class="form-control" name="keterangan" placeholder="Masukan keterangan penolakan">{{$surat_keterangan->keterangan}}</textarea>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>

            <button type="submit" class="btn btn-primary">Tolak</button>

          </div>

        </form>

    </div>

    <!-- /.modal-content -->

  </div>

  <!-- /.modal-dialog -->

</div>

<!-- /.modal -->



<script type="text/javascript">

    var onLoad = (function() {

        $('#detail-dialog').find('.modal-dialog').css({

            'width'     : '70%'

        });

        $('#detail-dialog').modal('show');

    })();

    

    $('#detail-dialog').on('hidden.bs.modal', function () {

        $('.modal-dialog').html('');

    });



    var acc = document.getElementsByClassName("accordion");

    

    for (var i = 0; i < acc.length; i++) {

      acc[i].addEventListener("click", function() {

        this.classList.toggle("active");

        var panel = this.nextElementSibling;

        if (panel.style.display === "block") {

          panel.style.display = "none";

        } else {

          panel.style.display = "block";

        }

      });

    }

    

    /*$(document).ready(function(){

        check_berkas();

    });*/

    function check_berkas(){

        <?php if (Auth::getUser()->id_level_user == '1'): ?>

            var status_check = document.getElementsByClassName("status_disetujui_admin");

        <?php elseif (Auth::getUser()->id_level_user == '3'): ?>

            var status_check = document.getElementsByClassName("status_disetujui_kasi");

        <?php elseif (Auth::getUser()->id_level_user == '4'): ?>

            var status_check = document.getElementsByClassName("status_disetujui_kabid");

        <?php elseif (Auth::getUser()->id_level_user == '5'): ?>

            var status_check = document.getElementsByClassName("status_disetujui_kadinkes");           

        <?php endif ?>



        for (var i = 0; i < status_check.length; i++) {

            if (status_check[i].value == 'Menunggu') {

               var eleman = document.getElementById('submit_all');

                eleman.setAttribute("disabled", true);

            }else{

                var eleman = document.getElementById('submit_all');

                element.removeAttribute("disabled");

            }

        }

    }



    function tampilkan_form_penolakan(id, persetujuan) {

        html = '<form action="" method="POst" style="margin-top: 20px;">'+

                    '<label>Tulis Keterangan ('+persetujuan+'): </label>'+

                    '<input type="hidden" class="form-control" id="id_syarat_pengajuan_'+id+'" value="'+id+'">'+

                    '<input type="hidden" class="form-control" id="persetujuan_'+id+'" value="'+persetujuan+'">'+

                    '<input type="text" class="form-control" id="keterangan_'+id+'" placeholder="Tulis keterangan...">'+

                    '<button type="button" class="btn btn-danger col-md-12" onclick="tolak_berkas(\''+id+'\')">Simpan</button>'+

                '</form>';

        $('#form_penolakan_'+id).html(html);

    }



    function tolak_berkas(id) {

        var persetujuan = $('#persetujuan_'+id).val();

        var keterangan = $('#keterangan_'+id).val();



        $.post("{{ route('verifikasi_berkas_surat_ket') }}", {id_syarat_pengajuan:id, persetujuan:persetujuan, keterangan:keterangan}).done(function(data){

            if(data.status == 'success'){

              $('#form_penolakan_'+id).html('');

              $('#tolak_'+id).attr('disabled','disabled');

              $('#ragu_'+id).attr('disabled','disabled');

              $('#disetujui_'+id).attr('disabled','disabled');

              $('#progress_pengajuan_'+id).html(data.content);

            }else{

                swal("Whooops!", 'Silahkan coba lagi!', "error");

            }

        });

    }



    function setujui_berkas(id) {

        $.post("{{ route('verifikasi_berkas_surat') }}", {id_syarat_pengajuan:id, persetujuan:'Hijau', keterangan:''}).done(function(data){

            if(data.status == 'success'){

              $('#tolak_'+id).attr('disabled','disabled');

              $('#ragu_'+id).attr('disabled','disabled');

              $('#disetujui_'+id).attr('disabled','disabled');

              $('#progress_pengajuan_'+id).html(data.content);

              //check_berkas();

            }else{

                swal("Whooops!", 'Silahkan coba lagi!', "error");

            }

        });

    }

</script>

