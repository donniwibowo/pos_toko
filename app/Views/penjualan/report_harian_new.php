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
            
            <div class="col-lg-12">
              
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