<?php
  echo $this->include('default/header');
?>

     
        <div class="container-fluid">
         

          <?php if(session()->getFlashData('danger')){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashData('danger') ?>
            </div>
          <?php } ?>
              
          <h5 class="card-title fw-semibold mb-4">Bundling Prduk</h5>
          <div class="card">
              <form id="form-bundling-produk" method="POST" action="<?= base_url().'produk/createbundling' ?>">
                <div class="card-body">
                  <div class="mb-3">
                      <label for="supplier_id" class="form-label">Supplier</label>
                      <!--<select id="supplier_id" name="supplier_id" class="form-select" disabled>
                      -->
                        <?php
                          if($supplier_data) {
                            foreach($supplier_data as $supplier) {
                                $isSelected = '';
                                if(strtolower($supplier['nama_supplier']) == 'bundling') {
                                    //$isSelected = ' selected';
                                    echo "<input type='hidden' class='form-control' name='supplier_id' value='".$supplier['supplier_id']."' />";
                                    echo "<input type='text' class='form-control' name='supplier_name' value='".$supplier['nama_supplier']."' disabled />";
                                }
                              //echo "<option".$isSelected." value='".$supplier['supplier_id']."'>".strtoupper($supplier['nama_supplier'])."</option>";
                            }
                          }

                        ?>
                      <!--</select>-->
                      <p class="error-msg"><?= \Config\Services::validation()->getError('supplier_id') ?></p>
                  </div>

                  <div class="mb-3">
                      <label for="kategori_id" class="form-label">Kategori</label>
                      <!--<select id="kategori_id" name="kategori_id" class="form-select" disabled>-->
                        <?php
                          if($kategori_data) {
                            foreach($kategori_data as $kategori) {
                                $isSelected = '';
                                if(strtolower($kategori['nama_kategori']) == 'bundling') {
                                   // $isSelected = ' selected';
                                    echo "<input type='hidden' class='form-control' name='kategori_id' value='".$kategori['kategori_id']."' />";
                                    echo "<input type='text' class='form-control' name='kategori_name' value='".$kategori['nama_kategori']."' disabled />";
                                }
                             // echo "<option".$isSelected." value='".$kategori['kategori_id']."'>".strtoupper($kategori['nama_kategori'])."</option>";
                            }
                          }

                        ?>
                      <!--</select>-->
                      <p class="error-msg"><?= \Config\Services::validation()->getError('kategori_id') ?></p>
                  </div>

                  <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Bundling Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Nama Bundling Produk" required>
                    <p class="error-msg"><?= \Config\Services::validation()->getError('nama_produk') ?></p>
                  </div>

                  <div class="mb-3">
                    <label for="jumlah_produk" class="form-label">Jumlah Bundling Produk</label>
                    <input type="text" class="form-control" id="jumlah_produk" name="jumlah_produk" placeholder="Jumlah Bundling Produk" required>
                    <p class="error-msg"><?= \Config\Services::validation()->getError('jumlah_produk') ?></p>
                  </div>

                  <br />
                  <hr />


                  <div class="table-responsive">
                    <table class="table dynamic-table" id="table-netto-penjualan">
                      <thead>
                        <tr>
                          <td>Nama Produk</td>
                          <td>Netto Penjualan</td>
                          
                          <td>Harga Beli</td>
                          <td>Harga Jual</td>
                          <td>Action</td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($produk_data as $key => $produks) : ?>
                            <tr>
                                <td><?= strtoupper($key) ?></td>
                                <td>

                                    <select class="form-control select-netto-penjualan" name="produk_harga_id[]">
                                      <?php
                                        $harga_beli = 0;
                                        $harga_jual = 0;
                                        $produk_id = 0;
                                      ?>
                                      <?php foreach($produks as $produk) :  ?>
                                        <?php
                                          
                                          $produk_id = $produk->produk_id;
                                          $satuan_penjualan = $produk->satuan;
                                          if($satuan_penjualan == $produk->satuan_terkecil) {
                                            $satuan_penjualan = '';
                                          } else {
                                            $satuan_penjualan = ' | '.$produk->satuan;
                                          }

                                          $netto_penjualan = number_format($produk->netto).' '.$produk->satuan_terkecil;

                                          if($produk->satuan_terkecil == 'gram') {
                                            if($produk->netto > 500) {
                                              $netto_penjualan = number_format($produk->netto / 1000).' KG';   
                                            }
                                          }

                                          if($harga_beli < 1) {
                                            $harga_beli = $produk->harga_beli;
                                          }

                                          if($harga_jual < 1) {
                                            $harga_jual = $produk->harga_jual;
                                          }
                                        ?>

                                        <option value="<?= $produk->produk_harga_id ?>" data-harga-beli="<?= number_format($produk->harga_beli) ?>" data-harga-jual="<?= number_format($produk->harga_jual) ?>" data-hb-nonformat="<?= $produk->harga_beli ?>" data-hj-nonformat="<?= $produk->harga_jual ?>">
                                            <?= $netto_penjualan.$satuan_penjualan ?>
                                            
                                        </option>

                                      <?php endforeach; ?>
                                    </select>

                                    <input type="hidden" name="produk_id[]" value="<?= $produk_id ?>" />
                                </td>

                                <td class="harga-beli" style="text-align: right;">
                                  <?= number_format($harga_beli) ?>
                                </td>

                                <td class="harga-jual" style="text-align: right;">
                                  <?= number_format($harga_jual) ?>
                                </td>

                                <td>
                                  
                                  <i role="button" class="ti ti-trash btn-delete-row"></i>
                                </td>
                            </tr>

                                
                        <?php endforeach; ?>
                      </tbody>

                      <tfoot>
                        <tr>
                          <td colspan="2" style="text-align: right;">Total</td>
                          <td id="total-harga-beli" style="text-align: right;">0</td>
                          <td id="total-harga-jual" style="text-align: right;">0</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  
                  

                  

                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="<?= base_url() ?>produk/list" class="btn btn-warning">Close</a>
                </div>

            

              </form>

            
          </div> <!-- end of card -->
         
        </div>
     

<?php
  echo $this->include('default/footer');
?>

<script type="text/javascript">
    function hitungTotal() {
      var total_hb = 0;
      var total_hj = 0;

      $('#table-netto-penjualan').find('.select-netto-penjualan').each(function() {
        var harga_beli = $(this).find(':selected').data('hb-nonformat');
        var harga_jual = $(this).find(':selected').data('hj-nonformat');

        total_hb += harga_beli;
        total_hj += harga_jual
      });

      $('#total-harga-beli').html(total_hb);
      $('#total-harga-jual').html(total_hj);

    }

    $(document).ready(function() {
      hitungTotal();
      $('.select-netto-penjualan').on('change', function() {
        var harga_beli = $(this).find(':selected').data('harga-beli');
        var harga_jual = $(this).find(':selected').data('harga-jual');

        $(this).parent().parent().find('.harga-beli').html(harga_beli);
        $(this).parent().parent().find('.harga-jual').html(harga_jual);

        hitungTotal();
      });

      $('#table-netto-penjualan').on('click', 'tbody .btn-delete-row', function() {
      
          if($(this).parent().parent().parent().children().length > 1) {
            $(this).parent().parent().remove();
          }

          hitungTotal();
      });
    });

</script>