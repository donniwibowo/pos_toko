<?php 
  echo $this->include('default/header');
?>
     
        <div class="container-fluid">
          <!-- <div class="card">
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
                    <h5 class="card-title fw-semibold">Daftar Pembelian</h5>
                  </div>
                  
                  <div>
                    <a href="<?= base_url('pembelian/create') ?>" class="btn btn-danger"><i class="ti ti-plus"></i></a>
                  </div>
                </div>

              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                  	<table class="table table-striped active-table">
                  		<thead>
                  			<tr>
                          <td>No</td>
                  				<td>Supplier</td>
                          <td>Total Pembelian</td>
                          <td>Status</td>
                          <td>Tanggal JT</td>
                          <td>Status Pembayaran</td>
                          <td>Tanggal Datang</td>
                  				<td>Tanggal Transaksi</td>
                          <td>Admin</td>
                          <td>Action</td>
                  			</tr>
                  		</thead>
                  		<tbody>
                  			<?php $ctr = 0; ?>
                  			<?php foreach($data as $d) { ?>
                  			<?php $ctr++; ?>
                  			<tr>
                  				<td><?php echo $ctr; ?></td>
                          <td><?php echo strtoupper($d->nama_supplier); ?></td>
                  				<td style="text-align: right;"><?php echo number_format($d->total_invoice, 0); ?></td>
                          <td>
                            <?php
                              if($d->status == 0) {
                                echo "Menunggu kedatangan";
                              } else {
                                echo "Selesai";
                              }
                            ?>
                          </td>
                          <td>
                            <?php
                              if($d->tgl_jatuh_tempo == '0000-00-00') {
                                echo "-";
                              } else {
                                echo date('d M Y', strtotime($d->tgl_jatuh_tempo)); 
                                
                              }
                              
                            ?>    
                          </td>
                          <td>
                            <?php echo $d->status_pembayaran == 0 ? 'Outstanding' : 'Lunas'; ?>
                              
                          </td>
                          <td>
                            <?php
                              if($d->tgl_datang == '0000-00-00') {
                                echo "-";
                              } else {
                                echo date('d M Y', strtotime($d->tgl_datang)); 
                                
                              }
                              
                            ?>
                              
                          </td>
                          <td><?php echo date('d M Y H:i:s', strtotime($d->tgl_dibuat)); ?></td>
                          <td><?php echo ucwords(strtolower($d->nama)) ?></td>
                  				<td>
                           <a href="detail/<?= pos_encrypt($d->pembelian_id) ?>"><i role="button" class="ti ti-info-circle fa-2y"></i></a>

                           <?php if($d->status == 0 && $d->status_pembayaran == 0) : ?>
                            <a href="update/<?= pos_encrypt($d->pembelian_id) ?>"><i role="button" class="ti ti-edit btn-edit-table fa-2y"></i></a>
                            <i role="button" class="ti ti-trash btn-delete-table fa-2y" data-modul="pembelian" data-id="<?= pos_encrypt($d->pembelian_id) ?>" data-label="pembelian dari <?= $d->nama_supplier ?>"></i>
                           <?php endif; ?>
                          </td>
                  			</tr>
                  			<?php } ?>
                  		</tbody>
                  	</table>
                  </div>

                </div>
              </div>
            <!-- </div>
          </div> -->
        </div>

    
<?php
  echo $this->include('default/footer');
?>