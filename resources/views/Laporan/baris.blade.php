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

	<td style="vertical-align:top;">{{$surat->status_aktif}}</td>
	<td style="vertical-align:top;">{{$surat->tanggal_disetujui_kabid}}</td>
	<td style="vertical-align:top;">{{$surat->nama_surat}}</td>
	<td style="vertical-align:top;">
		@if($surat->id_jenis_surat == 3)
		<span style="float:left;">551.5.1/</span>		

		@elseif ($surat->id_jenis_surat == 26 && ($surat->id_jenis_praktik == 3))
		<span style="float:left;">{!! date('Ymd', strtotime($surat->tanggal_lahir)) !!}/SIPA-3515/FP/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}/{!! ($surat->jenis_kelamin=='Perempuan') ? '2' : '1' !!}</span>

		@elseif ($surat->id_jenis_surat == 28 )
		<span style="float:left;">440/</span>

		@else
		<span style="float:left;">551.4.1/</span>
		@endif

		{{$surat->nomor_surat}}

		<?php
		$romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
		?>

		@if($surat->id_jenis_surat == 3)

		@if (($surat->id_jenis_praktik == 2))
		-K/SIPB/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@else

		/SIPB/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@endif

		@elseif ($surat->id_jenis_surat == 26)

	    @if (($surat->id_jenis_praktik == 2))
	    /SIPA.FK/{!! $romawi[(int) date('m', strtotime($surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}

	    @elseif (($surat->id_jenis_praktik == 5))
	    /SIPA.FD/{!! $romawi[(int) date('m', strtotime($surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}

	    @elseif (($surat->id_jenis_praktik == 4))
	    /SIPA.FP/{!! $romawi[(int) date('m', strtotime($surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime($surat->tanggal_pengajuan)) !!}

	    @else

	    @endif


		@elseif ($surat->id_jenis_surat == 17)

		@if(($surat->id_jenis_praktik == 2))
		-K/SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@else
		/SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@endif


		@elseif ($surat->id_jenis_surat == 4)
		/SIPOP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 7)
		/IP.DS/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 8)
		/IP.DGS/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 9)
		/SIPTF/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 11)
		/SIPTGz/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 12)
		/SIPR/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 13)
		/SIPTTK/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 14)
		/IP-DG/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 15)
		/SIP-RO/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 16)
		/SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 18)
		/SIP-ATLM/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 19)
		/SIPTGM/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 20)
		/SIP-E/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 21)
		/SIPTW/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 22)
		/SIPPA/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 23)
		/SIPAT/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 24)
		/IP.DU/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 28)
		/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 27)
		/SIPTS/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 33)
		/SIPOT/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 34)
		-K/SIPPK/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 35)
		-K/SIPP/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@elseif ($surat->id_jenis_surat == 36)
		SIPTD/{!! $romawi[(int) date('m', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan))] !!}/438.5.2/{!! date('Y', strtotime(($surat->tanggal_terbit!='') ? $surat->tanggal_terbit : $surat->tanggal_pengajuan)) !!}

		@endif

	</td>
	<td style="vertical-align:top;">{{$surat->jenis_kelamin}}</td>
	<td style="vertical-align:top;">{{$surat->tempat_lahir}}</td>
	<td style="vertical-align:top;">{{date('d-m-Y',strtotime($surat->tanggal_lahir))}}</td>
	<td style="vertical-align:top;">
		{{$surat->dusun}}, {{$surat->alamat_jalan_rt_rw}}, {{$surat->nama_desa}}, {{$surat->nama_kecamatan}}, {{$surat->nama_kabupaten}}, {{$surat->nama_provinsi}}
	</td>
	<td style="vertical-align:top;">{{$surat->alamat_domisili}}</td>
	<td style="vertical-align:top;">{{$surat->nomor_telpon}}</td>
	<td style="vertical-align:top;">{{$surat->email}}</td>
	<td style="vertical-align:top;">{{$surat->sebagai_jabatan}}</td>
	<td style="vertical-align:top;">{{$surat->nama_sarana}}</td>
	<td style="vertical-align:top;">{{$surat->nama_tempat_praktik}}</td>
	<td style="vertical-align:top;">{{$surat->alamat_tempat_praktik}}</td>
	<td style="vertical-align:top;">'{{$surat->nomor_str}}'</td>
	<td style="vertical-align:top;">{{date('d-m-Y',strtotime($surat->tanggal_berlaku_str))}}</td>
	<td style="vertical-align:top;">{{$surat->nomor_op}}</td>
	<td style="vertical-align:top;">'{{$surat->nomor_ktp}}'</td>
	<td style="vertical-align:top;">{{$surat->pendidikan_terakhir}}</td>
	<td style="vertical-align:top;">{{$surat->status_kepegawaian}}</td>
</tr>