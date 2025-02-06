<?php
  echo $this->include('default/header');
?>

      <div class="container-fluid">
       <!--  <div class="card">
          <div class="card-body"> -->

            <?php if(session()->getFlashData('danger')){ ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <?= session()->getFlashData('danger') ?>
                  </div>
                <?php } ?>
                
            <h5 class="card-title fw-semibold mb-4">Add Kategori</h5>
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
                  <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori*</label>
                    <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?= set_value('nama_kategori', $data->nama_kategori) ?>" placeholder="Nama Kategori">
                    <p class="error-msg"><?= \Config\Services::validation()->getError('nama_kategori') ?></p>
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