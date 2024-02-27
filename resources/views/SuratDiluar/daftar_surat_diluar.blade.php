<!-- /*`id_surat_diluar`, `id_user`, `tanggal`, `nama_tempat`, `alamat_tempat`, `nomor_str`, `tanggal_berlaku_str`, `status_aktif` FROM `surat_diluar`*/ -->

<div class="modal fade" id="detail-dialog" tabindex="-1" role="dialog" aria-labelledby="product-detail-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0px;">
                <section>
                  <div class="box" style="border-top:none">
                    <div class="col-md-13">
                      <div class="box box-info" id="btn-add" style="border-top:none">
                      <h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
                        Daftar SIP {{ $users->name }}
                      </h4>
                        <div class="box-body">
                          <span style="font-size: 16pt;"><center>{{ $users->name }}</center></span>
                          <label>Daftar Tempat Praktik Diwilayah Sidoarjo</label>
                          <?php if (count($surat_didalam) > 0): ?>
                            <table border="1px" style="padding: 10px; width: 100%; border-collapse: collapse;">
                              <tr style="background: #ababab; color: white; text-align: center;">
                                  <td>Nama Tempat</td>
                                  <td>Alamat Tempat</td>
                                  <td>Nomor STR</td>
                                  <td>Tanggal Berlaku STR</td>
                                  <td>SIP Ke</td>
                                  <td>Status</td>
                              </tr>
                              <?php foreach ($surat_didalam as $row): ?>
                                <tr>
                                  <td>{{ $row->nama_tempat_praktik }}</td>
                                  <td>{{ $row->alamat_tempat_praktik }}</td>
                                  <td>{{ $row->nomor_str }}</td>
                                  <td>{{ $row->tanggal_berlaku_str }}</td>
                                  <td>{{ $row->sip_ke }}</td>                        
                                  @if($row->status_aktif == 'Menunggu')
                                    <td> Menunggu Pengecekan </td>td>
                                  @elseif ($row->status_aktif == 'Aktif')
                                    <td> Proses Tanda Tanggan Basah </td>td>
                                  @elseif ($row->status_aktif == 'Dijadwalkan Tanggal')
                                    <td> Dijadwalkan Tanggal </td>td>
                                  @elseif ($row->status_aktif == 'Sudah Diambil')
                                    <td> Sudah Diambil </td>td>
                                  @else
                                  <td>{{ $row->status_aktif }}</td>                                    
                                  @endif
                                </tr>
                              <?php endforeach ?>
                            </table>
                          <?php else: ?>
                            <center>Tidak memiliki tempat diwilayah sidoarjo.</center>
                          <?php endif ?>

                          <label style="margin-top: 40px;">Daftar Tempat Praktik Diluar Sidoarjo</label>
                          <?php if (count($surat_diluar) > 0): ?>
                            <table border="1px" style="padding: 10px; width: 100%; border-collapse: collapse;">
                              <tr style="background: #ababab; color: white; text-align: center;">
                                  <td>Nama Tempat</td>
                                  <td>Alamat Tempat</td>
                                  <td>Nomor STR</td>
                                  <td>Tanggal Berlaku STR</td>
                                  <td>SIP Ke</td>
                                  <td>Status</td>
                              </tr>
                              <?php foreach ($surat_diluar as $row): ?>
                                <tr>
                                  <td>{{ $row->nama_tempat }}</td>
                                  <td>{{ $row->alamat_tempat }}</td>
                                  <td>{{ $row->nomor_str }}</td>
                                  <td>{{ $row->tanggal_berlaku_str }}</td>
                                  <td>{{ $row->sip_ke }}</td>
                                  @if($row->status_aktif == 'Menunggu')
                                    <td> Menunggu Pengecekan </td>td>
                                  @elseif ($row->status_aktif == 'Aktif')
                                    <td> Proses Tanda Tanggan Basah </td>td>
                                  @elseif ($row->status_aktif == 'Dijadwalkan Tanggal')
                                    <td> Dijadwalkan Tanggal </td>td>
                                  @elseif ($row->status_aktif == 'Sudah Diambil')
                                    <td> Sudah Diambil </td>td>
                                  @else
                                    <td>{{ $row->status_aktif }}</td>                                    
                                  @endif
                                </tr>
                              <?php endforeach ?>
                            </table>
                          <?php else: ?>
                            <center>Tidak memiliki tempat diluar sidoarjo.</center>
                          <?php endif ?>
                        </div>
                      </div>
                      <div class="box-footer">
                        <button type="button" class="btn btn-warning btn-cencel pull-right" data-dismiss="modal">
                          <span style="margin-right: 5px;" class="fa fa-chevron-left"></span> Kembali
                        </button>
                      </div>
                  </div>
                 </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var onLoad = (function() {
        $('#detail-dialog').find('.modal-dialog').css({
            'width'     : '70%'
        });
        $('#detail-dialog').modal('show');
    })();
    $('#detail-dialog').on('hidden.bs.modal', function () {
        $('.modal-dialog').html('');
    })
</script>