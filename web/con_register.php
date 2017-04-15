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

  public function check_displayname($input)
  {
    if (strlen($input) < 8 || strlen($input) > 16) {
      return "incorrect";
    }else if (!preg_match("/^[a-zA-Z ]+$/",$input)) {
      return "incorrect";
    }
    else {
      return "correct";
    }
  }

  public function check_num($input)
  {
    if (strlen($input) < 1 || strlen($input) > 4 ) {
      return "incorrect";
    }else if (!preg_match("/^[0-9 ]+$/",$input)) {
      return "incorrect";
    }
    else {
      return "correct";
    }
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
      return "success";
    }
    else
    {
      echo "Error: " . $sql . "<br>" . $conn->error;
      return "fail create";
    }
    $db->CloseCon($conn);
  }

  public function get_userID($username)
  {

    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "SELECT userID FROM User WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {
      while ($row = $result->fetch_assoc()) {
          $userID = $row["userID"];
          return $userID;
      }
    }
    else
    {
        return "null";
    }
    $db->CloseCon($conn);
  }

  public function create_userdetail($userID)
  {

    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "INSERT INTO Udetail (userID) VALUES ('$userID')";

    if ($conn->query($sql) === TRUE)
    {
      return "success";
    }
    else
    {
      echo "Error: " . $sql . "<br>" . $conn->error;
      return "fail create";
    }
    $db->CloseCon($conn);
  }

  public function update_userdetail($userID,$displayname,$gender,$weight,$height,$age,$bmr,$tdee)
  {
    // $age = calculate_age($bday);
    // $bmr = calculate_bmr($gender,$weight,$height,$age);
    // $tdee = calculate_tdee($bmr,$activity);

    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "UPDATE Udetail SET displayname ='$displayname', gender ='$gender', weight='$weight', height='$height', old='$age', bmr='$bmr', tdee='$tdee' WHERE (userID='$userID')";

    if ($conn->query($sql) === TRUE)
    {
      return "success";
    }
    else
    {
      echo "Error: " . $sql . "<br>" . $conn->error;
      return "fail create";
    }
    $db->CloseCon($conn);

  }

   public function calculate_age($bday)
  { $bday = date("m/d/Y",strtotime($bday));
    // mm/dd/yyyy
    $birthDate = explode("/", $bday);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
    ? ((date("Y") - $birthDate[2]) - 1)
    : (date("Y") - $birthDate[2]));
    // $age =
    return $age;
  }

   public function calculate_bmr($gender,$weight,$height,$age)
  {
    if ($gender == "Male") {
      $bmr = (66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age));
      return $bmr;
    }else if ($gender == "Female") {
      $bmr = (665 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age));
      return $bmr;
    }
  }

   public function calculate_tdee($bmr,$activity)
  {
    if ($activity = "0") {
      return $bmr*1.2;
    }elseif ($activity = "1") {
      return $bmr*1.375;
    }elseif ($activity = "2") {
      return $bmr*1.55;
    }elseif ($activity = "3") {
      return $bmr*1.725;
    }elseif ($activity = "4") {
      return $bmr*1.9;
    }
  }

  public function update_userIDline($userid_line)
  {
    # code...
  }


}


?>
