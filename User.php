<?php
/**
 *
 */

class User
{

    private $userId = null;

    private $servername = "sql6.freemysqlhosting.net";

    private $username = "sql6159246";

    private $password = "DBUUiG4F5U";

    private $dbname = "sql6159246";
    // Create connection

    private $conn;
    function __construct($userId)
    {
        $this->$userId = $userId;
        $this->$conn = new mysqli($servername, $username, $password, $dbname);
    }

    public function get_displayname()
    {
        // Create connection
        // $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection

        if ($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT Udetail.displayname FROM Udetail WHERE Udetail.userid_line = '$userId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {

            while ($row = $result->fetch_assoc())
            {
              $displayname = $row["Udetail.displayname"];
              return $displayname;
            }
        }
        else
        {
            return "no displayname";
        }
        $conn->close();
    }

    public function check_userId()
    {
        # code...

    }
}
?>
