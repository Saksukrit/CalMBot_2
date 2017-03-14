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
        $sql = "INSERT INTO Food_diary (userID, food_diary_id, save_date, total_calorie) VALUES ('$userID', null,'$save_date', 0)";
        
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
    
    // AND repast =
    
    // get all calorie
    public function get_repast_calorie($food_diary_id,$repast)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT calorie FROM Food_diary_list WHERE f_diary_id_List = '$food_diary_id' AND repast = '$repast'";
        $result = $conn->query($sql);
        
        $calorie = 0;
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $calorie = $calorie + intval($row["calorie"]);
                // $calorie = $row["calorie"];
            }
            return $calorie;
        }
        else
        {
            return "null";
        }
        $db->CloseCon($conn);
    }
    
    // get all calorie
    public function get_all_calorie($food_diary_id)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT calorie FROM Food_diary_list WHERE f_diary_id_List = '$food_diary_id'";
        $result = $conn->query($sql);
        
        $calorie = 0;
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                $calorie = $calorie + intval($row["calorie"]);
            }
            return $calorie;
        }
        else
        {
            return "null";
        }
        $db->CloseCon($conn);
    }
    
    // update summary calorie of day
    public function update_total_calorie($food_diary_id,$total_calorie)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "UPDATE Food_diary SET total_calorie = '$total_caloriel' WHERE (food_diary_id = '$food_diary_id')";
        $conn->query($sql);
        
        $db->CloseCon($conn);
    }
    
}
?>