<?php

class Food_save
{
  private $conn = null;

    function __construct()
    {
      $servername = "sql6.freemysqlhosting.net";
      $username = "sql6159246";
      $password = "DBUUiG4F5U";
      $dbname = "sql6159246";
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
    }



    public function save_food_dialy($userID, $save_date)
    {
        $userID = intval($userID);

        if ($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
            return "fail con";
        }
        else
        {
            $sql = "INSERT INTO Food_diary (userID, food_diary_id, save_date, total_caloriel) VALUES ('$userID', null,'$save_date', 0)";

            if ($conn->query($sql) === TRUE)
            {
                echo "New record created successfully";
                return "success";
            }
            else
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
                return "fail add";
            }
        }
        $conn->close();
    }

    public function save_food_dialy_list($fd_id, $fname, $unit, $repast)
    {
      $fd_id=intval($fd_id);
      $unit=intval($unit);
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection

        if ($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        }
        else
        {
          $sql = "INSERT INTO Food_diary_list (food_diary_id, food_name, unit_eat, repast) VALUES ('$fd_id', '$fname', '$unit', '$repast')";

          if ($conn->query($sql) === TRUE)
          {
              echo "New record created successfully";
              return "success";
          }
          else
          {
              echo "Error: " . $sql . "<br>" . $conn->error;
              return "fail add";
          }
        }
        $conn->close();
    }
}
?>
