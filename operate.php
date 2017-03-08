<?php


class Op
{
    function iplus($value)
    {
        return ++$value;
    }
    function inev($value, $num)
    {
        $value = $value - $num;
        return $value;
    }
}
?>
