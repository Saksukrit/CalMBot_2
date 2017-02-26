<?php
include 'dbcon.php';

class User
{

    public function get_displayname($userId)
    {
        $conn = OpenCon();
        $sql = "SELECT displayname FROM Udetail WHERE userid_line = '$userId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {

            while ($row = $result->fetch_assoc())
            {
                $displayname = $row["displayname"];
                return $displayname;
            }
        }
        else
        {
            return "no displayname";
        }
        CloseCon($conn);
    }

    public function check_userId($userId)
    {
        $conn = OpenCon();
        $sql = "SELECT userID FROM Udetail WHERE userid_line = '$userId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {

            while ($row = $result->fetch_assoc())
            {
                $displayname = $row["userID"];
                return $displayname;
            }
        }
        else
        {
            return "null";
        }
        CloseCon($conn);
    }
}
?>
