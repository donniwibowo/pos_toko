<?php
  echo $this->include('default/header');
?>

      
        <div class="container-fluid">
          <!-- <div class="card">
            <div class="card-body"> -->
              <h5 class="card-title fw-semibold mb-4">Add User</h5>
              <div class="card">
                <div class="card-body">
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
                  
                  <form method="POST" action="<?= $form_action ?>">
                    <?php if($is_new_data) { ?>
                      <div class="mb-3">
                        <label for="no_telp" class="form-label">No Telp*</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= set_value('no_telp', $data->no_telp) ?>" placeholder="No Telp">
                        <p class="error-msg"><?= \Config\Services::validation()->getError('no_telp') ?></p>
                      </div>
                    <?php } ?>
                    <div class="mb-3">
                      <label for="password" class="form-label">Password*</label>
                      <input type="password" class="form-control" id="password" name="password" value="<?= set_value('password', $data->password) ?>"  placeholder="Password">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('password') ?></p>
                    </div>
                    <div class="mb-3">
                      <label for="confirm_password" class="form-label">Confirm Password*</label>
                      <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="<?= set_value('confirm_password', $data->password) ?>" placeholder="Confirm Password">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('confirm_password') ?></p>
                    </div>
                    <div class="mb-3">
                      <label for="nama" class="form-label">Nama*</label>
                      <input type="text" class="form-control" id="nama" name="nama" value="<?= set_value('nama', $data->nama) ?>" placeholder="Nama">
                      <p class="error-msg"><?= \Config\Services::validation()->getError('nama') ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Pilih Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select">
                          <?php if(session()->is_superadmin) : ?>
                            <option value='admin'<?= $data->jabatan == 'admin' ? ' selected' : '' ?>>Admin</option>
                          <?php endif; ?>
                          <option value='kasir'<?= $data->jabatan == 'kasir' ? ' selected' : '' ?>>Kasir</option>
                        </select>
                      </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>

                </div>
              </div>
           <!--  </div>
          </div> -->
        </div>
      

<?php
  echo $this->include('default/footer');
?>