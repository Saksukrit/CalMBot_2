<?php
include_once 'dbcon.php';

class Postback
{
    public function setpostback($userId,$data)
    {
        // Create connection
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "INSERT INTO Postback (userId, content) VALUES ('$userId', '$data')";
        
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
    
    public function getpostback($userId)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT content FROM Postback WHERE userId = '$userId' LIMIT 1";
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
    
    
    public function deletepostback($userId)
    {
        // Create connection
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "DELETE FROM Postback WHERE userId = '$userId'";
        
        if ($conn->query($sql) === TRUE)
        {
            echo "New record created successfully";
            return "delete success";
        }
        else
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return "delete fail";
        }
        $db->CloseCon($conn);
    }

    public function changepostback($userId,$food)
    {
        // Create connection
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "DELETE FROM Postback WHERE userId = '$userId'";
        
        if ($conn->query($sql) === TRUE)
        {
            echo "New record created successfully";
            return "delete success";
        }
        else
        {
            echo "Error: " . $sql . "<br>" . $conn->error;
            return "delete fail";
        }
        $db->CloseCon($conn);
    }
    
}


?>