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
                  
              <h5 class="card-title fw-semibold mb-4">Tambah Data Produk</h5>
              <div class="card">
                  <form id="form-produk" method="POST" action="<?= $form_action ?>">
                    <div class="card-body">
                      <div class="mb-3">
                          <label for="supplier_id" class="form-label">Supplier</label>
                          <select id="supplier_id" name="supplier_id" class="form-select acive-dropdown">
                            <?php
                              if($supplier_data) {
                                foreach($supplier_data as $supplier) {
                                  $is_selected = '';
                                  if($supplier['supplier_id'] == $data->supplier_id) {
                                    $is_selected = ' selected';
                                  }

                                  echo "<option".$is_selected." value='".$supplier['supplier_id']."'>".strtoupper($supplier['nama_supplier'])."</option>";
                                }
                              }

                            ?>
                          </select>
                          <p class="error-msg"><?= \Config\Services::validation()->getError('supplier_id') ?></p>
                      </div>

                      <div class="mb-3">
                          <label for="kategori_id" class="form-label">Kategori</label>
                          <select id="kategori_id" name="kategori_id" class="form-select acive-dropdown">
                            <?php
                              if($kategori_data) {
                                foreach($kategori_data as $kategori) {
                                  $is_selected = '';
                                  if($kategori['kategori_id'] == $data->kategori_id) {
                                    $is_selected = ' selected';
                                  }

                                  echo "<option".$is_selected." value='".$kategori['kategori_id']."'>".strtoupper($kategori['nama_kategori'])."</option>";
                                }
                              }

                            ?>
                          </select>
                          <p class="error-msg"><?= \Config\Services::validation()->getError('kategori_id') ?></p>
                      </div>

                      <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama Produk*</label>
                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= set_value('nama_produk', $data->nama_produk) ?>" placeholder="Nama Produk" required>
                        <p class="error-msg"><?= \Config\Services::validation()->getError('nama_produk') ?></p>
                      </div>

                      <div class="mb-3">
                          <label for="related_produk" class="form-label">Produk Sebanding</label>
                          <select id="related_produk" name="related_produk_ids[]" class="form-select" multiple="multiple">
                            <?php
                              if($daftar_produk) {
                                foreach($daftar_produk as $p) {
                                  if(isset($related_produk_ids) && in_array($p['produk_id'], $related_produk_ids)) {
                                    echo "<option selected value='".$p['produk_id']."'>".$p['nama_produk']."</option>";
                                  } else {
                                    echo "<option value='".$p['produk_id']."'>".$p['nama_produk']."</option>";
                                  } 
                                  
                                }
                              }

                            ?>
                          </select>
                      </div>

                      <div class="mb-3">
                          <label for="satuan_terkecil" class="form-label">Satuan Terkecil</label>
                          <select id="satuan_terkecil" name="satuan_terkecil" class="form-select" required>
                            <option value='gram'<?= $data->satuan_terkecil == 'gram' ? ' selected' : '' ?>>Gram</option>
                            <option value='pack'<?= $data->satuan_terkecil == 'pack' ? ' selected' : '' ?>>Pack</option>
                            <option value='pcs'<?= $data->satuan_terkecil == 'pcs' ? ' selected' : '' ?>>Pcs</option>
                            <option value='rcg'<?= $data->satuan_terkecil == 'rcg' ? ' selected' : '' ?>>Rcg</option>
                            <option value='sch'<?= $data->satuan_terkecil == 'sch' ? ' selected' : '' ?>>Sch</option>
                          </select>
                          <p class="error-msg"><?= \Config\Services::validation()->getError('satuan_terkecil') ?></p>
                      </div>

                      <div class="mb-3">
                        <label for="netto" class="form-label">Jumlah / Netto per Carton (Dalam Satuan Terkecil)*</label>
                        <input type="text" class="form-control" id="netto" name="netto" value="<?= set_value('netto', $data->netto) ?>" placeholder="Jumlah" required>
                        <p class="error-msg"><?= \Config\Services::validation()->getError('netto') ?></p>
                      </div>

                      <div class="mb-3">
                        <label for="stok_min" class="form-label">Stok Minimal (Dalam Satuan Terkecil)</label>
                        <input type="text" class="form-control" id="stok_min" name="stok_min" value="<?= set_value('stok_min', $data->stok_min) ?>" placeholder="Stok Minimal">
                        <p class="error-msg"><?= \Config\Services::validation()->getError('stok_min') ?></p>
                      </div>

                      <div class="mb-3">
                        <label for="satuan_terbesar" class="form-label">Satuan Terbesar</label>
                        <input type="text" class="form-control" id="satuan_terbesar" name="satuan_terbesar" value="<?= set_value('satuan_terbesar', $data->satuan_terbesar) ?>" placeholder="Satuan Terbesar">
                        <p class="error-msg"><?= \Config\Services::validation()->getError('satuan_terbesar') ?></p>
                      </div>

                      <button type="button" id="btn_save_produk" class="btn btn-primary">Submit</button>
                      <a href="<?= base_url() ?>produk/list" class="btn btn-warning">Close</a>
                    </div>

                

                  </form>

                
              </div> <!-- end of card -->
           <!--  </div>
          </div> -->
        </div>
     

<?php
  echo $this->include('default/footer');
?>