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
    // for save
    public function searchfood_forsave($foodname)
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
                    'title' => '' . $row["food_name"] . ' ' . $row["f_unit"] . '',
                    'text' => 'มีพลังงาน ' . $row["f_calorie"] . ' แคลอรี่',
                    'actions' => array(
                    array(
                    'type' => 'postback',
                    'label' => 'เลือก',
                    'data' => 'food_selected',
                    'text' => ''.$row["food_name"].''
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
    
    // -----------------------------------------------------------
    // get data
    public function get_unit($foodname){
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT f_unit FROM Food WHERE food_name LIKE '%$foodname%' LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $unit = $row["f_unit"];
                $aunit = explode(" ",$unit);
                return $aunit[1];
            }
        } else {
            return "null";
        }
        $db->CloseCon($conn);
        
    }
    
    
    public function get_calorie($foodname){
        // f_calorie
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT f_calorie FROM Food WHERE food_name LIKE '%$foodname%' LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $calorie = $row["f_calorie"];
                return $calorie;
            }
        } else {
            return "null";
        }
        $db->CloseCon($conn);
    }
}