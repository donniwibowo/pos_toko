var produk_data = [];

function initProduKData() {
	var supplier_id = $('#supplier_id').val();
	var ajaxUrl = $('#baseUrl').val() + 'pembelian/getproduk/'+supplier_id;
	if(typeof supplier_id !== "undefined") {
		$.get(ajaxUrl, function( response ) {
			produk_data = response;
			var produk_opsi = '';

			if(typeof response.data !== "undefined") {
				for(var i=0; i<response.data.length; i++) {
					produk_opsi += "<option value='"+ response.data[i]['produk_id'] +"'>"+ response.data[i]['nama_produk'] +"</option>";
				}

			}

			$('#table-pembelian').find('.produk-data:first').html(produk_opsi);
			$('#table-pembelian').find('.produk-data:first').change();

			loadPembelianData();
		});
		
	}
}

function loadPembelianData() {
	var pembelian_id = $('#pembelian_id').val();
	if(pembelian_id > 0) {
		var ajaxUrl = $('#baseUrl').val() + 'pembelian/getdetail/'+pembelian_id;

		$.get(ajaxUrl, function( response ) {
			console.log(response.data);
			for(var i = 0; i < response.data.length; i++) {
				$('#table-pembelian').find('tbody tr:last').find('.produk-data').val(response.data[i].produk_id);
				$('#table-pembelian').find('tbody tr:last').find('.produk-qty').val(response.data[i].qty);
				$('#table-pembelian').find('tbody tr:last').find('.produk-harga-beli').val(response.data[i].harga_beli);

				$('#table-pembelian').find('tbody tr:last').find('.produk-data').change();
				$('#table-pembelian').find('tbody tr:last').find('.btn-add-row').click();
				

			}

			$('#table-pembelian').find('tbody tr:last').find('.btn-delete-row').click();
		});
	}
}

