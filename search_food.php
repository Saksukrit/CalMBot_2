<?php
include_once 'dbcon.php';

class Searchfood
{

    public function searchfood_byname($foodname)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT * FROM Food WHERE food_name LIKE '%$foodname%' LIMIT 5";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {

            while ($row = $result->fetch_assoc())
            {
                $colum = $row["food_name"];
                // $colum = array(
                //     'thumbnailImageUrl' => '' . $row["f_pic"] . '',
                //     'title' => '' . $row["food_name"] . ' ' . $row["f_unit"] . '',
                //     'text' => ' ' . $row["f_calorie"] . '',
                //     'actions' => array(
                //         array(
                //             'type' => 'message',
                //             'label' => ' ',
                //             'text' => ' ',
                //         )
                //     ) ,
                // );
                return $colum;
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
