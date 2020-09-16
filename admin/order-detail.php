<?php
session_start();
  require '../config/config.php';
  require '../config/common.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
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
              <h3 class="card-title">Order Detail Listings</h3>
              <a href="order-list.php" type="button" class="btn btn-sm btn-warning ml-auto">Back</a>
            </div>
            <?php
              if(!empty($_GET['pageno'])){
                $pageno=$_GET['pageno'];
              }else{
                $pageno=1;
              }
              $numOfRecs=5;
              $offset=($pageno-1) * $numOfRecs;

                $stmt = $pdo->prepare('SELECT * FROM sale_order_details WHERE sale_order_id='. $_GET['id']);
                $stmt->execute();
                $rawResult= $stmt->fetchAll();

                $total_pages=ceil(count($rawResult) / $numOfRecs);

                $stmt = $pdo->prepare("SELECT * FROM sale_order_details  WHERE sale_order_id=". $_GET['id'] ." LIMIT $offset,$numOfRecs");
                $stmt->execute();
                $result= $stmt->fetchAll();
             ?>
           
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Order Date</th>                   
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if($result){
                      $i = 1;
                      foreach ($result as $value) {
                        $pStmt = $pdo->prepare("SELECT * FROM products WHERE  id=".$value['product_id']);
                        $pStmt->execute();
                        $pResult= $pStmt->fetchAll();
                        ?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo escape($pResult[0]['name']); ?></td>
                          <td><?php echo escape($value['quantity']); ?></td>
                          <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))); ?></td>                          
                        </tr>
                        <?php
                        $i++;
                      }
                    }
                   ?>

                </tbody>
              </table>
              <br/>
              <nav aria-label="Page navigation example" style="float:right">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){echo '#';} else{echo '?pageno='.($pageno-1);} ?>">
                      Previous
                    </a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno ?></a></li>
                  <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';} else {echo '?pageno='.($pageno+1);} ?>">
                      Next
                    </a>
                  </li>
                  <li class="page-item"><a class="page-link" href="<?php echo '?pageno='.$total_pages ?>">Last</a></li>
                </ul>
              </nav>
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
