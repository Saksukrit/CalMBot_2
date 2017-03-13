<?php
include_once 'dbcon.php';

class Req_manage
{
    public function save_repast($userId,$repast)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "INSERT INTO RequestLine (userId, req_type, header, content) VALUES ('$userId', 'savefood', 'repast', '$repast')";
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
    
    public function save_food($userId,$food)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "INSERT INTO RequestLine (userId, req_type, header, content) VALUES ('$userId', 'savefood', 'food', '$food')";
        $conn->query($sql);
        $db->CloseCon($conn);
    }
    
    public function save_unit($unit)
    {
        # code...
    }
    
    public function save_calorie($calorie)
    {
        # code...
    }
    
    public function get_dialy_list()
    {
        # code...
    }
}
?>