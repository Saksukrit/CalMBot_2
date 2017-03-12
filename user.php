<?php
include_once 'dbcon.php';

class User
{
    
    public function get_displayname($userId)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT displayname FROM Udetail WHERE userid_line = '$userId'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $displayname = $row["displayname"];
                return $displayname;
            }
        } else {
            return "no displayname";
        }
        $db->CloseCon($conn);
    }
    
    public function get_userId($userId)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        $sql = "SELECT userID FROM Udetail WHERE userid_line = '$userId'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userID = $row["userID"];
                return $userID;
            }
        } else {
            return "null";
        }
        $db->CloseCon($conn);
    }
    
    public function get_profile($userId)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        $sql = "SELECT * FROM Udetail WHERE userid_line = '$userId'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userID = $row["userID"];
                $ms_profile = [
                'type' => 'text',
                'text' => 'ข้อมูลผู้ใช้ของ '.$row["displayname"].'
                น้ำหนัก : '.$row["weight"].'
                ส่วนสูง : '.$row["height"].'
                อายุ : '.$row["old"].'
                ค่า BMR : '.$row["bmr"].'
                ค่า TDEE : '.$row["tdee"].'
                **********---****---**********
                '];
                return $ms_profile;
            }
        } else {
            return "null";
        }
        $db->CloseCon($conn);
    }
}