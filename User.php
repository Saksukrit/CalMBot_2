<?php
/**
 *
 */
 require 'DBconfic.php';

class User
{
    // private $userId = null;
    //
    // private $servername = "sql6.freemysqlhosting.net";
    //
    // private $username = "sql6159246";
    //
    // private $password = "DBUUiG4F5U";
    //
    // private $dbname = "sql6159246";
    // Create connection
    // private $conn;
    // function __construct($userId)
    // {
    //     $this->$userId = $userId;
    //     $this->$conn = new mysqli($servername, $username, $password, $dbname);
    // }

    public function get_displayname($userId)
    {
        $servername = "sql6.freemysqlhosting.net";
        $username = "sql6159246";
        $password = "DBUUiG4F5U";
        $dbname = "sql6159246";
        // Create connection
        // $conn = new mysqli($servername, $username, $password, $dbname);
        // $conn = $mysqli;

        $sql = "SELECT displayname FROM Udetail WHERE userid_line = '$userId'";
        // $result = $conn->query($sql);
        $result = $mysqli->query($sql);

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
