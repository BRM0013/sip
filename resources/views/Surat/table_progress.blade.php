<input type="hidden" class="status_disetujui_admin" value="{{$syarat_pengajuan->disetujui_admin}}">
<input type="hidden" class="status_disetujui_kasi" value="{{$syarat_pengajuan->disetujui_kasi}}">
<input type="hidden" class="status_disetujui_kabid" value="{{$syarat_pengajuan->disetujui_kabid}}">
<input type="hidden" class="status_disetujui_kadinkes" value="{{$syarat_pengajuan->disetujui_kadinkes}}">
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
            <?php if ($syarat_pengajuan->disetujui_admin == 'Menunggu'): ?>
                <?php echo "Menunggu <b>persetujuan</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_admin == 'Hijau'): ?>
                <?php echo "Berkas <b>Disetujui</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_admin == 'Kuning'): ?>
                 <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_pengajuan->keterangan_admin.")" ?>
            <?php else: ?>
                <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_pengajuan->keterangan_admin.")" ?>
            <?php endif ?>
        </td>
        <td  style="text-align: center;">{{$syarat_pengajuan->tanggal_disetujui_admin}}</td>
    </tr>
    <!-- <tr>
        <td style="text-align: center;">2</td>
        <td>Kasi</td>
        <td>
            <?php if ($syarat_pengajuan->disetujui_kasi == 'Menunggu'): ?>
                <?php echo "Menunggu <b>persetujuan</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_kasi == 'Hijau'): ?>
                <?php echo "Berkas <b>Disetujui</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_kasi == 'Kuning'): ?>
                <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_pengajuan->keterangan_kasi.")" ?>
            <?php else: ?>
                <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_pengajuan->keterangan_kasi.")" ?>
            <?php endif ?>
        </td>
        <td  style="text-align: center;">{{$syarat_pengajuan->tanggal_disetujui_kasi}}</td>
    </tr> -->
    <tr>
        <td style="text-align: center;">3</td>
        <td>Kabid</td>
        <td>
            <?php if ($syarat_pengajuan->disetujui_kabid == 'Menunggu'): ?>
                <?php echo "Menunggu <b>persetujuan</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_kabid == 'Hijau'): ?>
                <?php echo "Berkas <b>Disetujui</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_kabid == 'Kuning'): ?>
                <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_pengajuan->keterangan_kabid.")" ?>
            <?php else: ?>
                <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_pengajuan->keterangan_kabid.")" ?>
            <?php endif ?>
        </td>
        <td  style="text-align: center;">{{$syarat_pengajuan->tanggal_disetujui_kabid}}</td>
    </tr>
    <!-- <tr>
        <td style="text-align: center;">4</td>
        <td>Kadinkes</td>
        <td>
            <?php if ($syarat_pengajuan->disetujui_kadinkes == 'Menunggu'): ?>
                <?php echo "Menunggu <b>persetujuan</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_kadinkes == 'Hijau'): ?>
                <?php echo "Berkas <b>Disetujui</b>" ?>
            <?php elseif ($syarat_pengajuan->disetujui_kadinkes == 'Kuning'): ?>
                <?php echo "Berkas <b>ragu-ragu</b>!<br>(".$syarat_pengajuan->keterangan_kadinkes.")" ?>
            <?php else: ?>
                <?php echo "Berkas <b>ditolak</b>!<br>(".$syarat_pengajuan->keterangan_kadinkes.")" ?>
            <?php endif ?>
        </td>
        <td  style="text-align: center;">{{$syarat_pengajuan->tanggal_disetujui_kadinkes}}</td>
    </tr> -->
</table>