<?php
include 'dbcon.php';

class User
{

    public function get_displayname($userId)
    {
        $servername = "sql6.freemysqlhosting.net";
        $username = "sql6159246";
        $password = "DBUUiG4F5U";
        $dbname = "sql6159246";
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
        // $conn->close();

    }

    public function check_userId()
    {
        # code...

    }
}
?>
