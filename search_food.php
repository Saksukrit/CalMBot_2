<?php
include_once 'dbcon.php';

class Searchfood
{

    public function searchfood_byname($foodname)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        $sql = "SELECT * FROM Food WHERE food_name LIKE '%ข้าว%' LIMIT 5";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {

            while ($row = $result->fetch_assoc())
            {
                $food_name = $row["food_name"];
                return $food_name;
            }
        }
        else
        {
            return "null";
        }
        $db->CloseCon($conn);
    }

    public function searchfood_bycalorie($foodcalorie)
    {
        # code...

    }

    public function searchfood_bytype($foodtype)
    {
        # code...

    }
}
?>
