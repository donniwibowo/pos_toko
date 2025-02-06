<?php
  echo $this->include('default/header');
?>

      
        <div class="container-fluid">
         <!--  <div class="card">
            <div class="card-body"> -->

              <?php if(session()->getFlashData('danger')){ ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashData('danger') ?>
                </div>
              <?php } ?>

              <?php if(session()->getFlashData('success')){ ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashData('success') ?>
                </div>
              <?php } ?>
                  
              <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                  <div class="mb-3 mb-sm-0">
                    <h5 class="card-title fw-semibold">Daftar Produk</h5>
                  </div>
                  <div>
                    <a href="<?= base_url('produk/create') ?>" class="btn btn-danger"><i class="ti ti-plus"></i></a>
                  </div>
                </div>

              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                  	<table class="table table-striped active-table">
                  		<thead>
                  			<tr>
                          <td>No</td>
                          <td>Nama Produk</td>
                  				<td>Supplier</td>
                          <td>Stok Minimal</td>
                  				<td>Stok</td>
                          <td>Harga Beli</td>
                          <td>Harga Jual</td>
                          <td>Profit</td>
                          <td>Tanggal Dibuat</td>
                  				<td>Tanggal Diubah</td>
                          <td>Action</td>
                  			</tr>
                  		</thead>
                  		<tbody>
                  			<?php $ctr = 0; ?>
                  			<?php foreach($produk_data as $produk) { ?>
                  			<?php $ctr++; ?>
                  			<tr>
                  				<td><?php echo $ctr; ?></td>
                          <td><?php echo strtoupper(strtolower($produk->nama_produk)); ?></td>
                  				<td><?php echo strtoupper(strtolower($produk->nama_supplier)); ?></td>
                          <td><?php echo $produk_stok_model->convertStok($produk->stok_min, $produk->netto, $produk->satuan_terkecil, $produk->satuan_terbesar); ?></td>
                          <td>
                            <?php echo $produk_model->getStok($produk->produk_id); ?>
                              
                          </td>
                          <td>
                            <?= $produk_harga_model->getPrice($produk->produk_id, 'beli') ?>
                          </td>
                          <td>
                            <?= $produk_harga_model->getPrice($produk->produk_id, 'jual') ?>
                          </td>
                          <td>
                            <?= $produk_harga_model->getPrice($produk->produk_id, 'profit') ?>
                          </td>
                          <td><?php echo date('d M Y H:i:s', strtotime($produk->tgl_dibuat)); ?></td>
                  				<td>
                  					<?php 
                  						if($produk->tgl_diupdate) {
                  							echo date('d M Y H:i:s', strtotime($produk->tgl_diupdate)); 
                  						} else {
                  							echo '';
                  						}
                  					?>
                  				</td>
                          <td>
                            <a href="detail/<?= pos_encrypt($produk->produk_id) ?>"><i role="button" class="ti ti-info-circle fa-2y"></i></a>

                            <a href="managestok/<?= pos_encrypt($produk->produk_id) ?>"><i role="button" class="ti ti-database fa-2y"></i></a>
                            <a href="manageharga/<?= pos_encrypt($produk->produk_id) ?>"><i role="button" class="ti ti-report-money fa-2y"></i></a>

                            <a href="update/<?= pos_encrypt($produk->produk_id) ?>"><i role="button" class="ti ti-edit btn-edit-table fa-2y"></i></a>
                            <a href="diskon/<?= pos_encrypt($produk->produk_id) ?>"><i role="button" class="ti ti-discount-2 fa-2y"></i></a> 
                            <i role="button" class="ti ti-trash btn-delete-table fa-2y" data-modul="produk" data-id="<?= pos_encrypt($produk->produk_id) ?>" data-label="<?= $produk->nama_produk ?>"></i>
                          </td>
                  			</tr>
                  			<?php } ?>
                  		</tbody>
                  	</table>
                  </div>

                </div>
              </div>
          <!--   </div>
          </div> -->
        </div>
     
   
<?php
  echo $this->include('default/footer');
?>