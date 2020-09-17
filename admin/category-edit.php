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
            $id = $_POST['id'];
            $stmt=$pdo->prepare("UPDATE categories SET name=:name, description=:description WHERE  id=:id");
            $res=$stmt->execute(
                array(':name'=>$name,':description'=>$description,':id'=>$id)
            );
            if($res){
                echo "<script>alert('Successfully Updated');window.location.href='category.php';</script>";
            }
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetchAll();
 ?>

<?php include('header.php'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
              <form class="" action="category-edit.php" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <input type="hidden" name="id" value="<?php echo escape($result[0]['id'])?>">
                <div class="card-header">
                  <h3 class="card-title">Category Form</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="">Name</label><p class="text-danger"><?php echo empty($nameError)? '': '*'.$nameError ?></p>
                      <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name'])?>">
                    </div>
                    <div class="form-group">
                      <label for="">Description</label><p class="text-danger"><?php echo empty($descError)? '': '*'.$descError ?></p>
                      <textarea class="form-control" name="description" rows="8" cols="80"><?php echo escape($result[0]['description']) ?></textarea>
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
