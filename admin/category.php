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
              <h3 class="card-title">Category Listings</h3>
              <a href="category-add.php" type="button" class="btn btn-sm btn-success ml-auto">New Categry</a>
            </div>
            <?php
              if(!empty($_GET['pageno'])){
                $pageno=$_GET['pageno'];
              }else{
                $pageno=1;
              }
              $numOfRecs=5;
              $offset=($pageno-1) * $numOfRecs;

              if(empty($_POST['search']) && empty($_COOKIE['search'])){
                $stmt = $pdo->prepare('SELECT * FROM categories ORDER BY  id DESC');
                $stmt->execute();
                $rawResult= $stmt->fetchAll();

                $total_pages=ceil(count($rawResult) / $numOfRecs);

                $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY  id DESC LIMIT $offset,$numOfRecs");
                $stmt->execute();
                $result= $stmt->fetchAll();
              }else{
                $searchKey=!empty($_POST['search'])?$_POST['search']: $_COOKIE['search'];
                $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY  id DESC");
                $stmt->execute();
                $rawResult= $stmt->fetchAll();

                $total_pages=ceil(count($rawResult) / $numOfRecs);

                $stmt = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY  id DESC LIMIT $offset,$numOfRecs");
                $stmt->execute();
                $result= $stmt->fetchAll();
              }
             ?>
           
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th style="width: 40px">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if($result){
                      $i = 1;
                      foreach ($result as $value) {
                        ?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo escape($value['name']); ?></td>
                          <td><?php echo escape(substr($value['description'],0,100)); ?></td>
                          <td>
                            <div class="btn-group">
                              <div class="container">
                                <a href="category-edit.php?id=<?php echo $value['id']?>"type="button" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                              </div>
                              <div class="container">
                                <a href="category-delete.php?id=<?php echo $value['id']?>" onclick="return confirm('Are you sure you want to delete this item?');" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                              </div>
                            </div>
                          </td>
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
