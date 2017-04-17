<?php
include('THSplitLib/segment.php');

class Wordcut
{

  public function check($keyword,$word)
  {
    $segment = new Segment;
    $result = $segment->get_segment_array($word);
    // $count = count($result);
    //js_thai_encode($result);
    // echo implode(' | ', $result);
    // echo '<br>'.$count.'<br><br>';

    // $keyword = explode(" ", "บันทึก มื้อ อาหาร");


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


    // if (check_key($result,$keyword) == "true") {
    //   echo "true";
    // }else {
    //   echo "false";
    // }

  }

  //
  // function check_key($req,$keyval)
  // {
  //   $mapper = 0;
  // // loop mapping algorithm
  //     for ($i=0; $i < count($req); $i++) {
  //       if ($mapper < count($keyval)) {
  //         if ($req[$i] == $keyval[$mapper]) {
  //           $mapper++;
  //         }
  //       }
  //     }
  // // check mapper
  //   if ($mapper == count($keyval)) {
  //     return "true";
  //   }else {
  //     return "false";
  //   }
  // }


}



 ?>
