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

if($_POST){
  if (empty($_POST['name'])|| empty($_POST['description'])) {
    if(empty($_POST['name'])){
      $nameError = "Name is required";
    }
    if(empty($_POST['description'])){
      $descError = "Description is required";
    }  
  }else{ 
      $name = $_POST['name'];
      $description = $_POST['description'];
      $stmt=$pdo->prepare('INSERT INTO categories(name,description) VALUES (:name,:description)');
      $result=$stmt->execute(
        array(':name'=>$name,':description'=>$description)
      );
      if($result){
        echo "<script>alert('Successfully added');window.location.href='category.php';</script>";
        // header('Location: index.php');
      }
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
              <form class="" action="category-add.php" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <div class="card-header">
                  <h3 class="card-title">Category Form</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="">Name</label><p class="text-danger">  <?php echo empty($nameError)? '': '*'.$nameError ?></p>
                      <input type="text" class="form-control" name="name" value="">
                    </div>
                    <div class="form-group">
                      <label for="">Description</label><p class="text-danger"><?php echo empty($descError)? '': '*'.$descError ?></p>
                      <textarea class="form-control" name="description" rows="8" cols="80"></textarea>
                    </div>                   
                </div>
                <div class="card-footer text-right">
                  <input type="submit" name="" value="SUMBIT" class="btn btn-sm btn-success">
                  <a href="category.php" type="button" class="btn btn-sm btn-warning">Back</a>
                </div>
              </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

<?php include('footer.html'); ?>
