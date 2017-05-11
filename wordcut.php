<?php
include_once 'THSplitLib/segment.php';
// require_once ('./THSplitLib/segment.php');

class Wordcut
{

  public function check($keyword,$word)
  {
    $keyword = explode(" ", $keyword);
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

  function run_swear($keyword,$word)
  {
    $keyword = explode(" ", $keyword);
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
      return  "false";
    }

  }

  public function swear($word)
  {
    // if ($this->check(explode(" ", "สัส"),$allword) == "true") {
    //   return "true";
    // }
    if (($this->run_swear("กู",$word) == "true")
    || ($this->run_swear("ไอ",$word) == "true")
    || ($this->run_swear("มึง",$word) == "true")
    || ($this->run_swear("เหี้ย",$word) == "true")
    || ($this->run_swear("ควาย",$word) == "true")
    || ($this->run_swear("สัด",$word) == "true")
    || ($this->run_swear("สัส",$word) == "true")
    || ($this->run_swear("ห่า",$word) == "true")
    || ($this->run_swear("เสือก",$word) == "true")
    || ($this->run_swear("ชิบ",$word) == "true")) {
      return "true";

    }else {
      return "false";
    }
    explode(" ", "เมนู อาหาร");
  }


}



 ?>
