<?php 
include "config.php";
$func = new Funcs;
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
<?php 
  if($_SESSION['type'] == 'user'){
?>
    <title>Shop - Tindahan ni Maya</title>
<?php
}
  if($_SESSION['type'] == 'admin'){
?>
    <title>Admin - Tindahan ni Maya</title>
<?php
  }
?>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/datatable.min.css">
    <script src="js/jquery.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.arctext.js"></script>
    <script src="js/jquery.datatable.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="jumbotron" style="background-color: rgba(255,255,255,1); height: 100%; margin-bottom: 5%; margin-top: 5%; padding-top: 3%; padding-bottom: 3%;">
            <div class="row">
              <div class="jumbotron" style="background-color: rgba(255,255,255,0.0); width: 20%; padding-top: 5%;">
                <ul class="nav flex-column nav-pills" id="navTab">
                  <?php 
                    if($_SESSION['type'] == 'user'){
                  ?>
                  <div id="storename" class="text-center text-primary font-weight-bold font-italic"><strong>Tindahan ni Maya</strong></div>
                    <div class="text-center"><img src="res/avatar.png" class="rounded-circle"></div>
                    <script>
                      $().ready(function() {
                          $('#storename').arctext({radius:60})
                      });
                    </script>
                  <li><a href="#sales" class="nav-link" data-toggle="tab">Shop</a></li>
                  <li><a href="#cart" class="nav-link" data-toggle="tab">Cart</a></li>
                  <li><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
              </div>
              <div class="col">
                <div class="jumbotron" style="background-color: rgba(255,255,255,0.0); padding-top: 2%;">
                  <div class="tab-content" id="conTab">
                    <!--Shop-->
                    <div class="tab-pane show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Shop</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                            <table class="table" id="storetable">
                              <thead>
                                <tr class="text-primary">
                                  <th>Product Image</th>
                                  <th>Product Name</th>
                                  <th>Product Price</th>
                                  <th>Product Description</th>
                                  <th>&nbsp;</th>
                                </tr>
                              </thead>
                            <?php
                            $items = $func->loadShop();
                            for($x = 0;$x<count($items,COUNT_NORMAL); $x++){
                              if($items[$x]["prod_quan"]==0){

                              }
                              else{
                                $img = "products/".$items[$x]['prod_img'].".jpg";
                              ?>
                                <tr>
                                  <td class="text-center"><img src="<?php echo $img ?>" style="max-width: auto; max-height: 120px;"></td>
                                  <td><?php echo $items[$x]["prod_name"]; ?></td>
                                  <td><?php echo $items[$x]["prod_price"]; ?></td>
                                  <td><?php echo $items[$x]["prod_desc"]?></td>
                                  <td class="text-center">
                                    <br><br>
                                    <button type="button" class="form-control btn-sm btn-primary" data-toggle="modal" data-target="<?php echo "#".$items[$x]['prod_name'] ?>" id="regbtn">Add to Cart</button>
                                  </td>
                                </tr>
                                <div class="modal fade" id=<?php echo $items[$x]['prod_name'] ?> tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title text-primary" id="exampleModalLabel">Add to Cart</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <?php
                                          if (isset($_POST[$x])) {
                                            $func->addtoCart($items[$x]["prod_id"],$_SESSION["id"],$items[$x]["prod_price"]);
                            ?>
                                            <script type="text/javascript">
                                              window.location.href = 'http://localhost/inventory%20final/app/index.php';
                                            </script>
                            <?php
                                          }
                                        ?>
                                        <div class="row">
                                          <div class="col">
                                            <img src="<?php echo $img; ?>" class="img-thumbnail">
                                          </div>
                                          <div class="col">
                                            <div>
                                              <small>
                                                <div class="text-primary">Product Name:&nbsp;</div><?php echo $items[$x]["prod_name"]; ?><hr>
                                                <div class="text-primary">Product Description:&nbsp;</div><?php echo $items[$x]["prod_desc"]; ?><hr>
                                                <div class="text-primary">Product Price:&nbsp;</div><?php echo $items[$x]["prod_price"]; ?>
                                              </small>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                        <form action="" method="post"><input type="submit" name="<?php echo $x; ?>" value="Add to Cart" class="btn btn-primary btn-sm"></form>
                                      </div>
                              <?php
                              }
                            }
                            ?>
                            </table>
                          <script type="text/javascript">
                            var table = $('#storetable').DataTable();
                            table.page.len(4).draw();
                          </script>
                    </div>
                      <!--Cart-->
                    <div class="tab-pane" id="cart" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Cart</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                            <?php
                              $cart = $func->getCart();
                              if($_SESSION['pay'] == true){
                                ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                  <strong>Items on the way!</strong>
                                </div>
                                <?php
                                $_SESSION['pay'] = false;
                              }
                              if($cart == NULL){
                          ?>
                                <br>
                                <div class="text-primary text-center">
                                  <h4>Cart is Empty!</h4>
                                </div>
                          <?php
                              }
                              else{
                          ?>
                                <table class="table">
                                  <thead class="text-primary">
                                    <tr>
                                      <td><small><strong>Product Image</strong></small></td>
                                      <td><small><strong>Product Name</strong></small></td>
                                      <td><small><strong>Product Price</strong></small></td>
                                      <td><small><strong>Product Quantity</strong></small></td>
                                      <td></td>
                                    </tr>
                                  </thead>
                                  <tbody>
                          <?php
                                for($x = 0; $x<count($cart,COUNT_NORMAL);$x++){
                                $img = $func->getImg($cart[$x]["prod_id"]);
                                $name = $func->getName($cart[$x]["prod_id"]);
                                $price = $func->getPrice($cart[$x]["prod_id"]);
                            ?>
                                  <tr>
                                    <form method="POST" action="">
                                    <td class="text-center"><img src="<?php echo $img ?>" style="max-width: auto; max-height: 120px;"></td>
                                    <td><?php echo $name ?></td>
                                    <td><?php echo $price?></td>
                                    <td><input type="number" name="<?php echo 'quan'.$x?>" class='form-control form-control-sm' value='<?php echo $cart[$x]["item_quan"] ?>' min="1" max="<?php $func->getStockCount($cart[$x]["prod_id"])?>"></td>
                                    <td>
                                      <form method="POST" action="">
                                        <input class="btn btn-primary btn-sm" type="submit" name="<?php echo 'up'.$x ?>" value="Update">
                                      </form>
                                    </td>
                                    <td>
                                        <input class="btn btn-primary btn-sm" type="submit" name="<?php echo 'rem'.$x ?>" value="Remove From Cart">
                                      </form>
                                    </td>
                                  </tr>
                            <?php
                                  $update = 'up'.$x;
                                  if (isset($_POST[$update])) {
                                    $q = "quan".$x;
                                    $func->updateItem($cart[$x]['prod_id'],$_POST[$q],$cart[$x]['user_id']);
                            ?>
                                    <script type="text/javascript">
                                      window.location.href = 'http://localhost/inventory%20final/app/index.php';
                                    </script>
                                  $rem = 'rem'.$x;
                                  if (isset($_POST[$rem])) {
                                    $func->removeItem($cart[$x]['prod_id'],$cart[$x]['user_id']);
                            ?>
                                    <script type="text/javascript">
                                      window.location.href = 'http://localhost/inventory%20final/app/index.php';
                                    </script>
                            <?php
                                  }
                                }
                            ?>
                            <tr>
                              <td colspan="5"></td>
                              <td class="text-center">
                                  <button class="form-control btn btn-primary btn-sm" data-toggle="modal" data-target="#checkoutmodal">Check Out</button>
                              </td>
                            </tr>
                          </tbody>
                          </table>
                          <?php 
                            }
                          ?>
                          <div class="modal fade" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="checkoutmodal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title text-primary" id="exampleModalLabel">Check Out</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body ">
                                    <table class="table">
                                      <tr class="text-center text-primary">
                                        <td></td>
                                        <td><small>Product Name</small></td>
                                        <td><small>Product Price</small></td>
                                        <td><small>Quantity</small></td>
                                        <td><small><strong>Total</strong></small></td>
                                      </tr>
                                      <?php
                                        $subtotal = 0;
                                        $cart = $func->getCart();
                                        for ($i=0; $i <count($cart,COUNT_NORMAL) ; $i++) { 
                                          $img = $func->getImg($cart[$i]["prod_id"]);
                                          $name = $func->getName($cart[$i]["prod_id"]);
                                          $price = $func->getPrice($cart[$i]["prod_id"]);
                                          $subtotal = $subtotal + $cart[$i]["total"];
                                          ?>
                                            <tr class="text-primary text-center">
                                              <td>
                                                <img src="<?php echo $img ?>" style="max-width: auto; max-height: 120px;">
                                              </td>
                                              <td><?php echo $name;?></td>
                                              <td><?php echo $price;?></td>
                                              <td><?php echo $cart[$i]["item_quan"] ?></td>
                                              <td><?php echo $cart[$i]["total"] ?></td>
                                            </tr>
                                          <?php
                                        }
                                      ?>
                                      <tr class="text-primary">
                                        <td colspan="4" class="text-right"><small><strong>Sub Total</strong></small></td>
                                        <td class="text-center"><?php echo $subtotal?></td>
                                      </tr>
                                    </table>
                                    <hr>
                                    <form action="" method="POST">
                                      <div class="form-row">
                                        <div class="col">
                                          <input type="text-center" name="address" class="form-control form-control-sm" placeholder="Delivery Address" required>
                                        </div>
                                        <div class="col">
                                          <input type="text-center" name="name" class="form-control form-control-sm" placeholder="Consignee Name" required>
                                        </div>
                                        <div class="col">
                                          <input type="number" name="money" class="form-control form-control-sm" placeholder="Bring Change for" required>
                                        </div>
                                        <div class="col">
                                          <input type="submit" name="pay" value="Deliver" class="form-control btn btn-primary btn-sm">
                                        </div>
                                      </div>
                                    </form>
                                    <?php 
                                      if(isset($_POST["pay"])){
                                        date_default_timezone_set('Asia/Manila');
                                        $func->pay($_SESSION['id'],$subtotal,date('Y-m-d'),$_POST["name"],$_POST["address"]);
                                        ?>
                                      <script type="text/javascript">
                                        window.location.href = 'http://localhost/inventory%20final/app/index.php';
                                        $(window).on('load',function(){
                                             $('#delivermodal').modal('show');
                                         });
                                      </script>
                                        <?php
                                        $_SESSION['pay'] = true;
                                      }
                                    ?>
                              </div>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
                  <?php 
                  }
                  else{
                  ?>
                  <script src="js/chart.min.js"></script>
                  <script src="js/graph.js"></script>
                  <div id="storename" class="text-center text-primary font-weight-bold font-italic"><strong>Tindahan ni Maya</strong></div>
                    <div class="text-center"><img src="res/avatar.png" class="rounded-circle"></div>
                    <script>
                      $().ready(function() {
                          $('#storename').arctext({radius:60})
                      });
                    </script>
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
                    <div class="tab-pane show active" id="inventory" role="tabpanel" aria-labelledby="sales-tab">
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
                            <?php $inven = $func->inven("");?>
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
                    <div class="tab-pane" id="stocks" role="tabpanel" aria-labelledby="sales-tab">
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
                                    $func->upload($img);
                                    $add = $func->addProduct($prodname,$prodprice,$prodquan,$desc,$supp,basename($img));
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
                                    <input type="number" class="form-control" placeholder="Product Price" name="price" required>
                                  </div>
                                </div>
                                <hr>
                                <div class="form-row">
                                  <div class="col">
                                    <input type="number" class="form-control" placeholder="Quantity" name="quan" required>
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
                                    <label for="pic" class="text-primary">Product Image</label>
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
                                      <input type="text" name="searchname" class="form-control" placeholder="Product Name/ID" style="width: 100%;"">
                                    </div>
                                    <div class="col">
                                      <input type="submit" name="stocksearch" class="form-control btn btn-primary btn-md float-right" value="Search">
                                    </div>
                                  </div>
                                </form>
                                <?php
                                  if(isset($_POST['stocksearch'])){
                                    $searchname = $_POST['searchname'];
                                    $search = $func->search($searchname);
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
                                    if($quan>0){
                                     $func->updatestocks($upid,$quan);
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
                                  else{
                                    $func->deleteStocks($upid);
                                    ?>
                                    <br>
                                    <script type="text/javascript">
                                        document.getElementById("delStock").classList.add('show');
                                    </script>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      <strong>Product Deleted!</strong>
                                    </div>
                                    <?php
                                  }
                                }
                              ?>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                    <!--Reports-->
                    <div class="tab-pane" id="reports" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Reports</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                        <style>
                          .chart-container{
                            width: 95%;
                            height: auto;
                          }
                        </style>
                        <div class="chart-container">
                          <canvas id="canvas"></canvas>
                        </div>
                        <div class="chart-container">
                          <canvas id="canvas2"></canvas>
                        </div>
                        <br>
                        <hr>
                        <div class="text-primary">
                          <h2 class="text-center"><small>Sales Report</small></h2>
                          <div class="text-left">
                            <table class="table">
                              <thead class="text-primary">
                                <tr>
                                  <td><small><strong>Product Name</strong></small></td>
                                  <td><small><strong>Price</strong></small></td>
                                  <td><small><strong>In Stock</strong></small></td>
                                  <td><small><strong>Sold</strong></small></td>
                                </tr>
                              </thead>
                              <tbody>
                              <?php 
                                $reports = $func->getReport();
                                for ($i=0; $i <count($reports,COUNT_NORMAL) ; $i++) {
                                ?>
                                  <tr class="text-primary">
                                    <td><small><?php echo $reports[$i]["prod_name"]?></small></td>
                                    <td><small><?php echo $reports[$i]["prod_price"]?></small></td>
                                    <td><small><?php echo $reports[$i]["prod_quan"]?></small></td>
                                    <td><small><?php echo $reports[$i]["sold"]?></small></td>
                                  </tr>
                                <?php
                                }
                              ?>
                              </tbody>
                            </table>
                            <p class="text-primary">
                              Total Amount of Sales: <?php $func->getSales(); ?><br>
                              Total Number of Products Sold: <?php $func->getSold(); ?>
                              <hr>
                            </p>
                          </div>
                        </div>
                    </div>
                    <!--Users-->
                    <div class="tab-pane" id="users" role="tabpanel" aria-labelledby="sales-tab">
                      <h3 class="text-primary"><small>Users</small></h3>
                        <hr style=" height: 6px; background: url(res/hr.png) repeat-x 0 0; border: 0;">
                        <div id="accordion1" role="tablist">
                          <div class="card">
                            <div class="card-header" role="tab" id="headingOne">
                              <h5 class="mb-0">
                                <a data-toggle="collapse" href="#addUser" aria-expanded="true" aria-controls="collapseOne">
                                  Add Admin
                                </a>
                              </h5>
                            </div>
                            <div id="addUser" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion1">
                              <div class="card-body">
                                <?php 
                                  if(isset($_POST['addUser'])){
                                    $uname = $_POST['uname'];
                                    $pass = sha1($_POST['pword']);
                                    $fname = $_POST['fname'];
                                    $lname = $_POST['lname'];
                                    $type = strtolower($_POST['type']);
                                    $adduser = $func->addUser($fname,$lname,$uname,$pass,$type);
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
                            <div id="delUser" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion1">
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
                                    $delete = $func->deleteUser($username);
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
                        <div class="card">
                          <div class="card-header" role="tab" id="headingThree">
                            <h5 class="mb-0">
                              <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                View Users
                              </a>
                            </h5>
                          </div>
                          <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion1">
                            <div class="card-body">
                              <table class="table text-center">
                                <thead>
                                  <tr class="text-primary">
                                    <td><small><strong>User Id</strong></small></td>
                                    <td><small><strong>First Name</strong></small></td>
                                    <td><small><strong>Last Name</strong></small></td>
                                    <td><small><strong>Username</strong></small></td>
                                    <td><small><strong>Privilege</strong></small></td>
                                  </tr>
                                </thead>
                                <tbody>
                              <?php
                                $users = $func->viewUsers();
                                for ($i=0; $i <count($users,COUNT_NORMAL) ; $i++) {
                                  ?>
                                    <tr class="text-center">
                                      <td><?php echo ucfirst($users[$i]["userid"]); ?></td>
                                      <td><?php echo ucfirst($users[$i]["fname"]); ?></td>
                                      <td><?php echo ucfirst($users[$i]["lname"]); ?></td>
                                      <td><?php echo ucfirst($users[$i]["username"]); ?></td>
                                      <td><?php echo ucfirst($users[$i]["type"]); ?></td>
                                    </tr>
                                  <?php
                                }
                              ?>
                                </tbody>
                              </table>
                            </div>
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

  </body>
</html>