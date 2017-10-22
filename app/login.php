<?php 
include "config.php";
$func = new Funcs;
?>
<!DOCTYPE html>
<html lang="en">
<title>Login - Tindahan ni Maya</title>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/app.css">
    <script src="js/jquery.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.arctext.js"></script>
  </head>
  <body id="body">
    <div class="container">
      <div class="row">
        <div class="col"></div>
        <div class="col">
          <div class="jumbotron" style="background-color: rgba(255,255,255,0.5); margin-top: 35%; padding-top: 10%; padding-bottom: 15%; margin-bottom: 1%;">
            <div id="storename" class="text-center text-primary font-weight-bold font-italic"><strong>Tindahan ni Maya</strong></div>
            <div class="text-center"><img src="res/avatar.png" class="rounded-circle"></div>
            <script>
              $().ready(function() {
                  $('#storename').arctext({radius: 60})
              });
            </script>
            <br>
            <?php 
              if(isset($_POST['btnSignin'])){
                $user = $_POST['user'];
                $pass = $_POST['pass'];
                $log = $func->login($user,sha1($pass));
                if($log == true){
                  header('location:index.php');
                }
                else{
                  ?>
                  <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <small>Invalid Username or Password!</small>
                  </div>
                  <?php
                }
              }
            ?>
            <form method="POST">
              <div class="form-group">
                <input type="text" name="user" placeholder="Username" class="form-control" required>
              </div>
              <div class="form-group">
                <input type="password" name="pass" placeholder="Password" class="form-control" required>
              </div>
              <button class="form-control btn btn-sm btn-primary btn-block" type="submit" name="btnSignin">Sign in</button>
            </form>
            <br>
            <div class="text-center">
            <button type="button" class="form-control btn-sm btn-primary" data-toggle="modal" data-target="#regModal" id="regbtn">Register</button>
            <!--Register Modal -->
            <div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-primary" id="exampleModalLabel">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <?php 
                      if (isset($_POST['register'])) {
                        $fname = $_POST['fname'];
                        $lname = $_POST['lname'];
                        $uname = $_POST['uname'];
                        $pass = sha1($_POST['pword']);
                        $type = strtolower('user');
                        $register = $func->addUser($fname,$lname,$uname,$pass,$type);
                        if($register == true){
                      ?>
                        <script type="text/javascript">
                          $(window).on('load',function(){
                               $('#regModal').modal('show');
                           });
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
                          $(window).on('load',function(){
                               $('#regModal').modal('show');
                           });
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
                            <input type="submit" class="btn btn-primary btn-sm float-right" value="Register" name="register">
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
        <div class="col"></div>
      </div>
    </div>
  </body>
</html>