$(document).ready(function() {
	initProduKData();
	
	$('.input-date').datepicker({
		format: 'dd-M-yy',
		autoclose: true,
        todayHighlight: true,
	});

	let table = new DataTable('.active-table');

	
	if($('#multiple_search_table').length > 0) {
		new DataTable('#multiple_search_table', {
		    initComplete: function () {
		        this.api()
		            .columns()
		            .every(function () {
		                let column = this;
		                let title = column.footer().textContent;
		 
		                // Create input element
		                let input = document.createElement('input');
		                input.placeholder = title;
		                column.footer().replaceChildren(input);
		 
		                // Event listener for user input
		                input.addEventListener('keyup', () => {
		                    if (column.search() !== this.value) {
		                        column.search(input.value).draw();
		                    }
		                });
		            });
		    }
		});
		
	}

	$('.acive-dropdown').select2({
		placeholder: 'Silahkan pilih'
	});
	$('#related_produk').select2({
		placeholder: 'Pilih produk sebanding'
	});

	$('#produk_bundling').select2({
		placeholder: 'Pilih produk bundling'
	});

	$('.simplebar-content-wrapper').on('click', '.nav.nav-underline', function() {
		return false;
	});
	

	$('.active-table').on('click', 'tbody .btn-delete-table', function() {
		var id = $(this).data('id');
		var label = $(this).data('label');
		var modul = $(this).data('modul');
		var url = 'delete';

		if(modul == 'produk-diskon') {
			url = $(this).data('url');
		}

		Swal.fire({
		  title: 'Konfirmasi',
		  text: "Apakah anda yakin untuk menghapus data " + label + "?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Ya',
		  cancelButtonText: 'Batal'
		}).then((result) => {
		  if (result.isConfirmed) {
		  	window.location.href = url + "/" + id;
		  }
		});

	});

	$('#table-produk-stok').on('click', 'tbody .btn-add-row', function() {
		var htmlElement = '';
		htmlElement += '<tr>';
        	htmlElement += '<td>'
          		htmlElement += '<input type="text" class="form-control input-date" name="tgl_kadaluarsa[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<input type="text" class="form-control input-stok" name="stok[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<i role="button" class="ti ti-plus btn-add-row"></i>';
          		htmlElement += '<i role="button" class="ti ti-trash btn-delete-row"></i>';
        	htmlElement += '</td>';
     	htmlElement += '</tr>';
     	
     	$(this).parent().parent().parent().append(htmlElement);
     	$(this).parent().parent().parent().find('tr:last-child').find('.input-date').datepicker({format: 'dd-M-yy', autoclose: true, todayHighlight: true});
	});

	$('#table-produk-stok').on('click', 'tbody .btn-delete-row', function() {
     	if($(this).parent().parent().parent().children().length > 1) {
     		$(this).parent().parent().remove();
     	}
	});


	$('#table-produk-penjualan').on('click', 'tbody .btn-add-row', function() {
		var htmlElement = '';
		htmlElement += '<tr>';
        	htmlElement += '<td>'
          		htmlElement += '<input type="text" class="form-control input-satuan" name="satuan_penjualan[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<input type="text" class="form-control input-qty" name="jumlah_penjualan[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<input type="text" class="form-control input-harga-beli" name="harga_beli[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<input type="text" class="form-control input-harga-jual" name="harga_jual[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<i role="button" class="ti ti-plus btn-add-row"></i>';
          		htmlElement += '<i role="button" class="ti ti-trash btn-delete-row"></i>';
        	htmlElement += '</td>';
     	htmlElement += '</tr>';
     	
     	$(this).parent().parent().parent().append(htmlElement);
	});

	$('#table-produk-penjualan').on('click', 'tbody .btn-delete-row', function() {
     	
     	if($(this).parent().parent().parent().children().length > 1) {
     		$(this).parent().parent().remove();
     	}
	});


	$('#table-pembelian').on('click', 'tbody .btn-add-row', function() {
		var produk_opsi = '';

		if(typeof produk_data.data !== "undefined") {
			for(var i=0; i<produk_data.data.length; i++) {
				produk_opsi += "<option value='"+ produk_data.data[i]['produk_id'] +"'>"+ produk_data.data[i]['nama_produk'] +"</option>";
			}

		}


		var htmlElement = '';
		htmlElement += '<tr>';
        	htmlElement += '<td>'
          		htmlElement += '<select class="form-control produk-data" name="produk_id[]">'+ produk_opsi +'</select>';
          		htmlElement += '<label class="label-netto"></label>';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<input type="text" class="form-control produk-qty" name="qty[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<input type="text" class="form-control produk-harga-beli" name="harga_beli[]" />';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<label class="label-ket"></label>';
        	htmlElement += '</td>';

        	htmlElement += '<td>';
          		htmlElement += '<i role="button" class="ti ti-plus btn-add-row"></i>';
          		htmlElement += '<i role="button" class="ti ti-trash btn-delete-row"></i>';
        	htmlElement += '</td>';
     	htmlElement += '</tr>';
     	
     	var jumlahRow = $('#table-pembelian tbody').children().length;

     	if(typeof produk_data.data !== "undefined" && jumlahRow < produk_data.data.length) {
	     	$(this).parent().parent().parent().append(htmlElement);
	     	// $(this).parent().parent().parent().find('tr:last-child').find('.input-date').datepicker({format: 'dd-M-yy', autoclose: true, todayHighlight: true});
	     }
	});

	$('#table-pembelian').on('click', 'tbody .btn-delete-row', function() {
     	if($(this).parent().parent().parent().children().length > 1) {
     		$(this).parent().parent().remove();
     	}
	});

	$('#supplier_id').on('change', function() {
		var supplier_id = $(this).val();
		$("#table-pembelian tbody tr").slice(1).remove();

		initProduKData();
		$.get("getproduk/"+supplier_id, function( response ) {
			produk_data = response;
		});
	});


	$('#table-pembelian').on('change', 'tbody .produk-data', function() {
		var obj = $(this)
		var produk_id = $(this).val();
		// console.log(produk_id);

		$(obj).parent().parent().find('.label-ket').html('');
		$(obj).parent().parent().find('.label-netto').html('');

		var ajaxUrl = $('#baseUrl').val() + 'pembelian/getprodukinfopenjualan/'+produk_id;
		$.get(ajaxUrl, function( response ) {
			var labelKet = "";
			labelKet += "<p>Mulai: "+response.data_penjualan.start_penjualan+"</p>";
			labelKet += "<p>Akhir: "+response.data_penjualan.end_penjualan+"</p>";
			labelKet += "<p>Total Penjualan: "+response.data_penjualan.total_penjualan+"</p>";
			labelKet += "<p>Penjualan / Hari: "+response.data_penjualan.penjualan_per_hari+"</p>";
			labelKet += "<p>Stok: "+response.data_stok+"</p>";
			
			$(obj).parent().parent().find('.label-ket').html(labelKet);
			$(obj).parent().parent().find('.label-netto').html(response.netto_produk);
			
		});
	});

	$('#btn_pembelian_datang').on('click', function() {
		Swal.fire({
		  title: 'Konfirmasi',
		  text: "Apakah anda yakin barang sudah datang dan sesuai?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Ya',
		  cancelButtonText: 'Batal'
		}).then((result) => {
		  if (result.isConfirmed) {
		    $('#form-tgl-datang').submit();
		  }
		});
	});

	$('#btn_update_pembayaran').on('click', function() {
		Swal.fire({
		  title: 'Konfirmasi',
		  text: "Apakah informasi pembayaran sudah sesuai?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Ya',
		  cancelButtonText: 'Batal'
		}).then((result) => {
		  if (result.isConfirmed) {
		    $('#form-update-pembayaran').submit();
		  }
		})
	});
	
	$('#btn_save_produk').on('click', function() {
		var numberRegex = /^\d+$/;
		var readyToSubmit = true;
		$('#nama_produk').parent().find('p.error-msg').html('');
		$('#netto').parent().find('p.error-msg').html('');
		$('#stok_min').parent().find('p.error-msg').html('');

		if($('#nama_produk').val() == '') {
			readyToSubmit = false
			$('#nama_produk').parent().find('p.error-msg').html('Nama produk wajib diisi.');
		}

		if($('#netto').val() == '') {
			readyToSubmit = false
			$('#netto').parent().find('p.error-msg').html('Jumlah / Netto produk wajib diisi.');
		} else {
			if(numberRegex.test($('#netto').val()) == false) {
				$('#netto').parent().find('p.error-msg').html('Jumlah / Netto produk harus angka.');
				readyToSubmit = false;
			}
		}

		if($('#stok_min').val() != '') {
			if(numberRegex.test($('#stok_min').val()) == false) {
				$('#stok_min').parent().find('p.error-msg').html('Stok minimal harus angka.');
				readyToSubmit = false;
			}
		} 


		

		if(readyToSubmit) {
			// alert('Form is ready');
			$('#form-produk').submit();
		}
		
	});

	$('#btn-save-pembelian').on('click', function() {
		var numberRegex = /^\d+$/;
		var readyToSubmit = true;

		$('#table-pembelian tbody tr').each(function(index, tr){
			$(tr).find('.produk-qty').parent().find('p.error-msg').remove();
			$(tr).find('.produk-harga-beli').parent().find('p.error-msg').remove();

			if($(tr).find('.produk-qty').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">QTY produk wajib diisi.</p>';
				$(tr).find('.produk-qty').parent().append(errorMsg);
				readyToSubmit = false;
			} else {
				if(numberRegex.test($(tr).find('.produk-qty').val()) == false) {
					errorMsg = '<p class="error-msg fa-sm">QTY produk harus angka.</p>';
					$(tr).find('.produk-qty').parent().append(errorMsg);
					readyToSubmit = false;
				}
			}

			if($(tr).find('.produk-harga-beli').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">Harga beli produk wajib diisi.</p>';
				$(tr).find('.produk-harga-beli').parent().append(errorMsg);
				readyToSubmit = false;
			} else {
				if(numberRegex.test($(tr).find('.produk-harga-beli').val()) == false) {
					errorMsg = '<p class="error-msg fa-sm">Harga beli harus angka.</p>';
					$(tr).find('.produk-harga-beli').parent().append(errorMsg);
					readyToSubmit = false;
				}
			}
		});

		

		if(readyToSubmit) {
			// alert('Form is ready');
			$('#form-pembelian').submit();
		}
		
	});



	$('#btn_stok_produk').on('click', function() {
		var numberRegex = /^\d+$/;
		var readyToSubmit = true;
		
		$('#table-produk-stok tbody tr').each(function(index, tr){
			$(tr).find('.input-date').parent().find('p.error-msg').remove();
			$(tr).find('.input-stok').parent().find('p.error-msg').remove();

			if($(tr).find('.input-date').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">Tanggal kadaluarsa wajib diisi.</p>';
				$(tr).find('.input-date').parent().append(errorMsg);
				readyToSubmit = false;
			}

			if($(tr).find('.input-stok').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">Stok produk wajib diisi.</p>';
				$(tr).find('.input-stok').parent().append(errorMsg);
				readyToSubmit = false;
			} /*else {
				if(numberRegex.test($(tr).find('.input-stok').val()) == false) {
					errorMsg = '<p class="error-msg fa-sm">Stok produk harus angka.</p>';
					$(tr).find('.input-stok').parent().append(errorMsg);
					readyToSubmit = false;
				}
			}*/
		});

		

		if(readyToSubmit) {
			// alert('Form is ready');
			$('#form-stok-produk').submit();
		}
		
	});

	$('#btn_harga_produk').on('click', function() {
		var numberRegex = /^\d+$/;
		var readyToSubmit = true;
		
		$('#table-produk-penjualan tbody tr').each(function(index, tr){
			$(tr).find('.input-satuan').parent().find('p.error-msg').remove();
			$(tr).find('.input-qty').parent().find('p.error-msg').remove();
			$(tr).find('.input-harga-beli').parent().find('p.error-msg').remove();
			$(tr).find('.input-harga-jual').parent().find('p.error-msg').remove();

			if($(tr).find('.input-satuan').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">Satuan penjualan wajib diisi.</p>';
				$(tr).find('.input-satuan').parent().append(errorMsg);
				readyToSubmit = false;
			}

			if($(tr).find('.input-qty').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">Jumlah / netto penjualan wajib diisi.</p>';
				$(tr).find('.input-qty').parent().append(errorMsg);
				readyToSubmit = false;
			} else {
				if(numberRegex.test($(tr).find('.input-qty').val()) == false) {
					errorMsg = '<p class="error-msg fa-sm">Jumlah / netto penjualan harus angka.</p>';
					$(tr).find('.input-qty').parent().append(errorMsg);
					readyToSubmit = false;
				}
			}

			if($(tr).find('.input-harga-beli').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">Harga beli wajib diisi.</p>';
				$(tr).find('.input-harga-beli').parent().append(errorMsg);
				readyToSubmit = false;
			} else {
				if(numberRegex.test($(tr).find('.input-harga-beli').val()) == false) {
					errorMsg = '<p class="error-msg fa-sm">Harga beli harus angka.</p>';
					$(tr).find('.input-harga-beli').parent().append(errorMsg);
					readyToSubmit = false;
				}
			}

			if($(tr).find('.input-harga-jual').val() == '') {
				errorMsg = '<p class="error-msg fa-sm">Harga jual wajib diisi.</p>';
				$(tr).find('.input-harga-jual').parent().append(errorMsg);
				readyToSubmit = false;
			} else {
				if(numberRegex.test($(tr).find('.input-harga-jual').val()) == false) {
					errorMsg = '<p class="error-msg fa-sm">Harga jual harus angka.</p>';
					$(tr).find('.input-harga-jual').parent().append(errorMsg);
					readyToSubmit = false;
				}
			}
		});

		
		if(readyToSubmit) {
			// alert('Form is ready');
			$('#form-harga-produk').submit();
		}
		
	});
});