<?php
include_once 'dbcon.php';

class Food_save
{
    
    public function check_food_dialy($userId, $currentdate)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        $sql = "SELECT food_diary_id FROM Food_diary WHERE userID = '$userId' AND save_date = '$currentdate'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $food_diary_id = $row["food_diary_id"];
                return $food_diary_id;
            }
        }
        else
        {
            return "null";
        }
        $db->CloseCon($conn);
    }
    
    public function save_food_dialy($userID, $save_date)
    {
        $userID = intval($userID);
        // Create connection
        $db = new Dbcon;
        $conn = $db->OpenCon();
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
        $db->CloseCon($conn);
    }
    
    
    public function save_food_dialy_list($fd_id, $fname, $unit, $calorie, $repast)
    {
        $fd_id = intval($fd_id);
        $unit = intval($unit);
        $calorie = intval($calorie);
        // Create connection
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        
        $sql = "INSERT INTO Food_diary_list (f_diary_id_List, food_name, unit_eat, calorie, repast) VALUES ('$fd_id', '$fname', '$unit', '$calorie', '$repast')";
        $conn->query($sql);
        
        $db->CloseCon($conn);
    }
}
?>