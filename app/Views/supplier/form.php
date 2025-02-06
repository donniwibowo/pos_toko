<?php
  echo $this->include('default/header');
?>

      
        <div class="container-fluid">
         <!--  <div class="card">
            <div class="card-body"> -->

              <?php if(session()->getFlashData('success')){ ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashData('success') ?>
                </div>
              <?php } ?>
                  
              <h5 class="card-title fw-semibold mb-4">Add Supplier</h5>
              <div class="card">
                <div class="card-body">
                  <?php if(session()->getFlashData('success')){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashData('success') ?>
                    </div>
                  <?php } ?>

                  <?php if(session()->getFlashData('danger')){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashData('danger') ?>
                    </div>
                  <?php } ?>
                  <form method="POST" action="<?= $form_action ?>">
                    <div class="mb-3">
                      <label for="nama_supplier" class="form-label">Nama Supplier*</label>
                      <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="<?= set_value('nama_supplier', $data->nama_supplier) ?>" placeholder="Nama Supplier">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('nama_supplier') ?></p>
                    </div>
                    <div class="mb-3">
                      <label for="nama_sales" class="form-label">Nama Sales*</label>
                      <input type="text" class="form-control" id="nama_sales" name="nama_sales" value="<?= set_value('nama_sales', $data->nama_sales) ?>" placeholder="Nama Sales">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('nama_sales') ?></p>
                    </div>
                    <div class="mb-3">
                      <label for="alamat" class="form-label">Alamat</label>
                      <input type="text" class="form-control" id="alamat" name="alamat" value="<?= set_value('alamat', $data->alamat) ?>" placeholder="Alamat">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('alamat') ?></p>
                    </div>
                    <div class="mb-3">
                      <label for="no_telp" class="form-label">No Telp*</label>
                      <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= set_value('no_telp', $data->no_telp) ?>" placeholder="No Telp">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('no_telp') ?></p>
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="text" class="form-control" id="email" name="email" value="<?= set_value('email', $data->email) ?>" placeholder="Email">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('email') ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="tempo_pembayaran" class="form-label">Tempo Pembayaran</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="tempo_pembayaran" name="tempo_pembayaran" value="<?= set_value('tempo_pembayaran', $data->tempo_pembayaran) ?>" placeholder="14">
                          <span class="input-group-text">
                            <label>Hari</label>
                          </span>
                        </div>
                        <p class="error-msg"><?= \Config\Services::validation()->getError('tempo_pembayaran') ?></p>
                      </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>

                </div>
              </div>
            <!-- </div>
          </div> -->
        </div>
     

<?php
  echo $this->include('default/footer');
?>