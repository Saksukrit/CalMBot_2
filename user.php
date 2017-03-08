<?php
include_once 'dbcon.php';

class User
{

    public function get_displayname($userId)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
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
}
