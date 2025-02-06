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
                  
                  <h5 class="card-title fw-semibold mb-4">Pengaturan Stok Produk</h5>
                  <div class="card">
                      <form id="form-stok-produk" method="POST" action="<?= $form_action ?>">
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
                                  <?= $produk_stok_model->convertStok($produk_data->stok_min, $produk_data->netto, $produk_data->satuan_terkecil, $produk_data->satuan_terbesar) ?>
                                </td>
                              </tr>

                              
                            </tbody>
                          </table>

                      <br />

                      <h4 class="btn d-flex btn-light-warning w-100 d-block text-warning font-medium">Stok & Tanggal Kadaluarsa</h4>
                      <table class="table dynamic-table" id="table-produk-stok">
                        <thead>
                          <tr>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Stok</th>
                            <th>Action</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php if(isset($produk_stok) && $produk_stok) { ?>

                            <?php foreach($produk_stok as $p) { ?>

                                <tr>
                                  <td>
                                    <input type="hidden" name="stok_id[]" value="<?= $p->stok_id ?>" />
                                    <input type="text" class="form-control input-date" value="<?= date('d-M-Y', strtotime($p->tgl_kadaluarsa)) ?>" name="tgl_kadaluarsa[]" />
                                  </td>

                                  <td>
                                    <input type="text" class="form-control input-stok" value="<?= $p->stok / $produk_data->netto ?>" name="stok[]" />
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
                                <input type="text" class="form-control input-date" name="tgl_kadaluarsa[]" />
                              </td>

                              <td>
                                <input type="text" class="form-control input-stok" name="stok[]" />
                              </td>

                              <td>
                                <i role="button" class="ti ti-plus btn-add-row"></i>
                                <i role="button" class="ti ti-trash btn-delete-row"></i>
                              </td>
                            </tr>
                          <?php } ?>

                        </tbody>
                      </table>

                      <button type="button" id="btn_stok_produk" class="btn btn-primary">Submit</button>
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