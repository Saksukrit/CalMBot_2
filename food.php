<?php
/**
 *
 */

class FoodCheck
{

    private $foodname = null;

    private $value = 0;
    // function __construct($foodname)
    // {
    //   # code...
    // }

    public function check_food($foodname)
    {

        if ($foodname == "ข้าวขาหมู")
        {
            return "food";
        }
        else
        {
            return "not_food";
        }
    }

    public function check_num($value)
    {

        if ($value == '1')
        {
            return "ok";
        }
        else
        {
            return "not ok";
        }
    }
}
?>
