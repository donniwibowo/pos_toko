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
                    <h5 class="card-title fw-semibold">Daftar Produk Diskon</h5>
                  </div>
                 
                </div>

              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped active-table">
                      <thead>
                        <tr>
                          <th>Produk</th>
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
                              <td><?= strtoupper($d->nama_produk) ?></td>
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
                                <i role="button" class="ti ti-trash btn-delete-table fa-2y" data-modul="produk-diskon" data-id="<?= pos_encrypt($d->produk_diskon_id) ?>" data-label="program diskon pada produk <?= $d->nama_produk ?>" data-url="deletediskon"></i>
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
           <!--  </div>
          </div> -->
        </div>
      
   
<?php
  echo $this->include('default/footer');
?>