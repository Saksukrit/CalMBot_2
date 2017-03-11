<?php
include_once 'dbcon.php';


class Searchfood
{

    public function searchfood_byname($foodname)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT * FROM Food WHERE food_name LIKE '%$foodname%' LIMIT 15";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $nummax = $result->num_rows;
            $num = 0;
            $colum = array();

            while ($row = $result->fetch_assoc()) {
                if ($num != $nummax) {
                    $colum[$num] = array(
                        'thumbnailImageUrl' => '' . $row["f_pic"] . '',
                        'title' => '' . $row["food_name"] . ' ' . $row["f_unit"] . '',
                        'text' => 'มีพลังงาน ' . $row["f_calorie"] . ' แคลอรี่',
                        'actions' => array(
                            array(
                                'type' => 'message',
                                'label' => ' ',
                                'text' => ' ',
                            )
                        ) ,
                    );
                    ++$num;
                }
            }
            return $this->getcolums($colum);
        } else {
            return "null";
        }
        $db->CloseCon($conn);
    }

    public function searchfood_bycalorie($foodcalorie)
    {
        $calorie = intval($foodcalorie);
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT * FROM Food WHERE f_calorie <= '$calorie' LIMIT 15";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $nummax = $result->num_rows;
            $num = 0;
            $colum = array();

            while ($row = $result->fetch_assoc()) {
                if ($num != $nummax) {
                    $colum[$num] = array(
                        'thumbnailImageUrl' => '' . $row["f_pic"] . '',
                        'title' => '' . $row["food_name"] . ' ' . $row["f_unit"] . '',
                        'text' => 'มีพลังงาน ' . $row["f_calorie"] . ' แคลอรี่',
                        'actions' => array(
                            array(
                                'type' => 'message',
                                'label' => ' ',
                                'text' => ' ',
                            )
                        ) ,
                    );
                    ++$num;
                }
            }
            return $this->getcolums($colum);
        } else {
            return "null";
        }
        $db->CloseCon($conn);
    }

    public function searchfood_bytype($foodtype)
    {
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT * FROM Food WHERE f_type = '$foodtype' LIMIT 15";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $nummax = $result->num_rows;
            $num = 0;
            $colum = array();

            while ($row = $result->fetch_assoc()) {
                if ($num != $nummax) {
                    $colum[$num] = array(
                        'thumbnailImageUrl' => '' . $row["f_pic"] . '',
                        'title' => '' . $row["food_name"] . ' ' . $row["f_unit"] . '',
                        'text' => 'มีพลังงาน ' . $row["f_calorie"] . ' แคลอรี่',
                        'actions' => array(
                            array(
                                'type' => 'message',
                                'label' => ' ',
                                'text' => ' ',
                            )
                        ) ,
                    );
                    ++$num;
                }
            }
            return $this->getcolums($colum);
        } else {
            return "null";
        }
        $db->CloseCon($conn);
    }
    //------------------------------------------------------------
    //
    function getcolums($colum)
    {
        // number of $colum

        if (count($colum) <= 5) { /*-------------- 5---------------- */

            $ms_foodlist = array();
            $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
                'type' => 'carousel',
                'columns' => $colum
            ) ];
            return $ms_foodlist;
        } elseif (count($colum) <= 10) { /*-------------- 10---------------- */

            $ms_foodlist = array();
            $colums = array();
            for ($i=0; $i < 5; $i++) {
                $colums[$i] = $colum[$i];
            }

            $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
                'type' => 'carousel',
                'columns' => $colums
                  ) ];
            $colums = array();
            for ($i=5; $i < count($colum); $i++) {
                $colums[$i-5] = $colum[$i];
            }

            $ms_foodlist[1] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
                'type' => 'carousel',
                'columns' => $colums
            ) ];
            return $ms_foodlist;
        } elseif (count($colum) <= 15) { /*-------------- 15---------------- */

            $ms_foodlist = array();
            $colums = array();

            for ($i=0; $i < 5; $i++) {
                $colums[$i] = $colum[$i];
            }
            $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
                'type' => 'carousel',
                'columns' => $colums
            ) ];

            $colums = array();
            for ($i=5; $i < 10; $i++) {
                $colums[$i-5] = $colum[$i];
            }
            $ms_foodlist[1] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
                'type' => 'carousel',
                'columns' => $colums
            ) ];

            $colums = array();
            for ($i = 10; $i < count($colum); $i++) {
                $colums[$i - 10] = $colum[$i];
            }
            $ms_foodlist[2] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
                'type' => 'carousel',
                'columns' => $colums
            ) ];
            return $ms_foodlist;
        }
    }
}
