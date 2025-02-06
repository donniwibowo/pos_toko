<?php
  echo $this->include('default/header');
  $encrypter = \Config\Services::encrypter();
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
                    <h5 class="card-title fw-semibold">Daftar User</h5>
                  </div>
                  <div>
                    <a href="<?= base_url('user/create') ?>" class="btn btn-danger"><i class="ti ti-plus"></i></a>
                  </div>
                </div>

              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                  	<table class="table table-striped active-table">
                  		<thead>
                  			<tr>
                  				<td>No</td>
                  				<td>Nama</td>
                  				<td>No Telp</td>
                  				<td>Jabatan</td>
                  				<td>Tanggal Dibuat</td>
                  				<td>Tanggal Diubah</td>
                          <td>Action</td>
                  			</tr>
                  		</thead>
                  		<tbody>
                  			<?php 
                          $ctr = 0;
                          $tampilkan_data = false; 
                        ?>
                  			<?php foreach($data as $d) { ?>
                          <?php
                            if(session()->is_superadmin) {
                              $tampilkan_data = true;
                            } else {
                              if($d['jabatan'] == 'kasir') {
                                $tampilkan_data = true;
                              } else {
                                $tampilkan_data = false;
                              }
                            }
                          ?>
                          <?php if($tampilkan_data) { ?>
                      			<?php $ctr++; ?>
                      			<tr>
                      				<td><?php echo $ctr; ?></td>
                      				<td><?php echo ucwords(strtolower($d['nama'])); ?></td>
                      				<td><?php echo $d['no_telp']; ?></td>
                      				<td><?php echo ucwords(strtolower($d['jabatan'])); ?></td>
                      				<td><?php echo date('d M Y H:i:s', strtotime($d['tgl_dibuat'])); ?></td>
                      				<td>
                      					<?php 
                      						if($d['tgl_diupdate']) {
                      							echo date('d M Y H:i:s', strtotime($d['tgl_diupdate'])); 
                      						} else {
                      							echo '';
                      						}
                      					?>
                      				</td>
                              <td>
                                <a href="update/<?= pos_encrypt($d['user_id']) ?>"><i role="button" class="ti ti-edit btn-edit-table fa-2y"></i></a>
                                <i role="button" class="ti ti-trash btn-delete-table fa-2y" data-modul="user" data-id="<?= pos_encrypt($d['user_id']) ?>" data-label="<?= $d['nama'] ?>"></i>
                              </td>
                      			</tr>
                          <?php } ?>
                  			<?php } ?>
                  		</tbody>
                  	</table>
                  </div>

                </div>
              </div>
           <!--  </div>
          </div> -->
        </div>
   
<?php
  echo $this->include('default/footer');
?>