<?php
include_once '../dbcon.php';


class Register
{

  public function checkmodle($userid_line)
  {
    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "SELECT userId_line FROM Modle_Register WHERE userId_line = '$userid_line'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {
        return $userid_line;
    }
    else
    {
        return "null";
    }
    $db->CloseCon($conn);
  }

  public function new_modle_register($userid_line)
  {
    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "INSERT INTO Modle_Register (userId_line) VALUES ('$userid_line')";

    if ($conn->query($sql) === TRUE)
    {
      echo "New create successfully";
      return "success";
    }
    else
    {
      echo "Error: " . $sql . "<br>" . $conn->error;
      return "fail create";
    }
    $db->CloseCon($conn);
  }

  public function check_username_password($input)
  {
    if (strlen($input) < 8 || strlen($input) > 16) {
      return "incorrect";
    }else if (!preg_match("/^[a-zA-Z0-9 ]+$/",$input)) {
      return "incorrect";
    }
    else {
      return "correct";
    }
  # code...
  }

  public function get_username_password($userid_line)
  {
    # code...
  }



  public function create_account($username,$password)
  {
    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "INSERT INTO User (username, password, typeUser) VALUES ('$username', '$password', 'U')";

    if ($conn->query($sql) === TRUE)
    {
      echo "New create successfully";
      return "success";
    }
    else
    {
      echo "Error: " . $sql . "<br>" . $conn->error;
      return "fail create";
    }
    $db->CloseCon($conn);
  }

  public function create_userdetail($userid_line)
  {

    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "INSERT INTO User (username, password, typeUser) VALUES ('$username', '$password', 'U')";

    if ($conn->query($sql) === TRUE)
    {
      echo "New create successfully";
      return "success";
    }
    else
    {
      echo "Error: " . $sql . "<br>" . $conn->error;
      return "fail create";
    }
    $db->CloseCon($conn);
  }

  public function update_userdetail($value='')
  {
  # code...
  }


}


?>
