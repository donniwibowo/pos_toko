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

            <?php if(session()->getFlashData('success')){ ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <?= session()->getFlashData('success') ?>
              </div>
            <?php } ?>
                
            <h5 class="card-title fw-semibold mb-4">Pengaturan</h5>
            <div class="card">
              <div class="card-body">
                
                <form method="POST" action="<?= $form_action ?>">
                  <?php if($setting_data) : ?>
                    <?php foreach($setting_data as $setting) : ?>

                      <div class="mb-3">
                        <label for="setting_name" class="form-label"><?= ucwords($setting['setting_name']) ?></label>
                        <input type="text" class="form-control" name="setting_value[]" value="<?= $setting['setting_value'] ?>" placeholder="<?= $setting['setting_value'] ?>">
                        <input type="hidden" name="setting_id[]" value="<?= $setting['setting_id'] ?>">
                      </div>
                    
                    <?php endforeach; ?>    
                  <?php endif; ?>
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