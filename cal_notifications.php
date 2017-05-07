<?php
include_once 'dbcon.php';

/**
 *
 */
class CalNotif
{
  public function checkOverCal($userId)
  {
    $total_calorie = $this->get_total_calorie($userId);
    $tdee = $this->get_tdee($userId);

    if ($total_calorie > $tdee) {
      return "over:".$total_calorie.":".$tdee."";
    }else {
      return "ok:ok:ok";
    }
  }

   function get_total_calorie($userId)
  {
    $currentdate = date('Y-m-d');
    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "SELECT total_calorie FROM Food_diary WHERE userID = '$userId' AND save_date = '$currentdate'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $total_calorie = $row["total_calorie"];
            return $total_calorie;
        }
    }
    else
    {
        return "null";
    }
    $db->CloseCon($conn);
  }

   function get_tdee($userId)
  {
    $currentdate = date('Y-m-d');
    $db = new Dbcon;
    $conn = $db->OpenCon();
    $sql = "SELECT tdee FROM Udetail WHERE userID = '$userId' ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $tdee = $row["tdee"];
            return $tdee;
        }
    }
    else
    {
        return "null";
    }
    $db->CloseCon($conn);
  }


}


 ?>
