
<tr>
	<td style="vertical-align:top;text-align: center;">{{$index+1}}</td>
	
	@if($surat->gelar_depan !='' && $surat->gelar_belakang != '')
	<td style="vertical-align:top;">{{$surat->gelar_depan}}. {{$surat->name}}, {{$surat->gelar_belakang}}</td>
	@elseif($surat->gelar_depan !='')
	<td style="vertical-align:top;">{{$surat->gelar_depan}}. {{$surat->name}}</td>
	@elseif($surat->gelar_belakang !='')
	<td style="vertical-align:top;">{{$surat->name}}, {{$surat->gelar_belakang}}</td>
	@else
	<td style="vertical-align:top;">{{$surat->name}}</td>
	@endif
	<td style="vertical-align:top;">{{$surat->sebagai_jabatan}}</td>
	@if($surat->status_aktif == 'Menunggu')
		<td style="vertical-align:top;">Menunggu Pengecekan</td>
	@elseif($surat->status_aktif == 'Aktif')
		<td style="vertical-align:top;">Proses TTD Basah</td>
	@else
		<td style="vertical-align:top;">{{$surat->status_aktif}} {{$surat->jadwalkan_tanggal}}</td>
	@endif
	<td style="vertical-align:top;">{{$surat->nama_tempat_praktik}}</td>
	<td style="vertical-align:top;">{{$surat->alamat_tempat_praktik}}</td>
</tr>