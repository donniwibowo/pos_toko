<?php
  echo $this->include('default/header');
?>

      
        <div class="container-fluid">

          <?php if(session()->getFlashData('danger')){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashData('danger') ?>
            </div>
          <?php } ?>
              
          <h5 class="card-title fw-semibold mb-4">Informasi Penjualan</h5>
          <div class="card">
              <div class="card-body">
                
                <table class="table" id="table-informasi-produk">
                  <tbody>
                    <tr>
                      <td>Order ID</td>
                      <td>
                        <?= $penjualan_data[0]->penjualan_id ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="col-md-2">Total Belanja</td>
                      <td>
                        <?= number_format($penjualan_data[0]->total_bayar, 0) ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Metode Pembayaran</td>
                      <td>
                        <?= strtoupper(strtolower($penjualan_data[0]->metode_pembayaran)) ?>
                      </td>
                    </tr>


                    <tr>
                      <td>Status Pembayaran</td>
                      <td>
                        <?= strtoupper(strtolower($penjualan_data[0]->status_pembayaran)) ?>
                      </td>
                    </tr>

                    
                    <tr>
                      <td>Tanggal Transaksi</td>
                      <td>
                        <?= date('d M Y H:i:s', strtotime($penjualan_data[0]->tgl_dibuat)); ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Kasir</td>
                      <td>
                        <?= strtoupper(strtolower($penjualan_data[0]->nama)) ?>
                      </td>
                    </tr>

                    
                  </tbody>
                </table>

                <br />

                <div class="row">
                  <div class="col-md-12">
                    <h4 class="btn d-flex btn-light-warning w-100 d-block text-warning font-medium">Informasi Item Penjualan</h4>
                    <table class="table dynamic-table" id="table-produk-stok">
                      <thead>
                        <tr>
                          <th>Nama Produk</th>
                          <th>Satuan Penjualan</th>
                          <th>Jumlah / Satuan Penjualan</th>
                          <th>QTY</th>
                          <th>Harga Jual</th>
                          <th>Diskon</th>
                          <th>Subtotal</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if($penjualan_detail) { ?>

                          <?php foreach($penjualan_detail as $d) { ?>

                            <tr>
                              <td><?= strtoupper(strtolower($d->nama_produk)) ?></td>
                              <td><?= $d->satuan ?></td>
                              <td><?= number_format($d->netto, 0).' '.$d->satuan_terkecil ?></td>
                              <td><?= $d->qty ?></td>
                              <td><?= number_format($d->harga_jual, 0) ?></td>
                              <td>
                                <?php
                                  if($d->diskon != '' || $d->diskon > 0) {
                                    if($d->tipe_diskon == 'persen') {
                                      echo $d->diskon.'%';
                                    } else {
                                      echo number_format($d->diskon, 0);
                                    }
                                  }
                                ?>

                              </td>
                              <td>
                                <?php
                                  $subtotal = $d->qty * $d->harga_jual;
                                  if($d->diskon != '' || $d->diskon > 0) {
                                    if($d->tipe_diskon == 'persen') {
                                      $jumlahDiskon = $subtotal * $d->diskon / 100;
                                      $subtotal -= $jumlahDiskon;

                                    } else {
                                      $subtotal -= $d->diskon;
                                    }
                                  }
                                ?>
                                <?= number_format($subtotal , 0) ?></td>
                            </tr>

                          <?php } ?>

                        <?php } else { ?>
                          <tr>
                            <td colspan=6>Tidak ada data</td>
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