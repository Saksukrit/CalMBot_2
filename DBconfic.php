<?php
class DB
{
private  $servername = "sql6.freemysqlhosting.net";
private  $username = "sql6159246";
private  $password = "DBUUiG4F5U";
private  $dbname = "sql6159246";

  function __construct()
  {
    new mysqli($this->$servername, $this->$username, $this->$password, $this->$dbname);
  }
}


 ?>
