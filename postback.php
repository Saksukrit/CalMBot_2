<?php


class Postback
{
    private $data;
    
    public function setpostback($data)
    {
        $this->$data = $data;
    }
    public function getpostback()
    {
        return $this->$data;
    }
    
    
}


?>