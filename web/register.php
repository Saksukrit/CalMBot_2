<!DOCTYPE html>
<html>
<?php
include 'con_register.php';

$register = new Register;
 ?>

    <head>
        <title>Cal.MBot Register</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="css/style.css" rel="stylesheet" type="text/css" media="all">
        <link href="css/nav.css" rel="stylesheet" type="text/css" media="all">
        <link href="http://fonts.googleapis.com/css?family=Carrois+Gothic+SC" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>

    <body class="column_right_grid">
        <div class="sign_in">
            <?php

        if (isset($_POST["username"])){
          // check txt username null
          if ($_POST["username"] == null) {
            echo '<script type="text/javascript">alert("กรุณาใส่ username")</script>';
            register_page(null,null,null);
          }
          // check_username
          else if ($register->check_username_password($_POST["username"]) == "incorrect") {
            echo '<script type="text/javascript">alert("username ผิดข้อกำหนด \n *อนุญาตเฉพาะ a-z A-Z 0-9 \n * ความยาว 8-16 ตัวอักษร")</script>';
            register_page(null,null,null);
          }
          // check txt password null
          else if ($_POST["password"] == null) {
            echo '<script type="text/javascript">alert("กรุณาใส่ password")</script>';
            register_page($_POST["username"],null,null);
          }
          // check password
          else if ($register->check_username_password($_POST["password"]) == "incorrect") {
            echo '<script type="text/javascript">alert("password ผิดข้อกำหนด \n *อนุญาตเฉพาะ a-z A-Z 0-9 \n * ความยาว 8-16 ตัวอักษร")</script>';
            register_page($_POST["username"],null,null);
          }
          // check txt confirm_password null
          else if ($_POST["confirm_password"] == null) {
            echo '<script type="text/javascript">alert("กรุณาใส่ confirm password")</script>';
            register_page($_POST["username"],$_POST["password"],null);
          }
          // check confirm_password Match
          else if ($_POST["confirm_password"] != $_POST["password"]) {
            echo '<script type="text/javascript">alert("Confirm Password not Match! ")</script>';
          register_page($_POST["username"],null,null);
          }

          // pass all condition -> add to DB
          else {
            if (  $register->create_account($_POST["username"],$_POST["password"]) == "success") {
              detail_page();
            }else {
              register_page(null,null,null);
            }
          }
        }
        elseif (isset($_POST["clear"])) {
          detail_page();
        }
        else {
          // register_page(null,null,null);
          detail_page();
        }
        ?>
        </div>
    </body>

</html>

<!-- fun php -->
<?php
function register_page($username,$password,$confirm_password)
{
?>
    <h3>Register</h3>
    <form name="register" method="post" action="register.php">
        <span>
          <i><img src="images/user.png" alt=""></i>
          <?php if ($username != null) {
            echo '<input type="text" name="username" id="txt_username" placeholder="Enter username" value="'.$username.'">';
          }else {
            echo '<input type="text" name="username" id="txt_username" placeholder="Enter username" >';
          }
           ?>
        </span>
        <span>
          <i><img src="images/lock.png" alt=""></i>
          <?php if ($password != null) {
            echo '<input type="password" name="password" id="confirm_password" placeholder="Enter password" value="'.$password.'">';
          }else {
            echo '<input type="password" name="password" id="confirm_password" placeholder="Enter password">';
          }
           ?>
        </span>
        <span>
          <i><img src="images/lock.png" alt=""></i>
          <?php if ($confirm_password != null) {
            echo '<input type="password" name="confirm_password" id="confirm_password" placeholder="Enter confirm password" value="'.$confirm_password.'">';
          }else {
            echo '<input type="password" name="confirm_password" id="confirm_password" placeholder="Enter confirm password">';
          }
           ?>
        </span>
        <button type="submit" class="btn btn-success btn-lg" name="signup" value="signup">Sign Up</button>
    </form>
    <h4><a href="" onclick="self.close()">Back to LINE</a></h4>
    <?php
}

function detail_page()
{
  ?>
        <h3>User Detail</h3>
        <form name="detail" method="post" action="register.php">
            <span>
              <input type="text" name="displayname" id="displayname" placeholder="displayname" >
            </span>
            <span>
              <input type="radio" name="gender" value="male" checked> Male &nbsp&nbsp&nbsp&nbsp&nbsp
              <input type="radio" name="gender" value="female"> Female
            </span>

            <span>
              <input type="text" name="weight" id="weight" placeholder="weight (kg.)" >
            </span>
            <span>
              <input type="text" name="height" id="height" placeholder="height (cm.)" >
            </span>
            <span>
              <input type="text" name="age" id="displayname" placeholder="age" >
            </span>
            <button type="submit" class="btn btn-warning btn-lg " name="clear" value="clear">Clear</button>
            <button type="submit" class="btn btn-success btn-lg" name="save" value="save">Save</button>
        </form>
        <h4><a href="" onclick="self.close()">Back to LINE</a></h4>
        <?php
}

function complete()
{
  ?>
            <h3>Register Complete</h3>
            <h4><a href="" onclick="self.close()">Back to LINE</a></h4>
            <?php
}

 ?>
