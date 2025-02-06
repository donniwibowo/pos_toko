<?php 
  echo $this->include('default/header');
?>
     
    <div class="container-fluid">
      

      <?php if(session()->getFlashData('danger')){ ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashData('danger') ?>
        </div>
      <?php } ?>
      
      <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
          <div class="mb-3 mb-sm-0">
            <h5 class="card-title fw-semibold">Dashboard</h5>
          </div>    
      </div>

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <form method="POST" action="<?= $form_action ?>">
                <div class="mb-3">
                    <label for="start_diskon" class="form-label">Pilih Tanggal</label>
                    <div class="input-group">
                      <input type="text" class="form-control input-date" id="report_date" name="report_date" value="<?= $tgl_dipilih ?>">
                      <span class="input-group-text">
                        <i class="ti ti-calendar fs-5"></i>
                      </span>
                      
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
              <hr />
            </div>
          </div>


          <div class="row">
            <div class="col-lg-4">
              
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-8">
                        <h5 class="card-title mb-9 fw-semibold">Total Penjualan</h5>
                        <h4 class="fw-semibold mb-3"><?= $omset ?></h4>
                        <div class="d-flex align-items-center pb-1">
                          <p class="text-dark me-1 fs-3 mb-0" id="persentase_bulanan"><?= $jumlah_transaksi ?></p>
                          <p class="fs-3 mb-0">transaksi</p>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="d-flex justify-content-end">
                          <div
                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                            Rp
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>

            </div>

            <div class="col-lg-4">
              
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-8">
                        <h5 class="card-title mb-9 fw-semibold">Profit</h5>
                        <h4 class="fw-semibold mb-3"><?= $profit ?></h4>
                        <div class="d-flex align-items-center pb-1">
                          <p class="text-dark me-1 fs-3 mb-0" id="persentase_bulanan"><?= $persentase_profit ?>%</p>
                          
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="d-flex justify-content-end">
                          <div
                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                            Rp
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>

            </div>


            <div class="col-lg-4">
              
                <div class="card">
                  <div class="card-body">
                    <div class="row alig n-items-start">
                      <div class="col-12">
                        <h5 class="card-title mb-9 fw-semibold">Produk Paling Dicari</h5>
                          <div class="row">
                            <div class="col-6">
                               <?php
                                  $index = 0; 
                                  foreach($terlaris as $t) {
                                      if($index < 3) {
                                          echo "<p class='text-dark me-1 fs-3 mb-0'>".ucwords(strtolower($t['nama_produk'])).' ('.$t['jumlah'].')'."</p>";
                                          $index++;
                                      }
                                  }

                               ?>
                          
                            </div>

                            <div class="col-6">
                                <?php
                                  $index = 0; 
                                  foreach($terlaris as $t) {
                                      if($index > 2 && $index < 6) {
                                          echo "<p class='text-dark me-1 fs-3 mb-0'>".ucwords(strtolower($t['nama_produk'])).' ('.$t['jumlah'].')'."</p>";
                                      }

                                      $index++;
                                  }

                               ?>
                            </div>
                          </div>
                          
                        
    
                      </div>
                    </div>
                  </div>
                  
                </div>

            </div>


          </div> <!-- end of row -->
        </div>
      </div>

    </div>

    
<?php
  echo $this->include('default/footer');
?>