<?php
  echo $this->include('default/header');
?>

        <div class="modal fade" id="modal-tgl-datang" tabindex="-1" aria-labelledby="modalTglDatang">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                  <h4 class="modal-title" id="modalTglDatang">
                    Input Tanggal Kedatangan
                  </h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <form method="POST" action="<?= base_url() . 'pembelian/updatetgldatang'?>" id="form-tgl-datang">
                    <div class="mb-3">
                      <label for="tgl-datang" class="control-label" >Tanggal Datang:</label>
                      <input type="hidden" value="<?= $pembelian_header[0]->pembelian_id ?>" name="pembelian_id" />
                      <input type="text" class="form-control input-date" id="tgl-datang" name="tgl_datang" />
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">
                    Tutup
                  </button>
                  <button type="button" class="btn btn-success" id="btn_pembelian_datang">
                    Simpan
                  </button>
                </div>
              </div>
            </div>
        </div>
        <!-- /.modal -->

        <div class="modal fade" id="modal-pembayaran" tabindex="-1" aria-labelledby="modalPembayaran">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                  <h4 class="modal-title" id="modalPembayaran">
                    Update Pembayaran
                  </h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                  <form method="POST" action="<?= base_url() . 'pembelian/updatepembayaran'?>" id="form-update-pembayaran" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="tgl-datang" class="control-label" >Metode Pembayaran:</label>
                      <input type="hidden" value="<?= $pembelian_header[0]->pembelian_id ?>" name="pembelian_id" />

                      <select class="form-control" name="metode_pembayaran">
                        <option value="transfer">Transfer</option>
                        <option value="cash">Cash</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="tgl-pembayaran" class="control-label" >Tanggal Pembayaran:</label>
                      <input type="text" class="form-control input-date" id="tgl-pembayaran" name="tgl_pembayaran" />
                    </div>

                    <div class="mb-3">
                      <label for="bukti-pembayaran" class="control-label">Bukti Pembayaran:</label>
                      <input class="form-control" type="file" id="formFile" name="bukti_pembayaran" />
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-light-danger text-danger font-medium" data-bs-dismiss="modal">
                    Tutup
                  </button>
                  <button type="button" class="btn btn-success" id="btn_update_pembayaran">
                    Simpan
                  </button>
                </div>
              </div>
            </div>
        </div>
        <!-- /.modal -->

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
              
          <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold">Informasi Pembelian</h5>
            </div>
            
            <div>
              
              
              <?php if($pembelian_header[0]->status_pembayaran == 0 && $pembelian_header[0]->status == 0) { ?>
                <a href="<?= base_url().'pembelian/update/'.pos_encrypt($pembelian_header[0]->pembelian_id) ?>" type="button" class="btn mb-1 btn-lg px-4 fs-4 font-medium btn-light-warning text-primary">
                  <i class="ti ti-edit"></i> Edit
                </a>
              <?php } ?>

              <?php if($pembelian_header[0]->tgl_datang == '0000-00-00') { ?>
                <button type="button" class="btn mb-1 btn-lg px-4 fs-4 font-medium btn-light-primary text-primary" data-bs-toggle="modal" data-bs-target="#modal-tgl-datang" data-bs-whatever="@mdo">
                  <i class="ti ti-calendar"></i> Datang
                </button>
              <?php } ?>
              
              <?php if($pembelian_header[0]->status_pembayaran == 0) { ?>
                <button type="button" class="btn mb-1 btn-lg px-4 fs-4 font-medium btn-light-danger text-primary" data-bs-toggle="modal" data-bs-target="#modal-pembayaran" data-bs-whatever="@mdo">
                  <i class="ti ti-cash"></i> Pembayaran
                </button>
              <?php } ?>

              <a href="<?= base_url() ?>pembelian/list" type="button" class="btn mb-1 btn-lg px-4 fs-4 font-medium btn-light-warning text-primary">
                  x
              </a>
            </div>
          </div>


          <div class="card">
              <div class="card-body">
                
                <table class="table" id="table-informasi-produk">
                  <tbody>
                    <tr>
                      <td>Supplier</td>
                      <td>
                        <?= strtoupper(strtolower($pembelian_header[0]->nama_supplier)) ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="col-md-2">Total Pembelian</td>
                      <td>
                        <?= number_format($pembelian_header[0]->total_invoice, 0) ?>
                      </td>
                    </tr>

                    <tr>
                      <td class="col-md-2">Status</td>
                      <td>
                        <?php
                            if($pembelian_header[0]->status == 0) {
                              echo "Menunggu kedatangan";
                            } else {
                              echo "Selesai";
                            }
                          ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Tanggal Jatuh Tempo</td>
                      <td>
                        <?php
                          if($pembelian_header[0]->tgl_jatuh_tempo == '0000-00-00') {
                            echo "-";
                          } else {
                            echo date('d M Y', strtotime($pembelian_header[0]->tgl_jatuh_tempo)); 
                            
                          }
                          
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Status Pembayaran</td>
                      <td>
                        <?php echo $pembelian_header[0]->status_pembayaran == 0 ? 'Outstanding' : 'Lunas'; ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Metode Pembayaran</td>
                      <td>
                        <?php echo $pembelian_header[0]->metode_pembayaran == '' ? '-' : strtoupper($pembelian_header[0]->metode_pembayaran); ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Tanggal Pembayaran</td>
                      <td>
                        <?php
                          if($pembelian_header[0]->tgl_bayar == '0000-00-00 00:00:00') {
                            echo "-";
                          } else {
                            echo date('d M Y', strtotime($pembelian_header[0]->tgl_bayar)); 
                            
                          }
                          
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Tanggal Datang</td>
                      <td>
                        <?php
                          if($pembelian_header[0]->tgl_datang == '0000-00-00') {
                            echo "-";
                          } else {
                            echo date('d M Y', strtotime($pembelian_header[0]->tgl_datang)); 
                            
                          }
                          
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Tanggal Transaksi</td>
                      <td>
                        <?= date('d M Y H:i:s', strtotime($pembelian_header[0]->tgl_dibuat)); ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Admin</td>
                      <td>
                        <?= strtoupper(strtolower($pembelian_header[0]->nama)) ?>
                      </td>
                    </tr>

                    <tr>
                      <td>Bukti Pembayaran</td>
                      <td>
                        <?php if($pembelian_header[0]->metode_pembayaran == 'transfer' && $pembelian_header[0]->bukti_pembayaran != '') : ?>
                          <img style="width: 100px;" src="<?= base_url().'uploads/'.$pembelian_header[0]->bukti_pembayaran ?>" data-bs-toggle="modal" data-bs-target="#al-success-alert" />


                          <div class="modal fade" id="al-success-alert" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" >
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content modal-filled bg-light-success text-success">
                                <div class="modal-body p-4">
                                  <div class="text-center text-success">
                                    <i class="ti ti-circle-check fs-7"></i>
                                    <h4 class="mt-2">Bukti Pembayaran</h4>
                                    <p class="mt-3 text-success-50">
                                       <img style="width: 100%;" src="<?= base_url().'uploads/'.$pembelian_header[0]->bukti_pembayaran ?>" />
                                    </p>
                                    <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">
                                      Tutup
                                    </button>
                                  </div>
                                </div>
                              </div>
                              <!-- /.modal-content -->
                            </div>
                        </div>

                        <?php else : ?>
                          <?php if($pembelian_header[0]->status_pembayaran == 1 && $pembelian_header[0]->metode_pembayaran == 'cash') : ?>
                              <?= 'Cash' ?>
                           <?php else : ?>
                              <?= '-' ?>
                          <?php endif; ?>
                          
                        <?php endif; ?>
                        
                      </td>
                    </tr>

                    
                  </tbody>
                </table>

                <br />

                <div class="row">
                  <div class="col-md-12">
                    <h4 class="btn d-flex btn-light-warning w-100 d-block text-warning font-medium">Informasi Item Pembelian</h4>
                    <table class="table dynamic-table" id="table-produk-stok">
                      <thead>
                        <tr>
                          <th>Produk</th>
                          <th>Netto</th>
                          <th style="text-align: right;">QTY</th>
                          <th style="text-align: right;">Harga Beli</th>
                          <th style="text-align: right;">Subtotal</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if($pembelian_detail) { ?>

                          <?php foreach($pembelian_detail as $d) { ?>

                            <tr>
                              <td><?= strtoupper(strtolower($d->nama_produk)) ?></td>
                              <td><?= number_format($d->netto, 0).' '.$d->satuan_terkecil ?></td>
                              <td style="text-align: right;"><?= $d->qty ?></td>
                              <td style="text-align: right;"><?= number_format($d->harga_beli, 0) ?></td>
                              
                              <td style="text-align: right;">
                                <?php
                                  $subtotal = $d->qty * $d->harga_beli;
                                  
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