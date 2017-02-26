<?php
function OpenCon()
{
    $servername = "sql6.freemysqlhosting.net";
    $username = "sql6159246";
    $password = "DBUUiG4F5U";
    $dbname = "sql6159246";
    //
    $conn = new mysqli($servername, $username, $password, $dbname);
    //

    if ($mysqli->connect_error)
    {
        die('Connect Error');
    }
    else
    {
        echo "success";
    }
    return $conn;
}
function CloseCon($conn)
{
    $conn->close();
}
?>
