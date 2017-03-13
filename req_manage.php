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
    
    // get data =========================================================
    public function get_repast($userId){
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT content FROM RequestLine WHERE userId = '$userId' AND req_type = 'savefood' AND header = 'repast' LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $content = $row["content"];
                return $content;
            }
        } else {
            return "null";
        }
        
        $db->CloseCon($conn);
    }
    
    public function get_food($userId){
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT content FROM RequestLine WHERE userId = '$userId' AND req_type = 'savefood' AND header = 'food' LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $content = $row["content"];
                return $content;
            }
        } else {
            return "null";
        }
        
        $db->CloseCon($conn);
    }
    
    public function get_unit($userId){
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT content FROM RequestLine WHERE userId = '$userId' AND req_type = 'savefood' AND header = 'unit' LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $content = $row["content"];
                return $content;
            }
        } else {
            return "null";
        }
        
        $db->CloseCon($conn);
    }
    
    public function get_dialy_list($userId)
    {
        # code...
    }

    // delete by userId
    public function delete_req($userId)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "DELETE FROM RequestLine WHERE userId = '$userId'";
        $conn->query($sql);
        $db->CloseCon($conn);
    }
}
?>