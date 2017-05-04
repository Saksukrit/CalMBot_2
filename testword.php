<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once 'THSplitLib/segment.php';

try {
$segment = new Segment();
$string = "ต้องการบันทึกมื้อมื้ออาหารมื้อ";
$result = $segment->get_segment_array($string);
$count = count($result);
//js_thai_encode($result);
echo implode(' | ', $result);
echo '<br>'.$count.'<br><br>';

// $req = explode(" ", "บันทึก มื้อ อาหาร");
$key = explode(" ", "บันทึก มื้อ อาหาร");


  if (check_keyword($key,$string) == "true") {
    echo "บันทึกมื้ออาหาร";
  }else {
    echo "ไม่ตรงคีย์";
  }


} catch (Exception $e) {
  $e->getMessage();
}



// if (check_keyword($key,$string) == "true") {
//   echo "บันทึกมื้ออาหาร";
// }else {
//   echo "ไม่ตรงคีย์";
// }




function check_keyword($keyword,$word)
          {
            $segment = new Segment();
            $result = $segment->get_segment_array($word);

            $mapper = 0;
            // loop mapping algorithm
            for ($i=0; $i < count($result); $i++) {
              if ($mapper < count($keyword)) {
                if ($result[$i] == $keyword[$mapper]) {
                  $mapper++;
                }
              }
            }
            // check mapper
            if ($mapper == count($keyword)) {
              return "true";
            }else {
              return "false";
            }
          }


 ?>
