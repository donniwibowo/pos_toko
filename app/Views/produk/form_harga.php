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
                  
                  <h5 class="card-title fw-semibold mb-4">Pengaturan Harga Produk</h5>
                  <div class="card">
                      <form id="form-harga-produk" method="POST" action="<?= $form_action ?>">
                        <div class="card-body">
                          <table class="table" id="table-informasi-produk">
                            <tbody>
                              <tr>
                                <td class="col-md-2">Nama Produk</td>
                                <td>
                                  <?= strtoupper(strtolower($produk_data->nama_produk)) ?>
                                </td>
                              </tr>

                              

                              <tr>
                                <td>Satuan Terkecil</td>
                                <td>
                                  <?= $produk_data->satuan_terkecil ?>
                                </td>
                              </tr>

                              <tr>
                                <td>Jumlah/Netto per Carton</td>
                                <td>
                                  <?= number_format($produk_data->netto, 0).' '.$produk_data->satuan_terkecil ?>
                                </td>
                              </tr>

                              
                              <tr>
                                <td>Stok Minimal</td>
                                <td>
                                  <?= $produk_stok_model->convertStok($produk_data->stok_min, $produk_data->netto, $produk_data->satuan_terkecil) ?>
                                </td>
                              </tr>

                              
                            </tbody>
                          </table>

                      <br />

                      <h4 class="btn d-flex btn-light-secondary w-100 d-block text-secondary font-medium">Penjualan</h4>
                      <div class="table table-responsive">
                        <table class="table dynamic-table" id="table-produk-penjualan">
                          <thead>
                            <tr>
                              <th>Satuan Penjualan</th>
                              <th>Jumlah / Netto (Dalam Satuan Terkecil)</th>
                              <th>Harga Beli</th>
                              <th>Harga Jual</th>
                              <th>Action</th>
                            </tr>
                          </thead>

                          <tbody>

                             <?php if(isset($produk_harga) && $produk_harga) { ?>

                              <?php foreach($produk_harga as $p) { ?>
                                  
                                  <tr>
                                    <td>
                                      <input type="hidden" name="produk_harga_id[]" value="<?= $p->produk_harga_id ?>" />
                                      <input type="text" class="form-control input-satuan" value="<?= $p->satuan ?>" name="satuan_penjualan[]" />
                                    </td>

                                    <td>
                                      <input type="text" class="form-control input-qty" value="<?= $p->netto ?>" name="jumlah_penjualan[]" />
                                    </td>

                                    <td>
                                      <input type="text" class="form-control input-harga-beli" value="<?= (int)$p->harga_beli ?>" name="harga_beli[]" />
                                    </td>

                                    <td>
                                      <input type="text" class="form-control input-harga-jual" value="<?= (int)$p->harga_jual ?>" name="harga_jual[]" />
                                    </td>

                                    <td>
                                      <i role="button" class="ti ti-plus btn-add-row"></i>
                                      <i role="button" class="ti ti-trash btn-delete-row"></i>
                                    </td>
                                  </tr>
                              <?php } ?>

                            <?php } else { ?>
                              <tr>
                                <td>
                                  <input type="text" class="form-control input-satuan" name="satuan_penjualan[]" />
                                </td>

                                <td>
                                  <input type="text" class="form-control input-qty" name="jumlah_penjualan[]" />
                                </td>

                                <td>
                                  <input type="text" class="form-control input-harga-beli" name="harga_beli[]" />
                                </td>

                                <td>
                                  <input type="text" class="form-control input-harga-jual" name="harga_jual[]" />
                                </td>

                                <td>
                                  <i role="button" class="ti ti-plus btn-add-row"></i>
                                  <i role="button" class="ti ti-trash btn-delete-row"></i>
                                </td>
                              </tr>
                            <?php } ?>
                            

                          </tbody>
                        </table>
                      </div>

                      <button type="button" id="btn_harga_produk" class="btn btn-primary">Submit</button>
                      <a href="<?= base_url() ?>produk/detail/<?= pos_encrypt($produk_data->produk_id) ?>" class="btn btn-warning">Close</a>


                    </div>


                  </form>

                
              </div> <!-- end of card -->
           <!--  </div>
          </div> -->
        </div>
     

<?php
  echo $this->include('default/footer');
?>