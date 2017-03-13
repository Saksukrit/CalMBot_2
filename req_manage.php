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
        $conn->query($sql);
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
    
    public function save_unit($userId,$unit)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "INSERT INTO RequestLine (userId, req_type, header, content) VALUES ('$userId', 'savefood', 'unit', '$unit')";
        $conn->query($sql);
        $db->CloseCon($conn);
    }
    
    public function save_calorie($calorie)
    {
        # code...
    }
    
    public function get_dialy_list($userId)
    {
        # code...
    }
}
?>