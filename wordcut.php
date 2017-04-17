<?php
include_once 'THSplitLib/segment.php';
// require_once ('./THSplitLib/segment.php');

class Wordcut
{

  public function check($keyword,$word)
  {
    $segment = new Segment;
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


}



 ?>
