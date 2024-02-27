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

                                            <?php if ($surat->disetujui_admin == 'Menunggu'): ?>

                                                <?php echo "Menunggu <b>persetujuan</b>" ?>

                                            <?php elseif ($surat->disetujui_admin == 'Disetujui'): ?>

                                                <?php echo "Telah <b>Disetujui</b>" ?>

                                            <?php else: ?>

                                                <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat->keterangan.")" ?>

                                            <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat->tanggal_disetujui_admin}}</td>

                                    </tr>

                                    <!-- <tr>

                                        <td style="text-align: center;">2</td>

                                        <td>Kasi</td>

                                        <td>

                                            <?php if ($surat->disetujui_kasi == 'Menunggu'): ?>

                                                <?php echo "Menunggu <b>persetujuan</b>" ?>

                                            <?php elseif ($surat->disetujui_kasi == 'Disetujui'): ?>

                                                <?php echo "Telah <b>Disetujui</b>" ?>

                                            <?php else: ?>

                                                <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat->keterangan.")" ?>

                                            <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat->tanggal_disetujui_kasi}}</td>

                                    </tr> -->

                                    <tr>

                                        <td style="text-align: center;">3</td>

                                        <td>Kabid</td>

                                        <td>

                                            <?php if ($surat->disetujui_kabid == 'Menunggu'): ?>

                                                <?php echo "Menunggu <b>persetujuan</b>" ?>

                                            <?php elseif ($surat->disetujui_kabid == 'Disetujui'): ?>

                                                <?php echo "Telah <b>Disetujui</b>" ?>

                                            <?php else: ?>

                                                <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat->keterangan.")" ?>

                                            <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat->tanggal_disetujui_kabid}}</td>

                                    </tr>

                                    <!-- <tr>

                                        <td style="text-align: center;">4</td>

                                        <td>Kadinkes</td>

                                        <td>

                                                <?php if ($surat->disetujui_kadinkes == 'Menunggu'): ?>

                                                    <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                <?php elseif ($surat->disetujui_kadinkes == 'Disetujui'): ?>

                                                    <?php echo "Telah <b>Disetujui</b>" ?>

                                                <?php else: ?>

                                                    <?php echo "<b>Menolak</b> permohonan anda!<br>(".$surat->keterangan.")" ?>

                                                <?php endif ?>

                                        </td>

                                        <td  style="text-align: center;">{{$surat->tanggal_disetujui_kadinkes}}</td>

                                    </tr> -->

                                </table>

                            </div>

                        </section>

                    </div>

                </div>

                <!-- =================================================================================================== -->



                <label>Data Permohonan</label>

                <section class="panel panel-default m-b-0">

                    <div class="panel-body">

                        <div class='col-lg-8 col-md-7 col-sm-12 col-xs-12'>                            
                            <!-- @if (in_array(Auth::getUser()->id_level_user, [1,8])) -->
                          	  <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                  <div class="form-group m-t-0 m-b-25">
                                    <form class="form-save form-inline">
                                      <label class="control-label col-md-4 col-sm-4 col-xs-6">Nomor Surat</label>:
                                        <input type="hidden" name="id" value="{{$surat->id_surat}}">
                                          <div class="form-group mb-2">
                                            @if($jenis_surat->id_jenis_surat == 3)
                                               <span style="float:left;">551.5.1/</span>

                                            @elseif ($jenis_surat->id_jenis_surat == 26 && ($surat->id_jenis_praktik == 3))
                                              <span style="float:left;">{!! date('Ymd', strtotime($users->tanggal_lahir)) !!}/SIPA-3515/FP/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}/{!! ($users->jenis_kelamin=='Perempuan') ? '2' : '1' !!}</span>

                                            @elseif ($jenis_surat->id_jenis_surat == 28 )
                                               <span style="float:left;">440/</span>

                                            @else
                                               <span style="float:left;">551.4.1/</span>
                                            @endif
                                          </div>
                                          <div class="form-group mx-sm-3 mb-2 box-noSurat">
                                            @if($surat->nomor_surat == '0')
                                              <input type="text" name="nomor_surat" value="{{$no_urut}}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" size="5"/ class="inNoSurat">
                                            @else
                                              <input type="text" name="nomor_surat" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" size="5"/ class="inNoSurat form-control" value="{{$surat->nomor_surat}}">
                                              <!-- <span style="font-weight:bold;">{{ $surat->nomor_surat }}</span> -->
                                            @endif
                                          </div>
                                          <div class="form-group mb-2" style="margin-left:10px;">
                                            <?php
                                            $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                                             ?>

                                              @if($jenis_surat->id_jenis_surat == 3)

                                                  @if (($surat->jenis_praktik->id_jenis_praktik == 2))
                                                  -K/SIPB/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                                  @else

                                                  /SIPB/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                                  @endif

                                              @elseif ($jenis_surat->id_jenis_surat == 26)

                                                  @if (($surat->jenis_praktik->id_jenis_praktik == 2))
                                                    <span style="float:left;">/SIPA.FK/{!! date('m', strtotime($surat->tanggal_pengajuan)) !!}/438.5.2/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}</span>

                                                  @elseif (($surat->id_jenis_praktik == 5))
                                                    <span style="float:left;">/SIPA.FD/{!! date('m', strtotime($surat->tanggal_pengajuan)) !!}/438.5.2/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}</span>

                                                  @elseif (($surat->id_jenis_praktik == 4))
                                                  <span style="float:left;">/SIPA.FP/{!! date('m', strtotime($surat->tanggal_pengajuan)) !!}/438.5.2/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}</span>

                                                  @endif

                                              @elseif ($jenis_surat->id_jenis_surat == 17)

                                                @if(($surat->jenis_praktik->id_jenis_praktik == 2))
                                                  -K/SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                                @else
                                                /SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                                @endif


                                              @elseif ($jenis_surat->id_jenis_surat == 4)
                                                /SIPOP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 7)
                                                /IP.DS/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 8)
                                                /IP.DS/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 9)
                                                /SIPTF/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 11)
                                                /SIPTGz/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 12)
                                                /SIPR/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 13)
                                                /SIPTTK/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 14)
                                                /IP-DG/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 15)
                                                /SIP-RO/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 16)
                                                /SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 18)
                                                /SIP-ATLM/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 19)
                                                /SIPTGM/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 20)
                                                /SIP-E/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 21)
                                                /SIPTW/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 22)
                                                /SIPPA/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 23)
                                                /SIPAT/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 24)
                                                /IP.DU/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 28)
                                                /438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 27)
                                                /SIPTS/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 33)
                                                /SIPOT/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 34)
                                                -K/SIPPK/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 35)
                                                -K/SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @elseif ($jenis_surat->id_jenis_surat == 36)
                                                /SIPTD/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

                                              @endif
                                          </div>
                                            @if($surat->nomor_surat == '0')
                                                <button type="button" class="btn btn-primary btn-sm btn-submit">Simpan</button>
                                            @endif
                                    </form>
                                  </div>
                              </div>
                            <!-- @endif -->

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>SIP Ke</label>: {{$surat->sip_ke}}

                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Tanggal Pengajuan</label>:
                                    {{ App\Http\Libraries\Formatters::tgl_indo($surat->tanggal_pengajuan,'') }} {{ date('H:i:s', strtotime($surat->tanggal_pengajuan))}}
                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Jenis Praktik</label>: {{$surat->jenis_praktik->nama_jenis_praktik}}

                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Jenis Sarana</label>: {{$surat->jenis_sarana->nama_sarana}}

                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Nomor STR</label>: {{$surat->nomor_str}}

                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Tanggal Belaku STR</label>: {{ App\Http\Libraries\Formatters::tgl_indo($surat->tanggal_berlaku_str,'') }}
                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Nomor Rekomendasi OP</label>: {{$surat->nomor_op}}

                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Nama Tempat Praktik</label>: {{$surat->nama_tempat_praktik}}

                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Alamat Tempat Praktik</label>: {{$surat->alamat_tempat_praktik}}

                                </div>

                            </div>



                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label'>Sebagai / Jabatan</label>: {{$surat->sebagai_jabatan}}

                                </div>

                            </div>



                            <?php if ($jenis_surat->jenis_waktu_praktik == 'Waktu Praktik' || $jenis_surat->jenis_waktu_praktik == 'Waktu Praktik dengan Shift'): ?>

                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <label class="control-label col-xs-12" id='modal-label'>Waktu Praktik</label>

                                        </div>

                                        <div class="col-md-8">

                                            <?php echo $surat->waktu_praktik ?>

                                        </div>

                                    </div>

                                </div>

                            <?php endif ?>

                        </div>

                    </div>

                </section>



                <label>Data Pemohon</label>

                <section class="panel panel-default m-b-0">

                    <div class="panel-body">

                        <div class='col-lg-8 col-md-7 col-sm-12 col-xs-12'>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Nama</label>: {{$users->gelar_depan}} {{$users->name}} {{$users->gelar_belakang}}

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

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">TTL</label>: {{$users->tempat_lahir}}, {{$users->tanggal_lahir}}

                                </div>

                            </div>

                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>

                                <div class="form-group m-t-0 m-b-25">

                                    <label class="control-label col-md-4 col-sm-4 col-xs-6" id='modal-label' for="first-name">Alamat Rumah</label>: {{$users->dusun}}{{$users->alamat_jalan_rt_rw}}, Ds. {{$users->desa->nama_desa}}, Kec. {{$users->kecamatan->nama_kecamatan}}, Kab. {{$users->kabupaten->nama_kabupaten}}, {{$users->provinsi->nama_provinsi}}

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



                <?php if (isset($surat->file_surat_sip_salinan)  && Auth::getUser()->id_level_user != 2): ?>
                    <label>Surat Permohonan</label>

                    <section class="panel panel-default m-b-0">

                        <div class="panel-body">
                            <iframe src="{{ url('/') }}/upload/file_sip_salinan/{{ $surat->file_surat_sip_salinan }}" width="100%" height="550px"></iframe>                           

                            <!-- <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/') }}/upload/file_sip_salinan/{{ $surat->file_surat_sip_salinan }}" width="100%" height="550px"></iframe>                            -->
                        </div>

                    </section>

                <?php endif ?>



                <?php if (Auth::getUser()->id_level_user != 2 && count($list_log_revisi) > 0): ?>

                    <label>Daftar Revisi</label>

                    <section class="panel panel-default m-b-0">

                        <div class="panel-body">

                            <table border="1px" style="padding: 10px; width: 100%; border-collapse: collapse;">

                                <tr style="background: #ababab; color: white; text-align: center;">

                                    <td style="text-align: center;">No</td>

                                    <td>Tanggal Pengajuan</td>

                                    <td>Tanggal Direvisi</td>

                                    <td>Keterangan</td>

                                    <td>Status</td>

                                </tr>

                                <tr>

                                    <?php $no = 1; ?>

                                    <?php foreach ($list_log_revisi as $row): ?>

                                        <tr>

                                            <td><?php echo $no++; ?></td>

                                            <td>{{ App\Http\Libraries\Formatters::tgl_indo($row->tanggal_pengajuan,'') }}</td>

                                            <td>{{ $row->tanggal_direvisi }}</td>

                                            <td>{{ $row->keterangan }}</td>

                                            <td>{{ $row->status }}</td>

                                        </tr>

                                    <?php endforeach ?>

                                </tr>

                            </table>

                        </div>

                    </section>

                <?php endif ?>



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

                                      <?php if (!empty($syarat_persyaratan[$i]->syarat_pengajuan) && $syarat_persyaratan[$i]->syarat_pengajuan->id_jenis_persyaratan == $berkas_persyaratan[$i]->id_jenis_persyaratan): ?>

                                            <div class="form-group m-t-0 m-b-25">

                                                <button class="accordion"><?php echo $no++ ?>. <?php echo $berkas_persyaratan[$i]->nama_jenis_persyaratan; ?></button>

                                                <div class="panel-accordion">

                                                    @if(!empty($syarat_persyaratan[$i]->syarat_pengajuan->nama_file_persyaratan))

                                                      @if(file_exists('upload/file_berkas/'.$syarat_persyaratan[$i]->syarat_pengajuan->nama_file_persyaratan))

                                                        <?php $ext = explode('.',$syarat_persyaratan[$i]->syarat_pengajuan->nama_file_persyaratan); ?>

                                                        @if($ext[1]=='pdf')

                                                          <iframe src="{{ url('/') }}/upload/file_berkas/{{$syarat_persyaratan[$i]->syarat_pengajuan->nama_file_persyaratan}}" width="100%" height="550px"></iframe>

                                                          <!-- <iframe src="{{url('/')}}/viewpdf.php?filepath={{ url('/') }}/upload/file_berkas/{{$syarat_persyaratan[$i]->syarat_pengajuan->nama_file_persyaratan}}" width="100%" height="550px"></iframe> -->

                                                        @else

                                                          <img src="{{ url('/') }}/upload/file_berkas/{{$syarat_persyaratan[$i]->syarat_pengajuan->nama_file_persyaratan}}" alt="" style="width: 100%">

                                                        @endif

                                                      @else

                                                        <?php $status = 'Tidak ada file'; ?>
                                                      @endif

                                                    @endif

                                                    <?php if ((in_array(Auth::getUser()->id_level_user, [1,8]) && $syarat_persyaratan[$i]->syarat_pengajuan->disetujui_admin == 'Menunggu') || (Auth::getUser()->id_level_user == 4 && $syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kabid == 'Menunggu')): ?>

                                                        <div id="form_penolakan_{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}"></div>

                                                        <div class="btn-group" style="margin: 10px; float: right;">

                                                          <button type="button" id="tolak_{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}" onclick="tampilkan_form_penolakan('{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}', 'Merah')" class="btn btn-danger">Tolak</button>

                                                          <button type="button" id="ragu_{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}" onclick="tampilkan_form_penolakan('{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}', 'Kuning')" class="btn btn-warning">Ragu - Ragu</button>

                                                          <button type="button" id="disetujui_{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}" onclick="setujui_berkas('{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}')" class="btn btn-success">Setuju</button>

                                                        </div>

                                                    <?php endif ?>



                                                    <div id="progress_pengajuan_{{$syarat_persyaratan[$i]->syarat_pengajuan->id_syarat_pengajuan}}">

                                                        <input type="hidden" class="status_disetujui_admin" value="{{$syarat_persyaratan[$i]->syarat_pengajuan->disetujui_admin}}">

                                                        <input type="hidden" class="status_disetujui_kasi" value="{{$syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kasi}}">

                                                        <input type="hidden" class="status_disetujui_kabid" value="{{$syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kabid}}">

                                                        <input type="hidden" class="status_disetujui_kadinkes" value="{{$syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kadinkes}}">



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

                                                                    <?php if ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_admin == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_admin == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_admin == 'Kuning'): ?>

                                                                         <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_admin.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_admin.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$syarat_persyaratan[$i]->syarat_pengajuan->tanggal_disetujui_admin}}</td>

                                                            </tr>

                                                            <!-- <tr>

                                                                <td style="text-align: center;">2</td>

                                                                <td>Kasi</td>

                                                                <td>

                                                                    <?php if ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kasi == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kasi == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kasi == 'Kuning'): ?>

                                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_kasi.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_kasi.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$syarat_persyaratan[$i]->syarat_pengajuan->tanggal_disetujui_kasi}}</td>

                                                            </tr> -->

                                                            <tr>

                                                                <td style="text-align: center;">3</td>

                                                                <td>Kabid</td>

                                                                <td>

                                                                    <?php if ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kabid == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kabid == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kabid == 'Kuning'): ?>

                                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_kabid.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_kabid.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$syarat_persyaratan[$i]->syarat_pengajuan->tanggal_disetujui_kabid}}</td>

                                                            </tr>

                                                            <!-- <tr>

                                                                <td style="text-align: center;">4</td>

                                                                <td>Kadinkes</td>

                                                                <td>

                                                                    <?php if ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kadinkes == 'Menunggu'): ?>

                                                                        <?php echo "Menunggu <b>persetujuan</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kadinkes == 'Hijau'): ?>

                                                                        <?php echo "Berkas <b>Disetujui</b>" ?>

                                                                    <?php elseif ($syarat_persyaratan[$i]->syarat_pengajuan->disetujui_kadinkes == 'Kuning'): ?>

                                                                        <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_kadinkes.")" ?>

                                                                    <?php else: ?>

                                                                        <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_persyaratan[$i]->syarat_pengajuan->keterangan_kadinkes.")" ?>

                                                                    <?php endif ?>

                                                                </td>

                                                                <td  style="text-align: center;">{{$syarat_persyaratan[$i]->syarat_pengajuan->tanggal_disetujui_kadinkes}}</td>

                                                            </tr> -->

                                                        </table>

                                                    </div>

                                                </div>

                                            </div>

                                      <?php else : ?>
                                        <div class="form-group m-t-0 m-b-25">

                                            <button class="accordion"><?php echo $no++ ?>. <?php echo $berkas_persyaratan[$i]->nama_jenis_persyaratan; ?></button>

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

                @if(!empty($surat->surat_sebelum_id))
                    @include('berkas_pindah_tempat')
                @endif

            </div>

            <div class="modal-footer">

                <?php if (((in_array(Auth::getUser()->id_level_user, [1,8])) and $surat->disetujui_admin == 'Menunggu') or (Auth::getUser()->id_level_user == 4 and $surat->disetujui_kabid == 'Menunggu') or (Auth::getUser()->id_level_user == 5 and $surat->disetujui_kabid == 'Disetujui' and $surat->disetujui_kadinkes == 'Menunggu' )): ?>

                    <form method="post" action="{{ route('verifikasi_surat') }}">

                        @csrf

                        <input type="hidden" name="id" value="{{$surat->id_surat}}">

                        <input type="hidden" name="status_verifikasi" value="Disetujui">

                        <input type="hidden" name="keterangan" value="">

                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default-admin">Tolak</button>

                        <button type="submit" id="submit_all" class="btn btn-info">Setujui</button>

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

        <form action="{{ route('verifikasi_surat') }}" method="post" enctype="multipart/form-data">
               @csrf
          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title">Tolak pengajuan</h4>

          </div>

          <div class="modal-body">

            <input type="hidden" name="id" value="{{$surat->id_surat}}">

            <input type="hidden" name="status_verifikasi" value="Ditolak">

            <textarea style="text-transform:uppercase" class="form-control" required="required" class="form-control" name="keterangan" placeholder="Masukan keterangan penolakan disini">{{$surat->keterangan}}</textarea>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>

            <button type="submit" class="btn btn-primary">Tolak Berkas</button>

          </div>

        </form>

    </div>

    <!-- /.modal-content -->

  </div>

  <!-- /.modal-dialog -->

