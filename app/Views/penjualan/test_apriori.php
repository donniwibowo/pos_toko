<?php
  echo $this->include('default/header');
?>

      
    <div class="container-fluid">
          
      <h5 class="card-title fw-semibold mb-4">Uji Coba Apriori</h5>
      <div class="card">
          <div class="card-body">
          
            
             <?php if(count($rules) > 0) : ?>

                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <td>No</td>
                        <td>Antecedent</td>
                        <td>Consequent</td>
                        <td>Support</td>
                        <td>Confidence</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $ctr = 0; ?>
                      <?php foreach($rules as $rule) { ?>
                      <?php $ctr++; ?>
                      <tr>
                        <td><?php echo $ctr; ?></td>
                        <td><?= implode(', ', $rule['antecedent']) ?></td>
                        <td><?= implode(', ', $rule['consequent']) ?></td>
                        <td><?= $rule['support'] ?></td>
                        <td><?= $rule['confidence'] ?></td>
                      </tr>
                     

                      <?php } ?>
                      
                    </tbody>
                  </table>
                </div>


            <?php else: ?>
                
                <div class="table-responsive">
                  <p>Tidak ada data</p>
                </div>


            <?php endif; ?>



            

          </div>
      </div> <!-- end of card -->
    </div> <!-- end of container -->
    

<?php
  echo $this->include('default/footer');
?>