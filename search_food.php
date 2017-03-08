<?php
include_once 'dbcon.php';
include 'operate.php';

class Searchfood
{

    public function searchfood_byname($foodname)
    {
        $op = new Op();
        $db = new Dbcon;
        $conn = $db->OpenCon();
        mysqli_set_charset($conn, "utf8");
        $sql = "SELECT * FROM Food WHERE food_name LIKE '%$foodname%' LIMIT 15";
        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            $nummax = $result->num_rows;
            $num = 0;
            $colum = array();

            while ($row = $result->fetch_assoc())
            {

                if ($num != $nummax)
                {
                    $colum[$num] = array(
                        'thumbnailImageUrl' => '' . $row["f_pic"] . '',
                        'title' => '' . $row["food_name"] . ' ' . $row["f_unit"] . '',
                        'text' => 'มีพลังงาน ' . $row["f_calorie"] . ' แคลอรี่',
                        'actions' => array(
                            array(
                                'type' => 'message',
                                'label' => ' ',
                                'text' => ' ',
                            )
                        ) ,
                    );
                    $num = $op->iplus($num);
                }
            }
            // $getcolums = array();
            // $getcolums = $this->getcolums($colum);
            return $this->getcolums($colum);
            // // number of $colum
            //
            // if (count($colum) <= 5) /*-------------- 5---------------- */
            //
            // {
            //     $ms_foodlist = array();
            //     $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
            //         'type' => 'carousel',
            //         'columns' => $colum
            //     ) ];
            //     return $ms_foodlist;
            // }
            // elseif (count($colum) <= 10) /*-------------- 10---------------- */
            //
            // {
            //     $ms_foodlist = array();
            //     $colums = array();
            //     $i = 0;
            //
            //     while ($i < 5)
            //     {
            //         $colums[$i] = $colum[$i];
            //         $i = $op->iplus($i);
            //     }
            //     $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
            //         'type' => 'carousel',
            //         'columns' => $colums
            //     ) ];
            //     $colums = array();
            //     $i = 5;
            //
            //     while ($i < count($colum))
            //     {
            //         $colums[$op->inev($i, 5) ] = $colum[$i];
            //         $i = $op->iplus($i);
            //     }
            //     $ms_foodlist[1] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
            //         'type' => 'carousel',
            //         'columns' => $colums
            //     ) ];
            //     return $ms_foodlist;
            // }
            // elseif (count($colum) <= 15) /*-------------- 15---------------- */
            //
            // {
            //     $ms_foodlist = array();
            //     $colums = array();
            //     $i = 0;
            //
            //     while ($i < 5)
            //     {
            //         $colums[$i] = $colum[$i];
            //         $i = $op->iplus($i);
            //     }
            //     $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
            //         'type' => 'carousel',
            //         'columns' => $colums
            //     ) ];
            //     $colums = array();
            //     $i = 5;
            //
            //     while ($i < 10)
            //     {
            //         $colums[$op->inev($i, 5) ] = $colum[$i];
            //         $i = $op->iplus($i);
            //     }
            //     $ms_foodlist[1] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
            //         'type' => 'carousel',
            //         'columns' => $colums
            //     ) ];
            //     $colums = array();
            //     $i = 10;
            //
            //     while ($i < count($colum))
            //     {
            //         $colums[$op->inev($i, 10) ] = $colum[$i];
            //         $i = $op->iplus($i);
            //     }
            //     $ms_foodlist[2] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
            //         'type' => 'carousel',
            //         'columns' => $colums
            //     ) ];
            //     return $ms_foodlist;
            // }
        }
        else
        {
            return "null";
        }
        $db->CloseCon($conn);
    }

    public function searchfood_bycalorie($foodcalorie)
    {
        # code...

    }

    public function searchfood_bytype($foodtype)
    {
        # code...

    }

     function getcolums($colum)
    {
      // number of $colum

      if (count($colum) <= 5) /*-------------- 5---------------- */

      {
          $ms_foodlist = array();
          $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
              'type' => 'carousel',
              'columns' => $colum
          ) ];
          return $ms_foodlist;
      }
      elseif (count($colum) <= 10) /*-------------- 10---------------- */

      {
          $ms_foodlist = array();
          $colums = array();
          $i = 0;

          while ($i < 5)
          {
              $colums[$i] = $colum[$i];
              $i = $op->iplus($i);
          }
          $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
              'type' => 'carousel',
              'columns' => $colums
          ) ];
          $colums = array();
          $i = 5;

          while ($i < count($colum))
          {
              $colums[$op->inev($i, 5) ] = $colum[$i];
              $i = $op->iplus($i);
          }
          $ms_foodlist[1] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
              'type' => 'carousel',
              'columns' => $colums
          ) ];
          return $ms_foodlist;
      }
      // elseif (count($colum) <= 15) /*-------------- 15---------------- */
      //
      // {
      //     $ms_foodlist = array();
      //     $colums = array();
      //     $i = 0;
      //
      //     while ($i < 5)
      //     {
      //         $colums[$i] = $colum[$i];
      //         $i = $op->iplus($i);
      //     }
      //     $ms_foodlist[0] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
      //         'type' => 'carousel',
      //         'columns' => $colums
      //     ) ];
      //     $colums = array();
      //     $i = 5;
      //
      //     while ($i < 10)
      //     {
      //         $colums[$op->inev($i, 5) ] = $colum[$i];
      //         $i = $op->iplus($i);
      //     }
      //     $ms_foodlist[1] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
      //         'type' => 'carousel',
      //         'columns' => $colums
      //     ) ];
      //     $colums = array();
      //     $i = 10;
      //
      //     while ($i < count($colum))
      //     {
      //         $colums[$op->inev($i, 10) ] = $colum[$i];
      //         $i = $op->iplus($i);
      //     }
      //     $ms_foodlist[2] = ['type' => 'template', 'altText' => 'รายการอาหาร', 'template' => array(
      //         'type' => 'carousel',
      //         'columns' => $colums
      //     ) ];
      //     return $ms_foodlist;
      // }
    }
}
