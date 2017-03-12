<?php


class Postback
{public $data;

public function setpostback($data)
{
  $data = $this->$data;
}
public function getpostback()
{
  return $this->$data;
}

}


 ?>
