<?php
include_once 'dbcon.php';

class HealthyFood
{
  public function get_healthyfood_by_cal($calorie)
  {
    $calorie = intval($calorie);
    $db = new Dbcon;
    $conn = $db->OpenCon();
    mysqli_set_charset($conn, "utf8");
    $sql = "SELECT * FROM HealthyFood WHERE hf_calorie <= '$calorie' LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $nummax = $result->num_rows;
        $num = 0;
        $colum = array();

        while ($row = $result->fetch_assoc()) {
            if ($num != $nummax) {
                $colum[$num] = array(
                'thumbnailImageUrl' => '' . $row["hf_referance"] . '',
                'title' => '' . $row["hf_name"] . ' ' . $row["hf_unit"] . '',
                'text' => 'มีพลังงาน ' . $row["hf_calorie"] . ' แคลอรี่',
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

  public function getall_healthyfood_by_cal()
  {
    $db = new Dbcon;
    $conn = $db->OpenCon();
    mysqli_set_charset($conn, "utf8");
    $sql = "SELECT * FROM HealthyFood LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $nummax = $result->num_rows;
        $num = 0;
        $colum = array();

        while ($row = $result->fetch_assoc()) {
            if ($num != $nummax) {
                $colum[$num] = array(
                'thumbnailImageUrl' => '' . $row["hf_referance"] . '',
                'title' => '' . $row["hf_name"] . ' ' . $row["hf_unit"] . '',
                'text' => 'มีพลังงาน ' . $row["hf_calorie"] . ' แคลอรี่',
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



}

 ?>
