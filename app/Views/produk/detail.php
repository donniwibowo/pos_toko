<?php
  echo $this->include('default/header');
?>

      
        <div class="container-fluid">

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
              
          <h5 class="card-title fw-semibold mb-4">Informasi Produk</h5>
          <div class="card">
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
                      <td>Kategori</td>
                      <td>
                        <?= strtoupper(strtolower($produk_data->nama_kategori)) ?>
                      </td>
                    </tr>


                    <tr>
                      <td>Supplier</td>
                      <td>
                        <?= strtoupper(strtolower($produk_data->nama_supplier)) ?>
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
                      <td>Total Stok</td>
                      <td>
                        <?= $produk_model->getStok($produk_data->produk_id) ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Stok Minimal</td>
                      <td>
                        <?= $produk_stok_model->convertStok($produk_data->stok_min, $produk_data->netto, $produk_data->satuan_terkecil, $produk_data->satuan_terbesar) ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Produk Sebanding</td>
                      <td>
                        <?php
                          $tmp = [];
                          foreach ($related_produk as $p) {
                            array_push($tmp, strtoupper(strtolower($p->nama_produk)));
                          }

                          echo implode(', ', $tmp);
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Keterangan</td>
                      <td>
                        <?= isset($produk_data->remarks) ? $produk_data->remarks : "-" ?>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <br />

                <div class="row">
                  <div class="col-md-5">
                    <h4 class="btn d-flex btn-light-warning w-100 d-block text-warning font-medium">
                      Stok & Tanggal Kadaluarsa&nbsp;
                      <a style="color: black;" href="<?= base_url() ?>produk/managestok/<?= pos_encrypt($produk_data->produk_id) ?>"><i role="button" class="ti ti-edit fa-2y"></i></a>
                    </h4>
                    <table class="table dynamic-table" id="table-produk-stok">
                      <thead>
                        <tr>
                          <th>Tanggal Kadaluarsa</th>
                          <th>Stok</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if($produk_stok) { ?>

                          <?php foreach($produk_stok as $d) { ?>

                            <tr>
                              <td class="col-md-5"><?= date('d M Y', strtotime($d->tgl_kadaluarsa)) ?></td>
                              <td><?= $produk_stok_model->convertStok($d->stok, $produk_data->netto, $produk_data->satuan_terkecil, $produk_data->satuan_terbesar) ?></td>
                            </tr>

                          <?php } ?>

                        <?php } else { ?>
                          <tr>
                            <td colspan=2>Tidak ada data</td>
                          </tr>
                        <?php } ?>
                        

                      </tbody>
                    </table>
                    <a href="<?= base_url().'pembelian/create' ?>" type="button" class="btn mb-1 btn-lg px-4 fs-4 font-medium btn-light-primary text-primary">Lakukan Pembelian</a>
                  </div>


                  <div class="col-md-7 table-responsive">
                    <h4 class="btn d-flex btn-light-secondary w-100 d-block text-secondary font-medium">
                      Penjualan&nbsp;
                      <a style="color: black;" href="<?= base_url() ?>produk/manageharga/<?= pos_encrypt($produk_data->produk_id) ?>"><i role="button" class="ti ti-edit fa-2y"></i></a>
                    </h4>
                    <table class="table dynamic-table" id="table-produk-stok">
                      <thead>
                        <tr>
                          <th>Satuan</th>
                          <th>Netto/Jumlah</th>
                          <th>Harga Beli</th>
                          <th>Harga Jual</th>
                          <th>Profit</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if($produk_harga) { ?>

                          <?php foreach($produk_harga as $d) { ?>

                            <tr>
                              <td><?= $d->satuan ?></td>
                              <td><?= number_format($d->netto, 0).' '.$produk_data->satuan_terkecil ?></td>
                              <td><?= number_format($d->harga_beli, 0) ?></td>
                              <td><?= number_format($d->harga_jual, 0) ?></td>
                              <td>
                                <?php
                                  $profit = $d->harga_jual - $d->harga_beli;
                                  $profit_percentage = number_format(($profit / $d->harga_beli * 100), 2);
                                  echo $profit_percentage.'%';
                                ?>
                              </td>
                            </tr>

                          <?php } ?>

                        <?php } else { ?>
                          <tr>
                            <td colspan=4>Tidak ada data</td>
                          </tr>
                        <?php } ?>
                        

                      </tbody>
                    </table>
                  </div>
                </div>



                <br />

                <div class="row">
                  <div class="col-md-12 table-responsive">
                    <h4 class="btn d-flex btn-light-danger w-100 d-block text-danger font-medium">Program Diskon</h4>
                    <table class="table dynamic-table" id="table-produk-diskon">
                      <thead>
                        <tr>
                          <th>Tipe Diskon</th>
                          <th>Diskon</th>
                          <th>Bundling</th>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Berakhir</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if($produk_diskon) { ?>

                          <?php foreach($produk_diskon as $d) { ?>
                             <?php
                              $status_diskon = 'aktif';
                              $tgl_skrg = date('Y-m-d H:i:s');
                              $start_diskon = date('Y-m-d H:i:s', strtotime($d->start_diskon));
                              $end_diskon = date('Y-m-d H:i:s', strtotime($d->end_diskon));

                              if($tgl_skrg > $end_diskon) {
                                $status_diskon = 'tidak aktif';
                              }

                              if($tgl_skrg < $start_diskon) {
                                $status_diskon = 'tidak aktif';
                              }
                            ?>
                            <tr>
                              <td><?= strtoupper($d->tipe_diskon) ?></td>
                              <td>
                                <?php
                                  if(strtolower($d->tipe_nominal) == 'nominal') {
                                    echo number_format($d->nominal, 2);
                                  } else {
                                    echo $d->nominal.' %';
                                  }
                                ?>
                              </td>
                              <td>
                                <?php
                                  if($d->tipe_diskon == 'bundling' || $d->tipe_diskon == 'tebus murah') {
                                      echo $produk_diskon_model->getBundlingProduk($d->produk_diskon_id);
                                  } else {
                                      echo '-';
                                  }
                                ?>

                              </td>
                              <td><?= date('d M Y', strtotime($d->start_diskon)) ?></td>
                              <td><?= date('d M Y', strtotime($d->end_diskon)) ?></td>
                              <td><?= strtoupper($status_diskon) ?></td>
                              <td>
                                <a href="<?= base_url().'produk/updatediskon/'.pos_encrypt($d->produk_diskon_id) ?>"><i role="button" class="ti ti-edit btn-edit-table fa-2y"></i></a>
                                <a href="<?= base_url().'produk/deletediskon/'.pos_encrypt($d->produk_diskon_id) ?>" onclick="return confirm('Apakah anda yakin untuk menghapus program diskon ini?')"><i role="button" class="ti ti-trash fa-2y"></i></a>
                              </td>
                            </tr>

                          <?php } ?>

                        <?php } else { ?>
                          <tr>
                            <td colspan=2>Tidak ada data</td>
                          </tr>
                        <?php } ?>
                        

                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
          </div> <!-- end of card -->
        </div> <!-- end of container -->
    

<?php
  echo $this->include('default/footer');
?>