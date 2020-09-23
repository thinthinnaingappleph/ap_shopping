<?php
session_start();
  require '../config/config.php';
  require '../config/common.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
  if($_SESSION['role'] != 1){
      header('Location: login.php');
  }

  if(!empty($_POST['search'])){
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']);
      setcookie('search', null, -1, '/');
    }
  }
 ?>
<?php include('header.php'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h3 class="card-title">Best Seller Item Report</h3>           
            </div>
            <?php
                $stmt = $pdo->prepare('SELECT product_id,SUM(quantity) as sale_qty FROM sale_order_details  GROUP BY product_id HAVING sale_qty >= 7 ORDER BY  sale_qty DESC');
                $stmt->execute();
                $result= $stmt->fetchAll();
             ?>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered" id="d-table">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Product</th>
                    <th>Total Qty</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if($result){
                      $i = 1;
                      foreach ($result as $value) {
                        $productStmt = $pdo->prepare("SELECT * FROM products WHERE  id=".$value['product_id']);
                        $productStmt->execute();
                        $proResult= $productStmt->fetchAll();
                        ?>                        
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo escape($proResult[0]['name']); ?></td>
                          <td><?php echo escape($value['sale_qty']); ?></td>                        
                        </tr>
                        <?php
                        $i++;
                      }
                    }
                   ?>

                </tbody>
              </table>
              <br/>
            
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
   
<?php include('footer.html'); ?>
<script>
    $(document).ready(function() {
            $('#d-table').DataTable();
    } );
</script>
