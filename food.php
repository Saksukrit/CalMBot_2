<?php
/**
 *
 */

class FoodCheck
{

    private $foodname = null;
    // function __construct($foodname)
    // {
    //   # code...
    // }

    public function checkDB($foodname)
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
}
?>
