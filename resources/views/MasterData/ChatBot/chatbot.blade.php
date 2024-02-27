@extends('layouts.admin-template')
<style>
table, th, td {
  border: 1px solid grey !important;
}
</style>
@section('content')

<div class="content-wrapper">
	<section class="content-header">
		<h1>ChatBot</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Master Data</a></li>
			<li class="active"> Master ChatBot</li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info" id="btn-add" style="border-top:none">
			<h4 style="padding: 10px;margin:0px;color:#fff;font-weight: 600;background: #b6d1f2; text-shadow: 0 1px 0 #aecef4;">
				<i class="fa fa-file iconLabel m-r-15"></i> Data ChatBot
			</h4>
			<div class="box-body main-layer">
				<div class="col-md-12" style="margin-bottom: 10px;">
					<a href="javascript:void(0)" onclick="add(``,``)" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Judul Pertanyaan</a>
				</div>
				<div class="col-md-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="3">No</th>
								<th>Pertanyaan</th>
								<th>Jawaban</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody class="tempatChatBot">
						</tbody>
					</table>
				</div>
			</div>
			<div class="other-content"></div>
		</div>
	</section>
	<div class="clearfix"></div>
</div>

<div class="modal-dialog"></div>

@endsection
@section('js')
<script type="text/javascript">

	$(document).ready(() => {
		loadData()
	});

	function loadData() {
		$.post("{{route('loadDataChatBot')}}").done((data) => {
			if (data.status == 'success') {

				var lvl1 = data.lvl1;
				var html = '';
				lvl1.forEach((v1,i) => {
					html += '<tr>';
					html += '<td colspan="3">'+v1.angka+'</td>';
					html += '<td>'+v1.pertanyaan+'</td>';
					html += '<td>'+((v1.jawaban) ? v1.jawaban : '')+'</td>';
					html += '<td><a href="javascript:void(0)" onclick="add(``,`'+v1.id_chatbot+'`)" class="btn btn-sm btn-success" style="margin-bottom:5px;width:100%;"><i class="fa fa-plus"></i></a>';
					html += '<a href="javascript:void(0)" onclick="edit(`'+v1.id_chatbot+'`,`'+v1.id_chatbot+'`)" class="btn btn-sm btn-warning" style="margin-bottom:5px;width:100%;"><i class="fa fa-edit"></i></a>';
					html += '<a href="javascript:void(0)" onclick="deletes(`'+v1.id_chatbot+'`)" class="btn btn-sm btn-danger" style="margin-bottom:5px;width:100%;"><i class="fa fa-trash"></i></a></td>';
					html += '</tr>';

					v1.lvl2.forEach((v2,i) => {
						html += '<tr>';
						html += '<td></td>';
						html += '<td colspan="2">'+v2.angka+'</td>';
						html += '<td>'+v2.pertanyaan+'</td>';
						html += '<td>'+((v2.jawaban) ? v2.jawaban : '')+'</td>';
						html += '<td><a href="javascript:void(0)" onclick="add(``,`'+v2.id_chatbot+'`)" class="btn btn-sm btn-success" style="margin-bottom:5px;width:100%;"><i class="fa fa-plus"></i></a>';
						html += '<a href="javascript:void(0)" onclick="edit(`'+v2.id_chatbot+'`,`'+v2.id_chatbot+'`)" class="btn btn-sm btn-warning" style="margin-bottom:5px;width:100%;"><i class="fa fa-edit"></i></a>';
						html += '<a href="javascript:void(0)" onclick="deletes(`'+v2.id_chatbot+'`)" class="btn btn-sm btn-danger" style="margin-bottom:5px;width:100%;"><i class="fa fa-trash"></i></a></td>';
						html += '</tr>';

						v2.lvl3.forEach((v3,i) => {
							html += '<tr>';
							html += '<td></td>';
							html += '<td></td>';
							html += '<td colspan="1">'+v3.angka+'</td>';
							html += '<td>'+v3.pertanyaan+'</td>';
							html += '<td>'+((v3.jawaban) ? v3.jawaban : '')+'</td>';
							html += '<td>';
							html += '<a href="javascript:void(0)" onclick="edit(`'+v3.id_chatbot+'`,`'+v3.id_chatbot+'`)" class="btn btn-sm btn-warning" style="margin-bottom:5px;width:100%;"><i class="fa fa-edit"></i></a>';
							html += '<a href="javascript:void(0)" onclick="deletes(`'+v3.id_chatbot+'`)" class="btn btn-sm btn-danger" style="margin-bottom:5px;width:100%;"><i class="fa fa-trash"></i></a></td>';
							html += '</tr>';
						})
					})
				})

				$('.tempatChatBot').html(html);
			} else {
				$('.tempatChatBot').html('');
			}
		})
	}

	function add(id, parent_id) {
		$.post("{{route('addChatBot')}}", {id:id,parent_id:parent_id}).done((data) => {
			if (data.status == 'success') {
				$('.main-layer').hide();
				$('.other-content').html(data.content);
			} else {
				$('.main-layer').show();
				$('.other-content').html('');
			}
		})
	}

	function edit(id, parent_id) {
		$.post("{{route('addChatBot')}}", {id:id,parent_id:parent_id}).done((data) => {
			if (data.status == 'success') {
				$('.main-layer').hide();
				$('.other-content').html(data.content);
			} else {
				$('.main-layer').show();
				$('.other-content').html('');
			}
		})
	}

	function deletes(id) {
		swal({
			title:"Jika Menghapus Parent Pertanyaan, Data Child Juga Akan Terhapus",
			text:"Apakah anda yakin ?",
			type:"warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Saya yakin!",
			cancelButtonText: "Batal!",
			closeOnConfirm: false
		},
		function(){
			$.post("{{route('deleteChatBot')}}", {id:id}).done(function(data){
				if(data.status == 'success'){
					loadData()
					swal("Success!", data.message, "success");
				}else{
					swal("Whooops!", data.message, "error");
				}
			});
		});
	}
</script>
@endsection
