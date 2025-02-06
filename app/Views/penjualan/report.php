<?php
  echo $this->include('default/header');
?>
    
  <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8 d-flex align-items-strech">
          <div class="card w-100">
            <div class="card-body">
              <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                <div class="mb-3 mb-sm-0">
                  <h5 class="card-title fw-semibold">Laporan Penjualan</h5>
                  <p><i>Skala 1,000</i></p>
                </div>
                <div>

                  <!-- <input type="text" class="form-control" id="periode_laporan" placeholder="Tahun"> -->

                  <!-- <select class="form-select" id="periode_laporan">
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                  </select> -->

                  <select class="form-select" id="periode_laporan">
                    <?php for($i = $setting_thn_min; $i <= $setting_thn_max; $i++) { ?>
                      <option value="<?= $i ?>"><?= $i ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div id="chart"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="row">
            <div class="col-lg-12">
              <!-- Monthly Earnings -->
              <div class="card">
                <div class="card-body">
                  <div class="row alig n-items-start">
                    <div class="col-8">
                      <h5 class="card-title mb-9 fw-semibold"> Profit Bulanan </h5>
                      <h4 class="fw-semibold mb-3" id="jumlah_profit_bulan_ini"></h4>
                      <div class="d-flex align-items-center pb-1">
                        <span
                          class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                          <i id="profit_turun" class="ti ti-arrow-down-right text-danger" style="display: none;"></i>
                          <i id="profit_naik" class="ti ti-arrow-up-right text-danger" style="display: none;"></i>
                        </span>
                        <p class="text-dark me-1 fs-3 mb-0" id="persentase_bulanan">+0%</p>
                        <p class="fs-3 mb-0">bulan lalu</p>
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
                <div id="earning"></div>
              </div>
            </div>





            <div class="col-lg-12">
              <!-- Yearly Breakup -->
              <div class="card overflow-hidden">
                <div class="card-body p-4">
                  <h5 class="card-title mb-9 fw-semibold">Informasi Data</h5>
                  <div class="row align-items-center">
                    <div class="col-8">

                      <div class="d-flex align-items-center mb-3">
                        
                        <p class="text-dark me-1 fs-3 mb-0">Produk</p>
                        <p class="fs-3 mb-0"><?= $produk_count ?></p>
                      </div>

                      <div class="d-flex align-items-center mb-3">
                        
                        <p class="text-dark me-1 fs-3 mb-0">Supplier</p>
                        <p class="fs-3 mb-0"><?= $supplier_count ?></p>
                      </div>
                      

                      <div class="d-flex align-items-center mb-3">
                        
                        <p class="text-dark me-1 fs-3 mb-0">Admin</p>
                        <p class="fs-3 mb-0"><?= $admin_count ?></p>
                      </div>

                      <div class="d-flex align-items-center mb-3">
                        
                        <p class="text-dark me-1 fs-3 mb-0">Kasir</p>
                        <p class="fs-3 mb-0"><?= $kasir_count ?></p>
                      </div>


                      
                    </div>
                    <div class="col-4">
                      <div class="d-flex justify-content-center">
                        <div id="breakup"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    
  </div> <!-- end of container -->
  

<?php
  echo $this->include('default/footer');
?>