</div>

<!-- /.modal -->



<script type="text/javascript">
    @if($warning)
        swal('Whoops !', "Maaf! Pengajuan Ini Sudah di Verifikasi oleh Admin Permohonan Lainnya", 'warning');
    @endif

    var onLoad = (function() {

        $('#detail-dialog').find('.modal-dialog').css({

            'width'     : '70%'

        });

        $('#detail-dialog').modal('show');

    })();



    $('#detail-dialog').on('hidden.bs.modal', function () {

        $('.modal-dialog').html('');

    });

    $('.btn-submit').click(function(e){
       e.preventDefault();
       $('.btn-submit').html('Please wait...').attr('disabled', true);
       var data  = new FormData($('.form-save')[0]);
       $.ajax({
         url: "{{ route('save_nomor') }}",
         type: 'POST',
         data: data,
         async: true,
         cache: false,
         contentType: false,
         processData: false
       }).done(function(data){
         // $('.form-save').validate(data, 'has-error');
         if(data.status == 'success'){
           swal("Success !", data.message, "success");
            $('.inNoSurat').attr('type','hidden');
            $('.box-noSurat').html(data.nomer_surat);
            $('.btn-submit').hide();
         } else if(data.status == 'error') {
           $('.btn-submit').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
           swal('Whoops !', data.message, 'warning');
         } else {
           var n = 0;
           for(key in data){
             if (n == 0) {var dt0 = key;}
             n++;
           }
           $('.btn-submit').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
           swal('Whoops !', 'Kolom '+dt0+' Tidak Boleh Kosong !!', 'error');
         }
       }).fail(function() {
         swal("MAAF !","Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
         $('.btn-submit').html('Simpan <i class="fa fa-save fs-14 m-l-5"></i>').removeAttr('disabled');
       });
     });

    /*$(document).ready(function(){
        check_berkas();
    });*/

    function check_berkas(){

        <?php if (in_array(Auth::getUser()->id_level_user,['1','2'])): ?>

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



        $.post("{{ route('verifikasi_berkas_surat') }}", {id_syarat_pengajuan:id, persetujuan:persetujuan, keterangan:keterangan}).done(function(data){

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
