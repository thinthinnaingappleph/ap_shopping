<?php
session_start();
require '../config/config.php';
require '../config/common.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('Location: login.php');
}

if($_SESSION['role']!= 1){
    header('Location: login.php');
}

if($_POST){

  if (empty($_POST['name'])|| empty($_POST['description']) || empty($_POST['category_id']) || empty($_POST['quantity'])  || empty($_POST['price']) || empty($_FILES['image']['name'])) {
    if(empty($_POST['name'])){
      $nameError = "Name is required";
    }
    if(empty($_POST['description'])){
      $descError= "Description is required";
    }
    if(empty($_POST['category_id'])){
        $categoryError= "Category is required";
    }
    if(empty($_POST['quantity'])){
        $quantityError= "Quantity is required";
    }}else if(is_numeric($_POST['quantity'])!= 1){
        $quantityError= "Quantity should be integer value";
      }
    if(empty($_POST['price'])){
        $priceError= "Price is required";
    } else if(is_numeric($_POST['price'])!= 1){
        $priceError= "Price should be integer value";
      }
    if(empty($_FILES['image']['name'])){
      $imageError="Image is required";
    }else{
        if(is_numeric($_POST['quantity'])!= 1){
          $quantityError= "Quantity should be integer value";
        }
        if(is_numeric($_POST['price'])!= 1){
          $priceError= "Price should be integer value";
        }
       if($quantityError == '' && $priceError == ''){
        $file = 'images/'.$_FILES['image']['name'];
        $fileType= pathinfo($file,PATHINFO_EXTENSION);
        if($fileType != 'png' && $fileType != 'jpg' && $fileType != 'jpeg'){
        echo "<script>alert('Image must be png,jpg,jpeg');</script>";
        }else{
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$file);
        $stmt=$pdo->prepare('INSERT INTO products(name,description,category_id,quantity,price,image) VALUES (:name,:description,:category_id,:quantity,:price,:image)');
        $result=$stmt->execute(
            array(':name'=>$name,':description'=>$description,':category_id'=>$category_id,':quantity'=>$quantity,':price'=>$price,':image'=>$image)
        );
            if($result){
                echo "<script>alert('Product is added');window.location.href='index.php';</script>";
            }
        }
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
              <form class="" action="product-add.php" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <div class="card-header">
                  <h3 class="card-title">Produt Form</h3>
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
                    <div class="form-group">
                    <?php 
                      $catStmt = $pdo->prepare("SELECT * FROM categories");
                      $catStmt->execute();
                      $catResult= $catStmt->fetchAll();
                    ?>
                      <label for="">Category</label><p class="text-danger">  <?php echo empty($categoryError)? '': '*'.$categoryError ?></p>
                        <select name="category_id" class="form-control">
                        <option value="">SELECT CATEGORY</option>
                        <?php
                            foreach($catResult as $value){
                            ?>
                            <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                            <?php
                            }
                        ?>
                       
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="">Quantity</label><p class="text-danger">  <?php echo empty($quantityError)? '': '*'.$quantityError ?></p>
                      <input type="number" class="form-control" name="quantity" value="">
                    </div>
                    <div class="form-group">
                      <label for="">Price</label><p class="text-danger"><?php echo empty($priceError)? '': '*'.$priceError ?></p>
                      <input type="number" class="form-control" name="price" value="">
                    </div>
                    <div class="form-group">
                      <label for="">Image</label><p class="text-danger">  <?php echo empty($imageError)? '': $imageError ?></p>
                      <input type="file" name="image" value="">
                    </div>
                </div>
                <div class="card-footer text-right">
                  <input type="submit" name="" value="SUMBIT" class="btn btn-sm btn-success">
                  <a href="index.php" type="button" class="btn btn-sm btn-warning">Back</a>
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
