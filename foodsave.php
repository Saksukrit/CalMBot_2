<?php

class Food_save
{
    // function __construct(argument)
    // {
    //   # code...
    // }
    //
    //

    public function save_food_dialy($userID, $save_date)
    {
        $servername = "sql6.freemysqlhosting.net";
        $username = "sql6159246";
        $password = "DBUUiG4F5U";
        $dbname = "sql6159246";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection

        if ($conn->connect_error)
        {
            die("Connection failed: " . $conn->connect_error);
        }
        else
        {
            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO Food_diary (userID, food_diary_id, save_date, total_caloriel) VALUES (?, NULL,?, NULL);");
            $stmt->bind_param("sss", $userID, $save_date);
            // set parameters and execute
            // $firstname = "John";
            // $lastname = "Doe";
            // $email = "john@example.com";
            $stmt->execute();
            $stmt->close();
        }
        $conn->close();

    }

    public function save_food_dialy_list($fd_id, $fname, $unit, $repast)
    {
        # code...

    }
}
?>
