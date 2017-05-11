<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once 'THSplitLib/segment.php';
include 'wordcut.php';

$word = "ค้นหาข้อมูลออกกำลังกาย";
$keyword = explode(" ", "เมนู อาหาร");
$wc = new Wordcut;

echo $wc->implode_word($word);



if ($wc->check("ข้อมูล อาหาร",$word) == "true") {
  echo "<br>เข้าเมนู";
}else if ($wc->swear($word) == "true") {
  $re = array("นี่คำหยาบ ไม่ใช้ๆ","หยาบหรอ ไม่เอาน่า","โอ้ ฉันไม่ชอบคำหยาบ");
  echo "<br><br>".$re[array_rand($re,1)];
}else {
  $a = array("ไม่เข้าใจ","อะไรหรอ","ว่าไงนะ");
  echo "<br><br>".$a[array_rand($a,1)];
}


































// try {
// $segment = new Segment();
// $string = "ต้องการบันทึกมื้อมื้ออาหารมื้อเมนู";
// $result = $segment->get_segment_array($string);
// $count = count($result);
// //js_thai_encode($result);
// echo implode(' | ', $result);
// echo '<br>'.$count.'<br><br>';
//
// // $req = explode(" ", "บันทึก มื้อ อาหาร");
// $key = explode(" ", "บันทึก มื้อ อาหาร");
//
//
//   if (check_keyword($key,$string) == "true") {
//     echo "บันทึกมื้ออาหาร";
//   }else {
//     echo "ไม่ตรงคีย์";
//   }
//
//
// } catch (Exception $e) {
//   $e->getMessage();
// }



// if (check_keyword($key,$string) == "true") {
//   echo "บันทึกมื้ออาหาร";
// }else {
//   echo "ไม่ตรงคีย์";
// }


//
//
// function check_keyword($keyword,$word)
//           {
//             $segment = new Segment();
//             $result = $segment->get_segment_array($word);
//
//             $mapper = 0;
//             // loop mapping algorithm
//             for ($i=0; $i < count($result); $i++) {
//               if ($mapper < count($keyword)) {
//                 if ($result[$i] == $keyword[$mapper]) {
//                   $mapper++;
//                 }
//               }
//             }
//             // check mapper
//             if ($mapper == count($keyword)) {
//               return "true";
//             }else {
//               return "false";
//             }
//           }


 ?>
