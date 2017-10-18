<?php 
include "config.php";
session_start();
if (!isset($_SESSION['username'])) {
    header('location:login.php');
}
if($_SESSION['type']=='user' && $_SESSION['log'] == 0){
  $_SESSION['log'] = 1;
?>
<script type="text/javascript">
  localStorage.setItem('activeTab', "#sales");
</script>
<?php
}
if($_SESSION['type']=='admin' && $_SESSION['log'] == 0){
  $_SESSION['log'] = 1;
?>
<script type="text/javascript">
  localStorage.setItem('activeTab', "#inventory");
</script>
<?php
}
?>
<!DOCTYPE html>
<html lang="en">
<title>App - Sales and Inventory System</title>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="jumbotron" style="background-color: rgba(255,255,255,0.9); height: 100%; margin-bottom: 5%; margin-top: 5%; padding-top: 3%; padding-bottom: 3%;">
            <div class="row">
              <div class="jumbotron" style="background-color: rgba(255,255,255,0.0); width: 20%; padding-top: 5%;">
                <ul class="nav flex-column nav-pills" id="navTab">
                  <?php 
                    if($_SESSION['type'] == 'user'){
                  ?>
                  <li><a href="#sales" class="nav-link" data-toggle="tab">Shop</a></li>
                  <li><a href="#cart" class="nav-link" data-toggle="tab">Cart</a></li>
                  <li><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
              </div>
              <div class="col">
                <div class="jumbotron" style="background-color: rgba(255,255,255,0.0); padding-top: 2%;">
                  <div class="tab-content" id="conTab">
                    <!--Shop-->
                    <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Shop</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                    </div>
                      <!--Cart-->
                    <div class="tab-pane fade" id="cart" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Cart</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                    </div>
                  </div>
                </div>
              </div>
                  <?php 
                  }
                  else{
                  ?>
                  <li><a href="#inventory" class="nav-link" data-toggle="tab">Inventory</a></li>
                  <li><a href="#stocks" class="nav-link" data-toggle="tab">Stocks</a></li>
                  <li><a href="#reports" class="nav-link" data-toggle="tab">Reports</a></li>
                  <li><a href="#users" class="nav-link" data-toggle="tab">User Management</a></li>
                  <li><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
              </div>
              <div class="col">
                <div class="jumbotron" style="background-color: rgba(255,255,255,0.0); padding-top: 2%;">
                  <div class="tab-content" id="conTab">
                    <!--Inventory-->
                    <div class="tab-pane fade show active" id="inventory" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Inventory</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                        <table class="table table-hover">
                          <thead class="text-primary">
                            <tr>
                              <th>Product ID</th>
                              <th>Product Name</th>
                              <th>Product Quantity</th>
                              <th>Product Price</th>
                              <th>Product Description</th>
                              <th>Product Supplier</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $inven = inven("");?>
                          </tbody>
                          <?php for($x = 0; $x<count($inven,COUNT_NORMAL); $x++){ ?>
                          <tr data-toggle="collapse" data-target="#<?php echo $search[$x]["prod_id"]?>" class="clickable">
                            <td><?php echo $inven[$x]["prod_id"]; ?></td>
                            <td><?php echo $inven[$x]["prod_name"]; ?></td>
                            <td><?php if($inven[$x]["prod_quan"]>0){
                              echo $inven[$x]["prod_quan"];
                            } 
                            else{
                              echo "<div class='text-danger'>Out of Stock</div>"; 
                            }

                            ?></td>
                            <td><?php echo $inven[$x]["prod_price"]; ?></td>
                            <td><?php echo $inven[$x]["prod_desc"]; ?></td>
                            <td><?php echo $inven[$x]["prod_supplier"]; ?></td>
                          </tr>
                          <?php } ?>
                        </table>
                    </div>
                    <!--Stocks-->
                    <div class="tab-pane fade" id="stocks" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Stocks</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                        <div id="accordion" role="tablist">
                          <div class="card">
                            <div class="card-header" role="tab" id="headingOne">
                              <h5 class="mb-0">
                                <a data-toggle="collapse" href="#addStock" aria-expanded="true" aria-controls="collapseOne">
                                  Add Product
                                </a>
                              </h5>
                            </div>
                            <div id="addStock" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                              <div class="card-body">
                                <?php 
                                  if (isset($_POST['addProd'])) {
                                    $prodname = $_POST['pname'];
                                    $prodprice = $_POST['price'];
                                    $prodquan = $_POST['quan'];
                                    $supp = $_POST['supplier'];
                                    $desc = $_POST['desc'];
                                    $img = $_FILES['pic']['tmp_name'];
                                    upload($img);
                                    $add = addProduct($prodname,$prodprice,$prodquan,$desc,$supp,basename($img));
                                    if($add == true){

                                ?>
                                  <script type="text/javascript">
                                      document.getElementById("addStock").classList.add('show');
                                  </script>
                                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>Product Added!</strong>
                                  </div>
                                <?php
                                    }
                                    else{
                                ?>
                                  <script type="text/javascript">
                                      document.getElementById("addStock").classList.add('show');
                                  </script>
                                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>Product Already Exists!</strong>
                                  </div>
                                <?php
                                    }
                                  }
                                ?>
                                <form method="post" action="" enctype="multipart/form-data">
                                <div class="form-row">
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="Product Name" name="pname" required>
                                  </div>
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="Product Price" name="price" required>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="Quantity" name="quan" required>
                                  </div>
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="Supplier" name="supplier" required>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                  <div class="col">
                                    <textarea class="form-control" name="desc" placeholder="Product Description" rows="3"></textarea>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                  <div class="col">
                                    <label for="pic" class="text-primary">Product Images</label>
                                    <input id="pic" type="file" class="form-control" style="border: none;" name="pic" 
                                     accept="image/*" required>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                  <div class="col">
                                    <input type="submit" class="btn btn-primary btn-sm float-right" value="Add Product" name="addProd">
                                  </div>
                                </div>
                              </form>
                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header" role="tab" id="headingTwo">
                              <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#delStock" aria-expanded="false" aria-controls="collapseTwo">
                                  Update Stocks
                                </a>
                              </h5>
                            </div>
                            <div id="delStock" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                              <div class="card-body">
                                <form action="" method="post">
                                  <div class="form-inline">
                                    <div class="col">
                                      <input type="text" name="searchname" class="form-control" placeholder="Product Name" style="width: 100%;"">
                                    </div>
                                    <div class="col">
                                      <input type="submit" name="stocksearch" class="form-control btn btn-primary btn-md float-right" value="Search">
                                    </div>
                                  </div>
                                </form>
                                <?php
                                  if(isset($_POST['stocksearch'])){
                                    $searchname = $_POST['searchname'];
                                    $search = search($searchname);
                                    if($search==false){
                                ?>
                                      <br>
                                      <script type="text/javascript">
                                        document.getElementById("delStock").classList.add('show');
                                      </script>
                                      <div class="col">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>Product Not Found!</strong>
                                        </div>
                                      </div>
                                <?php                  
                                    }
                                    else{
                                ?>
                                      <br>
                                      <script type="text/javascript">
                                        document.getElementById("delStock").classList.add('show');
                                      </script>
                                      <table class="table table-hover">
                                            <tr class="text-primary">
                                              <th>Product ID</th>
                                              <th>Product Name</th>
                                              <th>Product Stocks</th>
                                            </tr>
                                        <form class="form-inline" method="post" action="">
                                          <div class="form-row">

                              <?php 
                                        for($x = 0; $x<count($search,COUNT_NORMAL); $x++){
                                          $upid = $search[$x]['prod_id'];
                              ?>
                                          <tr>
                                            <td><input type="text" name="upID" class="form-control" readonly value="<?php echo $search[$x]['prod_id']?>"></td>
                                            <td><?php echo $search[$x]['prod_name']?></td>
                                            <td><input type="number" name="upStock" class="form-control" value="<?php echo $search[$x]['prod_quan']?>">
                                            </td>
                                          </tr>
                              <?php
                                        }
                                      ?>
                                          </div>
                                      </table>
                                        <div class="form-row">
                                          <div class="col"></div>
                                          <div class="col"></div>
                                          <div class="col"><input type="submit" name="updatestocks" class="btn btn-primary btn-sm float-right"></div>
                                        </div>
                                      </form>
                              <?php
                                    }
                                  }
                                  if (isset($_POST["updatestocks"])) {
                                    $upid = $_POST["upID"];
                                    $quan = $_POST["upStock"];
                                    updatestocks($upid,$quan);
                              ?>
                                    <br>
                                    <script type="text/javascript">
                                        document.getElementById("delStock").classList.add('show');
                                    </script>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>Product Updated!</strong>
                                  </div>
                              <?php
                                  }
                              ?>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                    <!--Reports-->
                    <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Reports</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                    </div>
                    <!--Users-->
                    <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Users</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                        <div id="accordion" role="tablist">
                        <div class="card">
                          <div class="card-header" role="tab" id="headingOne">
                            <h5 class="mb-0">
                              <a data-toggle="collapse" href="#addUser" aria-expanded="true" aria-controls="collapseOne">
                                Add Admin
                              </a>
                            </h5>
                          </div>
                          <div id="addUser" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                              <?php 
                                if(isset($_POST['addUser'])){
                                  $uname = $_POST['uname'];
                                  $pass = sha1($_POST['pword']);
                                  $fname = $_POST['fname'];
                                  $lname = $_POST['lname'];
                                  $type = strtolower($_POST['type']);
                                  $adduser = addUser($fname,$lname,$uname,$pass,$type);
                                  if($adduser == true){
                                    ?>
                                    <script type="text/javascript">
                                      document.getElementById("addUser").classList.add('show');
                                    </script>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>User successfully added!</strong>
                                    </div>
                                    <?php
                                  }
                                  else{
                                    ?>
                                    <script type="text/javascript">
                                      document.getElementById("addUser").classList.add('show');
                                    </script>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>User already exists!</strong>
                                    </div>
                                    <?php
                                  }
                                }
                              ?>
                              <form method="post" action="">
                                <div class="form-row">
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="First name" name="fname" required>
                                  </div>
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="Last name" name="lname" required>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="Username" name="uname" required>
                                  </div>
                                  <div class="col">
                                    <input type="password" class="form-control" placeholder="Password" name="pword" required>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                  <div class="col">
                                    <input type="submit" class="btn btn-primary btn-sm float-right" value="Add User" name="addUser">
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-header" role="tab" id="headingTwo">
                            <h5 class="mb-0">
                              <a class="collapsed" data-toggle="collapse" href="#delUser" aria-expanded="false" aria-controls="collapseTwo">
                                Delete User
                              </a>
                            </h5>
                          </div>
                          <div id="delUser" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                              <?php 
                                if (isset($_POST['deleteUser'])) {
                                  $username = $_POST['unamedel'];
                                  if($_SESSION['username'] == $username){
                                    ?>
                                    <script type="text/javascript">
                                      document.getElementById("collapseTwo").classList.add('show');
                                      rel();
                                    </script>
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>Cannot delete own account!</strong>
                                    </div>
                                    <?php
                                  }
                                  else{
                                  $delete = deleteUser($username);
                                  if($delete == true){
                                    ?>
                                    <script type="text/javascript">
                                      document.getElementById("delUser").classList.add('show');
                                      rel();
                                    </script>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>User deleted!</strong>
                                    </div>
                                    <?php
                                  }
                                  else{
                                    ?>
                                    <script type="text/javascript">
                                      document.getElementById("collapseTwo").classList.add('show');
                                    </script>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>User does not exists!</strong>
                                    </div>
                                    <?php
                                  }
                                  }
                                }
                              ?>
                              <form method="post" action="">
                                <div class="form-row">
                                  <div class="col">
                                    <input type="text" class="form-control" placeholder="Username" name="unamedel" required>
                                  </div>
                                  <div class="col">
                                    <input type="submit" class="btn btn-primary btn-sm float-right" value="Delete User" name="deleteUser" required>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                  <?php
                  }
                  ?>
              </div>
              <?php 

              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>