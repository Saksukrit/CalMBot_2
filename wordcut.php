<?php
include_once 'THSplitLib/segment.php';
// require_once ('./THSplitLib/segment.php');

class Wordcut
{

  public function check($keyword,$word)
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
      // echo '<br>'.$word;
      return "true";
    }else {
      // echo '<br> false';
      return "false";
    }
  }

  public function implode_word($word)
  {
    $segment = new Segment();
    $result = $segment->get_segment_array($word);
    return implode(' | ', $result);
  }

  function run_swear($keyword,$allword)
  {
    if ($this->check(explode(" ", $keyword),$allword) == "true") {
      return  "true";
    }else {
      return  "false";
    }

  }

  public function swear($allword)
  {
    // if ($this->check(explode(" ", "สัส"),$allword) == "true") {
    //   return "true";
    // }
    if (($this->run_swear("กู",$allword) == "true")
    || ($this->run_swear("ไอ",$allword) == "true")
    || ($this->run_swear("มึง",$allword) == "true")
    || ($this->run_swear("เหี้ย",$allword) == "true")
    || ($this->run_swear("ควาย",$allword) == "true")
    || ($this->run_swear("สัด",$allword) == "true")
    || ($this->run_swear("สัส",$allword) == "true")
    || ($this->run_swear("ห่า",$allword) == "true")
    || ($this->run_swear("เสือก",$allword) == "true")
    || ($this->run_swear("ชิบ",$allword) == "true")) {
      return "true";

    }else {
      return "false";
    }
    explode(" ", "เมนู อาหาร");
  }


}



 ?>
